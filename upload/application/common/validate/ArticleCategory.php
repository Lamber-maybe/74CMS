<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class ArticleCategory extends BaseValidate
{
    protected $rule =   [
        'name'  => 'require|max:10', 
        'sort_id'=>'number',
        'seo_keywords'=>'max:100',
        'seo_description'=>'max:200', 
    ];
}