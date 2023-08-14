<?php

namespace app\common\model;

class CompanyServicePoints extends BaseModel
{
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
