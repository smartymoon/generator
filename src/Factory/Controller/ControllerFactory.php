<?php
/**
 * 实验性质搞搞 Controller
 */

namespace Smartymoon\Generator\Factory\Controller;


use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class ControllerFactory2
 * @package Smartymoon\Generator\Factory\Controller
 */
class ControllerFactory extends MakeFactory implements FactoryContract
{
    public function buildContent(): string
    {
        $content = str_replace(
            'DummyNamespace',
            $this->dealModuleNamespace('App\Http\Controller'),
            $this->getStub(
             $this->config->hasRepository ?
                 'controller/controller.stub' : 'controller/simpleController.stub'
            )
        );

        $content = str_replace(
            'DummyShowBaseController',
            $this->config->inModule ? 'use App\Http\Controllers\BaseController;' : '' ,
            $content
        );

        $content = str_replace('DummyClass', $this->getModelClass() . 'Controller', $content);
        $content = $this->modelReplaces($content);
        return $content;
    }

    public function getFilePath(): string
    {
        $base_path = base_path('app/Http/Controller/');
        return $this->dealModulePath($base_path) . $this->getModelClass() . 'Controller' . '.php';
    }
}
