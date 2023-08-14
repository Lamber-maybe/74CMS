<?php

/**
 * 阿里云dm发送邮件
 *
 * @author
 */

namespace app\common\lib\mail;

include_once EXTEND_PATH . 'aliyunsdk/aliyun-php-sdk-core/Config.php';

use Dm\Request\V20151123 as Dm;

class aliyundm
{
    protected $accessKey;
    protected $accessSecret;
    protected $sendcloud_sender_address;
    protected $fromName;
    protected $config;
    public function __construct($config = [])
    {
        $this->config = empty($config)
            ? config('global_config.account_aliyundm')
            : $config;
        $this->accessKey = $this->config['access_key'];
        $this->accessSecret = $this->config['access_secret'];
        $this->sendcloud_sender_address = $this->config['sender_address'];
        $this->fromName = config('global_config.sitename');
    }
    public function send($subject, $body, $sendto)
    {
        $sendto = is_array($sendto) ? implode(',', $sendto) : $sendto;
        //需要设置对应的region名称，如华东1（杭州）设为cn-hangzhou，新加坡Region设为ap-southeast-1，澳洲Region设为ap-southeast-2。
        $iClientProfile = \DefaultProfile::getProfile(
            'cn-hangzhou',
            $this->accessKey,
            $this->accessSecret
        );
        //新加坡或澳洲region需要设置服务器地址，华东1（杭州）不需要设置。
        //$iClientProfile::addEndpoint("ap-southeast-1","ap-southeast-1","Dm","dm.ap-southeast-1.aliyuncs.com");
        //$iClientProfile::addEndpoint("ap-southeast-2","ap-southeast-2","Dm","dm.ap-southeast-2.aliyuncs.com");
        $client = new \DefaultAcsClient($iClientProfile);
        $request = new Dm\SingleSendMailRequest();
        //新加坡或澳洲region需要设置SDK的版本，华东1（杭州）不需要设置。
        //$request->setVersion("2017-06-22");
        $request->setAccountName($this->sendcloud_sender_address);
        $request->setFromAlias($this->fromName);
        $request->setAddressType(1);
        // $request->setTagName("控制台创建的标签");
        $request->setReplyToAddress('true');
        $request->setToAddress($sendto);
        //可以给多个收件人发送邮件，收件人之间用逗号分开,若调用模板批量发信建议使用BatchSendMailRequest方式
        //$request->setToAddress("邮箱1,邮箱2");
        $request->setSubject($subject);
        $request->setHtmlBody($body);
        $response = $client->getAcsResponse($request);
    }
}
