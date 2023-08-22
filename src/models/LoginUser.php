<?php

namespace App\models;

use Dulannadeeja\Mvc\Application;

class LoginUser extends User
{

    protected const ERRORS = [
        'required' => 'Please enter your {attribute}.',
        'format' => '{attribute} must be a valid format.',
        'present' => "There is no account associated with this {attribute}.",
        'match' => '{attribute} does not match.'
    ];

    public function validate(): array
    {
        //get requirements
        $requirements = $this->getRequirements();

        // loop through requirements
        foreach ($requirements as $attribute => $rules) {
            $attributeValue = $this->{$attribute};
            $attributeDisplayName = $this->getAttributeLabel($attribute);

            foreach ($rules as $rule) {
                $ruleName = is_array($rule) ? $rule[0] : $rule;

                switch ($ruleName) {

                    case self::RULE_REQUIRED:
                        if (empty($attributeValue)) {
                            $this->addError($attribute, str_replace('{attribute}', $attributeDisplayName, self::ERRORS['required']));
                        }
                        break;

                    case self::RULE_FORMAT:
                        if ($rule['format'] === 'email' && !filter_var($attributeValue, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($attribute, str_replace('{attribute}', $attributeDisplayName, self::ERRORS['format']));
                        }
                        break;

                    case self::RULE_PRESENT:
                        if (!$this->findOne([$attribute => $attributeValue])) {
                            $this->addError($attribute, str_replace('{attribute}', $attributeDisplayName, self::ERRORS['present']));
                        }
                        break;

                    case self::RULE_MATCH:
                        $user = $this->findOne(['email' => $this->email]);
                        if (!$this->errorMessages && !(password_verify($this->password, $user->password))) {
                            $this->addError($attribute, str_replace('{attribute}', $attributeDisplayName, self::ERRORS['match']));
                        }
                        break;
                }
            }
        }

        return $this->errorMessages;
    }

    public function getRequirements(): array
    {
        return [
            'email' => [
                self::RULE_REQUIRED,
                [self::RULE_FORMAT, 'format' => 'email'],
                [self::RULE_PRESENT, 'attribute' => 'email']
            ],
            'password' => [
                self::RULE_REQUIRED,
                [self::RULE_MATCH, 'match' => 'email']
            ]
        ];
    }

    public function login()
    {
        $user = $this->findOne(['email' => $this->email]);
        if ($user) {
            if (password_verify($this->password, $user->password)) {
                Application::$app->login($user);
                return $user;
            }
        }
        return false;
    }

}