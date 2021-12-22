<?php

namespace App\Core\Form;

use App\Core\Model;

class TextareaField extends BaseField
{
    const TYPE_TEXT = 'text';
    const TYPE_PASSWORD = 'password';
    const TYPE_NUMBER = 'number';

    public string $type;
    public Model $model;
    public string $attribute;

    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute); 
    }


    public function renderInput(): string
    {
        return sprintf(
            '<textarea name="%s"class="form-control%s">%s</textarea>',
            $this->attribute,
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
            $this->model->{$this->attribute}
        );
    }
    

    public function passwordField()
    {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }
}