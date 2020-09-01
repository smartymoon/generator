<?php
namespace Smartymoon\Generator\Factory;


use Smartymoon\Generator\Config;

/**
 * Class EnumDirector
 * @package Smartymoon\Generator\Factory
 */
class EnumDirector
{
    /**
     * @var Config
     */
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function launch()
    {
        // enum 文件
        // 从前端把 enums 提取出来
        foreach($this->enums as $enum) {
            (new EnumFactory($this->config, $enum))->generateFile();
            (new EnumLangFactory($this->config, $enum))->generateFile();
        }
    }
}
