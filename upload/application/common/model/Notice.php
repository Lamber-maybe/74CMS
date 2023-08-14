<?php
namespace app\common\model;

class Notice extends \app\common\model\BaseModel
{
    protected $readonly = ['id'];
    protected $type     = [
        'id'        => 'integer',
        'is_display' => 'integer',
        'click'      => 'integer',
        'addtime'    => 'integer',
        'sort_id'    => 'integer',
    ];
}
