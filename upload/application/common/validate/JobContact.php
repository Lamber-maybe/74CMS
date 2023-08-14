<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class JobContact extends BaseValidate
{
    public function __construct()
    {
        parent::__construct();
        $this->initValidateRule('JobContact');
    }
    protected $rule = [
        'uid' => 'number|gt:0',
        'jid' => 'number|gt:0|unique:job_contact',
        'contact' => 'require|max:30',
        'mobile' => 'require|checkMobile',
        'weixin' => 'max:15',
        'telephone' => 'max:20',
        'qq' => 'max:15|checkQq',
        'email' => 'max:30|checkEmail',
        'is_display' => 'require|in:0,1'
    ];
}
