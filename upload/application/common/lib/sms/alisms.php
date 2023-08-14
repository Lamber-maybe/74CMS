<?php

/**
 * 阿里sms短信类
 * @author
 */

namespace app\common\lib\sms;

ini_set('display_errors', 'on');

require_once dirname(__FILE__) . '/alisms/vendor/autoload.php';

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

Config::load();

class alisms
{
    static $acsClient = null;
    protected $mobile;
    protected $sms_tpl;
    protected $params;
    protected $account_config;

    public function __construct($config = [])
    {
        $this->account_config = empty($config)
            ? config('global_config.account_alisms')
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
    protected function handle()
    {
        if (!$this->mobile) {
            throw new \Exception('手机号不能为空');
        }
        if (!$this->sms_tpl) {
            throw new \Exception('模板错误');
        }
        if (!$this->sms_tpl['alisms_tplcode']) {
            throw new \Exception('模板ID错误');
        }
        if (
            !$this->account_config['accesskey_id'] ||
            !$this->account_config['accesskey_secret']
        ) {
            throw new \Exception('请正确设置短信key和密钥');
        }
    }

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public function getAcsClient()
    {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = 'Dysmsapi';

        //产品域名,开发者无需替换
        $domain = 'dysmsapi.aliyuncs.com';

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = $this->account_config['accesskey_id']; // AccessKeyId

        $accessKeySecret = $this->account_config['accesskey_secret']; // AccessKeySecret

        // 暂时不支持多Region
        $region = 'cn-hangzhou';

        // 服务结点
        $endPointName = 'cn-hangzhou';

        if (static::$acsClient == null) {
            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile(
                $region,
                $accessKeyId,
                $accessKeySecret
            );

            // 增加服务结点
            DefaultProfile::addEndpoint(
                $endPointName,
                $region,
                $product,
                $domain
            );

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * 发送短信
     * @return stdClass
     */
    public function send($mobile, $templateCode, $params)
    {
        unset($params['sitename']);
        $this->mobile = $mobile;
        $this->params = $params;
        $this->sms_tpl = model('SmsTpl')->getCache($templateCode);
        $this->handle();
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($this->mobile);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName($this->account_config['signature']);

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode($this->sms_tpl['alisms_tplcode']);

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        !empty($this->params) &&
            $request->setTemplateParam(
                json_encode($this->params, JSON_UNESCAPED_UNICODE)
            );

        // 可选，设置流水号
        $request->setOutId('yourOutId');

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        $request->setSmsUpExtendCode('1234567');

        // 发起访问请求
        $acsResponse = $this->getAcsClient()->getAcsResponse($request);
        if ($acsResponse->Code != 'OK') {
            throw new \Exception($acsResponse->Message);
        }
    }
}
