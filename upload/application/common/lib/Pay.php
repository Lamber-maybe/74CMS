<?php
/**
 * 支付类
 *
 * @author
 */
namespace app\common\lib;
class Pay
{
    const PAY_TYPE_ALIPAY = 1;
    const PAY_TYPE_ALIPAY_WEB = 11;
    const PAY_TYPE_ALIPAY_MOBILE = 12;
    const PAY_TYPE_ALIPAY_APP = 13;
    const PAY_TYPE_WXPAY = 2;
    const PAY_TYPE_WXPAY_NATIVE = 21;
    const PAY_TYPE_WXPAY_JSAPI = 22;
    const PAY_TYPE_WXPAY_H5 = 23;
    const PAY_TYPE_WXPAY_APP = 24;

    protected $_error;
    protected $_setting = array();
    protected $_platform;
    public function __construct($platform = 'web', $payment)
    {
        $this->_platform = $platform;
        $payment = $payment ? $payment : 'alipay';
        $class = '\\app\\common\\lib\\pay\\' . $payment . '\\' . $payment;
        if ($payment == 'alipay') {
            switch ($this->_platform) {
                case 'web':
                    $order_prefix = self::PAY_TYPE_ALIPAY_WEB;
                    break;
                case 'mobile':
                    $order_prefix = self::PAY_TYPE_ALIPAY_MOBILE;
                    break;
                case 'app':
                    $order_prefix = self::PAY_TYPE_ALIPAY_APP;
                    break;
                default:
                    $order_prefix = self::PAY_TYPE_ALIPAY_WEB;
                    break;
            }
            $order_prefix = 'A' . $order_prefix . '-';
        } elseif ($payment == 'wxpay') {
            switch ($this->_platform) {
                case 'web':
                    $order_prefix = self::PAY_TYPE_WXPAY_NATIVE;
                    break;
                case 'wechat':
                case 'mobile':
                    $order_prefix = self::PAY_TYPE_WXPAY_H5;
                    break;
                case 'app':
                    $order_prefix = self::PAY_TYPE_WXPAY_APP;
                    break;
                default:
                    $order_prefix = self::PAY_TYPE_WXPAY_NATIVE;
                    break;
            }
            $order_prefix = 'W' . $order_prefix . '-';
        }

        $this->_pay = new $class($order_prefix);
    }
    /*
        $option
        oid 订单号
        ordsubject 订单名称
        ordtotal_fee 订单金额
        ordbody 订单描述
        site_dir 网站域名
    */
    public function callPay($option)
    {
        $option['platform'] = $this->_platform;

        if (!$option['oid']) {
            $this->_error = '请填写订单号！';
            return false;
        }
        if (!$option['service_name']) {
            $this->_error = '请填写订单名称！';
            return false;
        }
        if (!$option['amount']) {
            $this->_error = '请填订单金额！';
            return false;
        }
        $result = $this->_pay->callPay($option);
        if ($result === false) {
            $this->_error = $this->_pay->getError();
        }
        return $result;
    }
    public function alipayNotify($data)
    {
        return $this->_pay->alipayNotify($data);
    }
    public function alipayNotifyReturn($data)
    {
        return $this->_pay->alipayNotifyReturn($data);
    }
    public function wxpayNotify()
    {
        return $this->_pay->notify();
    }
    // /**
    //  * [payment 企业付款]
    //  */
    // public function payment($data)
    // {
    //     return $this->_pay->payment($data);
    // }
    public function getError()
    {
        return $this->_error;
    }
}
