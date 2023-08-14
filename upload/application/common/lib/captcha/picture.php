<?php
namespace app\common\lib\captcha;

class picture
{
    private $config;
    private $_error;

    public function __construct()
    {
        $this->config = config('global_config.captcha_picture_rule');
    }
    public function get_config()
    {
        try {
            $captcha = new \think\captcha\Captcha($this->config);
            $result = $captcha->entryWithJwt();
        } catch (\Exception $e) {
            $this->_error = $e->getMessage();
            return false;
        }
        return $result;
    }
    public function validate($code, $secret_str)
    {
        try {
            $captcha = new \think\captcha\Captcha($this->config);
            $result = $captcha->checkWithJwt($code, $secret_str);
        } catch (\Exception $e) {
            $this->_error = $e->getMessage();
            return false;
        }
        if (false === $result) {
            $this->_error = '验证码错误';
            return false;
        }
        return true;
    }
    public function getError()
    {
        return $this->_error;
    }
}
