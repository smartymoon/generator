<?php

namespace Smartymoon\Generator\Factory\Resource;

use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class CollectionResourceFactory
 * @package Smartymoon\Generator\Factory\Resource
 */
class CollectionResourceFactory extends MakeFactory implements FactoryContract
{
    use ResourceFactoryTrait;

    protected string $stubFile = 'resource/collectionResource.stub';
    protected int $fieldTabs = 4;

    protected function getFileName(): string
    {
        return $this->getModelClass() . 'CollectionResource';
    }

    public function getFilePath(): string
    {
        return  $this->dealModulePath(base_path('app/Http/resources/'))
            . $this->getModelClass()
            . '/' . $this->getFileName() . 'php';
    }
}
