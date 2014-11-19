<?php
namespace RrcomScriptMerge;

use Zend\Mvc\MvcEvent;

class Module
{
    protected $config = null;

    public function onBootstrap(MvcEvent $e)
    {
        $config = $e->getApplication()->getServiceManager()->get('Config');
        if(empty($config['rrcom-script-merge']) || empty($config['rrcom-script-merge']['enable'])) return;
        $this->config = $config['rrcom-script-merge'];
        $this->writeToFile();
    }

    public function writeToFile()
    {
        $scriptMerge = new ScriptMerge();
        $cssConfig = (isset($this->config['css']) && is_array($this->config['css'])) ? $this->config['css'] : array();
        $jsConfig = (isset($this->config['js']) && is_array($this->config['js'])) ? $this->config['js'] : array();
        foreach($cssConfig as $config) {
            $output = $config['output'];
            $files = $config['files'];
            $minify = $config['minify'];
            $scriptMerge->merge(ScriptMerge::TYPE_CSS, $files, $output, $minify);
        }
        foreach($jsConfig as $config) {
            $output = $config['output'];
            $files = $config['files'];
            $minify = $config['minify'];
            $scriptMerge->merge(ScriptMerge::TYPE_JAVASCRIPT, $files, $output, $minify);
        }
    }
}
