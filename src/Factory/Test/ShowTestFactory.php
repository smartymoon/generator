<?php
namespace Smartymoon\Generator\Factory\Test;

use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * @package Smartymoon\Generator\Factory
 */
class ShowTestFactory extends MakeFactory implements FactoryContract
{
    public function buildContent(string $content): string
    {
        $content = $this->replaceNamespace('Tests\Feature', $content);
        $content = str_replace('DummyPathInView', $this->modelPathInView(), $content);
        return $content;
    }

    public function getFilePath(): string
    {
        return base_path('tests/Feature/') . $this->modelPathInView() . '/ShowTest.php';
    }


    public function getTemplate(): string
    {
        return $this->getStub('test/show.stub');
    }
}
