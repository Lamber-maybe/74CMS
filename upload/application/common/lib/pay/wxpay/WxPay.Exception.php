<?php
namespace app\common\lib\pay\wxpay;
/**
 *
 * 微信支付API异常类
 * @author widyhu
 *
 */
class WxPayException extends \Exception
{
    public function errorMessage()
    {
        return $this->getMessage();
    }
}
