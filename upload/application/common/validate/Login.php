<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class Login extends BaseValidate
{
    protected $rule = [
        'username' => 'require|max:15',
        'password' => 'require|checkPassword',
        'code' => 'require|checkCaptcha',
        'secret_str' => 'require'
    ];

    protected $message = [
        'username.require' => '请填写用户名',
        'username.max' => '用户名不能超过15个字',
        'password.require' => '请填写密码'
    ];

    public function processRule(){
        unset($this->rule['code'], $this->rule['secret_str']);
    }
    // 自定义验证规则
    protected function checkPassword($value, $rule, $data)
    {
        $admininfo = model('Admin')
            ->where('username', $data['username'])
            ->find();
        if (!$admininfo) {
            return '用户名或密码错误';
        }
        if (
            model('Admin')->makePassword($value, $admininfo->pwd_hash) !==
            $admininfo->password
        ) {
            return '用户名或密码错误';
        }
        return true;
    }
    // 自定义验证规则
    protected function checkCaptcha($value, $rule, $data)
    {
        $captcha = new \think\captcha\Captcha();
        if (false === $captcha->checkWithJwt($value, $data['secret_str'])) {
            return '验证码错误';
        }
        return true;
    }
}
