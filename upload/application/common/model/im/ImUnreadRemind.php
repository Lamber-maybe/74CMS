<?php

namespace app\common\model\im;

use app\common\model\BaseModel;

class ImUnreadRemind extends BaseModel
{
    // 主键
    protected $pk = 'id';

    // 只读字段
    protected $readonly = [
        'id'
    ];

    // 数据类型
    protected $type = [
        'id' => 'integer',
        'to_uid' => 'integer',
        'from_uid' => 'integer',
        'add_time' => 'integer'
    ];

    // 设置返回数据集的对象名
    protected $resultSetType = 'collection';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'add_time';

}