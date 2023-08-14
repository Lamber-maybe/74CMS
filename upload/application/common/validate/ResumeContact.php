<?php

namespace app\common\validate;

use app\common\validate\BaseValidate;

class ResumeContact extends BaseValidate
{
    public function __construct()
    {
        parent::__construct();
        $this->initValidateRule('ResumeContact');
    }
    protected $rule = [
        'uid' => 'number|gt:0',
        'rid' => 'number|gt:0',
        'mobile' => 'require|checkMobile',
        'email' => 'email|max:30|checkEmail',
        'qq' => 'max:30|checkQq',
        'weixin' => 'max:30'
    ];
    protected $field = [
        'mobile' => '联系手机',
        'email' => '联系邮箱',
        'qq' => 'QQ',
        'weixin' => '微信'
    ];
    protected $scene = [
        //app上注册简历（表单式）
        'reg_from_app_by_form' => [
            'mobile'
        ],
        //app上注册简历（交互式）
        'reg_from_app_by_interactive' => [
            'mobile'
        ]
    ];
}
