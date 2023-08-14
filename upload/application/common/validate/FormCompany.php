<?php
/**
 * 表单验证 - 企业资料
 */
namespace app\common\validate;

use app\common\validate\BaseValidate;

class FormCompany extends BaseValidate
{
    public function __construct()
    {
        parent::__construct();
        $this->initValidateRule('Company');
        $this->initValidateRule('CompanyInfo');
        $this->initValidateRule('CompanyContact');
    }

    protected $rule = [
        'companyname' => 'require|max:30',
        'nature' => 'require|number|gt:0',
        'scale' => 'require|number|gt:0',
        'trade' => 'require|number|gt:0',
        'contact' => 'require|max:30',
        'mobile' => 'require|checkMobile',
        'district1' => 'require|number|gt:0',
        'district2' => 'require|number|egt:0',
        'district3' => 'require|number|egt:0',
        'address' => 'require|max:200',
        'logo' => 'number|egt:0',
        'short_name' => 'max:60',
        'registered' => 'max:15',
        'currency' => 'number|gt:0',
        'website' => 'max:50',
        'tag' => 'max:50',
        'short_desc' => 'max:255',
        'weixin' => 'max:15',
        'telephone' => 'max:20',
        'qq' => 'max:15',
        'email' => 'max:30'
    ];
}
