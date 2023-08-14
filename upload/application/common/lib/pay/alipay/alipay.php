<?php
namespace app\common\lib\pay\alipay;

class alipay
{
    protected $order_prefix;
    protected $client_ip;
    protected $_error = 0;
    protected $config;
    public function __construct($order_prefix)
    {
        $this->order_prefix = $order_prefix;
        $this->config = config('global_config.account_alipay');
        $this->client_ip = get_client_ip();
    }
    /*
    支付操作
     */
    public function callPay($data)
    {
        $return = '';
        switch ($data['platform']) {
            case 'web':
                $return = $this->_pay_from_web($data);
                break;
            case 'mobile':
            case 'wechat':
                $return = $this->_pay_from_mobile($data);
                break;
            case 'app':
                $return = $this->_pay_from_app($data);
                break;
        }
        return $return;
    }
    protected function _pay_from_web($data)
    {
        \think\Loader::import('alipay.AopClient');
        \think\Loader::import('alipay.request.AlipayTradePagePayRequest');
        $parameter = [
            'body' => $data['service_name'],
            'subject' => $data['service_name'],
            'out_trade_no' => $this->order_prefix . $data['oid'],
            'timeout_express' => '90m',
            'total_amount' => PAY_TEST_MODE ? 0.01 : $data['amount'],
            'product_code' => 'FAST_INSTANT_TRADE_PAY',
        ];
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $this->config['appid'];
        $aop->rsaPrivateKey = $this->config['privatekey'];
        $aop->alipayrsaPublicKey = $this->config['publickey'];
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';
        $request = new \AlipayTradePagePayRequest();
        $request->setNotifyUrl(
            config('global_config.sitedomain') .
            config('global_config.sitedir') .
            'index/callback/alipayNotify'
        );
        $request->setReturnUrl($data['return_url']);
        $request->setBizContent(
            json_encode($parameter, JSON_UNESCAPED_UNICODE)
        );
        $result = $aop->pageExecute($request, 'GET');
        return $result;
    }
    /**
     * 触屏支付
     */
    protected function _pay_from_mobile($data)
    {
        \think\Loader::import('alipay.AopClient');
        \think\Loader::import('alipay.request.AlipayTradeWapPayRequest');
        $parameter = [
            'body' => $data['service_name'],
            'subject' => $data['service_name'],
            'out_trade_no' => $this->order_prefix . $data['oid'],
            'timeout_express' => '90m',
            'total_amount' => PAY_TEST_MODE ? 0.01 : $data['amount'],
            'product_code' => 'QUICK_WAP_WAY',
        ];
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $this->config['appid'];
        $aop->rsaPrivateKey = $this->config['privatekey'];
        $aop->alipayrsaPublicKey = $this->config['publickey'];
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';
        $request = new \AlipayTradeWapPayRequest();
        $request->setNotifyUrl(
            config('global_config.sitedomain') .
            config('global_config.sitedir') .
            'index/callback/alipayNotify'
        );
        $request->setReturnUrl($data['return_url']);
        $request->setBizContent(
            json_encode($parameter, JSON_UNESCAPED_UNICODE)
        );
        $result = $aop->pageExecute($request, 'GET');
        return $result;
    }
    /**
     * app支付
     */
    protected function _pay_from_app($data)
    {
        \think\Loader::import('alipay.AopClient');
        \think\Loader::import('alipay.request.AlipayTradeAppPayRequest');
        $parameter = [
            'body' => $data['service_name'],
            'subject' => $data['service_name'],
            'out_trade_no' => $this->order_prefix . $data['oid'],
            'timeout_express' => '90m',
            'total_amount' => PAY_TEST_MODE ? 0.01 : $data['amount'],
            'product_code' => 'QUICK_MSECURITY_PAY',
        ];
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $this->config['appid'];
        $aop->rsaPrivateKey = $this->config['privatekey'];
        $aop->alipayrsaPublicKey = $this->config['publickey'];
        // $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';
        $request = new \AlipayTradeAppPayRequest();
        $request->setNotifyUrl(
            config('global_config.sitedomain') .
            config('global_config.sitedir') .
            'index/callback/alipayNotify'
        );
        $request->setBizContent(
            json_encode($parameter, JSON_UNESCAPED_UNICODE)
        );
        $result = $aop->sdkExecute($request);
        return $result;
    }

