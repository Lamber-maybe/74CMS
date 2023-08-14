<?php
namespace app\common\model;

class HelpCategory extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'is_sys'];
    protected $type = [
        'id' => 'integer',
        'is_sys' => 'integer'
    ];
    protected $insert = ['is_sys' => 0];
}
