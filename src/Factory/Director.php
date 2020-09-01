<?php


namespace Smartymoon\Generator\Factory;

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
use Smartymoon\Generator\GenerateLog;

/**
 * 处理各种工厂
 * Class Director
 * @package Smartymoon\Generator\Factory
 */
class Director
{
    public static array $factories = [
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
    ];

    /**
     * 制作文件入口
     * @param $to_create_files
     * @return false|int
     */
    public static function launch($to_create_files)
    {
        // todo 用 laravel 容器，避免重复 new Config

        if (in_array('repository', $to_create_files)) {
            $to_create_files[] = 'resource';
            $to_create_files[] = 'collectionResource';
        }

        $to_create_files = array_merge($to_create_files, [
            'model', 'migration', 'factory', 'seeder', 'databaseSeeder'
        ]);

        foreach ($to_create_files as $file_key) {
            $factory = new self::$factories[$file_key]();

            $content = $factory->buildContent();
            $file_path = $factory->getFilePath();

            return file_put_contents($file_path, $content);
        }

        $enum_director = new EnumDirector(Config::class);
        $enum_director->launch();

    }
}
