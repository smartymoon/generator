<?php


namespace Smartymoon\Generator;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class FieldMaker
{

    private $right_methods = [];

    public $field_name;

    /**
     * FieldMaker constructor.
     * @param $field_name
     */
    public function __construct($field_name)
    {
        $this->field_name = $field_name;
        // $this->FunctionMaker = New FunctionMaker();
        $this->right_methods = array_merge(get_class_methods(Blueprint::class), [
            'after',
            'always',
            'autoIncrement',
            'change',
            'charset',
            'collation',
            'comment',
            'default',
            'first',
            'generatedAs',
            'index',
            'nullable',
            'primary',
            'spatialIndex',
            'storedAs',
            'unique',
            'unsigned',
            'useCurrent',
            'virtualAs',
            'persisted',
        ]);
    }

    public function makeForeign($field, $foreign)
    {
        if (!$foreign) {
            return '';
        }
        $trigger = $foreign;
        $table = Str::plural(substr($field, 0, -3));
        if (!in_array($trigger, ['restrict', 'cascade'])) {
            dd('表字段 '. $this->field_name . ' 的外键 trigger  '. $trigger . ' 不存在');
        }
        return '$table'."->foreign('$this->field_name')->references('id')->on('${table}')->onDelete('${trigger}');";

    }


    public function makeFieldType($field_config_type)
    {
        // type 可能 abc() , abc
        $this->checkMigrateMethod($field_config_type);
        $pos = strpos($field_config_type, '(');
        if ($pos === false) {
            return $field_config_type . '(\''. $this->field_name . '\')';
        } else {
            return str_replace('(', '(\''. $this->field_name .'\', ', $field_config_type);
        }
    }

    public function makeMigrations($migrations)
    {
        return array_map(function($migrate) {
            $this->checkMigrateMethod($migrate);
            return $pos = strpos($migrate, '(') ? $migrate : $migrate . '()'; 
        }, $migrations);
    }

    public function makeFaker($faker)
    {
        if ($faker) {
            return "'$this->field_name' => "
                .($faker ? 'Faker::'.$faker : '')
                .',';
        }
        return '';
    }

    public function makeRules($field)
    {
        return $field['rules'];
    }

    public function checkMigrateMethod(String $method)
    {
        $pos = strpos($method, '(');
        $toCheck = $pos === false ? $method : 
            substr($method, 0, $pos);
        if(!in_array($toCheck, $this->right_methods)) {
            dd('表字段'. $this->field_name . '的类型 '. $toCheck. ' 不存在');
        }
    }
}
