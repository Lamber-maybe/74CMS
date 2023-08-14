<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class CompanyServiceEmergency extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'days' => 'require|number|gt:0',
        'expense' => 'require|number|gt:0',
        'sort_id' => 'require|number|egt:0'
    ];
}
