<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class Help extends BaseValidate
{
    protected $rule =   [
        'cid'  => 'require|gt:0',
        'title'   => 'require|max:100',
        'content' => 'require',    
        'is_display'=>'require|in:0,1',
        'seo_keywords'=>'max:100',
        'seo_description'=>'max:200',
        'sort_id'=>'number',
    ];
}