<?php

namespace app\common\model\b2bcrm;

use app\common\model\BaseModel;

class CrmCompanyContact extends BaseModel
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'addtime';
    protected $updateTime = 'updatetime';
}