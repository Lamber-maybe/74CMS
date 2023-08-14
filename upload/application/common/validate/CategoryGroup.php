<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class CategoryGroup extends BaseValidate
{
    protected $rule =   [
        'pid'  => 'number',
        'alias'  => 'require|max:30',
        'name'   => 'require|max:30',   
    ];
    
    protected $message  =   [
        'pid' => '上级id必须为数字',
        'alias.require' => '请填写别名',
        'alias.max'     => '别名不能超过30个字',
        'name.require'   => '请填写分组名称',
        'name.max'  => '分组名称不能超过30个字',
    ];
    
    protected $scene = [
        'default'  =>  ['pid','alias','name'],
    ];
}