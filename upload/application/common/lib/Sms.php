<?php

/**
 * 发送短信
 *
 * @author
 */

namespace app\common\lib;

class Sms
{
    private $_error = 0;
    protected $mobile;
    protected $templateCode;
    protected $params;
    public function __construct()
    {
        $type_name = config('global_config.sendsms_type');
        $this->class_name = '\\app\\common\\lib\\sms\\' . $type_name;
    }
    public function testSend($sendsms_type, $config, $mobile, $params = [])
    {
        $this->class_name = '\\app\\common\\lib\\sms\\' . $sendsms_type;
        if (!class_exists($this->class_name)) {
            $this->_error = '参数错误，请检查短信类型是否正确';
            return false;
        }
        /**
         * 【ID1000526】
         * 【优化】设置短信黑名单后，被屏蔽的手机依然会接收通知类短信
         * zch - 2023.02.14
         */
        $isExist = model('SmsBlacklist')->isExist($mobile);
        if ($isExist === true) {
            $this->_error = '当前号码已被加入黑名单';
            return false;
        }
        try {
            $class = new $this->class_name($config);
            $class->send($mobile, 'SMS_13', $params);
        } catch (\Exception $e) {
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }
    public function send($mobile, $templateCode, $params = [])
    {
        if (!class_exists($this->class_name)) {
            $this->_error = '参数错误，请检查短信类型是否正确';
            return false;
        }
        $isExist = model('SmsBlacklist')->isExist($mobile);
        if($isExist===true){
            $this->_error = '当前号码已被加入黑名单';
            return false;
        }
        try {
            $class = new $this->class_name();
            $class->send($mobile, $templateCode, $params);
        } catch (\Exception $e) {
            $this->_error = $e->getMessage();
            return false;
        }
        return true;
    }
    /**
     * 获取错误信息
     */
    public function getError()
    {
        return $this->_error;
    }
}
