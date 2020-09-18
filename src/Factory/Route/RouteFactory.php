<?php


namespace Smartymoon\Generator\Factory\Route;


use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;
use Str;

/**
 * Class RouteFactory
 * @package Smartymoon\Generator\Factory\Route
 */
class RouteFactory extends MakeFactory implements FactoryContract
{

    protected string $stubFile;

    public function buildContent(string $content): string
    {
        $this->stubFile = base_path('routes/api.php');
        // 如果有子模块，路由就用单独的文件, 否则使用默认路由
        if ($this->config->inModule) {
            // 如果没有就新建
            $this->stubFile = base_path('routes/') . Str::camel($this->config->module) . '.php';
            if (!file_exists($this->stubFile)) {
                file_put_contents($this->stubFile, $this->getStub('route/baseRoute.stub'));
            }
        }

        return str_replace('//DummyRoute', $this->makeRoute(), $content);
    }

    public function getFilePath(): string
    {
        return $this->stubFile;
    }

    private function makeRoute(): string
    {
        $content = $this->getStub('route/routeBlock.stub');
        $content = str_replace(
            'DummyURI',
            Str::of($this->getModelClass())->snake()->replace('_', '-'),
            $content
        );
        return $content;
    }

    public function getTemplate(): string
    {
        return $this->getRealFile($this->stubFile);
    }
}
