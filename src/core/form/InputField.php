<?php

namespace Dulannadeeja\Mvc\form;

use App\models\Contact;
use App\models\LoginUser;
use App\models\User;

class InputField extends BaseField
{
    // constructor
    public function __construct(User|LoginUser|Contact $model, string $attribute, array $properties = [])
    {
        //parent constructor
        parent::__construct($model, $attribute, $properties);
    }

    // render input
    protected function renderInput(): string
    {
        return sprintf("<input name='%s' type='%s' class='form-control %s' id='%s' value='%s' placeholder='%s'>",
            $this->properties['name'],
            $this->properties['type'],
            $this->properties['class'] ,
            $this->properties['name'],
            $this->properties['value'],
            $this->properties['placeholder'],
        );
    }

}