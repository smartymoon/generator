<?php
namespace Smartymoon\Generator;
use Smartymoon\Generator\factory\Director;
use Illuminate\Support\Str;

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
    public $admin_menu = '';
    public $fields = [];
    public $to_create_files = [];
    public $has_many_relations = [];
    public $controller_namespace;
    public $route_file;
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

        dump('git local files');
        dump(shell_exec('cd '. $base_path . ' && git commit -am  \'for sm:generate commit\''));


        // step 1 (数据): 通过一个配置文件,检查数据(fields 的方法是否存在)
        $this->setAttributes($this->all_config);

        // step 2 (文件):
        $this->makeFiles();

        // step 3 (auto-load):
        $this->dumpAutoLoad();

        // step 4 (结果): git 实现
        dump(shell_exec('cd '. $base_path .'&& git status'));

        // $this->getCurrentProperties();
        dump('生成完毕');
    }

    private function setAttributes($config)
    {
        // controller_namespace
        $this->controller_namespace = $config['controller_namespace'];

        // admin_menu
        $this->admin_menu = $config['admin_menu'];

        // route_file
        $this->route_file = $config['route_file'];

        // seed_times
        $this->seed_times = $config['seed_times'];

        // model name
        if (!$config['model']) {
            dd('model 名不能为空');
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
            $fieldMaker = new FieldMaker($field['field_name']);
            if (!Str::endsWith( $field['field_name'], '_id') && $field['belongsTo'] == true) {
                dump($field['field_name']. ' 无法设置为 BelongsTo');
                die();
            }
            return [
                // field_name
                'field_name' => $field['field_name'],

                // ifBelongsTo
                'belongsTo' => $field['belongsTo'],

                // type
                'type' => $fieldMaker->makeFieldType($field['type']),

                // migrations
                'migrations' => $fieldMaker->makeMigrations($field['migration']),

                // foreign
                'foreign' => $fieldMaker->makeForeign($field['field_name'], $field['foreign']),

                // faker
                'faker' => $fieldMaker->makeFaker($field['faker']),

                // rules
                'rules' => $fieldMaker->makeRules($field),

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
        if (in_array('repository', $may_create_files)) {
            $this->to_create_files[] = 'collectionResource';
        }
    }


    private function makeFiles()
    {
        $directory = new Director([
            'fields' => $this->fields,
            'model' => $this->model,
            'admin_menu' => $this->admin_menu,
            'fields' => $this->fields,
            'to_create_files' => $this->to_create_files,
            'has_many_relations' => $this->has_many_relations,
            'controller_namespace' => $this->controller_namespace,
            'route_file' => $this->route_file,
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
        system('composer dump-autoload');
        dump('composer dump-autoload');
    }

}