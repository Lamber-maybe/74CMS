<?php
namespace app\common\model;


class Article extends \app\common\model\BaseModel
{
    protected $readonly = ['id'];
    protected $type     = [
        'id'        => 'integer',
        'cid'        => 'integer',
        'thumb' => 'integer',
        'is_display' => 'integer',
        'click'      => 'integer',
        'addtime'    => 'integer',
        'sort_id'    => 'integer',
    ];
}
