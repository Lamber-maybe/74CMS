<?php
namespace app\common\model;

class Message extends \app\common\model\BaseModel
{
    const TYPE_INFO = 1;
    const TYPE_SYSTEM = 2;
    const TYPE_NOTICE = 3;
    public $map_type = [
        self::TYPE_INFO => '招聘动态',
        self::TYPE_SYSTEM => '系统提示',
        self::TYPE_NOTICE => '公告通知'
    ];
    public function sendMessage(
        $uid,
        $content,
        $type,
        $inner_link = '',
        $inner_link_params = 0,
        $urlParams=''
    ) {
        $uid = is_array($uid) ? $uid : [$uid];
        $setsqlarr = [];
        $timestamp = time();
        foreach ($uid as $key => $value) {
            $data['uid'] = $value;
            $data['type'] = $type;
            $data['content'] = $content;
            $data['inner_link'] = $inner_link;
            $data['inner_link_params'] = $inner_link_params;
            $data['addtime'] = $timestamp;
            $data['is_readed'] = 0;
            $data['spe_link_params'] = $urlParams;
            $setsqlarr[] = $data;
        }
        if (!empty($setsqlarr)) {
            $this->saveAll($setsqlarr);
        }
        return;
    }
}
