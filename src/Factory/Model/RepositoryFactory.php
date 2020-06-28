<?php

namespace Smartymoon\Generator\Factory\Model;

use Smartymoon\Generator\Factory\BaseFactory;

class RepositoryFactory extends BaseFactory
{

    protected $buildType = 'new';
    protected $stub = 'repository/repository.stub';
    protected $path = 'app/Repositories/';
    protected $baseRepositoryRealPath = 'app/Repositories/BaseRepository.php';
    protected $baseRepositoryStubPath = 'repository/BaseRepository.stub';

    /**
     * @inheritDoc
     */
    public function buildContent($content)
    {
        $this->makeBaseRepository();

        $content = str_replace('DummyFields', $this->getFields(), $content);
        $content = str_replace('DummyHas', $this->getHasMany(), $content);

        return $content;
    }

    protected function getFileName()
    {
        return $this->ucModel . 'Repository';
    }

    private function makeBaseRepository()
    {
        $absoluteRealPath = base_path($this->baseRepositoryRealPath);
        if(!file_exists($absoluteRealPath)) {
            $source = __DIR__ . '/../../stubs/'. $this->baseRepositoryStubPath;
            copy($source ,$absoluteRealPath);
        }
    }

    private function getFields()
    {
        $content = "";
        foreach ($this->fields as $field) {
            $field_name = $field['field_name'];
            $content .= "'$field_name', ";
        }
        return $content;
    }

    private function getHasMany() 
    {
        if (count($this->hasMany) == 0) return '';

        $content = '['."\n";
        foreach($this->hasMany as $hasMany) {
           $name = $this->hasManyRelation($hasMany);
           $content .=  $this->tab(3) . "'${name}', ". "\n";
        }
        return $content . $this->tab(2) . ']';
    }
}
