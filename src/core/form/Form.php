<?php

namespace Dulannadeeja\Mvc\form;

use App\models\Contact;
use App\models\LoginUser;
use App\models\User;

class Form
{
    // start form
    public static function start(string $action, string $method): Form
    {
        echo sprintf("<form action='%s' method='%s'>", $action, $method);
        return new Form();
    }

    // input field
    public static function inputField(User|LoginUser|Contact $model , $attribute , $properties): InputField
    {
        return new InputField($model, $attribute, $properties);
    }

    // end form
    public static function end(): string
    {
        return '</form>';
    }

    public function TextAreaField($contactModel, string $string, array $array): Textarea
    {
        return new Textarea($contactModel, $string, $array);
    }

}