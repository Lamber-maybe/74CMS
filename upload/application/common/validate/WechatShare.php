<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class WechatShare extends BaseValidate
{
    protected $rule =   [
        'name'  => 'require|max:30',
    ];
}