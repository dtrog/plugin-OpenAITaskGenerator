<?php

namespace Kanboard\Plugin\OpenAITaskGenerator\Controller;

/**
 *
 * @author Damien Trog
 */

class OpenAISettingsController extends \Kanboard\Controller\PluginController
{

    public function show()
    {
        $this->response->html($this->helper->layout->config('OpenAITaskGenerator:config/openai-settings', array(
            'title' => t('OpenAITaskGenerator'),
        )));
    }


    /**
     * Save settings
     *
     */
    public function save()
    {
        $values = $this->request->getRawFormValues();
        $redirect = $this->request->getStringParam('redirect', 'config/openai-settings');

        if ($this->configModel->save($values)) {
            $this->languageModel->loadCurrentLanguage();
            $this->flash->success(t('Settings saved successfully'));
        } else {
            $this->flash->failure(t('Unable to save your settings'));
        }
        $this->response->redirect($this->helper->url->to('OpenAISettingsController', 'show', ['plugin' => 'OpenAITaskGenerator']));

    }
}

