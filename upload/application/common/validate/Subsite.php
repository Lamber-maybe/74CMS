<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class Subsite extends BaseValidate
{
    protected $rule =   [
        'sitename'   => 'require|max:30',
        'district1'=>'require|gt:0',
        'keywords'   => 'max:100',
        'description'   => 'max:200',
        'tpl' => 'require',
        'is_display' =>  'number|in:0,1'
    ];
}