<?php
namespace Smartymoon\Generator\Factory;


use Smartymoon\Generator\Config;

/**
 * Class MakeFactory
 * @package Smartymoon\Generator\Factory
 */
class MakeFactory
{
    protected Config $config;

    public function __construct(Config $config) {
        $this->config = $config;
    }

    protected function getStub(string $stub_path): string
    {
        return  file_get_contents(__DIR__ . '/../stubs/' . $stub_path);
    }

    protected function getRealFile(string $path): string
    {
        return  file_get_contents($path);
    }

    protected function hasManyMethodName(string $name): string
    {
        return \Str::of($name)->camel()->plural();
    }

    protected function getModelClass(string $name = null): string
    {
        if (is_null($name)) {
            $name = $this->config->getModel('studly');
        }
       return \Str::studly($name);
    }

    protected function getModelVariable(string $name = null): string
    {
        if (is_null($name)) {
            $name = $this->config->getModel('camel');
        }
        return \Str::camel($name);
    }

    /**
     * return path finish with '/'
     * @param string $path
     * @return string
     */
    protected function dealModulePath(string $path): string
    {
        $module = $this->config->getModule();
        $path = \Str::finish($path, '/');
        if ($module != '/') {
            return \Str::finish($path . $module, '/');
        }
        return $path;
    }

    protected function replaceNamespace(string $base_namespace, string $stub): string
    {
        return str_replace(
            'DummyNamespace',
            $this->dealModuleNamespace($base_namespace),
            $this->getStub($stub)
        );
    }

    protected function tableName(string $name = null): string
    {
        $name = is_null($name) ? $this->config->getModel('snake') : $name;
        return \Str::plural(\Str::snake($name));
    }

    protected function tab($number = 2)
    {
        return str_repeat('    ', $number);
    }

    protected function dealModuleNamespace(string $namespace): string
    {
        $module = $this->config->getModule();
        if ($module === '/') {
            return $namespace;
        }
        return $namespace . '\\' . $module;
    }

    /**
     * 和 Model 相关的一些常见的替换
     * @param string $content
     * @return string
     */
    protected function modelReplaces(string $content): string
    {
        return str_replace(
            ['DummyUseModel', 'DummyModel', 'DummyVariableModel'],
            [
                $this->dealModuleNamespace('App\Models') . '\\' . $this->getModelClass(),
                $this->getModelClass(),
                $this->getModelVariable()
            ],
            $content
        );
    }
}
