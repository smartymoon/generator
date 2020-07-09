<?php


namespace Smartymoon\Generator\Factory\Controller;

use Smartymoon\Generator\Factory\BaseFactory;

class ControllerFactory extends BaseFactory
{

    protected $buildType = 'new';
    protected $stub = 'controller/controller.stub';
    protected $path = 'app/Http/Controllers/';
    protected $isSimple = false;

    public function __construct($config)
    {
        if (!in_array('repository', $config['to_create_files'])) {
           $this->isSimple = true;
           $this->stub = 'controller/simpleController.stub'; 
        }
        parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function buildContent($content)
    {
        $content = str_replace('DummyNamespace', $this->getControllerNamespace(), $content);
        $content = str_replace('DummyShowBaseController', $this->getBaseController(), $content);
        $content = str_replace('DummyClass', $this->getFileName(), $content);

        return $content;
    }

    protected function getFileName()
    {
        return $this->ucModel . 'Controller';
    }

    private function getControllerNamespace()
    {
        if($this->controller_namespace) {
            return 'App\Http\Controllers\\' . $this->controller_namespace;
        }
        return 'App\Http\Controllers';
    }

    protected function setPath()
    {
        if($this->controller_namespace) {
            $this->path = $this->path . $this->controller_namespace . '/';
        }
    }

    private function getBaseController()
    {
        return $this->controller_namespace ?
             'use App\Http\Controllers\Controller;':
              "";
    }

}
