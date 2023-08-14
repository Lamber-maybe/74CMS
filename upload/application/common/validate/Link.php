<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class Link extends BaseValidate
{
    protected $rule =   [
        'name'   => 'require|max:20',  
        'link_url'   => 'require|max:255',  
        'notes'   => 'max:100',  
        'sort_id'   => 'number',
        'is_display'   => 'in:0,1',   
    ];
    
    protected $message  =   [
        'name.require'   => '请填写名称',
        'name.max'  => '名称不能超过20个字',
        'link_url.require'  => '请填写跳转链接',
        'link_url.max'  => '跳转链接不能超过255个字',
        'notes'  => '备注不能超过100个字',
        'sort_id'  => '排序必须是数字',
        'is_display'  => '是否显示必须是0,1',
    ];
    
    protected $scene = [
        'default'  =>  ['name','link_url','notes','sort_id','is_display'],
    ];
}