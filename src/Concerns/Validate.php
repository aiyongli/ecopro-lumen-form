<?php
namespace Ecopro\Form\Concerns;

use Ecopro\Form\Validatable;

trait Validate
{
    use ValidateDated;

    /**
     * @return static
     */
    public function init($attrs = [])
    {
        foreach ($attrs as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    /**
     * 表单检验
     */
    public function validate($base_message = null)
    {
        $errors = [];
        $base_message = $base_message ?? '400|错误请求信息(%s)';
        if(method_exists($this, 'validateSymfony')) {
            // 优先使用Symfony注解验证
            $violations = $this->validateSymfony();
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
                // 只一条
                break;
            }
        } else {
            // 没有使用新特性
            $errors = $this->validateDated();
        }

        return count($errors) ? sprintf($base_message, implode(',', $errors)) : '';
    }
}
