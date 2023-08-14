<?php
namespace app\common\model;

class PersonalServiceTag extends \app\common\model\BaseModel
{
    //解决移动端，个人用户购买醒目标签.进入后会默认选择第一个，但点立即支付，还是提醒需要选择标签，需要手工选择后才能支付
    public $map_tag = [1=>'能力强', 2=>'踏实稳定', 3=>'好学上进'];
}
