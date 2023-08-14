<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class CustomerService extends BaseValidate
{
    protected $rule = [
        'name' => 'require|max:30',
        'mobile' => 'require|max:30'
    ];
}
