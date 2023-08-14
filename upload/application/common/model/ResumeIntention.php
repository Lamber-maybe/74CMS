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
    protected static function init()
    {
        ResumeIntention::afterInsert(function ($info) {
            if(is_object($info)){
                $info = $info->toArray();
            }
            if(isset($info['rid'])){
                model('Resume')->where('id',$info['rid'])->setField('updatetime',time());
            }else if(isset($info['uid'])){
                model('Resume')->where('uid',$info['uid'])->setField('updatetime',time());
            }
        });
        ResumeIntention::afterUpdate(function ($info) {
            if(is_object($info)){
                $info = $info->toArray();
            }
            if(isset($info['rid'])){
                model('Resume')->where('id',$info['rid'])->setField('updatetime',time());
            }else if(isset($info['uid'])){
                model('Resume')->where('uid',$info['uid'])->setField('updatetime',time());
            }
        });
        ResumeIntention::afterDelete(function ($info) {
            if(is_object($info)){
                $info = $info->toArray();
            }
            if(isset($info['rid'])){
                model('Resume')->where('id',$info['rid'])->setField('updatetime',time());
            }else if(isset($info['uid'])){
                model('Resume')->where('uid',$info['uid'])->setField('updatetime',time());
            }
        });
    }
}
