<?php
namespace Smartymoon\Generator\Factory;


use Smartymoon\Generator\Config;
use Smartymoon\Generator\Factory\Enum\EnumFactory;
use Smartymoon\Generator\Factory\Enum\EnumLangFactory;

/**
 * Class EnumDirector
 * @package Smartymoon\Generator\Factory
 */
// 可能生成很多个 Enum 文件, 因此这里是循环生成 Enum 文件
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
        foreach($this->config->enums as $enum) {
            $enum_factory = app(EnumFactory::class);

            $enum_factory->initEnum($enum);
            $enum_content = $enum_factory->buildContent(
                $enum_factory->getTemplate()
            );
            $enum_path = $enum_factory->getFilePath();

            $enum_dir = \Str::beforeLast($enum_path, '/');
            if (!is_dir($enum_dir)) {
                mkdir($enum_dir, 0777, true);
            }
            file_put_contents($enum_path, $enum_content);
        }
    }
}
