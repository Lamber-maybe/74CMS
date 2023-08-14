<?php
namespace app\common\model;

class JobContact extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'uid', 'jid'];
    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'jid' => 'integer',
        'is_display' => 'integer'
    ];
}
