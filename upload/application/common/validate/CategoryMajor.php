<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class CategoryMajor extends BaseValidate
{
    protected $rule =   [
        'pid'  => 'number',
        'name'   => 'require|max:30',   
    ];
}