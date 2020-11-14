<?php
namespace Smartymoon\Generator;
use Smartymoon\Generator\factory\Director;
use Illuminate\Support\Str;
use Smartymoon\Generator\Exceptions\GenerateException;

class Manager {

    /**
     * 一定要做的文件
     *
     *  model,migration,factory,seeder
     *
     * --------------------
     *
     * 可能要做的文件
     * @var array
     */

    protected $must_create_files = [
     'model','migration','factory','seeder', 'databaseSeeder'
    ];

    public $model = '';
    public $fields = [];
    public $to_create_files = [];
    public $has_many_relations = [];
    public $controller_namespace;
    public $seed_times;
    public $all_config;

    public function __construct($all_config)
    {
       $this->all_config = $all_config;
    }


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // step 0: git commit
        $base_path = base_path();

        GenerateLog::record('git commit local files');
        shell_exec('cd '. $base_path . ' && git commit -am  \'for sm:generate commit\'');

        // step 1 (数据): 通过一个配置文件,检查数据(fields 的方法是否存在)
        $this->setAttributes($this->all_config);

        // step 2 (文件):
        $this->makeFiles();

        // step 3 (auto-load):
        $this->dumpAutoLoad();

    }

    private function setAttributes($config)
    {
        // controller_namespace
        $this->controller_namespace = $config['controller_namespace'];

        // seed_times
        $this->seed_times = $config['seed_times'];

        // model name
        if (!$config['model']) {
            throw new GenerateException('model 名不能为空');
        }

        $this->model = $config['model'];

        // hasManyRelations;
        $this->setHasMany($config['hasMany']);

        // $fields
        $this->setFields($config['fields']);

        // $enums
        $this->setEnums($config['fields']);

        // $may_create_files
        $this->setToCreateFiles($config['may_create_files']);
    }

    private function setEnums($fields)
    {
        $data = [];
        foreach($fields as $field) {
            if ($field['enum']['fileName'] && count($field['enum']['list']) > 0 ) {
                $data[] = $field['enum'];
            }
        }
        $this->enums = $data;
    }

    private function setHasMany($hasMany)
    {
        $hasMany = is_array($hasMany) ? $hasMany : [$hasMany];
        $this->has_many_relations = $hasMany;
    }

    private function setFields($fields)
    {
        $new_fields = array_map( function ($field) {

            if (!Str::endsWith( $field['field_name'], '_id') && $field['belongsTo'] == true) {
                throw new GenerateException($field['field_name']. ' 无法设置为 BelongsTo');
            }
            return [
                // field_name
                'field_name' => $field['field_name'],

                // ifBelongsTo
                'belongsTo' => $field['belongsTo'],

                // type
                'type' => $field['type'],

                // migrations
                'migration' => $field['migration'],

                // foreign
                // 'foreign' => $fieldMaker->makeForeign($field['field_name'], $field['foreign']),
                'foreign_policy' => $field['foreign_policy'],

                'foreign_table' => $field['foreign_table'],

                // faker
                'faker' => $field['faker'],

                // rules
                'rules' => $field['rules'],

                // enums
            ];
        }, $fields);

        $this->fields = $new_fields;
    }

    private function setToCreateFiles($may_create_files)
    {
        $this->to_create_files = array_merge(
            $may_create_files,
            $this->must_create_files,
        );
    }

    private function makeFiles()
    {
        $directory = new Director([
            'fields' => $this->fields,
            'model' => $this->model,
            'fields' => $this->fields,
            'to_create_files' => $this->to_create_files,
            'has_many_relations' => $this->has_many_relations,
            'controller_namespace' => $this->controller_namespace,
            'seed_times' => $this->seed_times,
            'enums' => $this->enums
        ]);
        $directory->launch();
    }

    private function getCurrentProperties()
    {
        dump('model', $this->model);
        dump('must_create_files', $this->must_create_files);
        dump('may_create_file', $this->may_create_files);
        dump('to_create_files', $this->to_create_files);
        dump('fields', $this->fields);
        dump('has_many_relations', $this->has_many_relations);
    }

    private function dumpAutoLoad()
    {
        exec('composer dump-autoload');
    }

}
