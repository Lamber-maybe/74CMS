<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class Hrtool extends BaseValidate
{
    protected $rule =   [
        'cid'  => 'require|gt:0',
        'filename'   => 'require|max:200', 
        'fileurl'   => 'require|max:200',   
    ];
    
    protected $message  =   [
        'cid.require' => '请选择分类',
        'cid.gt' => '请选择分类',
        'filename.require'   => '请填写名称',
        'filename.max'  => '名称不能超过200个字',
        'fileurl.require'   => '请填写文件路径',
        'fileurl.max'  => '文件路径不能超过200个字',
    ];
    
    protected $scene = [
        'default'  =>  ['cid','filename','fileurl'],
    ];
}