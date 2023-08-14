<?php

namespace app\common\validate;

class ArticleCategory extends BaseValidate
{
    protected $rule = [
        'name' => 'require|max:10',
        'sort_id' => 'number',
        'seo_title' => 'max:100',
        'seo_keywords' => 'max:100',
        'seo_description' => 'max:200'
    ];
}