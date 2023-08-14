<?php
/**
 * 二、明文传输漏洞
 * MD5加密密码登录
 */

namespace app\common\validate;

use app\common\validate\BaseValidate;

/**
 * Class LoginMd5
 * @package  app\common\validate
 * @author   Mr.yx
 * @since    2022/4/27
 * @version  1.0
 * @see      (参照)
 */
class LoginMd5 extends BaseValidate
{
    protected $rule = [
        'code' => 'require|checkCaptcha',
        'username' => 'require|max:15',
        'password' => 'require|checkMd5Password',
        'secret_str' => 'require'
    ];

    protected $message = [
        'code.require' => '请填写验证码',
        'username.require' => '请填写用户名',
        'username.max' => '用户名不能超过15个字',
        'password.require' => '请填写密码'
    ];

    public function processRule(){
        unset($this->rule['code'], $this->rule['secret_str']);
    }
    // 自定义验证规则
    protected function checkMd5Password($value, $rule, $data)
    {
        $admininfo = model('Admin')
            ->where('username', $data['username'])
            ->find();
        if (!$admininfo) {
            return '用户名或密码错误';
        }
        if (
            model('Admin')->makeMd5Password($value, $admininfo->pwd_hash) !==
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
