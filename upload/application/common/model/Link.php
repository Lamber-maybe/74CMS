<?php
namespace app\common\model;

class Link extends \app\common\model\BaseModel
{
    protected $readonly = ['id'];
    protected $type     = [
        'id'        => 'integer',
        'is_display' => 'integer',
        'sort_id'    => 'integer',
    ];
}
