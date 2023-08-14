<?php

namespace app\common\model;

class ResumeAnalysisLog extends BaseModel
{
    protected $readonly = ['id', 'uid', 'file_id'];

    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'file_id' => 'integer',
        'code' => 'integer',
        'addtime' => 'integer'
    ];

}
