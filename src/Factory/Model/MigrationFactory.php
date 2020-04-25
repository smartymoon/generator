<?php
namespace Smartymoon\Generator\Factory\Model;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
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
            $content .= $this->tab(3).'$table->'. $field['type'];
            foreach($field['migrations'] as $otherMethod) {
                $content .= '->'.$otherMethod;
            }
            $content .= ";\n";
            $content .=  $field['foreign'] ?  ($this->tab(3) . $field['foreign']. "\n") : '';
        }
        return $content;
    }

    protected function afterGenerate()
    {
        // system('php artisan migrate');
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

}
