<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class Cron extends BaseValidate
{
    protected $rule = [
        'name' => 'require|max:30',
        'action' => 'require|max:30',
        'weekday' => 'require|number',
        'day' => 'require|number',
        'hour' => 'require|number',
        'minute' => 'require|max:10'
    ];
}
