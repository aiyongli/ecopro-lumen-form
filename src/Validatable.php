<?php

namespace Ecopro\Form;

/**
 * 验证
 */
interface Validatable
{
    public function validate($base_message = '');
}
