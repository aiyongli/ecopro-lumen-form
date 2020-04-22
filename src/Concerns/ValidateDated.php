<?php
namespace Ecopro\Form\Concerns;

use Ecopro\Form\Dated\DatedValidator;

trait ValidateDated
{
    /**
     * 表单检验
     */
    public function validateDated()
    {
        $reflectionClass = new \ReflectionClass($this);
        $props = $reflectionClass->getProperties(\ReflectionProperty::IS_PROTECTED);
        $validator = app(DatedValidator::class);
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
