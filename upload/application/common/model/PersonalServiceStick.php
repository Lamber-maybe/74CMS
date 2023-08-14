<?php

namespace app\common\model;

class PersonalServiceStick extends BaseModel
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
}
