<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class Explain extends BaseValidate
{
    protected $rule =   [
        'title'   => 'require|max:100',  
        'content'   => 'require',   
        'is_display'=>'require|in:0,1',
        'link_url'=>'max:200',
        'seo_keywords'=>'max:100',
        'seo_description'=>'max:200',
        'sort_id'=>'number',
    ];
}