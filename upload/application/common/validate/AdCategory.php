<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class AdCategory extends BaseValidate
{
    protected $rule = [
        'alias' => 'require|max:30|unique:ad_category',
        'name' => 'require|max:30',
        'ad_num' => 'require|number|gt:0'
    ];
}
