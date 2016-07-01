<?php

namespace App\Validators;

use TomLerendu\MicroAPI\Validator\Validator;

class ValidatorExample extends Validator
{
    protected function rules()
    {
        $this->addRules('username', ['type:string', 'length:5,10', 'equals:helloworld']);
        $this->addRules('password', ['in:hunter2,otherpassword']);
    }
}