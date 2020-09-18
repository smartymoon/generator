<?php

namespace Smartymoon\Generator\Factory\Resource;

/**
 * Trait ResourceFactoryTrait
 * @package Smartymoon\Generator\Factory\Resource
 */
Trait ResourceFactoryTrait
{

    public function buildContent(string $content): string
    {
        $content = str_replace(
            'DummyNamespace',
            $this->dealModuleNamespace('App\Http\Resources') . '\\'. $this->getModelClass(),
            $content
        );

        $content = str_replace('DummyClass', $this->getFileName(), $content);
        $content = str_replace('DummyFields', $this->getFields(), $content);

        return $content;
    }

    private function getFields(): string
    {
        $upper_obj = $this->stubFile === 'resource/resource.stub' ? '$this' : '$item';
        $content = "'id' => " . $upper_obj .'->id,' . "\n";

        foreach($this->config->fields as $field) {
            $content .= $this->tab($this->fieldTabs);
            $field_name = $field['field_name'];

            if ($field['belongsTo']) {
                $relation = substr($field_name, 0, -3);
                $content .= "'$relation' => " . $upper_obj .'->' . "$relation,\n";
            } else {
                $content .= "'$field_name' => " . $upper_obj .'->' . "$field_name,\n";
            }
        }

        // hasMany
        foreach($this->config->hasManyRelations as $has_many) {
            $has_many_key = $this->tableName($has_many);
            $has_many_name = $this->config->hasManyRelation($has_many);
            $content .= $this->tab($this->fieldTabs)."'$has_many_key' => " . $upper_obj .'->' . "$has_many_name,\n";
        }

        return $content;
    }

    public function getTemplate(): string
    {
        return $this->getStub($this->stubFile);
    }
}
