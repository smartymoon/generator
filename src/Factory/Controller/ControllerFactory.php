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

    public function buildContent(string $content): string
    {
        $content = $this->replaceNamespace('App\Http\Controllers', $content);
        $content = str_replace('DummyClass', $this->getModelClass() . 'Controller', $content);
        $content = str_replace('DummyPathInView', $this->modelPathInView(), $content);
        return $content;
    }

    public function getFilePath(): string
    {
        $base_path = base_path('app/Http/Controllers/');
        return $this->dealModulePath($base_path) . $this->getModelClass() . 'Controller' . '.php';
    }

    public function getTemplate(): string
    {
        return $this->getStub(
        $this->config->hasRepository ? 'controller/controller.stub' : 'controller/simpleController.stub'
        );
    }
}
