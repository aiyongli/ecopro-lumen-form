<?php

namespace Ecopro\Form;

use Ecopro\Form\Concerns\Validate;

/**
 * 注解表单验证
 */
class Validation implements Validatable
{
    use Validate;

    public function __construct($attrs)
    {
        foreach ($attrs as $key => $value) {
            $this->$key = $value;
        }
    }
}
