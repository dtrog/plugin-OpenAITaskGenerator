<?php

namespace Kanboard\Plugin\OpenAITaskGenerator\Controller;
require_once __DIR__.'/../vendor/autoload.php';

use Kanboard\Controller\BaseController;
use Kanboard\Api\Authorization\ProjectAuthorization;
use Kanboard\Import\TaskImport;
use OpenAI;
use Kanboard\Core\Log\Logger;


class TaskGeneratorController extends BaseController
{
    private $project_id;

    public function generateTasks(array $values = array())
    {
        $goal = null;
        $tasks = null;
        $project_id = null;

        if (!$project_id) {
            $project_id = $this->request->getIntegerParam('project_id');
            $this->project_id = $project_id;
        }

        $formValues = $this->request->getRawFormValues();

        if (!$goal) {
            $goal = $formValues['goal'];
        }
        
        if ($goal) {
            $tasks = $this->getTasksFromOpenAI($goal);
        }

        if (!$tasks) {
            $this->response->html($this->template->render('OpenAITaskGenerator:project/project_header/generate_tasks', [
                'project_id' => $project_id,
                'tasks' => $tasks,
                'goal' => $goal
            ]));      
        } else {
            $this->response->html($this->template->render('OpenAITaskGenerator:project/task_preview', [
                'project_id' => $project_id,
                'tasks' => serialize($tasks),
                'goal' => $goal
            ]));
        }
    }

    protected function getTasksFromOpenAI($goal): Array
    {
        $openai_api_key = $this->configModel->get('openai_api_key');

        $client = OpenAI::client($openai_api_key);
        $user = $this->userSession->getUsername();

        $message = "Genereer taken voor Kanboard met het volgende doel als PHP array, zonder de response te wrappen  in enige code formattering of backticks.                    
                    Doel: ".$goal." 

                    met volgende keys: 
                    'reference',
                    'title',
                    'description',
                    'assignee',
                    'creator',
                    'color',
                    'category',
                    'score',
                    'time_estimated',
                    'priority',
                    'tags',
                    'subtasks'

                    de assignee en creator zijn beide".$user."


                    voor priorities Low, Medium en High de kleuren Yellow, Orange en Red gebruikt worden,
                    category moet beperkt worden tot 1 woord,
                    subtasks voor een taak moeten als array toegevoegd worden aan de taak in het veld subtasks,
                    alle time_estimated velden moeten numeriek zijn waar elk getal het aantal uren representeert.
                    elke subtask moet 'title' en 'time_estimated' bevatten.
                    Antwoord enkel met de raw PHP array";
       
        $answer = $client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $message]
            ],
        ]);

        $answerString = $answer['choices'][0]['message']['content'];
        $tasks = eval("return $answerString;");
        return $tasks;
    }


    public function addTasks()
    {
        $project_id = $this->request->getIntegerParam('project_id');
        $this->project_id = $project_id;
        $position = 0;
        $tasks = unserialize($this->request->getRawFormValues()['tasks']);
        foreach ($tasks as $task) {
            $preparedTask = $this->prepareTask($task);
            $preparedTask['position'] = $position;
            $position = $position + 1;
            $id = $this->taskCreationModel->create($preparedTask);
            $this->createSubTasks($id, $preparedTask['owner_id'], $task['subtasks']);
        }

        $this->response->redirect($this->helper->url->to('BoardViewController', 'show', ['project_id' => $this->project_id]));
    }

    protected function prepareTask($task) : array 
    {
        $preparedTask = 
        [
            'project_id'      =>  $this->project_id,
            'column_id'       =>  $this->columnModel->getAll($this->project_id)[0]['id'],
            'reference'       =>  $task['reference'],
            'title'           =>  $task['title'],
            'description'     =>  $task['description'],
            'owner_id'        =>  $this->userModel->getIdByUsername($task['assignee']),
            'creator_id'      =>  $this->userModel->getIdByUsername('bot'),
            'color_id'        =>  $this->colorModel->find($task['color']),
            'category_id'     =>  $this->processCategory($task['category']),
            'score'           =>  $task['score'],
            'time_estimated'  =>  $task['time_estimated'],
            'priority'        =>  $task['priority'],
            'tags'            =>  $task['tags']
        ];
        return $preparedTask;
    }

    protected function createSubTasks(int $task_id, int $assignee_id, array $subtasks) {
        if(!$subtasks) return;

        foreach ($subtasks as $subtask) {
            $this->subtaskModel->create(
                ['title' => $subtask['title'],
                 'time_estimated' => $subtask['time_estimated'],
                 'task_id' => (int)$task_id,
                 'user_id' => $assignee_id]
            );
        }
    }

    protected function processCategory($category) {
        $id = $this->categoryModel->getIdByName($this->project_id, $category);
        if($id) return $id;
 
        $id = $this->categoryModel->create(['project_id' => $this->project_id, 'name' => $category]);
        return $id;
     }
}