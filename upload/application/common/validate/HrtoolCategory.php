<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class HrtoolCategory extends BaseValidate
{
    protected $rule =   [
        'name'  => 'require|max:80',
        'describe'   => 'max:200',   
    ];
    
    protected $message  =   [
        'name.require' => '请填写分类名称',
        'name.max' => '分类名称不能超过80个字',
        'describe'  => '描述不能超过200个字',
    ];
    
    protected $scene = [
        'default'  =>  ['name','describe'],
    ];
}