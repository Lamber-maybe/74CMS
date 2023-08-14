<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class CompanyServiceRefreshJobPackage extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'times' => 'require|number|gt:0',
        'expense' => 'require|number|gt:0',
        'sort_id' => 'require|number|egt:0'
    ];
}
