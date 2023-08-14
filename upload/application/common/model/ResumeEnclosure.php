<?php

namespace app\common\model;

class ResumeEnclosure extends BaseModel
{
    protected $readonly = ['id', 'rid', 'uid'];

    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'rid' => 'integer',
        'addtime' => 'integer',
        'audit' => 'integer'
    ];

    public $map_audit = [
        0 => '待审核',
        1 => '审核通过',
        2 => '审核未通过'
    ];

    protected static function init()
    {
        ResumeImg::afterInsert(function ($info) {
            if (is_object($info)) {
                $info = $info->toArray();
            }
            if (isset($info['rid'])) {
                model('Resume')->where('id', $info['rid'])->setField('updatetime', time());
            } else if (isset($info['uid'])) {
                model('Resume')->where('uid', $info['uid'])->setField('updatetime', time());
            }
        });

        ResumeImg::afterUpdate(function ($info) {
            if (is_object($info)) {
                $info = $info->toArray();
            }
            if (isset($info['rid'])) {
                model('Resume')->where('id', $info['rid'])->setField('updatetime', time());
            } else if (isset($info['uid'])) {
                model('Resume')->where('uid', $info['uid'])->setField('updatetime', time());
            }
        });

        ResumeImg::afterDelete(function ($info) {
            if (is_object($info)) {
                $info = $info->toArray();
            }
            if (isset($info['rid'])) {
                model('Resume')->where('id', $info['rid'])->setField('updatetime', time());
            } else if (isset($info['uid'])) {
                model('Resume')->where('uid', $info['uid'])->setField('updatetime', time());
            }
        });
    }

    public function getEnclosure($map)
    {
        $enclosureResume = $this
            ->field('rid,uid', true)
            ->where($map)
            ->find();

        if (null === $enclosureResume) {
            return '';
        }

        $file_arr = model('Uploadfile')->getFileUrlBatch($enclosureResume['enclosure']);

        $enclosureResume['enclosure'] = isset($file_arr[$enclosureResume['enclosure']])
            ? $file_arr[$enclosureResume['enclosure']]
            : '';

        return $enclosureResume;
    }
}
