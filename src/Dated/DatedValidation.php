<?php

namespace Ecopro\Form\Dated;

use Ecopro\Form\Concerns\Ensure;
use Ecopro\Form\Concerns\Validate;
use Ecopro\Form\Validatable;

/**
 * 注解表单验证
 */
class DatedValidation implements Validatable
{
    use Ensure, Validate;

    public function __construct($attrs = [])
    {
        foreach ($attrs as $key => $value) {
            $this->$key = $value;
        }
    }
}
