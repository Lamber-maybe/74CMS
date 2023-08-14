<?php

namespace app\common\model;

class Tipoff extends BaseModel
{
    /**
     * 举报类型
     * @var string[]
     */
    public $map_type = [
        1 => '职位',
        2 => '简历'
    ];

    /**
     * 职位-举报原因
     * @var string[]
     */
    public $map_type_job = [
        1 => '电话虚假（空号、无人接听）',
        2 => '职介收费',
        3 => '职介冒充',
        4 => '虚假（职位、待遇等）',
        5 => '网赚虚假（刷钻、刷单）',
        6 => '其他'
    ];

    /**
     * 简历-举报原因
     * @var string[]
     */
    public $map_type_resume = [
        1 => '无人接听',
        2 => '打广告',
        3 => '找到工作',
        4 => '虚假信息',
        5 => '号码错误'
    ];

    /**
     * 核实情况
     * @var string[]
     */
    public $map_status = [
        0 => '未核实',
        1 => '属实',
        2 => '不属实'
    ];
}
