<?php

/**
 * 发送邮件
 *
 * @author
 */

namespace app\common\lib;

class Mail
{
    private $_error = 0;
    protected $class_name;
    protected $subject;
    protected $body;
    public function __construct()
    {
        $type_name = config('global_config.sendmail_type');
        $this->class_name = '\\app\\common\\lib\\mail\\' . $type_name;
    }
    public function testSend($sendmail_type, $config, $sendto, $subject, $body)
    {
        $this->class_name = '\\app\\common\\lib\\mail\\' . $sendmail_type;
        if (!class_exists($this->class_name)) {
            $this->_error = '参数错误，请检查邮件类型是否正确';
            return false;
        }
        try {
            $class = new $this->class_name($config);
            $class->send($subject, $body, $sendto);
        } catch (\Exception $e) {
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }
    public function send($sendto, $subject, $body)
    {
        if (!class_exists($this->class_name)) {
            $this->_error = '参数错误，请检查邮件类型是否正确';
            return false;
        }
        try {
            $class = new $this->class_name();
            $class->send($subject, $body, $sendto);
        } catch (\Exception $e) {
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }
    public function sendTpl($sendto, $alias, $replac = [])
    {
        if (!class_exists($this->class_name)) {
            $this->_error = '参数错误，请检查邮件类型是否正确';
            return false;
        }
        $this->buildTpl($alias, $replac);
        try {
            $class = new $this->class_name();
            $class->send($this->subject, $this->body, $sendto);
        } catch (\Exception $e) {
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }
    protected function buildTpl($alias, $replac = [])
    {
        $mail_templates = model('MailTpl')->getCache();
        $this->subject = model('MailTpl')->labelReplace(
            $mail_templates[$alias]['title'],
            $replac
        );
        $this->body = model('MailTpl')->labelReplace(
            $mail_templates[$alias]['value'],
            $replac
        );
    }
    public function getError()
    {
        return $this->_error;
    }
}
