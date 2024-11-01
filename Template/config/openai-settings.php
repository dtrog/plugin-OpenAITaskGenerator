<fieldset id="OpenAISettings" class="panel openai-settings">
    <h3 class="">
        <?= t('OpenAITaskGenerator') ?>
    </h3>

    <fieldset class="openai-settings">
    <form action="<?= $this->url->href('OpenAISettingsController', 'save', ['plugin' => 'OpenAITaskGenerator', 'project_id' => $project_id]) ?>" method="post">
    <legend class=""><?= t('OpenAI API Key') ?></legend>
        <p class=""><?= t('Adjust the settings below to set the api key for OpenAI') ?></p>
        <fieldset class="settings-subsection">
            <legend><?= t('API Key') ?></legend>
            <div class="openai-api-key">
                <?= $this->form->label(t('API Key'), 'openai_api_key', array('class=""')) ?>
                <?= $this->form->text('openai_api_key', $values, $errors, array(), 'openai-api-key-text') ?>
            </div>
        </fieldset>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><?= t('Save') ?></button>
        </div>
    </fieldset>
</fieldset>
