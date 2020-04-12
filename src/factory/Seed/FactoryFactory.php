<?php


namespace Smartymoon\Generator\Factory\Seed;

use Smartymoon\Generator\Factory\BaseFactory;

class FactoryFactory extends BaseFactory
{

    protected $buildType = 'new';
    protected $stub = 'Factory/Factory.stub';
    protected $path = 'database/factories/';

    /**
     * @inheritDoc
     */
    public function buildContent($content)
    {

        $content = str_replace('DummyFakers', $this->makeFakers(), $content);

        return $content;
    }

    protected function getFileName()
    {
        return $this->ucModel . 'Factory';
    }

    private function makeFakers()
    {
        $content = "\n";
        foreach($this->fields as $field) {
            $content .= $this->tab(2).$field['faker']. "\n";
        }
        return $content;
    }
}
