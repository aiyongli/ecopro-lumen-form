<?php

namespace Ecopro\Form;

use Illuminate\Http\UploadedFile;

/**
 * 验证器
 */
class Validator
{
    /**
     * 不允许内容为空
     */
    public function notEmpty($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return is_null($value) || (is_string($value) && trim($value)=='') || (is_array($value) && empty($value)) || (empty($value) && trim($value)!=0) ?end($result):'';// || (empty($value) && is_string($value) && trim($value)==0)
    }

    /**
     * 手机号码检验
     */
    public function mobile($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return preg_match('/^1[3-9]\d{9}$/', $value)?'':end($result);
    }

    /**
     * 验证是否是数字
     */
    public function digits($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return is_numeric($value)?'':end($result);
    }

    /**
     * 验证是否是整数
     */
    public function integer($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return is_numeric($value) && false !== filter_var($value, FILTER_VALIDATE_INT, FILTER_FLAG_ALLOW_OCTAL)?'':end($result);
    }

    /**
     * 验证是否是数组类型
     */
    public function assertArray($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return is_array($value)?'':end($result);
    }

    /**
     * 验证是否是数组类型（正整数）
     */
    public function assertArrayNotZeroStraight($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        $ret = is_array($value)?'':end($result);

        if($ret) {
            return $ret;
        }
        foreach ($value as $v) {
            $ret = is_numeric($v) && $v > 0 && preg_match('/^[1-9]\d*$/', $v)?'':end($result);
            if($ret) {
                return $ret;
            }
        }

        return '';
    }

    /**
     * @Min(value="0",message="最小值不能低于0")
     */
    public function min($element, $value)
    {
        preg_match('/\(value=\"(\-?\d+\.?\d?)\",message=\"(.+)\"\)/',$element,$result);
        if(empty($result)){
            throw new \Exception(__METHOD__);
        }
        list(,$min,$message) = $result;
        return is_numeric($value) && $value>=$min?'':$message;
    }

    /**
     * @Max(value="20",message="最大值不能高于20")
     * 应优先对是否是数据作判断
     */
    public function max($element, $value)
    {
        preg_match('/\(value=\"(\-?\d+\.?\d?)\",message=\"(.+)\"\)/',$element,$result);
        if(empty($result)){
            throw new \Exception(__METHOD__);
        }
        list(,$max,$message) = $result;
        return is_numeric($value) && $value<=$max?'':$message;
    }

    /**
     * @In(value="['guest','user','member','suplier']",message="非标准用户角色")
     */
    public function in($element, $value)
    {
        preg_match('/\(value=\"\[(.+)\]\",message=\"(.+)\"\)/',$element,$result);
        if(empty($result)){
            throw new \Exception(__METHOD__);
        }
        list(,$element,$message) = $result;
        preg_match_all('/\'([a-zA-Z0-9\x{4e00}-\x{9fa5}]+)\'+/u',$element,$matches);
        list(,$items) = $matches;

        return in_array($value, $items)?'':$message;
    }

    /**
     * @Range(min="1",max="20",message="")
     */
    public function range($element, $value)
    {
        preg_match('/\(min=\"(\-?\d+\.?\d?)\",max=\"(\-?\d+\.?\d?)\",message=\"(.+)\"\)/',$element,$result);
        if(empty($result)){
            throw new \Exception(__METHOD__);
        }
        list(,$min,$max,$message) = $result;
        return $value>=$min && $value<=$max?'':$message;
    }

