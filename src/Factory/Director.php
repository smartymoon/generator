<?php


namespace Smartymoon\Generator\Factory;

use Smartymoon\Generator\Factory\Admin\AdminFactory;
use Smartymoon\Generator\Factory\Controller\ControllerFactory;
use Smartymoon\Generator\Factory\Enum\EnumFactory;
use Smartymoon\Generator\Factory\Enum\EnumLangFactory;
use Smartymoon\Generator\Factory\Model\MigrationFactory;
use Smartymoon\Generator\Factory\Model\ModelFactory;
use Smartymoon\Generator\Factory\Model\RepositoryFactory;
use Smartymoon\Generator\Factory\Request\RequestFactory;
use Smartymoon\Generator\Factory\Resource\CollectionResourceFactory;
use Smartymoon\Generator\Factory\Resource\ResourceFactory;
use Smartymoon\Generator\Factory\Seed\DatabaseSeederFactory;
use Smartymoon\Generator\Factory\Seed\FactoryFactory;
use Smartymoon\Generator\Factory\Seed\SeederFactory;

class Director
{

    private $config;

    // 注册需要 make Factory
    public $factories = [
         'model' => ModelFactory::class,
         'migration' => MigrationFactory::class,
         'factory' => FactoryFactory::class,
         'seeder' => SeederFactory::class,
         'databaseSeeder' => DatabaseSeederFactory::class,
         'controller' => ControllerFactory::class,
         'resource' => ResourceFactory::class,
         'collectionResource' => CollectionResourceFactory::class,
         'repository' => RepositoryFactory::class,
         'request' => RequestFactory::class,
         'admin' => AdminFactory::class,
    ];

    /**
     * @var array
     */
    private $to_create_files;

    public function __construct($config)
    {
        $this->config = $config;
        $this->to_create_files = $config['to_create_files'];
        $this->enums = $config['enums'];
    }

    public function launch()
    {
        // 传统文件
        foreach($this->factories as $key => $Factory) {
            if (in_array($key, $this->to_create_files)) {
                dump('making ' . $key);
                (new $Factory($this->config))->generateFile();
            }
        }

        // enum 文件
        foreach($this->enums as $enum) {
            dump('making enum ' . $enum['fileName']); 
            (new EnumFactory($this->config, $enum))->generateFile();
            (new EnumLangFactory($this->config, $enum))->generateFile();
        }
    }
}
