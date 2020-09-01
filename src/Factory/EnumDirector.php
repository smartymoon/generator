<?php
namespace Smartymoon\Generator\Factory;


use Smartymoon\Generator\Config;
use Smartymoon\Generator\Factory\Enum\EnumFactory;
use Smartymoon\Generator\Factory\Enum\EnumLangFactory;

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
        foreach($this->config->enums as $enum) {
            $enum_factory = app(EnumFactory::class);
            $enum_lang = app(EnumLangFactory::class);

            $enum_factory->initEnum($enum);
            $enum_content = $enum_factory->buildContent();
            $enum_path = $enum_factory->getFilePath();
            file_put_contents($enum_path, $enum_content);

            $enum_lang->initEnum($enum);
            $enum_lang_content = $enum_lang->buildContent();
            $enum_lang_path = $enum_lang->getFilePath();
            file_put_contents($enum_lang_path, $enum_lang_content);
        }
    }
}
