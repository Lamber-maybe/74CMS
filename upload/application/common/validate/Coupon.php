<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class Coupon extends BaseValidate
{
    protected $rule = [
        'name' => 'require|max:30',
        'face_value' => 'require|number|gt:0',
        'bind_setmeal_id' => 'require|number',
        'days' => 'require|number'
    ];
}
