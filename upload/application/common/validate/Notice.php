<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class Notice extends BaseValidate
{
    protected $rule =   [
        'title'   => 'require|max:60',
        'content' => 'require',    
        'is_display'=>'require|in:0,1',
        'link_url'=>'max:200',
        'seo_keywords'=>'max:100',
        'seo_description'=>'max:200',
        'addtime'=>'require|number',
        'click'=>'number',
        'sort_id'=>'number'
    ];
    protected $message = [
        'title.max' =>  '标题最多60个字'
    ];
}