<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class Hotword extends BaseValidate
{
    protected $rule =   [
        'word'   => 'require|max:30',
        'hot'=>'number',
    ];
}