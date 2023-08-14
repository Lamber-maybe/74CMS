<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class CompanyReport extends BaseValidate
{
    protected $rule = [
        'uid' => 'number|gt:0|unique:company_report',
        'company_id' => 'require|max:60|unique:company_report'

    ];
}
