<?php
namespace app\common\model;

class CompanyContact extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'uid', 'comid'];
    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'comid' => 'integer'
    ];
}
