<?php
namespace app\common\model;

class CouponLog extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'coupon_id', 'admin_name', 'addtime'];
    protected $type = [
        'id' => 'integer',
        'addtime' => 'integer'
    ];
}
