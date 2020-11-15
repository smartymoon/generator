<?php
namespace Smartymoon\Generator\Factory\Test;

use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class EditVueFactory
 * @package Smartymoon\Generator\Factory
 */
class IndexTestFactory extends MakeFactory implements FactoryContract
{
    public function buildContent(string $content): string
    {
        $content = $this->replaceNamespace('Tests\Feature', $content);
        $content = str_replace('DummyPathInView', $this->modelPathInView(), $content);
        return $content;
    }

    public function getFilePath(): string
    {
        return base_path('tests/Feature/') . $this->modelPathInView() . '/IndexTest.php';
    }


    public function getTemplate(): string
    {
        return $this->getStub('test/index.stub');
    }
}
