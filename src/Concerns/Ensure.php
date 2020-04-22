<?php
namespace Ecopro\Form\Concerns;

use Ecopro\Form\Validatable;

trait Ensure
{
    /**
     * @var Validatable
     */
    protected static $validation;
    /**
     * 表单检验
     */
    public static function ensure($form, $base_message = null)
    {
        $clazz = get_called_class();
        if(is_subclass_of($clazz, Validatable::class)) {
            $validation = (new $clazz())->init($form);
        } else {
            throw new \Exception("$clazz is not validatable");
        }
        if(isset($base_message)) {
            $message = $validation->validate($base_message);
        } else {
            $message = $validation->validate();
        }
        if($message) {
            throw new \Exception($message);
        }
    }
}
