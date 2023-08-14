<?php
namespace app\common\model;

class CompanyInfo extends \app\common\model\BaseModel
{
    protected $readonly = ['id','uid','comid'];
    protected $type     = [
        'id'        => 'integer',
        'uid'        => 'integer',
        'comid' => 'integer',
    ];
}
