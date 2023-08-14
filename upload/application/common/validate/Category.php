<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class Category extends BaseValidate
{
    protected $rule = [
        'alias' => 'max:30',
        'name' => 'require|max:30'
    ];

    protected $message = [
        'alias.max' => '分组别名不能超过30个字',
        'name.require' => '请填写分类名称',
        'name.max' => '分类名称不能超过30个字'
    ];

    protected $scene = [
        'default' => ['alias', 'name']
    ];
}
