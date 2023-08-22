<?php

namespace App\models;

use Dulannadeeja\Mvc\form\DatabaseModel;
use Dulannadeeja\Mvc\UserModel;

class User extends UserModel
{
    protected const ERRORS = [
        'required' => 'The {attribute} field is required.',
        'min' => 'The {attribute} field must be at least {min} characters.',
        'max' => 'The {attribute} field must be less than {max} characters.',
        'format' => 'The {attribute} field must be a valid format.',
        'match' => 'The {attribute} field must match the {match} field.',
        'unique' => 'The {attribute} field must be unique.'
    ];

    protected const STATUS = [
        'inactive' => 0,
        'active' => 1,
        'deleted' => 2
    ];
    public string $firstName='';
    public string $lastName='';
    public string $email;
    public string $password;
    public string $confirmPassword;
    public int $status = self::STATUS['inactive'];

    public array $errorMessages = [];


    public function register(): bool
    {
        return $this->save();
    }

    public function validate(): array
    {
        // get requirements
        $requirements = $this->getRequirements();

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

                    case self::RULE_MIN:
                        if (strlen($attributeValue) < $rule['min']) {
                            $this->addError(
                                $attribute,
                                str_replace(['{attribute}', '{min}'], [$attributeDisplayName, $rule['min']], self::ERRORS['min'])
                            );
                        }
                        break;

                    case self::RULE_MAX:
                        if (strlen($attributeValue) > $rule['max']) {
                            $this->addError(
                                $attribute,
                                str_replace(['{attribute}', '{max}'], [$attributeDisplayName, $rule['max']], self::ERRORS['max'])
                            );
                        }
                        break;

                    case self::RULE_MATCH:
                        $matchingAttribute = $this->{$rule['match']};
                        if ($attributeValue !== $matchingAttribute) {
                            $this->addError(
                                $attribute,
                                str_replace(['{attribute}', '{match}'], [$attributeDisplayName, $this->getAttributeLabel($rule['match'])], self::ERRORS['match'])
                            );
                        }
                        break;

                    case self::RULE_UNIQUE:
                        // Implement the unique rule validation logic
                        if ($this->findOne(['email'=>$this->email])) {
                            $this->addError($attribute, str_replace('{attribute}', $attributeDisplayName, self::ERRORS['unique']));
                        }
                        break;

                    case self::RULE_FORMAT:
                        if ($rule['format'] === 'email' && !filter_var($attributeValue, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($attribute, str_replace('{attribute}', $attributeDisplayName, self::ERRORS['format']));
                        } elseif ($rule['format'] !== 'email' && !preg_match($rule['format'], $attributeValue)) {
                            $this->addError($attribute, str_replace('{attribute}', $attributeDisplayName, self::ERRORS['format']));
                        }
                        break;

                    default:
                        // Handle unknown rule name
                        break;
                }
            }

        }

        return $this->errorMessages;

    }


    public function getRequirements(): array
    {
        return [
            'firstName' => [
                self::RULE_REQUIRED,
                [self::RULE_MIN, 'min' => 3],
                [self::RULE_MAX, 'max' => 60],
                [self::RULE_FORMAT, 'format' => '/^[a-zA-Z]+$/']
            ],
            'lastName' => [
                self::RULE_REQUIRED,
                [self::RULE_MIN, 'min' => 3],
                [self::RULE_MAX, 'max' => 60],
                [self::RULE_FORMAT, 'format' => '/^[a-zA-Z]+$/']
            ],
            'email' => [
                self::RULE_REQUIRED,
                [self::RULE_MAX, 'max' => 255],
                [self::RULE_FORMAT, 'format' => 'email'],
                [self::RULE_UNIQUE, 'class' => self::class]
            ],
            'password' => [
                self::RULE_REQUIRED,
                [self::RULE_MIN, 'min' => 8],
                [self::RULE_MAX, 'max' => 255],
                [self::RULE_FORMAT, 'format' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/']
            ],
            'confirmPassword' => [
                self::RULE_REQUIRED,
                [self::RULE_MATCH, 'match' => 'password']
            ]
        ];
    }

    public function getAttributeLabel(string $attribute): string
    {
        $splitString = preg_replace('/([a-z])([A-Z])/', '$1 $2', $attribute);
        return strtolower($splitString);
    }

    protected function addError(string $attribute, string $errorString): void
    {
        $this->errorMessages[$attribute][] = $errorString;
    }

    public function hasError(string $attribute): bool
    {
        return !empty($this->errorMessages[$attribute]);
    }

    public function getFirstError(string $attribute): string
    {
        return $this->errorMessages[$attribute][0] ?? '';
    }

    public static function tableName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return ['firstName', 'lastName', 'email', 'password', 'status'];
    }

    public function save(): bool
    {
        $this->status = self::STATUS['active'];
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    public static function primaryKey(): string
    {
        return 'id';
    }

    public function getUserDisplayName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}