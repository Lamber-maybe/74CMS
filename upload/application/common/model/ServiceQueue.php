<?php

namespace app\common\model;

class ServiceQueue extends BaseModel
{
    /**
     * 推广类型
     * @var string[]
     */
    public $map_type = [
        'emergency' => '紧急招聘',
        'jobstick' => '职位置顶',
        'tag' => '醒目标签',
        'stick' => '简历置顶'
    ];
}
