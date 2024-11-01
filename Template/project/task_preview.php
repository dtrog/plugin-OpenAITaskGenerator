<div class="modal fade" id="generate-tasks-modal" tabindex="-1" role="dialog" aria-labelledby="generateTasksModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= $this->url->href('TaskGeneratorController', 'addTasks', ['plugin' => 'OpenAITaskGenerator', 'project_id' => $project_id, 'tasks' => $tasks]) ?>" method="post">
                    <?= $this->form->hidden('tasks', ["tasks" => $tasks]); ?>
                    <?= $this->form->hidden('project_id', ["project_id" => $project_id]); ?>

                <ol> 
                    <?php 
                        $counter = 0;
                        $tasklist = unserialize($tasks);
                        
                        foreach($tasklist as $task) {
                            echo '<li>';
                            $counter = $counter + 1;
                            print($counter.': '.$task['title'].'<br>'.
                            $task['description'].'<br>');
                            if($task['subtasks']) {
                                $subcounter = 0;
                                echo '<ul>';
                                foreach($task['subtasks'] as $subtask) {
                                    $subcounter = $subcounter + 1;
                                    echo '<li>';
                                    print($counter.".".$subcounter.": ".$subtask['title']."<br>");
                                    echo '</li>';
                                }
                               echo '<ul>';
                            }

                            echo '</li>';
                            echo '</br>';
                        }
                    ?>
                </ol>
                <?= $this->modal->submitButtons(array('submitLabel' => t('Create Tasks') )) ?>
            </form>
        </div>
    </div>
</div>