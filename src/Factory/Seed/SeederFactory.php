<?php


namespace Smartymoon\Generator\Factory\Seed;

use Illuminate\Support\Facades\Artisan;
use Smartymoon\Generator\Factory\BaseFactory;
use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class SeederFactory
 * @package Smartymoon\Generator\Factory\Seed
 */
class SeederFactory extends MakeFactory implements FactoryContract
{
    protected string $stubFile = 'seeder/seeder.stub';

    public function buildContent(): string
    {
        $content = str_replace('DummyClass', $this->getModelClass().'Seeder', $this->getStub($this->stubFile));
        $content = str_replace('DummySeedTimes', $this->config->seedTimes, $content);

        return $content;
    }

    public function getFilePath(): string
    {
        return $this->dealModulePath(base_path('database/seeds/')).$this->getModelClass().'Seeder.php';
    }
}
