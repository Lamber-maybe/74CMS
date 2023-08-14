<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class Ad extends BaseValidate
{
    protected $rule =   [
        'cid'  => 'require|gt:0',
        'title'   => 'require|max:100',
        'note' => 'max:200',    
        'is_display'=>'require|in:0,1',
        'imageurl'=>'max:255',
        'link_url'=>'max:255',
        'explain'=>'max:255',
        'starttime'=>'require|number',
        'deadline'=>'number',
        'uid'=>'number',
        'sort_id'=>'number',
    ];
}