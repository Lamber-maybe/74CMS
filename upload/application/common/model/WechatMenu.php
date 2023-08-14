<?php

namespace app\common\model;

class WechatMenu extends BaseModel
{
    /**
     * 菜单类型
     * @var string[]
     */
    public $map_type = [
        'click' => '点击事件',
        'view' => '链接事件',
        'miniprogram' => '小程序'
    ];
}
