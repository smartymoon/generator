<?php


namespace Smartymoon\Generator\Factory\Resource;

use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class ResourceFactory
 * @package Smartymoon\Generator\Factory\Resource
 */
class ResourceFactory extends MakeFactory implements FactoryContract
{
    use ResourceFactoryTrait;

    protected string $stubFile = 'resource/resource.stub';
    protected int $fieldTabs = 3;

    protected function getFileName(): string
    {
        return $this->getModelClass() . 'Resource';
    }

    public function getFilePath(): string
    {
       return  $this->dealModulePath(base_path('app/Http/resources/'))
               .$this->getModelClass()
               . '/' . $this->getFileName() . 'php';
    }
}
