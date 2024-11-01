<li class="active">
<?= $this->modal->medium('bell', t('AI Task Generator'), 'TaskGeneratorController', 'generateTasks', 
             array('plugin' => 'OpenAITaskGenerator', 'project_id' => $project['id']), 'AI Task Generator') ?>
</li>
