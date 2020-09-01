<?php


namespace Smartymoon\Generator\Factory\Seed;

use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class DatabaseSeederFactory
 * @package Smartymoon\Generator\Factory\Seed
 */
class DatabaseSeederFactory extends MakeFactory implements FactoryContract
{

    protected string $path;

    public function buildContent(): string
    {
        $this->path = base_path('database/seeds/DatabaseSeeder.php');
        return str_replace('//DummySeeder', $this->injectSeeder(), $this->getRealFile($this->path));
    }

    public function getFilePath(): string
    {
        return $this->path;
    }

    private function injectSeeder(): string
    {
        return '$this->call('.$this->getModelClass().'Seeder::class);'."\n".
               $this->tab(2).'//DummySeeder';
    }

}
