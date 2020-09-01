<?php
namespace Smartymoon\Generator\Factory\Model;

use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class ModelFactory
 * @package Smartymoon\Generator\Factory\Model
 */
class ModelFactory extends MakeFactory implements FactoryContract
{
    public function buildContent(): string
    {
        $content = $this->replaceNamespace('App\Models', 'model/model.stub');
        $content = str_replace('DummyFillable', $this->makeFillable(), $content);
        $content = str_replace('DummyHasMany', $this->makeHasMany(), $content);
        $content = str_replace('DummyBelongsTo', $this->makeBelongsTo(), $content);
        return $content;
    }

    public function getFilePath(): string
    {
        $base_path = base_path('app/Models/');
        return $this->dealModulePath($base_path) . $this->getModelClass() . '.php';
    }

    private function makeFillable(): string
    {
        return collect($this->config->fields)->pluck('field_name')->map(function($field) {
           return "'${field}'";
        })->implode(',');
    }

    private function makeHasMany(): string
    {
        if (!$this->config->hasManyRelations) return '';

        $content = $this->getStub('model/hasMany.stub');

        foreach($this->config->hasManyRelations as $relation) {
           $content =  str_replace('DummyRelation', $this->hasManyMethodName($relation), $content);
           $content =  str_replace('DummyHasManyModel', $this->getModelClass($relation), $content);
        }
        return $content;
    }

    private function makeBelongsTo(): string
    {
        $function_tpl = $this->getStub('model/belongsTo.stub');
        $content = '';
        foreach($this->config->fields as $field) {
            if ($field['belongsTo']) {
                $function_name = substr($field['field_name'], 0, -3);
                $content_temp =  str_replace(
                    'DummyRelation',
                    $function_name,
                    $function_tpl
                );
                $content_temp = str_replace(
                    'DummyBelongsToModel',
                    $this->getModelClass($function_name),
                    $content_temp
                );
                $content .= $content_temp;
            }
        }
        return $content;
    }

}
