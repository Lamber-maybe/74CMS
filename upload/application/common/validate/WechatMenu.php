<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class WechatMenu extends BaseValidate
{
    protected $rule =   [
        'title'  => 'require|max:30',
    ];
}