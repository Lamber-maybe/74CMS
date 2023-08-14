<?php
namespace app\common\model;

class CategoryGroup extends \app\common\model\BaseModel
{
    protected $readonly = ['id','is_sys'];
    protected $type     = [
        'id'        => 'integer',
        'is_sys'    => 'integer',
    ];
}
