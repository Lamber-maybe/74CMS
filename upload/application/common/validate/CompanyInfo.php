<?php

namespace app\common\validate;

use app\common\validate\BaseValidate;

class CompanyInfo extends BaseValidate
{
    public function __construct()
    {
        parent::__construct();
        $this->initValidateRule('CompanyInfo');
    }

    protected $rule = [
        'uid' => 'number|gt:0|unique:company_info',
        'comid' => 'number|gt:0|unique:company_info',
        'website' => 'max:200',
        'short_desc' => 'max:255',
        'address' => 'max:200',
        'content' => 'max:100000'
    ];

    /**
     * 场景验证
     */
    protected $scene = [
        'address' => ['address'],
        'website' => ['website'],
        'introduction' => ['short_desc', 'content'],
    ];
}
