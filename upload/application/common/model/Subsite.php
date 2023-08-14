<?php

namespace app\common\model;

class Subsite extends BaseModel
{
    /**
     * 是否显示
     * 0:不显示|1:显示
     * @var string[]
     */
    public $map_is_display = [
        0 => '不显示',
        1 => '显示'
    ];

    /**
     * 分站形式
     * 1:首页目录|2:首页目录
     * @var string[]
     */
    public $map_url_type = [
        1 => '二级域名',
        2 => '首页目录'
    ];
}
