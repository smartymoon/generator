<?php

namespace Smartymoon\Generator\Factory\Resource;

Trait ResourceFactoryTrait
{

    /**
     * @inheritDoc
     */
    public function buildContent($content)
    {
        $content = str_replace('DummyClass', $this->getFileName(), $content);
        $content = str_replace('DummyFields', $this->getFields(), $content);

        return $content;
    }


    private function getFields()
    {
        $content = "'id' => " . '$this->id,' . "\n";


        foreach($this->fields as $field) {
            $content .= $this->tab($this->field_tabs);
            $field_name = $field['field_name'];

            if ($field['belongsTo']) {
                $relation = substr($field_name, 0, -3);
                $content .= "'$relation' => " . '$this->' . "$relation,\n";
            } else {
                $content .= "'$field_name' => " . '$this->' . "$field_name,\n";
            }
        }

        // hasMany
        foreach($this->hasMany as $hasMany) {
            $hasMany_name = $this->tableName($hasMany);
            $content .= $this->tab($this->field_tabs)."'$hasMany_name' => " . '$this->' . "$hasMany_name,\n";
        }
        return $content;
    }
}
