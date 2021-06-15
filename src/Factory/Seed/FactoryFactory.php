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

    public function buildContent(string $content): string
    {
        $content = $this->replaceNamespace('Database\Factories', $content);
        $content =  str_replace('DummyFakers', $this->makeFakers(), $content);
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
            $enumsExp = null;
            if ($field['faker'] == 'enum') {
                if ($moduleInUse = $this->dealModuleInUse()) {
                    $enumsExp = '\App\Enums\\' . $moduleInUse . $field['enum']['fileName'] . '::toValues()';
                } else {
                    $enumsExp = '/App/Enums/' . $field['enum']['fileName'] . '::toValues()';
                }
                $field['faker'] = 'enum(' . $enumsExp . ')';
            }
            $content .= $this->tab(3). $this->makeFaker($field['field_name'], $field['faker']). "\n";
        }
        return $content;
    }

    private function makeFaker(string $field_name, string $faker, string $enum_file = null): string
    {
        if ($faker) {
            return "'$field_name' => "
                .('Faker::'.$faker)
                .',';
        }
        return '';
    }

    public function getTemplate(): string
    {
        return $this->getStub($this->stubFile);
    }
}
