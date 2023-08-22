<?php

namespace Dulannadeeja\Mvc\form;

class Textarea extends BaseField
{
    // constructor
    public function __construct(\Dulannadeeja\Mvc\Model $model, string $attribute, array $properties = [])
    {
        parent::__construct($model, $attribute, $properties);
    }

    protected function renderInput(): string
    {
        return sprintf("<textarea name='%s' class='form-control %s' id='%s' placeholder='%s'>%s</textarea>",
            $this->properties['name'],
            $this->properties['class'] ,
            $this->properties['name'],
            $this->properties['placeholder'],
            $this->properties['value'],
        );
    }
}