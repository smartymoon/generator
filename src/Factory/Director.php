<?php


namespace Smartymoon\Generator\Factory;

use Smartymoon\Generator\Exceptions\GenerateException;
use Smartymoon\Generator\Factory\Controller\ControllerFactory;
use Smartymoon\Generator\Factory\Enum\EnumFactory;
use Smartymoon\Generator\Factory\Enum\EnumLangFactory;
use Smartymoon\Generator\Factory\Model\MigrationFactory;
use Smartymoon\Generator\Factory\Model\ModelFactory;
use Smartymoon\Generator\Factory\Model\RepositoryFactory;
use Smartymoon\Generator\Factory\Request\RequestFactory;
use Smartymoon\Generator\Factory\Resource\CollectionResourceFactory;
use Smartymoon\Generator\Factory\Resource\ResourceFactory;
use Smartymoon\Generator\Factory\Route\RouteFactory;
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
         'route' => RouteFactory::class,
    ];

    /**
     * 制作文件入口
     * @param $to_create_files
     * @return false|int
     */
    public static function launch($to_create_files)
    {
        if (in_array('repository', $to_create_files)) {
            $to_create_files[] = 'resource';
            $to_create_files[] = 'collectionResource';
        }

        $to_create_files = array_merge($to_create_files, [
            'model', 'migration', 'factory', 'seeder', 'databaseSeeder', 'controller'
        ]);

        foreach ($to_create_files as $file_key) {
            $factory = app(self::$factories[$file_key]);

            $content = $factory->initContent(
                $factory->getTemplate()
            ); // 初始化内容
            $content = $factory->buildContent($content); // 子组件
            $file_path = $factory->getFilePath();

            // throw new GenerateException($file_path);
            $file_dir = \Str::beforeLast($file_path, '/');
            if (!is_dir($file_dir)) {
                mkdir($file_dir, 0777, true);
            }
            file_put_contents($file_path, $content);
        }

        app(EnumDirector::class)->launch();
    }
}
