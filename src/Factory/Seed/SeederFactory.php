<?php


namespace Smartymoon\Generator\Factory\Seed;

use Illuminate\Support\Facades\Artisan;
use Smartymoon\Generator\Factory\BaseFactory;

class SeederFactory extends BaseFactory
{

    protected $buildType = 'new';
    protected $stub = 'seeder/seeder.stub';
    protected $path = 'database/seeds/';

    /**
     * @inheritDoc
     */
    public function buildContent($content)
    {
        $content = str_replace('DummyClass', $this->getFileName(), $content);
        $content = str_replace('DummySeedTimes', $this->seed_times, $content);

        return $content;
    }

    protected function getFileName()
    {
        return $this->ucModel . 'Seeder';
    }

    protected function afterGenerate()
    {
        exec('cd '. base_path() .  '&& composer dump-autoload');
        // sleep(15);
        exec('cd '. base_path() . '&& php artisan db:seed --class '. $this->getFileName());
    }
}
