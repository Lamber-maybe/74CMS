<?php
namespace app\common\model;


class ResumeProject extends \app\common\model\BaseModel
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
    protected static function init()
    {
        ResumeProject::afterInsert(function ($info) {
            if(is_object($info)){
                $info = $info->toArray();
            }
            if(isset($info['rid'])){
                model('Resume')->where('id',$info['rid'])->setField('updatetime',time());
            }else if(isset($info['uid'])){
                model('Resume')->where('uid',$info['uid'])->setField('updatetime',time());
            }
        });
        ResumeProject::afterUpdate(function ($info) {
            if(is_object($info)){
                $info = $info->toArray();
            }
            if(isset($info['rid'])){
                model('Resume')->where('id',$info['rid'])->setField('updatetime',time());
            }else if(isset($info['uid'])){
                model('Resume')->where('uid',$info['uid'])->setField('updatetime',time());
            }
        });
        ResumeProject::afterDelete(function ($info) {
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
