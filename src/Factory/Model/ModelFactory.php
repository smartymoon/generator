<?php
namespace Smartymoon\Generator\Factory\Model;

use Illuminate\Support\Str;
use Smartymoon\Generator\Factory\BaseFactory;

class ModelFactory extends BaseFactory
{
    /*
     * 可选 new,  replace
     * new: 制做新的文件
     * replace:
     */
    protected $buildType = 'new';
    protected $stub = 'model/model.stub';
    protected $path = 'app/Models/';


    /**
     * @inheritDoc
     */

    // 1, 简单词替换 (无模板)

    // 2. 块替换, migrations, Factory, validation(todo) (无模板)

    // 3. 函数模板 (有模板)

    // 4. 给已有文件打补丁 （route）

    /*
     * @return string $content
     */
    public function buildContent($content)
    {
        $content = str_replace('DummyFillable', $this->makeFillable(), $content);
        $content = str_replace('DummyHasMany', $this->makeHasMany(), $content);
        $content = str_replace('DummyBelongsTo', $this->makeBelongsTo(), $content);

        // 1. get stub
        return $content;
    }

    protected function getFileName()
    {
        return $this->ucModel;
    }

    private function makeFillable()
    {
        return collect($this->fields)->pluck('field_name')->map(function($field) {
           return "'${field}'";
        })->implode(',');
    }

    private function makeHasMany()
    {
        if (!$this->hasMany) return '';

        $content = $this->getStub('model/hasMany.stub');
        foreach($this->hasMany as $hasMany) {
           $content =  str_replace('DummyRelation', $this->tableName($hasMany), $content);
           $content =  str_replace('DummyHasManyModel', $hasMany, $content);
        }
        return $content;
    }

    private function makeBelongsTo()
    {
        $function_tpl = $this->getStub('model/belongsTo.stub');
        $content = '';
        foreach($this->fields as $field) {
            if ($field['belongsTo']) {
                $function_name = substr($field['field_name'], 0, -3);
                $content_temp =  str_replace('DummyRelation',
                    $function_name,
                    $function_tpl);
                $content_temp = str_replace('DummyBelongsToModel',
                    Str::studly($function_name),
                    $content_temp);
                $content .= $content_temp;
            }
        }
        return $content;
    }

}
