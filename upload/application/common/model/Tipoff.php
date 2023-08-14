<?php
namespace app\common\model;

class Tipoff extends \app\common\model\BaseModel
{
    public $map_type_job = [
        1 => '电话虚假（空号、无人接听）', 2 => '职介收费', 3 => '职介冒充', 4 => '虚假（职位、待遇等）',5=>'网赚虚假（刷钻、刷单）',6=>'其他'
    ];
    public $map_type_resume = [
        1 => '无人接听', 2 => '打广告', 3 => '找到工作', 4 => '虚假信息',5=>'号码错误'
    ];
}
