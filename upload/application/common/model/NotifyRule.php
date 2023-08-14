<?php
namespace app\common\model;

class NotifyRule extends \app\common\model\BaseModel
{
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_notify_rule', null);
        });
    }
    public function getCache()
    {
        if (false === ($list = cache('cache_notify_rule'))) {
            $data = $this->field(true)->select();
            $list = [];
            foreach ($data as $key => $value) {
                $list[$value['utype']][$value['alias']] = $value;
            }
            cache('cache_notify_rule', $list);
        }
        return $list;
    }
    public function notify(
        $uid,
        $utype,
        $alias,
        $replace_arr = [],
        $link_param=0,
        $urlParams=''
    ) {
        $uid = is_array($uid) ? $uid : [$uid];
        $ruleAll = $this->getCache();
        if (!isset($ruleAll[$utype])) {
            return;
        }
        $ruleAll = $ruleAll[$utype];
        if (!isset($ruleAll[$alias])) {
            return;
        }
        $ruleOne = $ruleAll[$alias];
        $timestampToday = strtotime('today');
        if($ruleOne['max_time_per_day']>0){
            $notifyLog = model('NotifyLog')->where('uid','in',$uid)->where('alias',$alias)->where('addtime',$timestampToday)->group('uid')->column('uid,count(*) as num');
            foreach ($notifyLog as $k => $v) {
                if($v>=$ruleOne['max_time_per_day']){
                    unset($uid[array_search($k,$uid)]);
                }
            }
        }
        if(empty($uid)){
            return;
        }
        foreach ($uid as $key => $value) {
            model('NotifyLog')->save(['uid'=>$value,'alias'=>$alias,'addtime'=>$timestampToday]);
        }
        $message_to_arr = $sms_to_arr = $mail_to_arr = [];
        if ($ruleOne['open_message'] == 1) {
            $message_to_arr = $uid;
        }
        if ($ruleOne['open_sms'] == 1 || $ruleOne['open_email'] == 1) {
            $memberlist = model('Member')
                ->where('uid', 'in', $uid)
                ->field('uid,mobile,email')
                ->select();
            foreach ($memberlist as $key => $value) {
                if ($ruleOne['open_sms'] == 1 && $value['mobile']) {
                    $sms_to_arr[] = $value['mobile'];
                }
                if ($ruleOne['open_email'] == 1 && $value['email']) {
                    $mail_to_arr[] = $value['email'];
                }
            }
        }

        $content = $this->replaceContent($ruleOne['content'], $replace_arr);

        if (!empty($message_to_arr)) {
            $message_to_arr = array_unique($message_to_arr);
            model('Message')->sendMessage(
                $message_to_arr,
                $content,
                $ruleOne['type'],
                $ruleOne['inner_link'],
                $link_param,
                $urlParams
            );
        }
        if (!empty($sms_to_arr)) {
            $sms_to_arr = array_unique($sms_to_arr);
            $instance = new \app\common\lib\sms\qscms();
            $instance->sendDirect($sms_to_arr, $content);
        }
        if (!empty($mail_to_arr)) {
            $mail_to_arr = array_unique($mail_to_arr);
            $instance = new \app\common\lib\Mail();
            $instance->send(
                $mail_to_arr,
                model('Message')->map_type[$ruleOne['type']],
                $content
            );
        }
    }
    protected function replaceContent($content, $replace_arr)
    {
        foreach ($replace_arr as $key => $value) {
            $content = str_replace('{' . $key . '}', $value, $content);
        }
        return $content;
    }
}
