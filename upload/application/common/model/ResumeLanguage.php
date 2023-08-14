<?php
namespace app\common\model;


class ResumeLanguage extends \app\common\model\BaseModel
{
    protected $readonly = ['id','rid','uid'];
    protected $type     = [
        'id'        => 'integer',
        'uid'        => 'integer',
        'rid' => 'integer',
        'language'    => 'integer',
        'level'    => 'integer',
    ];
    protected static function init()
    {
        ResumeLanguage::afterInsert(function ($info) {
            if(is_object($info)){
                $info = $info->toArray();
            }
            if(isset($info['rid'])){
                model('Resume')->where('id',$info['rid'])->setField('updatetime',time());
            }else if(isset($info['uid'])){
                model('Resume')->where('uid',$info['uid'])->setField('updatetime',time());
            }
        });
        ResumeLanguage::afterUpdate(function ($info) {
            if(is_object($info)){
                $info = $info->toArray();
            }
            if(isset($info['rid'])){
                model('Resume')->where('id',$info['rid'])->setField('updatetime',time());
            }else if(isset($info['uid'])){
                model('Resume')->where('uid',$info['uid'])->setField('updatetime',time());
            }
        });
        ResumeLanguage::afterDelete(function ($info) {
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
