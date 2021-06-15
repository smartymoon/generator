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
    public function buildContent(string $content): string
    {
        $content = str_replace('DummyModelPathInView', $this->modelPathInView(), $content);
        return $content;
    }

    public function getFilePath(): string
    {
        return base_path('resources/js/Pages/') . $this->modelPathInView() . '/views/Index.vue';
    }


    public function getTemplate(): string
    {
        return $this->getStub('vue/index.stub');
    }
}
