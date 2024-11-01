<li <?= $this->app->checkMenuSelection('OpenAISettingsController', 'show', 'OpenAITaskGenerator') ?>>
    <?= $this->url->link(t('OpenAI Settings'), 'OpenAISettingsController', 'show', ['plugin' => 'OpenAITaskGenerator']) ?>
</li>