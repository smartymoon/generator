<?php


namespace Smartymoon\Generator\Factory\Enum;

use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

class EnumFactory extends MakeFactory implements FactoryContract
{
    protected $stubFile = 'enum/Enum.stub';
    protected $path = 'app/Enums/';

    protected $fileName;
    protected $enums;

    public function initEnum(array $enum): string
    {
        $this->fileName = $enum['fileName'];
        $this->enums = $enum['list'];
    }

    public function buildContent(): string
    {
        $content = str_replace('DummyClass', $this->fileName, $this->getStub($this->stubFile));
        $content = str_replace('DummyConst', $this->makeConst(), $content);

        return $content;
    }

    public function getFilePath(): string
    {
        return base_path($this->path) . $this->fileName . '.php';
    }

    public function makeConst(): string
    {
        //const ToSale =   0;
        //const QiFang =   1;
        //const XianFang = 2;
        //const WeiPan = 4;
        $content = '';
        foreach($this->enums as $key => $enum) {
            $content .= $this->tab(1) . 'const ' . $enum['english'] . ' = ' . ($key + 1) . ';' . "\n";
        }
        return $content;
    }

}
