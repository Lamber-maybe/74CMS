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

    /**
     * @param $uid
     * @param $utype
     * @param $alias
     * @param $replace_arr
     * @param $link_param
     * @param $urlParams
     * @param $otherParams array 新增[为解决'关于职位联系方式的短信通知']
     */
    public function notify(
        $uid,
        $utype,
        $alias,
        $replace_arr = [],
        $link_param = 0,
        $urlParams = '',
        $otherParams = []
    )
    {
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
        if ($ruleOne['max_time_per_day'] > 0) {
            $notifyLog = model('NotifyLog')->where('uid', 'in', $uid)->where('alias', $alias)->where('addtime', $timestampToday)->group('uid')->column('uid,count(*) as num');
            foreach ($notifyLog as $k => $v) {
                if ($v >= $ruleOne['max_time_per_day']) {
                    unset($uid[array_search($k, $uid)]);
                }
            }
        }
        if (empty($uid)) {
            return;
        }
        $notifyLog = [];
        foreach ($uid as $key => $value) {
            $notifyLog[] = [
                'uid' => $value,
                'alias' => $alias,
                'addtime' => $timestampToday
            ];
        }
        model('NotifyLog')->insertAll($notifyLog);

        $message_to_arr = $sms_to_arr = $mail_to_arr = [];
        if ($ruleOne['open_message'] == 1) {
            $message_to_arr = $uid;
        }
        if ($ruleOne['open_sms'] == 1 || $ruleOne['open_email'] == 1) {
            $memberlist = model('Member')
                ->where('uid', 'in', $uid)
                ->field('uid,mobile,email')
                ->select();

            /**
             * yx - 2022.12.30
             * [新增]：
             * 群发无法使用，避免群发短信发生错误，
             * 增加【if (1 === count($uid) && !empty($otherParams))】判断
             */
            if (!isset($memberlist) || empty($memberlist)) {
                return;
            } else {
                $memberlist = collection($memberlist)->toArray();
            }
            if (1 === count($uid) && !empty($otherParams)) {
                switch ($alias) {
                    case 'job_audit_success':
                    case 'job_audit_fail':
                    case 'job_apply':
                        if (!isset($otherParams['job_id']) || empty($otherParams['job_id'])) {
                            break;
                        }
                        $job_id = $otherParams['job_id'];
                        $job_contact = model('JobContact')
                            ->where('jid', $job_id)
                            ->find();
                        if (!isset($job_contact) || empty($job_contact)) {
                            break;
                        }
                        if (0 === $job_contact['use_company_contact']) {
                            $memberlist[0]['mobile'] = $job_contact['mobile'];
                        }
                        break;

                    default:
                        break;
                }
            }

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
            if (is_array($content)) {
                foreach ($content as $k => $v) {
                    model('Message')->sendMessage(
                        $message_to_arr,
                        $v,
                        $ruleOne['type'],
                        $ruleOne['inner_link'],
                        $link_param,
                        $urlParams
                    );
                }
            } else {
                model('Message')->sendMessage(
                    $message_to_arr,
                    $content,
                    $ruleOne['type'],
                    $ruleOne['inner_link'],
                    $link_param,
                    $urlParams
                );
            }
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
        $arr = [];
        foreach ($replace_arr as $key => $value) {
            $contents = '';
            if (is_array($value)) {
                $contents = $content;
                foreach ($value as $k => $v) {
                    $contents = str_replace('{' . $k . '}', $v, $contents);
                }

                $arr[] = $contents;
            } else {
                $content = str_replace('{' . $key . '}', $value, $content);
            }
        }
        if (empty($arr)) {
            return $content;
        }
        return $arr;
    }
}
