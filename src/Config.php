<?php
namespace Smartymoon\Generator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Smartymoon\Generator\Exceptions\GenerateException;

/**
 * 用于处理所有的 Request 参数
 * @package Smartymoon\Generator
 */
class Config
{
    /**
     * 模块的子文件夹, 在模块比较复杂时使用，如 decision, 管理决策系统
     * @var string | /
     */
    public string $module;
    public bool $inModule;

    /**
     * $fields = [
     *   'field_name' => (string) 字段名
     *   'belongsTo' => (bool) 是否为关联字段，example: user_id
     *   'type' => (string) string, unsignedInteger...
     *   'migration' => nullable | default | comment | index | unique
     *   'foreign_policy' => cascade | restrict
     *   'foreign_table' => (string) 关连表
     *   'faker' => (function) example: rand(1, 6)
     *   'rules' => [
     *      [
     *          'rule' => unique|required,
     *          'message' => string
     *      ]
     *   ],
     *   'enums' => [
     *       'fileName' => (string),
     *       'list' => (string)[]
     *    ]
     * ]
     */
    public array $fields;
    public int $seedTimes;
    public array $hasManyRelations;
    public bool $hasRepository;
    public array $enums;

    private string $model;

    /**
     * 初始化
     * @param Request $request
     * @throws GenerateException
     */
    public function __construct(Request $request)
    {
        // controller_namespace,
        $this->module = $request->input('module') ?: '/';

        $this->seedTimes = $request->input('seed_times');

        // 单字词，多字词，
        $this->model = $request->input('model');

        // hasManyRelations;
        $this->setHasMany($request->input('hasMany', []));

        // $fields
        $this->setFields($request->input('fields'));

        $this->inModule =  $this->module === '/';

        $this->hasRepository = in_array('repository', $request->input('to_create_files'));

        // enums
        $this->setEnums($this->fields);

    }

    /**
     * 通过配置来的 model 进行简单变形
     * @param string $type plural | camel | studly | snake
     * @return string
     */
    public function getModel(string $type): string
    {
        return Str::$type($this->model);
    }

    /**
     * studly String
     * @return string
     */
    public function getModule(): string
    {
       return Str::studly($this->module);
    }

    /**
     * 强行把 $hasMany 变成数组
     * @param string | array $has_many
     */
    private function setHasMany($has_many): void
    {
        $this->hasManyRelations = is_array($has_many) ? $has_many : [$has_many];
    }


    /**
     * @param $fields
     * @throws GenerateException 无 _id, 不能 belongsTo
     */
    private function setFields($fields)
    {
        foreach($fields as $field) {
            if (!Str::endsWith( $field['field_name'], '_id') && $field['belongsTo'] == true) {
                throw new GenerateException($field['field_name']. ' 无法设置为 BelongsTo');
            }
        }

        $this->fields = $fields;
    }

    private function setEnums(array $fields): void
    {
        $enums = [];
        foreach($fields as $field) {
            if ($field['enum']['fileName'] && count($field['enum']['list']) > 0 ) {
                $enums[] = $field['enum'];
            }
        }
        $this->enums = $enums;
    }
}

