<?php

namespace Ecopro\Form;

/**
 * 验证
 */
interface Validatable
{
    /**
     * @return static
     */
    public function init($attrs = []);
    public function validate($base_message = null);
}
