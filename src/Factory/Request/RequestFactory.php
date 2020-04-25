<?php


namespace Smartymoon\Generator\Factory\Request;


use Smartymoon\Generator\Factory\BaseFactory;

class RequestFactory extends BaseFactory
{

    protected $buildType = 'new';
    protected $stub = 'request/request.stub';
    protected $path = 'app/Http/Requests/';

    private $DummyRules = '';
    private $DummyMessages = '';

    /**
     * @inheritDoc
     */
    public function buildContent($content)
    {
        $this->makeRulesAndMessage();

        $content = str_replace('DummyRules', $this->DummyRules, $content);
        $content = str_replace('DummyMessages', $this->DummyMessages, $content);

        return $content;
    }

    protected function getFileName()
    {
        return $this->ucModel . 'Request';
    }

    private function makeRulesAndMessage()
    {
        $rules_content = "";
        $messages_content = "";
        foreach($this->fields as $field) {
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
        $this->DummyRules = $rules_content;
        $this->DummyMessages = $messages_content;
    }


    private function makeUnique($field_name)
    {
        return "'unique:".$this->tableName().",$field_name'.".
            ' $this->method == \'POST\' ? "" : ",\'".request()->id,';
    }

}
