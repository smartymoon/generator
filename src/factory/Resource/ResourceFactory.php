<?php


namespace Smartymoon\Generator\Factory\Resource;

use Smartymoon\Generator\Factory\BaseFactory;

class ResourceFactory extends BaseFactory
{
    use ResourceFactoryTrait;

    protected $buildType = 'new';
    protected $stub = 'resource/resource.stub';
    protected $path = 'app/Http/resources/';
    protected $field_tabs = 3;

    protected function getFileName()
    {
        return $this->ucModel . 'Resource';
    }
}
