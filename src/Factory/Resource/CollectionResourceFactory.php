<?php

namespace Smartymoon\Generator\Factory\Resource;

use Smartymoon\Generator\Factory\BaseFactory;

class CollectionResourceFactory extends BaseFactory
{
    use ResourceFactoryTrait;

    protected $buildType = 'new';
    protected $stub = 'resource/collectionResource.stub';
    protected $path = 'app/Http/resources/';
    protected $field_tabs = 4;

    protected function getFileName()
    {
        return $this->ucModel . 'CollectionResource';
    }
}
