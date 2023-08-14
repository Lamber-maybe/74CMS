<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class MarketTask extends BaseValidate
{
    protected $rule = [
        'title' => 'require|max:30',
        'content' => 'require',
        'send_type' => 'require',
        'target' => 'require',
        'condition' => 'require'
    ];
}
