<?php

namespace Dulannadeeja\Mvc\form;

abstract class BaseField
{
    // constructor
    public function __construct(private readonly \Dulannadeeja\Mvc\Model $model, private readonly string $attribute, protected array $properties = [])
    {
        // set properties
        $properties = $this->properties;

        if (!empty($this->model->{$this->attribute})) {
            $properties['value'] = $this->model->{$this->attribute};
        } else {
            $properties['value'] = '';
        }

        $properties['name'] = $this->attribute ?? '';
        $properties['type'] = $this->properties['type'] ?? 'text';
        $properties['class'] = $this->model->hasError($this->attribute) ? 'is-invalid' : '';
        $properties['placeholder'] = $this->properties['placeholder'] ?? '';
        $properties['error'] = $this->model->getFirstError($this->attribute) ?? '';

        $this->properties = $properties;
    }

    // render
    public function __toString(): string
    {
        return sprintf(
            "
            <div class='mb-3'>
                <label for='%s' class='form-label'>%s</label>
                %s
                <div id='field-error' class='form-text invalid-feedback'>%s</div>
            </div>
            ",
            $this->properties['name'],
            $this->resolveLabel($this->properties['name']),
            $this->renderInput(),
            $this->properties['error']
        );
    }

    protected abstract function renderInput(): string;

    protected function resolveLabel($propertyName): string
    {
        return match ($propertyName) {
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'confirmPassword' => 'Confirm Password',
            'subject' => 'Your Subject',
            'body' => 'Your Message Body',
            default => ucfirst($propertyName),
        };
    }


}