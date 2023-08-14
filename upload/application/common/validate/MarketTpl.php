<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class MarketTpl extends BaseValidate
{
    protected $rule = [
        'name' => 'require|max:30',
        'content' => 'require'
    ];
}
