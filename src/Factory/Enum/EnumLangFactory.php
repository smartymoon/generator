<?php


namespace Smartymoon\Generator\Factory\Enum;


use Smartymoon\Generator\Factory\BaseFactory;

class EnumLangFactory extends BaseFactory
{

    protected $buildType = 'patch';
    protected $stub = '';
    protected $path = 'resources/lang/zh-CN/enums.php';

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

        $content = str_replace('//DummyBlock', $this->makeConst(), $content);
        return $content;
    }

    protected function getFileName()
    {
        return $this->fileName;
    }

    public function makeConst()
    {

        // DemandStatus::class => [
        //    DemandStatus::todo =>  '未处理',
        //    DemandStatus::doing =>  '处理中',
        //    DemandStatus::done =>  '完成',
        // ],

        //todo
        $class = 'App\Enums\\' .$this->fileName;
        $content = $this->tab(1) . $class . '::class => [' . "\n";
        foreach($this->enums as $enum) {
            $content .= $this->tab(2) . $class .'::' . $enum['english'] . ' => \''. $enum['chinese'] . "',\n";
        }
        $content .= $this->tab(1). '],'. "\n" . '//DummyBlock';
        return $content;
    }

}
