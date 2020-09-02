<?php

namespace Smartymoon\Generator\Factory\Model;


use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class RepositoryFactory
 * @package Smartymoon\Generator\Factory\Model
 */
class RepositoryFactory extends MakeFactory implements FactoryContract
{
    protected string $stubFile = 'repository/repository.stub';

    /**
     * @inheritDoc
     */
    public function buildContent(): string
    {
        $content = $this->replaceNamespace('App\Repositories', $this->stubFile);
        $content = str_replace('DummyFields', $this->getFields(), $content);
        $content = $this->modelReplaces($content);
        $content = str_replace('DummyHas', $this->getHasMany(), $content);

        return $content;
    }

    public function getFilePath(): string
    {
        return $this->dealModulePath(base_path('app/Repositories')). $this->getModelClass() . 'Repository.php';
    }

    private function getFields(): string
    {
        $content = "";
        foreach ($this->config->fields as $field) {
            $field_name = $field['field_name'];
            $content .= "'$field_name', ";
        }
        return $content;
    }

    private function getHasMany(): string
    {
        if (count($this->config->hasManyRelations) == 0) return '';

        $content = '['."\n";
        foreach($this->config->hasManyRelations as $has_many) {
           $name = $this->hasManyMethodName($has_many);
           $content .=  $this->tab(3) . "'${name}', ". "\n";
        }
        return $content . $this->tab(2) . ']';
    }

}
