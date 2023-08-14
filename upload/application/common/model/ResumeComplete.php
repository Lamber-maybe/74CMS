<?php
namespace app\common\model;


class ResumeComplete extends \app\common\model\BaseModel
{
    protected $readonly = ['id','rid','uid'];
    protected $type     = [
        'id'        => 'integer',
        'uid'        => 'integer',
        'rid' => 'integer',
        'basic'    => 'integer',
        'intention'    => 'integer',
        'specialty'    => 'integer',
        'education'    => 'integer',
        'work'    => 'integer',
        'training'    => 'integer',
        'project'    => 'integer',
        'certificate'    => 'integer',
        'language'    => 'integer',
        'tag'    => 'integer',
        'img'    => 'integer',
        'attach'    => 'integer',
    ];
}
