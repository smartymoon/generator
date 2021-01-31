<?php


namespace Smartymoon\Generator\Factory;

use Smartymoon\Generator\Exceptions\GenerateException;
use Smartymoon\Generator\Factory\Controller\ControllerFactory;
use Smartymoon\Generator\Factory\Enum\EnumFactory;
use Smartymoon\Generator\Factory\Enum\EnumLangFactory;
use Smartymoon\Generator\Factory\Model\MigrationFactory;
use Smartymoon\Generator\Factory\Model\ModelFactory;
use Smartymoon\Generator\Factory\Model\RepositoryFactory;
use Smartymoon\Generator\Factory\Route\RouteFactory;
use Smartymoon\Generator\Factory\Seed\DatabaseSeederFactory;
use Smartymoon\Generator\Factory\Seed\FactoryFactory;
use Smartymoon\Generator\Factory\Seed\SeederFactory;
use Smartymoon\Generator\Factory\Vue\IndexVueFactory;
use Smartymoon\Generator\Factory\Vue\ShowVueFactory;
use Smartymoon\Generator\Factory\Vue\EditVueFactory;
use Smartymoon\Generator\Factory\Test\IndexTestFactory;
use Smartymoon\Generator\Factory\Test\ShowTestFactory;
use Smartymoon\Generator\Factory\Test\EditTestFactory;
use Smartymoon\Generator\Factory\Test\DeleteTestFactory;
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
        'repository' => RepositoryFactory::class,
        'route' => RouteFactory::class,
        'index_vue' => IndexVueFactory::class,
        'show_vue' => ShowVueFactory::class,
        'edit_vue' => EditVueFactory::class,
        'index_test' => IndexTestFactory::class,
        'show_test' => ShowTestFactory::class,
        'edit_test' => EditTestFactory::class,
        'delete_test' => DeleteTestFactory::class,
    ];

    /**
     * 制作文件入口
     * @param $to_create_files
     * @return false|int
     */
    public static function launch($to_create_files)
    {
        if (($test_key = array_search('test', $to_create_files)) !== false) {
            unset($to_create_files[$test_key]);
            $to_create_files = array_merge($to_create_files, [
                'index_test',
                'show_test',
                'edit_test',
                'delete_test',
            ]);
        }

        if (($vue_key = array_search('vue', $to_create_files)) !== false) {
            unset($to_create_files[$vue_key]);
            $to_create_files = array_merge($to_create_files, [
                'index_vue',
                'show_vue',
                'edit_vue',
            ]);
        }

        $to_create_files = array_merge($to_create_files, [
            'model', 'migration', 'factory', 'seeder', 'databaseSeeder'
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
