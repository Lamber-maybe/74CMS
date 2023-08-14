<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class WechatKeyword extends BaseValidate
{
    protected $rule =   [
        'word'  => 'require|max:30',
    ];
}