<?php
namespace Ecopro\Form\Concerns;

use Ecopro\Form\Validator;

trait Validate
{
    /**
     * 表单检验
     */
    public function validate($base_message = '400|错误请求信息(%s)')
    {
        $errors = [];

        $reflectionClass = new \ReflectionClass($this);
        $props = $reflectionClass->getProperties(\ReflectionProperty::IS_PROTECTED);
        $validator = app(Validator::class);
        try{
            foreach($props as $prop){
                $prop->setAccessible(true);
                $comment =  $prop->getDocComment();
                preg_match_all('/@(.+)[\r|\n]/i', $comment, $matches);
                $elements = end($matches);
                foreach($elements as $element){
                    preg_match('/(.+)(?:\()/i', $element, $match);
                    $method = end($match);
                    $method = lcfirst($method);
                    $result = $validator->$method($element, $prop->getValue($this));
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

        return count($errors) ? sprintf($base_message, implode(',', $errors)) : '';
    }
}
