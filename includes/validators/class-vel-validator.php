<?php
namespace VEL\Includes\Validators;

class VEL_Validator
{
    protected $errors = [];
    protected $data = [];
    protected $rules = [];

    public function validate($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->errors = [];

        foreach ($rules as $field => $rule_list) {
            $this->validate_field($field, $rule_list);
        }

        return empty($this->errors);
    }

    protected function validate_field($field, $rule_list)
    {
        $value = $this->data[$field] ?? null;
        $rules = explode('|', $rule_list);

        foreach ($rules as $rule) {
            if (strpos($rule, ':') !== false) {
                list($rule_name, $rule_value) = explode(':', $rule);
            } else {
                $rule_name = $rule;
                $rule_value = null;
            }

            $method = 'validate_' . $rule_name;
            if (method_exists($this, $method)) {
                if (!$this->$method($field, $value, $rule_value)) {
                    $this->add_error($field, $rule_name);
                }
            }
        }
    }

    protected function add_error($field, $rule)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $rule;
    }
}