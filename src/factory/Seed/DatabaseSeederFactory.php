<?php


namespace Smartymoon\Generator\Factory\Seed;

use Smartymoon\Generator\Factory\BaseFactory;

class DatabaseSeederFactory extends BaseFactory
{

    protected $buildType = 'patch';
    protected $stub = '';
    protected $path = 'database/seeds/DatabaseSeeder.php';

    /**
     * @inheritDoc
     */
    public function buildContent($content)
    {

        $content = str_replace('//DummySeeder', $this->injectSeeder(), $content);

        return $content;
    }

    protected function getFileName()
    {
        return $this->ucModel . 'Seeder';
    }

    private function injectSeeder()
    {
        // $this->call(SubjectSeeder::class);
        return '$this->call('.$this->ucModel.'Seeder::class);'."\n".
               $this->tab(2).'//DummySeeder';

    }
}
