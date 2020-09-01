<?php
namespace Smartymoon\Generator\Factory\Model;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Smartymoon\Generator\Exceptions\GenerateException;
use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class MigrationFactory
 * @package Smartymoon\Generator\Factory\Model
 */
class MigrationFactory extends MakeFactory implements FactoryContract
{
    /*
     * 可选 new,  replace
     * new: 制做新的文件
     * replace:
     */
    protected string $stubFile = 'migration/migration.stub';
    protected array $rightMethods = [];

    protected string $migrationFold;

    public function buildContent(): string
    {
        $this->migrationFold = base_path('database/migrations/');

        foreach (scandir($this->migrationFold) as $file) {
            if (Str::contains($file, '_create_'.$this->tableName().'_table')) {
                unlink($this->migrationFold . $file);
                // $this->commander->info('删除 migration :'. $file);
            }
        }

        $content = str_replace(
            'DummyClass',
            'Create'. \Str::plural($this->getModelClass()). 'Table',
            $this->getStub($this->stubFile)
        );
        $content = str_replace('DummyTable', $this->tableName(), $content);
        $content = str_replace('DummyColumns', $this->makeColumns(), $content);
        return $content;
    }

    public function getFilePath(): string
    {
        return $this->migrationFold . date('Y_m_d_His') .'_create_'.$this->tableName().'_table.php';
    }

    private function makeColumns(): string
    {
        $content = "\n";
        foreach($this->config->fields as $field) {
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

    private function makeForeign(string $field_name, string $foreign_policy, string $foreign_table = ''): string
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

        return  $foreign . ';';
    }

    private function makeMigrate(string $field_name, string $migrate): string
    {
        $this->checkMigrateMethod($field_name, $migrate);
        return $position = strpos($migrate, '(') ? $migrate : $migrate . '()';
    }

    private function checkMigrateMethod(String $name, String $method): void
    {
        $position = strpos($method, '(');
        $to_check = $position === false ? $method :
            substr($method, 0, $position);

        if(!in_array($to_check, $this->getRightMethodList())) {
            throw new GenerateException('表字段'. $name . '的类型 '. $to_check. ' 不存在');
        }
    }

    private function getRightMethodList(): array
    {

        if (count($this->rightMethods) == 0) {
            $this->rightMethods = array_merge(get_class_methods(Blueprint::class), [
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
        return $this->rightMethods;
    }

    private function makeFieldType(string $field_name, string $field_config_type): string
    {
        $this->checkMigrateMethod($field_name, $field_config_type);
        $position = strpos($field_config_type, '(');
        if ($position === false) {
            return $field_config_type . '(\''. $field_name . '\')';
        } else {
            return str_replace('(', '(\''. $field_name .'\', ', $field_config_type);
        }
    }

}
