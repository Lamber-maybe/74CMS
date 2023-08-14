<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class Member extends BaseValidate
{
    protected $rule = [
        'utype' => 'in:0,1,2',
        'mobile' => 'require|checkMobile',
        'username' => 'require|max:30|checkUsername',
        'email' => 'email|max:30',
        'password' => 'max:100',
        'pwd_hash' => 'max:30',
        'reg_time' => 'number',
        'reg_ip' => 'max:30',
        'reg_address' => 'max:30',
        'last_login_time' => 'number',
        'last_login_ip' => 'max:30',
        'last_login_address' => 'max:30',
        'status' => 'number',
        'avatar' => 'max:255',
        'robot' => 'number'
    ];
    protected $scene = [
        'add' => ['utype', 'mobile', 'username', 'password'],
        'edit' => ['mobile', 'username']
    ];
    protected function checkMobile($value, $rule, $data)
    {
        if (fieldRegex($value, 'mobile')) {
            $info = model('Member')
                ->where([
                    'mobile' => $value,
                    'utype' => $data['utype']
                ])
                ->find();
            if (null === $info) {
                return true;
            } elseif (isset($data['uid']) && $info['uid'] == $data['uid']) {
                return true;
            } else {
                return '手机号已被占用';
            }
        } else {
            return '请输入正确的手机号码';
        }
    }
    protected function checkUsername($value, $rule, $data)
    {
        $info = model('Member')
            ->where([
                'username' => $value,
                'utype' => $data['utype']
            ])
            ->find();
        if (null === $info) {
            return true;
        } elseif (isset($data['uid']) && $info['uid'] == $data['uid']) {
            return true;
        } else {
            return '用户名已被占用';
        }
        return true;
    }
}
