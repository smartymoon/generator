<?php


namespace Smartymoon\Generator\Factory\Seed;

use Illuminate\Support\Facades\Artisan;
use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class SeederFactory
 * @package Smartymoon\Generator\Factory\Seed
 */
class SeederFactory extends MakeFactory implements FactoryContract
{
    protected string $stubFile = 'seeder/seeder.stub';

    public function buildContent(string $content): string
    {
        $content = $this->replaceNamespace('Database\Seeders', $content);
        $content = str_replace('DummyClass', $this->getModelClass().'Seeder', $content);
        $content = str_replace('DummySeedTimes', $this->config->seedTimes, $content);

        return $content;
    }

    public function getClassName(): string
    {
        return $this->getModelClass().'Seeder';
    }

    public function getFilePath(): string
    {
        return $this->dealModulePath(base_path('database/seeders/')). $this->getClassName() . '.php';
    }

    public function getTemplate(): string
    {
        return $this->getStub($this->stubFile);
    }
}
