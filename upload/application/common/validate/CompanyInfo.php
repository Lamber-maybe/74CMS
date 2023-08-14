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

    protected $message = [
        'website.require' => '请填写企业网址',
        'website.max' => '请填写200个字以内的企业网址',
        'short_desc.require' => '请填写企业简介',
        'short_desc.max' => '请填写255个字以内的企业简介',
        'address.require' => '请填写企业地址',
        'address.max' => '请填写200个字以内的企业地址',
        'content.require' => '请填写企业介绍',
        'content.max' => '请填写100000个字以内的企业介绍',
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
