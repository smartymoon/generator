<?php
namespace Smartymoon\Generator\Factory;

use Illuminate\Support\Str;

/**
 * 
 * 每个实现类只专注于一个文件
 * Class BaseFactory
 * 
 */
abstract class BaseFactory {

    /**
     * @var array
     */
    protected $fields;
    /**
     * @var array
     */
    protected $hasMany;
    /**
     * @var string
     */
    protected $model;


    protected $realPath;
    /**
     * @var GeneratorCommand
     */
    protected $commander;

    protected $ucModel;

    protected $lcModel;

    // 1, 简单词替换 (无模板)

    // 2. 块替换, migrations, Factory, validation(todo) (无模板)

    // 3. 函数模板 (有模板)

    // 4. 给已有文件打补丁 （route）
    abstract public function buildContent($content);
    abstract protected function getFileName();

    /**
     * BaseFactory constructor.
     * @param $allConfig
     */
    public function __construct($config)
    {
        $this->commander = $config;
        $this->admin_menu = $config['admin_menu'];
        $this->model = $config['model'];
        $this->controller_namespace = $config['controller_namespace'];
        $this->route_file = $config['route_file'];
        $this->seed_times = $config['seed_times'];
        $this->ucModel = ucfirst($this->model);
        $this->lcModel = lcfirst($this->model);
        $this->modelNamespace = 'App\Models\\'.$this->ucModel;
        $this->hasMany = $config['has_many_relations'];
        $this->fields = $config['fields'];
        $this->setPath();
        $this->realPath = base_path($this->path);
        if ($this->buildType == 'new' && !is_dir($this->realPath)) {
            mkdir($this->realPath);
        }
    }


    public function generateFile()
    {
        $this->beforeGenerate();

        if ($this->buildType == 'new') {
            $content = $this->buildContent($this->initContent());
            $file = $this->realPath . $this->getFileName() . '.php';
            $res = $this->putFile($file, $content);
        } else {
            $content = $this->buildContent($this->originContent());
            $file = $this->path;
            $res = $this->replaceFile($content);
        }

        if ($res) {
            dump('文件生成成功: '.$file);
        } else {
            dump('文件没生成: '.$file);
        }
        $this->afterGenerate();
    }


    protected function initContent()
    {
       $content = $this->getStub();

       // common replace Model Namespace , Model
       return str_replace(
           ['DummyModelNamespace', 'DummyModel', 'DummyLModel'],
           [$this->modelNamespace, $this->ucModel, $this->lcModel],
           $content
       );
    }

    private function originContent()
    {
        return file_get_contents($this->realPath);
    }

    /**
     * 在子函数中指定 stubFile
     * @param string $stub
     * @return string
     */
    protected function getStub($stub = '')
    {
        $content = '';
        $stub = $stub ? $stub : $this->stub;
        if (isset($stub)) {
            $content = file_get_contents(__DIR__. '/../stubs/'.$stub);
            if ($content === false)
            {
                dd('stub: '. $stub . '不存在');
            }
        }
        return $content;
    }

    /**
     * @param $content
     * @return string $content
     */



    public function putFile($file, $content)
    {
        /*
        if (file_exists($file)) {
            $ifConfirm = $this->commander->confirm($file. ' 文件已经存在，要覆盖么?');
            if ($ifConfirm === false) {
                return false;
            }
        }
        */
        return file_put_contents($file, $content);

    }

    protected function tableName($name = null)
    {
        $name = is_null($name) ? $this->model : $name;
        return Str::plural(Str::snake($name));
    }

    public function replaceFile($content)
    {
        return file_put_contents($this->realPath, $content);
    }

    public function tab($number = 2)
    {
        return str_repeat('    ', $number);
    }

    private function buildPatchContent(string $initContent)
    {

    }

    protected function setPath()
    {

    }

    protected function afterGenerate()
    {

    }

    private function beforeGenerate()
    {
    }

}
