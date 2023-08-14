<?php
namespace app\common\model;

class Hrtool extends \app\common\model\BaseModel
{
    protected $readonly = ['id'];
    protected $type     = [
        'id'        => 'integer',
        'cid'        => 'integer',
        'sort_id'    => 'integer',
    ];
}
