<?php

namespace App\models;

use Dulannadeeja\Mvc\Model;

class Contact extends Model
{

    const ERRORS = [
        'required' => 'Please enter your {attribute}.',
        'format' => '{attribute} must be a valid format.',
        'min' => '{attribute} must be at least {min} characters.',
        'max' => '{attribute} must be less than {max} characters.'
    ];
    public string $subject = '';
    public string $email = '';
    public string $body = '';
    public array $errorMessages = [];
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
                    case self::RULE_MIN:
                        if (strlen($attributeValue) < $rule['min']) {
                            $this->addError($attribute, str_replace(['{attribute}', '{min}'], [$attributeDisplayName, $rule['min']], self::ERRORS['min']));
                        }
                        break;
                    case self::RULE_MAX:
                        if (strlen($attributeValue) > $rule['max']) {
                            $this->addError($attribute, str_replace(['{attribute}', '{max}'], [$attributeDisplayName, $rule['max']], self::ERRORS['max']));
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
            "subject" => [
                self::RULE_REQUIRED,
                [self::RULE_MIN, 'min' => 3],
                [self::RULE_MAX, 'max' => 255]
            ],
            "email" => [
                self::RULE_REQUIRED,
                [self::RULE_FORMAT, 'format' => 'email']
            ],
            "body" => [
                self::RULE_REQUIRED,
                [self::RULE_MIN, 'min' => 3],
                [self::RULE_MAX, 'max' => 512]
            ]
        ];
    }

    private function addError(string $attribute, array|string $str_replace): void
    {
        $this->errorMessages[$attribute][] = $str_replace;
    }

    private function getAttributeLabel(string $attribute): string
    {
        return match ($attribute) {
            'subject' => 'subject',
            'email' => 'email',
            'body' => 'message',
            default => ''
        };
    }

    // send email
    public function send()
    {
        return true;
    }

    // has error
    public function hasError($attribute): bool|string|array
    {
        return $this->errorMessages[$attribute] ?? false;
    }

    // get first error
    public function getFirstError($attribute): string
    {
        return $this->errorMessages[$attribute][0] ?? '';
    }
}