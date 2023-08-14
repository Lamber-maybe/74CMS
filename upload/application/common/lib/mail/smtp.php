<?php

/**
 * SMTP发送邮件
 *
 * @author
 */

namespace app\common\lib\mail;

class smtp
{
    protected $host;
    protected $port;
    protected $security;
    protected $username;
    protected $password;
    protected $smtp_sender_address;
    protected $config;
    public function __construct($config = [])
    {
        $this->config = empty($config)
            ? config('global_config.account_smtp')
            : $config;
        $this->host = $this->config['host'];
        $this->port = $this->config['port'];
        $this->security = $this->config['security'];
        $this->username = $this->config['username'];
        $this->password = $this->config['password'];
        $this->smtp_sender_address = $this->config['sender_address'];
    }
    public function send($subject, $body, $sendto)
    {
        $sendto = is_array($sendto) ? $sendto : [$sendto];
        $message = \Swift_Message::newInstance();
        $message->setEncoder(\Swift_Encoding::get8BitEncoding());
        $message->setSubject($subject);
        $message->setBody(
            "<html lang='zh-CN'><body>" . $body . '</body></html>',
            'text/html'
        );
        $message->setTo($sendto);
        $transport = \Swift_SmtpTransport::newInstance(
            $this->host,
            $this->port,
            $this->security
        );
        $transport->setUsername($this->username);
        $transport->setPassword($this->password);
        // 创建mailer对象
        $mailer = \Swift_Mailer::newInstance($transport);

        // 用关联数组设置发件人地址，可以设置多个发件人
        $addresses = [$this->smtp_sender_address=>config('global_config.sitename')];
        $message->setFrom($addresses);
        if (false === $mailer->send($message)) {
            throw new \Exception('发送失败');
        }
    }
}
