<?php
namespace Ecopro\Form\Concerns;

use Ecopro\Form\Dated\DatedValidator;

trait ValidateDated
{
    /**
     * @var object
     */
    protected $datedValidator;

    /**
     * 默认使用注解验证
     * @return object
     */
    public function getDatedValidator()
    {
        $this->datedValidator =  $this->datedValidator ?? new DatedValidator;

        return $this->datedValidator;
    }

    /**
     * 设置验证器
     * @param object $datedValidator
     * @return static
     */
    public function setDatedValidator($datedValidator)
    {
        $this->datedValidator =  $datedValidator;

        return $this;
    }

    /**
     * 表单检验
     */
    public function validateDated()
    {
        $reflectionClass = new \ReflectionClass($this);
        $props = $reflectionClass->getProperties(\ReflectionProperty::IS_PROTECTED);
        $validator = $this->getDatedValidator();
        try{
            foreach($props as $prop) {
                $prop->setAccessible(true);
                $comment =  $prop->getDocComment();
                preg_match_all('/@(.+)[\r|\n]/i', $comment, $matches);
                $elements = end($matches);
                foreach($elements as $element){
                    preg_match('/(.+)(?:\()/i', $element, $match);
                    $method = end($match);
                    $method = lcfirst($method);
                    $name = $prop->getName();
                    $value = !isset($this->$name) ? null : $this->$name;
                    $result = $validator->$method($element, $value);
                    if($result) {
                        $errors[] = $result;
                        break 2;
                    }
                }
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $errMessage = substr($message, strrpos($message,':') + 1);

            throw new \Exception('注解异常@' . $errMessage);
        }

        return $errors;
    }

}
