<?php
namespace app\common\model;

class JobSearchRtime extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'uid'];
    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'audit' => 'integer',
        'license' => 'integer',
        'stick' => 'integer',
        'setmeal_id' => 'integer',
        'nature' => 'integer',
        'category1' => 'integer',
        'category2' => 'integer',
        'category3' => 'integer',
        'category' => 'integer',
        'trade' => 'integer',
        'scale' => 'integer',
        'district1' => 'integer',
        'district2' => 'integer',
        'district3' => 'integer',
        'district' => 'integer',
        'education' => 'integer',
        'experience' => 'integer',
        'minwage' => 'integer',
        'maxwage' => 'integer',
        'refreshtime' => 'integer'
    ];
}
