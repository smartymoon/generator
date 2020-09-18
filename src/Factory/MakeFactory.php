<?php
namespace Smartymoon\Generator\Factory;


use Smartymoon\Generator\Config;
use Str;

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

    /**
     * $template 可能是真实文件，也可能是 stub
     *
     * @param string $template
     * @return string
     */
    public function initContent(string $template): string
    {
        return $this->commonReplaces($template);
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

    protected function dealModuleInUse(): string
    {
        $module = $this->config->getModule();
        if ($module === '/') {
            return '';
        }
        return Str::finish($module , '\\');
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
    protected function commonReplaces(string $content): string
    {
        return str_replace(
            ['DummyUseModel', 'DummyModel', 'DummyVariableModel', 'DummyModuleInUse'],
            [
                $this->dealModuleNamespace('App\Models') . '\\' . $this->getModelClass(),
                $this->getModelClass(),
                $this->getModelVariable(),
                $this->dealModuleInUse()
            ],
            $content
        );
    }
}
