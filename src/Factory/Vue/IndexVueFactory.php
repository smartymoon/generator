<?php
namespace Smartymoon\Generator\Factory\Vue;

use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class IndexVueFactory
 * @package Smartymoon\Generator\Factory
 */
class IndexVueFactory extends MakeFactory implements FactoryContract
{
    protected string $stubFile = 'model/model.stub';

    public function buildContent(string $content): string
    {
        $content = $this->replaceNamespace('App\Models', $content);
        $content = str_replace('DummyFillable', $this->makeFillable(), $content);
        return $content;
    }

    public function getFilePath(): string
    {
        $base_path = base_path('app/Models/');
        return $this->dealModulePath($base_path) . $this->getModelClass() . '.php';
    }


    public function getTemplate(): string
    {
        return $this->getStub($this->stubFile);
    }
}
