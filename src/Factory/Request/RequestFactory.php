<?php


namespace Smartymoon\Generator\Factory\Request;

use Smartymoon\Generator\Factory\FactoryContract;
use Smartymoon\Generator\Factory\MakeFactory;

/**
 * Class RequestFactory
 * @package Smartymoon\Generator\Factory\Request
 */
class RequestFactory extends MakeFactory implements FactoryContract
{
    protected string $stubFile = 'request/request.stub';

    private string $dummyRules;
    private string $dummyMessages;

    public function buildContent(): string
    {
        $this->makeRulesAndMessage();

        $content = str_replace('dummyRules', $this->dummyRules, $this->getStub($this->stubFile));
        $content = str_replace('dummyMessages', $this->dummyMessages, $content);

        return $content;
    }

    public function getFilePath(): string
    {
       return $this->dealModulePath(base_path('app/Http/Requests/')) .$this->getModelClass() . 'Request.php' ;
    }

    private function makeRulesAndMessage()
    {
        $rules_content = "";
        $messages_content = "";
        foreach($this->config->fields as $field) {
            $field_name = $field['field_name'];
            if (!isset($field['rules'])) {
                continue;
            }
            $rules = $field['rules'];
            $final_rules = '';
            foreach($rules as $rule) {
                $message_key = explode(':', $rule['rule'])[0];
                $messages_content .= $this->tab(3)."'$field_name.$message_key' => '" . $rule['message'] ."',\n";
                if ($rule['rule'] == 'unique') {
                    $final_rules .= $this->makeUnique($field_name);
                } else {
                    $final_rules .= "'{$rule['rule']}', ";
                }
                $final_rules .= "\n". $this->tab(4);
            }
            $rules_content .= $this->tab(3) . "'$field_name' => "
                . "[\n". $this->tab(4). "$final_rules\n". $this->tab(3) ."],\n";
        }
        $this->dummyRules = $rules_content;
        $this->dummyMessages = $messages_content;
    }


    private function makeUnique(string $field_name): string
    {
        return "'unique:".$this->tableName().",$field_name'.".
            ' $this->method == \'POST\' ? "" : ",\'".request()->id,';
    }

}