    /**
     * 邮箱检验
     */
    public function email($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $value)?'':end($result);
    }

    /**
     * 最多两位小数的非负数检验
     */
    public function negative($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return is_numeric($value) && preg_match('/^([0-9]*)+(\.[0-9]{1,2})?$/', $value)?'':end($result);
    }
    /**
     * 含零的正整数
     */
    public function straight($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return is_numeric($value) && preg_match('/^[0-9]\d*$/', $value)?'':end($result);
    }
    /**
     * 非零的正整数
     */
    public function notZeroStraight($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return is_numeric($value) && $value > 0 && preg_match('/^[1-9]\d*$/', $value)?'':end($result);
    }
    /**
     * 不为0 的最多两位小数的非负数检验
     */
    public function negativeNotZero($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return is_numeric($value) && $value > 0 && preg_match('/^([1-9]\d*(\.\d*[1-9])?)|(0\.\d*[1-9])|0$/', $value)?'':end($result);
    }
    /**
     * 身份证号
     */
    public function idNumber($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return preg_match('/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/', $value)?'':end($result);
    }

    /**
     * 电话号码与手机号码同时验证
     */
    public function phone($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return preg_match('/^(((0\d{2,3}-)?\d{7,8})|(1[3-9]\d{9}))$/', $value)?'':end($result);
    }

    /**
     * 日期
     */
    public function date($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/', $element, $result);
        return strtotime(date('Y-m-d', strtotime($value))) === strtotime($value)?'':end($result);
    }

    /**
     * 日期或空
     */
    public function emptyOrDate($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/', $element, $result);
        return (empty($value) || strtotime(date('Y-m-d', strtotime($value))) === strtotime($value))?'':end($result);
    }

    /**
     * 日期时间格式
     */
    public function dateTime($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/', $element, $result);
        return strtotime(date('Y-m-d H:i:s', strtotime($value))) === strtotime($value)?'':end($result);
    }

    /**
     * 日期时间格式
     */
    public function emptyOrDateTime($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/', $element, $result);
        return (empty($value) || strtotime(date('Y-m-d H:i:s', strtotime($value))) === strtotime($value))?'':end($result);
    }

    /**
     * 时间格式
     */
    public function time($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/', $element, $result);
        return strtotime(date('H:i:s', strtotime($value))) === strtotime($value)?'':end($result);
    }


    /**
     * @Length(min="16",max="19",message="")
     */
    public function length($element, $value)
    {
        preg_match('/\(min=\"(\-?\d+\.?\d?)\",max=\"(\-?\d+\.?\d?)\",message=\"(.+)\"\)/',$element,$result);
        if(empty($result)){
            throw new \Exception(__METHOD__);
        }
        list(,$min,$max,$message) = $result;
        return strlen($value)>=$min && strlen($value)<=$max?'':$message;
    }
    /**
     * 验证税号
     * 15或者17或者18或者20位字母、数字组成
     */
    public function tax($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return preg_match('/^[A-Z0-9]{15}$|^[A-Z0-9]{17}$|^[A-Z0-9]{18}$|^[A-Z0-9]{20}$/', $value)?'':end($result);
    }

    /**
     * 验证订单号
     */
    public function tradeNo($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return preg_match('/^[a-zA-Z0-9]+$/', $value)?'':end($result);
    }
    /**
     * 验证产品类型
     */
    public function strUpper($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return preg_match('/^[A-Z]+$/', $value)?'':end($result);
    }

    /**
     * 验证月份
     */
    public function month($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return strtotime("$value-01") !== false?'':end($result);
    }

    /**
     * 月份或空
     */
    public function emptyOrMonth($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/', $element, $result);
        return (empty($value) || strtotime("$value-01") !== false)?'':end($result);
    }

    /**
     * 验证年份
     */
    public function year($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return preg_match('/^\d{4}$/', $value)?'':end($result);
    }

    /**
     * 年份或空
     */
    public function emptyOrYear($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/', $element, $result);
        return (empty($value) || preg_match('/^\d{4}$/', $value))?'':end($result);
    }

    /**
     * 上传文件
     * @param UploadedFile $value
     */
    public function file($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/', $element, $result);
        $err = $value->getError();
        return $value->isValid()?'':( end($result) . "($err)" );
    }

    /**
     * 验证字母
     */
    public function alpha($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return preg_match('/^[a-zA-Z]+$/', $value)?'':end($result);
    }

    /**
     * 验证字母和短横
     */
    public function alphaDash($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return preg_match('/^[a-zA-Z\-]+$/', $value)?'':end($result);
    }

    /**
     * 验证字母和数字
     */
    public function alphaNumber($element, $value)
    {
        preg_match('/\(.+=\"(.+)\"\)/',$element,$result);
        return preg_match('/^[0-9a-zA-Z]+$/', $value)?'':end($result);
    }
}
