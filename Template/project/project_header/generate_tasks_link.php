<div>
     <?= 
        $this->modal->medium('bell', 'AI', 'TaskGeneratorController', 'generateTasks', 
             array('plugin' => 'OpenAITaskGenerator', 'project_id' => $project['id']), 'AI Task Generator') 
      ?>
</div>