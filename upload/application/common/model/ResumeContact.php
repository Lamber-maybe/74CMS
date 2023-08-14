<?php
namespace app\common\model;

class ResumeContact extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'uid', 'rid'];
    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'rid' => 'integer'
    ];
}
