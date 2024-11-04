<li class="active">
<?= $this->modal->medium('bell', t('AI Task Generator'), 'TaskGeneratorController', 'generateTasks', 
             array('plugin' => 'OpenAITaskGenerator', 'project_id' => $project['id']), t('AI Task Generator')) ?>
</li>
