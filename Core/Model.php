<?php

namespace App\Core;

abstract class Model
{
    const RULE_REQUIRED = 'required';
    const RULE_EMAIL = 'email';
    const RULE_MATCH = 'match';
    const RULE_MIN = 'min';
    const RULE_MAX = 'max';
    const RULE_UNIQUE = 'unique';

    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    abstract public function rules(): array;

    public function labels()
    {
        return [];
    }

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    public array $errors = [];

    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->$attribute;

            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }

                switch ($ruleName) {
                    case self::RULE_REQUIRED:
                        if (empty($value)) {
                            $this->addErrorForRuleForRule($attribute, self::RULE_REQUIRED);
                        }
                        break;
                    case self::RULE_EMAIL:
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->addErrorForRuleForRule($attribute, self::RULE_EMAIL);
                        }
                        break;
                    case self::RULE_MIN:
                        if (mb_strlen($value) < $rule['min']) {
                            $this->addErrorForRuleForRule($attribute, self::RULE_MIN, ['min' => $rule['min']]);
                        }
                        break;
                    case self::RULE_MAX:
                        if (mb_strlen($value) > $rule['max']) {
                            $this->addErrorForRuleForRule($attribute, self::RULE_MAX, ['max' => $rule['max']]);
                        }
                        break;
                    case self::RULE_MATCH:
                        if ($value !== $this->{$rule['match']}) {
                            $rule['match'] = $this->getLabel($rule['match']);
                            $this->addErrorForRuleForRule($attribute, self::RULE_MATCH, ['match' => $rule['match']]);
                        }
                        break;
                    case self::RULE_UNIQUE:
                        $className = $rule['class'];
                        $uniqueAttr = $rule['attribute'] ?? $attribute;
                        $tableName = $className::tableName();
                        $stmt = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
                        $stmt->bindValue(":attr", $value);
                        $stmt->execute();
                        $record = $stmt->fetchObject();
                        if ($record) {
                            $this->addErrorForRuleForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                        }
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    private function addErrorForRuleForRule(string $attribute, string $rule, $params = [])
    {
        $msg = $this->errorMessages()[$rule] ?? '';
        
        foreach ($params as $key => $value) {
            $msg = str_replace("{{$key}}", $value, $msg);
        }

        $this->errors[$attribute][] = $msg;
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages()
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists'
        ];
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }
}