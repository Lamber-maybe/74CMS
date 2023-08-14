<?php
namespace app\common\model;

class CouponRecord extends \app\common\model\BaseModel
{
    protected $readonly = [
        'id',
        'log_id',
        'uid',
        'coupon_name',
        'coupon_face_value',
        'coupon_bind_setmeal_id',
        'deadline',
        'addtime'
    ];
    protected $type = [
        'id' => 'integer',
        'log_id' => 'integer',
        'coupon_bind_setmeal_id' => 'integer',
        'deadline' => 'integer',
        'usetime' => 'integer',
        'addtime' => 'integer'
    ];
}