    /*
    验证操作(异步)
     */
    public function alipayNotify($data)
    {

        \think\Loader::import('alipay.AopClient');
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $this->config['appid'];
        $aop->rsaPrivateKey = $this->config['privatekey'];
        // $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';
        $aop->alipayrsaPublicKey = $this->config['publickey'];
        $result = $aop->rsaCheckV1(
            $data,
            $this->config['publickey'],
            'RSA2'
        );

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
         */
        if ($result) {
            //验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代

            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号
            $out_trade_no = $data['out_trade_no'];
            $total_fee = $data['receipt_amount']; //交易金额
            $notify_time = $data['notify_time']; //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
            //交易状态
            $trade_status = $data['trade_status'];
            if (
                $data['trade_status'] == 'TRADE_FINISHED' ||
                $data['trade_status'] == 'TRADE_SUCCESS'
            ) {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
                //付款完成后，支付宝系统发送该交易状态通知
                $out_trade_no = substr($out_trade_no, 4);
                
                $order = model('Order')
                    ->where([
                        'oid' => $out_trade_no,
                    ])
                    ->find();
                if ($order === null) {
                    $order = model('OrderTmp')
                        ->where([
                            'oid' => $out_trade_no,
                        ])
                        ->find();
                    if ($order === null) {
                        return false;
                    }

                }
                if ($order['status'] == 1) {
                    return true;
                }
                if (!PAY_TEST_MODE && $order['amount'] != $total_fee) {
                    return false;
                }
                $result = model('Order')->orderPaid(
                    $order['oid'],
                    'alipay',
                    strtotime($notify_time)
                );
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            return true;
        } else {
            //验证失败
            return false;
        }
    }
    /*
    验证操作(同步)
     */
    public function alipayNotifyReturn($data)
    {

        \think\Loader::import('alipay.AopClient');

        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $this->config['appid'];
        $aop->rsaPrivateKey = $this->config['privatekey'];
        // $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';
        $aop->alipayrsaPublicKey = $this->config['publickey'];
        $result = $aop->rsaCheckV1(
            $data,
            $this->config['publickey'],
            'RSA2'
        );

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
         */
        if ($result) {
            //验证成功
            $out_trade_no = htmlspecialchars($data['out_trade_no']);
            $out_trade_no = substr($out_trade_no, 4);
            $total_fee = htmlspecialchars($data['total_amount']); //交易金额
            $notify_time = htmlspecialchars($data['timestamp']); //通知的发送时间。格式为yyyy-MM-dd HH:mm:ss。
            //交易状态
            $trade_status = $this->check_status($data);
            if (
                $trade_status === true
            ) {
                $order = model('Order')
                    ->where([
                        'oid' => $out_trade_no,
                    ])
                    ->find();
                if ($order === null) {
                    $order = model('OrderTmp')
                        ->where([
                            'oid' => $out_trade_no,
                        ])
                        ->find();
                    if ($order === null) {
                        return false;
                    }

                }
                if ($order['status'] == 1) {
                    return true;
                }
                if (!PAY_TEST_MODE && $order['amount'] != $total_fee) {
                    return false;
                }
                $result = model('Order')->orderPaid(
                    $order['oid'],
                    'alipay',
                    strtotime($notify_time)
                );
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            return $out_trade_no;
        } else {
            //验证失败
            return false;
        }
    }
    protected function check_status($data)
    {
        \think\Loader::import('alipay.AopClient');
        \think\Loader::import('alipay.request.AlipayTradeQueryRequest');
        $parameter = [
            'out_trade_no' => $this->order_prefix . $data['out_trade_no'],
            'trade_no' => $data['trade_no'],
        ];
        $aop = new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $this->config['appid'];
        $aop->rsaPrivateKey = $this->config['privatekey'];
        $aop->alipayrsaPublicKey = $this->config['publickey'];
        // $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format = 'json';
        $request = new \AlipayTradeQueryRequest();

        $request->setBizContent(json_encode($parameter, JSON_UNESCAPED_UNICODE));
        $result = $aop->execute($request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if (!empty($resultCode) && $resultCode == 10000) {
            return true;
        } else {
            return false;
        }
    }
    public function getError()
    {
        return $this->_error;
    }
}
