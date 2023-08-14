<?php
namespace app\common\model;

class ResumeIntention extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'rid', 'uid'];
    protected $insert = [
        'nature' => 1
    ];
    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'rid' => 'integer',
        'nature' => 'integer',
        'category1' => 'integer',
        'category2' => 'integer',
        'category3' => 'integer',
        'category' => 'integer',
        'district' => 'integer',
        'minwage' => 'integer',
        'maxwage' => 'integer',
        'trade' => 'integer'
    ];
}
