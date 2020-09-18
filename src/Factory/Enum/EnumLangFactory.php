<?php


namespace Smartymoon\Generator\Factory\Enum;


use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

class EnumLangFactory extends MakeFactory implements FactoryContract
{
    protected $stubFile = 'resources/lang/zh-CN/enums.php';

    protected $fileName;
    protected $enums;

    public function initEnum(array $enum)
    {
        $this->fileName = $enum['fileName'];
        $this->enums = $enum['list'];
    }

    public function buildContent(): string
    {
        return str_replace('//DummyBlock', $this->makeConst(), $this->getStub($this->stubFile));
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

}
