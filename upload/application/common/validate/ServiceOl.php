<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class ServiceOl extends BaseValidate
{
    protected $rule =   [
        'name'   => 'require|max:100',
        'mobile' => 'require',
        'weixin' => 'require',
        'qq' => 'require',
        'sort'=>'number',
        'display'=>'require|in:0,1'
    ];
}
