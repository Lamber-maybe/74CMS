<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class CompanyContact extends BaseValidate
{
    public function __construct()
    {
        parent::__construct();
        $this->initValidateRule('CompanyContact');
    }
    protected $rule = [
        'uid' => 'number|gt:0|unique:company_contact',
        'comid' => 'number|gt:0|unique:company_contact',
        'contact' => 'require|max:30',
        'mobile' => 'require|checkMobile',
        'weixin' => 'max:15',
        'telephone' => 'max:20',
        'qq' => 'max:15|checkQq',
        'email' => 'max:30|checkEmail'
    ];
}
