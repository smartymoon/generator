<?php
namespace Smartymoon\Generator\Factory\Model;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Smartymoon\Generator\Exceptions\GenerateException;
use Smartymoon\Generator\Factory\BaseFactory;

class MigrationFactory extends BaseFactory
{
    /*
     * 可选 new,  replace
     * new: 制做新的文件
     * replace:
     */
    protected $buildType = 'new';
    protected $stub = 'migration/migration.stub';
    protected $path = 'database/migrations/';
    protected $right_methods = [];


    /**
     * @inheritDoc
     */
    // 1, 简单词替换 (无模板)
    // 2. 块替换, migrations, Factory, validation(todo) (无模板)
    // 3. 函数模板 (有模板)
    // 4. 给已有文件打补丁 
    /*
     * @return string $content
     */
    public function buildContent($content)
    {
        $content = str_replace('DummyClass', $this->getClass(), $content);
        $content = str_replace('DummyTable', $this->tableName($this->model), $content);
        $content = str_replace('DummyColumns', $this->makeColumns(), $content);
        return $content;
    }

    protected function getFileName()
    {
        return $this->getDatePrefix().'_create_'.$this->tableName($this->model).'_table';
    }

    protected function getClass()
    {
        return 'Create'. Str::plural($this->ucModel). 'Table';
    }


    private function getDatePrefix()
    {
        return date('Y_m_d_His');
    }

    private function makeColumns()
    {
        //    $table->timestamp('failed_at')->useCurrent();
        $content = "\n";
        foreach($this->fields as $field) {

            if ($field['foreign_policy']) {
                $foreign = $this->makeForeign($field['field_name'], $field['foreign_policy'], $field['foreign_table']);
                $content .=  $foreign ?  ($this->tab(3) . $foreign. "\n") : '';
            } else {
                $content .= $this->tab(3).'$table->'. $this->makeFieldType($field['field_name'], $field['type']);
                foreach($field['migration'] as $migrate) {
                    $content .= '->'. $this->makeMigrate($field['field_name'], $migrate);
                }
                $content .= ";\n";
            }
        }
        return $content;
    }

    protected function afterGenerate()
    {
        Artisan::call('migrate');
    }

    protected function beforeGenerate()
    {
        // clear migration files
        foreach (scandir($this->realPath) as $file) {
           if (Str::contains($file, '_create_'.$this->tableName($this->model).'_table')) {
                unlink($this->realPath . $file);
                $this->commander->info('删除 migration :'. $file);
           }
        }
    }

    /** 
    * todo 这里的 nullable 有什么用
    */
    public function makeForeign($field_name, $foreign_policy, $foreign_table = '')
    {
        if (!$foreign_policy) {
            return '';
        }

        $foreign = '$table->foreignId(\''. $field_name . '\')';

        if ($foreign_table == '') {
            $foreign .=  '->constrained()';
        } else {
            $foreign .=  '->constrained(\''. $foreign_table .'\')';
        }

        if ($foreign_policy == 'cascade') {
            $foreign .= "->onDelete('cascade')";
        }

        return $foreign .= ';';

    }

    public function makeMigrate($field_name, $migrate)
    {
        $this->checkMigrateMethod($field_name, $migrate);
        return $pos = strpos($migrate, '(') ? $migrate : $migrate . '()'; 
    }

    public function checkMigrateMethod(String $name, String $method)
    {
        $pos = strpos($method, '(');
        $toCheck = $pos === false ? $method : 
            substr($method, 0, $pos);

        if(!in_array($toCheck, $this->getRightMethodList())) {
            throw new GenerateException('表字段'. $name . '的类型 '. $toCheck. ' 不存在');
        }
    }

    public function getRightMethodList()
    {

        if (count($this->right_methods) == 0) {
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
        return $this->right_methods;
    }

    public function makeFieldType($field_name, $field_config_type)
    {
        // type 可能 abc() , abc
        $this->checkMigrateMethod($field_name, $field_config_type);
        $pos = strpos($field_config_type, '(');
        if ($pos === false) {
            return $field_config_type . '(\''. $field_name . '\')';
        } else {
            return str_replace('(', '(\''. $field_name .'\', ', $field_config_type);
        }
    }
}
