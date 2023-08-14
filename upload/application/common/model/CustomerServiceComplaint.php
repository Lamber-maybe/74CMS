<?php

namespace app\common\model;

class CustomerServiceComplaint extends BaseModel
{
    /**
     * 处理状态
     * @var string[]
     */
    public $map_status = [
        0 => '未处理',
        1 => '已处理'
    ];
}
