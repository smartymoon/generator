<?php


namespace Smartymoon\Generator\Factory\Enum;


use Smartymoon\Generator\Factory\BaseFactory;

class EnumFactory extends BaseFactory
{

    protected $buildType = 'new';
    protected $stub = 'enum/Enum.stub';
    protected $path = 'app/Enums/';

    protected $fileName;
    protected $enums;


    public function __construct($config, $enum)
    {
       $this->fileName = $enum['fileName']; 
       $this->enums = $enum['list']; 
       parent::__construct($config);
    }

    /**
     * @inheritDoc
     */
    public function buildContent($content)
    {

        $content = str_replace('DummyClass', $this->fileName, $content);
        $content = str_replace('DummyConst', $this->makeConst(), $content);

        return $content;
    }

    protected function getFileName()
    {
        return $this->fileName;
    }

    public function makeConst()
    {
        //const ToSale =   0;
        //const QiFang =   1;
        //const XianFang = 2;
        //const WeiPan = 4;

        //todo
        $content = '';
        foreach($this->enums as $key => $enum) {
            $content .= $this->tab(1) . 'const ' . $enum['english'] . ' = ' . ($key + 1) . ';' . "\n";
        }
        return $content;
    }

}
