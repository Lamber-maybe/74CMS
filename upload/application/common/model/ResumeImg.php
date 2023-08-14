<?php
namespace app\common\model;

class ResumeImg extends \app\common\model\BaseModel
{
    public $map_audit = [
        0 => '待审核',
        1 => '审核通过',
        2 => '审核未通过'
    ];
    protected $readonly = ['id', 'rid', 'uid'];
    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'rid' => 'integer',
        'addtime' => 'integer',
        'audit' => 'integer'
    ];
    protected static function init()
    {
        ResumeImg::afterInsert(function ($info) {
            if(is_object($info)){
                $info = $info->toArray();
            }
            if(isset($info['rid'])){
                model('Resume')->where('id',$info['rid'])->setField('updatetime',time());
            }else if(isset($info['uid'])){
                model('Resume')->where('uid',$info['uid'])->setField('updatetime',time());
            }
        });
        ResumeImg::afterUpdate(function ($info) {
            if(is_object($info)){
                $info = $info->toArray();
            }
            if(isset($info['rid'])){
                model('Resume')->where('id',$info['rid'])->setField('updatetime',time());
            }else if(isset($info['uid'])){
                model('Resume')->where('uid',$info['uid'])->setField('updatetime',time());
            }
        });
        ResumeImg::afterDelete(function ($info) {
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
    public function getList($map){
        $img_list = model('ResumeImg')
            ->field('rid,uid', true)
            ->where($map)
            ->select();
        $fileid_arr = $file_arr = [];
        foreach ($img_list as $key => $value) {
            if ($value['img'] > 0) {
                $fileid_arr[] = $value['img'];
            }
        }
        if (!empty($fileid_arr)) {
            $file_arr = model('Uploadfile')->getFileUrlBatch($fileid_arr);
        }
        foreach ($img_list as $key => $value) {
            $value['audit_text'] = isset(
                model('ResumeImg')->map_audit[$value['audit']]
            )
                ? model('ResumeImg')->map_audit[$value['audit']]
                : '待审核';
            $value['img_src'] = isset($file_arr[$value['img']])
                ? $file_arr[$value['img']]
                : '';
            $img_list[$key] = $value;
        }
        return $img_list;
    }
}
