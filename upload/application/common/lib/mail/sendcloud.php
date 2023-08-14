<?php

/**
 * SEND CLOUD发送邮件
 *
 * @author
 */

namespace app\common\lib\mail;

class sendcloud
{
    protected $url = 'https://api.sendcloud.net/apiv2/mail/send';
    protected $apiUser;
    protected $apiKey;
    protected $sendcloud_sender_address;
    protected $fromName;
    protected $config;
    public function __construct($config = [])
    {
        $this->config = empty($config)
            ? config('global_config.account_sendcloud')
            : $config;
        $this->apiUser = $this->config['api_user'];
        $this->apiKey = $this->config['api_key'];
        $this->sendcloud_sender_address = $this->config['sender_address'];
        $this->fromName = config('global_config.sitename');
    }
    public function send($subject, $body, $sendto)
    {
        $sendto = is_array($sendto) ? implode(';', $sendto) : $sendto;
        //您需要登录SendCloud创建API_USER，使用API_USER和API_KEY才可以进行邮件的发送。
        $param = array(
            'apiUser' => $this->apiUser,
            'apiKey' => $this->apiKey,
            'from' => 'service@sendcloud.im',
            'fromName' => $this->fromName,
            'to' => $sendto,
            'subject' => $subject,
            'html' => $body,
            'respEmailId' => 'true'
        );

        $data = http_build_query($param);

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $data
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($this->url, false, $context);
        $result_arr = json_decode($result, true);
        if (
            $result_arr['result'] === false ||
            $result_arr['statusCode'] != 200
        ) {
            throw new \Exception($result_arr['message']);
        }
    }
}
