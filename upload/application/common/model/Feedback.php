<?php
namespace app\common\model;

class Feedback extends \app\common\model\BaseModel
{
    public $map_type = [
        1 => '建议', 2 => '意见', 3 => '求助', 4 => '投诉',
    ];
}
