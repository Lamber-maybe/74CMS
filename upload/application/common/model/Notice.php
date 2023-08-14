<?php
namespace app\common\model;

class Notice extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'addtime'];
    protected $type     = [
        'id'        => 'integer',
        'is_display' => 'integer',
        'click'      => 'integer',
        'addtime'    => 'integer',
        'sort_id'    => 'integer',
    ];
    protected $insert = ['addtime','click'=>0];
    protected function setAddtimeAttr()
    {
        return time();
    }
}
