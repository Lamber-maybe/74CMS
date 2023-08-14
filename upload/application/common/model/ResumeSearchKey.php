<?php

namespace app\common\model;

class ResumeSearchKey extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'uid'];
    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'audit' => 'integer',
        'stick' => 'integer',
        'sex' => 'integer',
        'nature' => 'integer',
        'birthyear' => 'integer',
        'education' => 'integer',
        'enter_job_time' => 'integer',
        'major' => 'integer',
        'photo' => 'integer',
        'refreshtime' => 'integer'
    ];
}
