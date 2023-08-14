<?php
/**
 * 骑士短信类
 */
namespace app\common\lib\sms;

class qscms
{
    protected $platform;
    protected $content;
    protected $mobile;
    protected $sms_tpl;
    protected $params;
    protected $_base_url = 'https://smsapi.74cms.com?noencode=1&'; //基础类短信请求地址
    protected $_notice_url = 'https://smsapi.74cms.com?noencode=1&'; //通知类短信请求地地址
    protected $_captcha_url = 'https://smsapi.74cms.com?noencode=1&'; //验证码类短信请求地址
    protected $_other_url = 'https://smsapi.74cms.com?market=1&noencode=1&'; //其它类短信请求地址
    protected $account_config;

    public function __construct($config = [])
    {
        $this->platform = config('platform');
        $this->account_config = empty($config)
            ? config('global_config.account_sms')
            : $config;
    }
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }
    public function setParams($params)
    {
        $this->params = $params;
    }
    public function setTpl($templateCode)
    {
        $this->sms_tpl = model('SmsTpl')->getCache($templateCode);
    }
    public function sendDirect($mobile, $content)
    {
        $data['sms_name'] = $this->account_config['app_key'];
        $data['sms_key'] = $this->account_config['secret_key'];
        $data['content'] = $content;
        $request = \think\Request::instance();
        $data['client_ip'] = $request->ip();
        if (is_array($mobile)) {
            $url = [];
            /**
             * 【ID1000526】
             * 【优化】设置短信黑名单后，被屏蔽的手机依然会接收通知类短信
             * zch - 2023.02.14
             */
            $smsblacklist = model('SmsBlacklist')->column('mobile');
            foreach ($mobile as $key => $value) {
                if (!in_array($value, $smsblacklist)) {
                    $data['mobile'] = $value;
                    $url[] = $this->_notice_url . http_build_query($data);
                }
            }
            if (!empty($url)) {
                $this->httpsRequestAsyncConcurrency($url);
            }
        } else {
            $isExist = model('SmsBlacklist')->isExist($mobile);
            if ($isExist === false) {
                $data['mobile'] = $mobile;
                $url = $this->_notice_url . http_build_query($data);
                $this->httpsRequest($url);
            }
        }
    }
    public function send($mobile, $templateCode, $params, $type = 'captcha')
    {
        $this->mobile = $mobile;
        $this->params = $params;
        $this->sms_tpl = model('SmsTpl')->getCache($templateCode);
        $this->handle();
        $data['sms_name'] = $this->account_config['app_key'];
        $data['sms_key'] = $this->account_config['secret_key'];
        $data['content'] = $this->content;
        $data['mobile'] = $this->mobile;
        $request = \think\Request::instance();
        $data['client_ip'] = $request->ip();
        $data['platform'] = $this->platform;
        $name = '_' . $type . '_url';
        $url = $this->$name . http_build_query($data);
        $this->httpsRequest($url);
    }
    protected function handle()
    {
        if (!$this->platform) {
            throw new \Exception('平台不能为空');
        }
        if (!$this->mobile) {
            throw new \Exception('手机号不能为空');
        }
        if (!$this->sms_tpl) {
            throw new \Exception('模板错误');
        }
        if (
            !$this->account_config['app_key'] ||
            !$this->account_config['secret_key']
        ) {
            throw new \Exception('请正确设置短信key和密钥');
        }
        $this->content = $this->sms_tpl['content'];
        $tpl_params = $this->sms_tpl['params']
            ? explode(',', $this->sms_tpl['params'])
            : array();
        $this->replaceContent($this->params, $tpl_params);
        $this->content = str_replace('【', '[', $this->content);
        $this->content = str_replace('】', ']', $this->content);
    }
    /**
     * 替换模板参数
     */
    protected function replaceContent($post_params, $tpl_params)
    {
        if (!empty($tpl_params)) {
            if (!$post_params) {
                return true;
            }
            foreach ($tpl_params as $key => $value) {
                if (isset($post_params[$value])) {
                    $this->content = str_replace(
                        '{' . $value . '}',
                        $post_params[$value],
                        $this->content
                    );
                }
            }
        }
    }
    protected function httpsRequest($url)
    {
        $http = new \app\common\lib\Http();
        $result = $http->get($url);
        if ($result != 'success') {
            throw new \Exception('触发业务控制，错误码：' . $result);
        }
    }
    protected function httpsRequestAsyncConcurrency($url)
    {
        $http = new \app\common\lib\Http();
        $http->getAsyncConcurrency($url);
    }
}
