<?php
namespace Smartymoon\Generator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Smartymoon\Generator\Exceptions\GenerateException;

class Config
{
    /**
     * 模块的子文件夹, 在模块比较复杂时使用，如 decision, 管理决策系统
     * @var string | /
     */
    public string $module;
    private string $model;

    /**
     * $fields = [
     *   'field_name' => (string) 字段名
     *   'belongsTo' => (bool) 是否为关联字段，example: user_id
     *   'type' => (string) string, unsignedInteger...
     *   'migration' => nullable | default | comment | index | unique
     *   'migration_params' => [
     *        default: '',
     *        comment: ''
     *   ],
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
    /**
     * 初始化
     */
    public function __construct(Request $request)
    {
        // controller_namespace,
        $this->module = $request->input('module', '/');

        $this->seedTimes = $request->input('seed_times');

        // 单字词，多字词，
        $this->model = $request->input('model');

        // hasManyRelations;
        $this->setHasMany($request->input('hasMany', []));

        // $fields
        $this->setFields($config['fields']);

        // $may_create_files
        $this->setToCreateFiles($config['may_create_files']);
    }

    /**
     * 通过配置来的 model 进行简单变形
     * @param string $type plural | camel
     * @return string
     */
    public function getModel(string $type): string
    {
        return Str::$type($this->model);
    }

    /**
     * 强行把 $hasMany 变成数组
     * @param  $hasMany
     */
    private function setHasMany(array|string $hasMany): void
    {
        $hasMany = is_array($hasMany) ? $hasMany : [$hasMany];
        $this->hasManyRelations = $hasMany;
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
}
