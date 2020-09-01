<?php


namespace Smartymoon\Generator\Factory\Seed;

use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class FactoryFactory
 * @package Smartymoon\Generator\Factory\Seed
 */
class FactoryFactory extends MakeFactory implements FactoryContract
{
    protected string $stubFile = 'factory/factory.stub';

    public function buildContent(): string
    {
        $content =  str_replace('DummyFakers', $this->makeFakers(), $this->getStub($this->stubFile));
        $content = $this->modelReplaces($content);
        return $content;
    }

    public function getFilePath(): string
    {
        return $this->dealModulePath(base_path('database/factories/')) . $this->getModelClass() . 'Factory.php';
    }


    private function makeFakers(): string
    {
        $content = "\n";
        foreach($this->config->fields as $field) {
            $content .= $this->tab(2). $this->makeFaker($field['field_name'], $field['faker']). "\n";
        }
        return $content;
    }

    private function makeFaker(string $field_name, string $faker): string
    {
        if ($faker) {
            return "'$field_name' => "
                .('Faker::'.$faker)
                .',';
        }
        return '';
    }

}
