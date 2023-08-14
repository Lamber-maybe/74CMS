<?php
namespace app\common\model;


class ResumeTraining extends \app\common\model\BaseModel
{
    protected $readonly = ['id','rid','uid'];
    protected $type     = [
        'id'        => 'integer',
        'uid'        => 'integer',
        'rid' => 'integer',
        'starttime'    => 'integer',
        'endtime'    => 'integer',
        'todate'    => 'integer',
    ];
}
