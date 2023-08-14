<?php
namespace app\common\lib\pay\wxpay;

require_once dirname(__FILE__) . '/WxPay.Data.php';
require_once dirname(__FILE__) . '/WxPay.NativePay.php';
require_once dirname(__FILE__) . '/WxPay.JsApiPay.php';
require_once dirname(__FILE__) . '/WxPay.MchPay.php';
require_once dirname(__FILE__) . '/WxPay.H5Pay.php';
require_once dirname(__FILE__) . '/WxPay.MchPackagePay.php';
require_once dirname(__FILE__) . '/WxPay.Notify.php';
class wxpay
{
    protected $order_prefix;
    protected $client_ip;
    protected $_error = 0;
    protected $config = array();
    public function __construct($order_prefix)
    {
        $this->order_prefix = $order_prefix;
        $this->config = config('global_config');
        WxPayConfig::$appid = $this->config['payment_wechat_appid'];
        WxPayConfig::$mchid = $this->config['payment_wechat_mchid'];
        WxPayConfig::$key = $this->config['payment_wechat_key'];
        WxPayConfig::$appsecret = $this->config['payment_wechat_appsecret'];
        $this->client_ip = get_client_ip();
    }
    /**
     * [微信支付订单二维码生成]
     * @param  string $type   [description]
     * @param  [type] $option [description]
     * @return [type]         [description]
     */
    public function callPay($option)
    {
        $return = '';
        switch ($option['platform']) {
            case 'web':
                $return = $this->_pay_from_web($option);
                break;
            case 'mobile':
            case 'wechat':
                $return = $this->_pay_from_mobile($option);
                break;
        }
        return $return;
    }
    /**
     * 网页版支付
     */
    protected function _pay_from_web($option)
    {
        $wxpay = new WxPayUnifiedOrder();
        $notify = new NativePay();
        $wxpay->SetBody($option['service_name']); //描述
        $wxpay->SetAttach('test'); //回调附加参数
        $wxpay->SetOut_trade_no($this->order_prefix . $option['oid']); //商户订单号
        $option['amount'] = PAY_TEST_MODE ? 0.01 : $option['amount'];
        $wxpay->SetTotal_fee($option['amount'] * 100); //支付金额
        $wxpay->SetTime_start(date('YmdHis')); //交易起始时间
        $wxpay->SetTime_expire(date('YmdHis', time() + 600)); //交易结束时间
        $wxpay->SetGoods_tag($option['service_name']); //商品标记
        $wxpay->SetNotify_url(
            config('global_config.sitedomain') .
                config('global_config.sitedir') .
                'index/callback/wxpayNotify'
        ); //支付通知回调地址
        $wxpay->SetTrade_type('NATIVE'); //交易类型
        $wxpay->SetProduct_id('123456789');
        $result = $notify->GetPayUrl($wxpay);
        return $result['code_url'];
    }
    /**
     * 触屏支付
     */
    protected function _pay_from_mobile($option)
    {
        if (isset($option['platform']) && $option['platform'] == 'mobile') {
            $h5pay = 1;
        } else {
            $h5pay = 0;
        }
        $wxpay = new WxPayUnifiedOrder();
        if ($h5pay == 1) {
            $notify = new H5Pay();
        } else {
            $notify = new JsApiPay();
        }
        $wxpay->SetBody($option['service_name']); //描述
        $wxpay->SetAttach('test'); //回调附加参数
        $wxpay->SetOut_trade_no($this->order_prefix . $option['oid']); //商户订单号
        $option['amount'] = PAY_TEST_MODE ? 0.01 : $option['amount'];
        $wxpay->SetTotal_fee($option['amount'] * 100); //支付金额
        $wxpay->SetTime_start(date('YmdHis')); //交易起始时间
        $wxpay->SetTime_expire(date('YmdHis', time() + 600)); //交易结束时间
        $wxpay->SetGoods_tag($option['service_name']); //商品标记
        $wxpay->SetNotify_url(
            config('global_config.sitedomain') .
                config('global_config.sitedir') .
                'index/callback/wxpayNotify'
        ); //支付通知回调地址
        if ($h5pay == 1) {
            $wxpay->SetTrade_type('MWEB'); //交易类型
            $wxpay->SetScene_info(
                json_encode([
                    'h5_info' => [
                        'type' => 'Wap',
                        'wap_url' =>
                            config('global_config.sitedomain') .
                            config('global_config.sitedir') .
                            'm',
                        'wap_name' => config('global_config.sitename')
                    ]
                ])
            ); //场景信息
            $wxpay->SetSpbill_create_ip($this->client_ip);
            $order = WxPayApi::unifiedOrder($wxpay); //创建统一支付表单信息
            if ($order['return_code'] == 'SUCCESS') {
                if ($order['result_code'] == 'SUCCESS') {
                    $url = $order['mweb_url'];
                    isset($option['redirect_url']) &&
                        $option['redirect_url'] != '' &&
                        ($url .=
                            '&redirect_url=' .
                            urlencode($option['redirect_url']));
                } else {
                    $this->_error = $order['err_code_des'];
                    return false;
                }
            } else {
                $this->_error = $order['return_msg'];
                return false;
            }
            return $url;
        } else {
            // $openId = $notify->GetOpenidByCode($option['code']);
            $wxpay->SetOpenid($option['openid']); //用户标识
            $wxpay->SetTrade_type('JSAPI'); //交易类型
            $order = WxPayApi::unifiedOrder($wxpay); //创建统一支付表单信息
            $jsApiParameters = $notify->GetJsApiParameters($order);
            //获取共享收货地址js函数参数
            $editAddress = $notify->GetEditAddressParameters();
            return array(
                'jsApiParameters' => json_decode($jsApiParameters,1),
                'editAddress' => json_decode($editAddress,1)
            );
        }
    }
    
    /*
	验证操作(异步)
	*/
    public function notify()
    {
        $notify = new WxPayNotify();
        $notify->Handle();
    }
    public function getError()
    {
        return $this->_error;
    }
}
?>
