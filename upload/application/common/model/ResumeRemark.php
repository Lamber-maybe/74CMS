<?php

namespace app\common\model;

class ResumeRemark extends BaseModel
{
    protected $pk = 'id';

    protected $readonly = [
        'id'
    ];

    protected $type = [
        'id' => 'integer',
        'comid' => 'integer',
        'resume_id' => 'integer',
        'addtime' => 'integer',
        'updatetime' => 'integer'
    ];

    // 设置返回数据集的对象名
    protected $resultSetType = 'collection';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'addtime';
    protected $updateTime = 'updatetime';
}