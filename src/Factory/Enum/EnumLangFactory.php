<?php


namespace Smartymoon\Generator\Factory\Enum;


use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class EnumLangFactory
 * @package Smartymoon\Generator\Factory\Enum
 */
class EnumLangFactory extends MakeFactory implements FactoryContract
{
    protected string $stubFile = 'resources/lang/zh-CN/enums.php';

    protected string $fileName;
    protected array $enums;

    public function initEnum(array $enum): void
    {
        $this->fileName = $enum['fileName'];
        $this->enums = $enum['list'];
    }

    public function buildContent(string $content): string
    {
        return str_replace('//DummyBlock', $this->makeConst(), $content);
    }

    public function getFilePath(): string
    {
        return base_path($this->stubFile);
    }

    public function makeConst(): string
    {

        // DemandStatus::class => [
        //    DemandStatus::todo =>  '未处理',
        //    DemandStatus::doing =>  '处理中',
        //    DemandStatus::done =>  '完成',
        // ],

        $class = 'App\Enums\\' .$this->fileName;
        $content = $this->tab(1) . $class . '::class => [' . "\n";
        foreach($this->enums as $enum) {
            $content .= $this->tab(2) . $class .'::' . $enum['english'] . ' => \''. $enum['chinese'] . "',\n";
        }
        $content .= $this->tab(1). '],'. "\n" . '//DummyBlock';
        return $content;
    }

    public function getTemplate(): string
    {
        return $this->getStub($this->stubFile);
    }
}
