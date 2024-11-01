<?php

namespace Kanboard\Plugin\OpenAITaskGenerator;

use Kanboard\Core\Plugin\Base;

class Plugin extends Base
{
    public function initialize()
    {
        // Add a route for generating tasks
        $this->route->addRoute('/openai/generate-task', 'TaskGeneratorController', 'generateTasks', 'OpenAITaskGenerator');
  
        $this->template->hook->attach('template:config:sidebar', 'openAITaskGenerator:config/sidebar');         
        $this->template->hook->attach('template:project:dropdown', 'openAITaskGenerator:project/project_header/dropdown'); 
    }


    public function getPluginName()
    {
        return 'OpenAITaskGenerator';
    }

    public function getPluginDescription()
    {
        return 'Generate tasks based on a goal using OpenAI and add them to the project';
    }

    public function getPluginAuthor()
    {
        return 'Damien Trog';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/dtrog/plugin_OpenAITaskGenerator';
    }
}
