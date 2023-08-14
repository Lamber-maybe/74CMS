<?php

namespace app\common\model;

class Feedback extends BaseModel
{
    /**
     * 处理情况
     * @var string[]
     */
    public $map_status = [
        0 => '未处理',
        1 => '已处理'
    ];

    /**
     * 类型
     * @var string[]
     */
    public $map_type = [
        1 => '建议',
        2 => '意见',
        3 => '求助',
        4 => '投诉'
    ];
}
