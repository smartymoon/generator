<?php
namespace Smartymoon\Generator\Factory\Vue;

use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class EditVueFactory
 * @package Smartymoon\Generator\Factory
 */
class ShowVueFactory extends MakeFactory implements FactoryContract
{
    public function buildContent(string $content): string
    {
        return $content;
    }

    public function getFilePath(): string
    {
        return base_path('resources/js/Pages/') . $this->modelPathInView() . '/Show.vue';
    }


    public function getTemplate(): string
    {
        return $this->getStub('vue/show.stub');
    }
}
