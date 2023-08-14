<?php

namespace app\common\model;

class PersonalServiceTag extends BaseModel
{
    /**
     * 积分抵扣规则
     * 0:不允许|1:全额兑换|2:部分抵扣欧
     * @var string[]
     */
    public $map_enable_points_deduct = [
        0 => '不允许',
        1 => '全额兑换',
        2 => '部分抵扣'
    ];

    /**
     * 显示
     * 0:显示中|1:不显示
     * @var string[]
     */
    public $map_is_display = [
        0 => '不显示',
        1 => '显示中'
    ];

    /**
     * 推荐
     * 0:不推荐|1:推荐
     * @var string[]
     */
    public $map_recommend = [
        0 => '不推荐',
        1 => '推荐'
    ];

    //解决移动端，个人用户购买醒目标签.进入后会默认选择第一个，但点立即支付，还是提醒需要选择标签，需要手工选择后才能支付
    public $map_tag = [
        1 => '能力强',
        2 => '踏实稳定',
        3 => '好学上进'
    ];
}
