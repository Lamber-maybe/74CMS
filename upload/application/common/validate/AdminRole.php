<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class AdminRole extends BaseValidate
{
    protected $rule =   [
        'name'  => 'require|max:15',
    ];
}