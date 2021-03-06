<?php

namespace Ecopro\Form;

use Ecopro\Form\Concerns\Ensure;
use Ecopro\Form\Concerns\Validate;
use Ecopro\Form\Concerns\ValidateSymfony;

/**
 * 注解表单验证
 */
class Validation implements Validatable
{
    use Ensure, Validate, ValidateSymfony;

    public function __construct($attrs = [])
    {
        foreach ($attrs as $key => $value) {
            $this->$key = $value;
        }
    }
}
