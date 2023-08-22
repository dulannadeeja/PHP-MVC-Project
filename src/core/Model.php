<?php

namespace Dulannadeeja\Mvc;

abstract class Model
{
    // Requirements for validation
    protected const RULE_REQUIRED = 'required';
    protected const RULE_MIN = 'min';
    protected const RULE_MAX = 'max';
    protected const RULE_MATCH = 'match';
    protected const RULE_UNIQUE = 'unique';
    protected const RULE_FORMAT = 'format';
    protected const RULE_PRESENT= 'present';


    // load data to the model
    public function loadData(array $data): void
    {
        // loop through the data array and set properties
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                // set property
                $this->{$key} = $value;
            }
        }
    }

    // validate data
    abstract public function validate():array;

    // get requirements
    abstract public function getRequirements(): array;

}