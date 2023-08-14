<?php

namespace app\apiadmin\controller\im;

use app\common\controller\Backend;
use app\common\lib\Sms;
use app\common\lib\Wechat;
use app\common\model\im\ImUnreadRemind;
use app\common\model\ImShortUrl;

class UnreadRemind extends Backend
{
    /**
     * @Purpose
     * 企业ID
     * @var array
     */
    private $clientIp = [
        '47.98.211.99'   // im服务器
    ];

    public function timingPush()
    {
        // 推送IP
        $client_ip = get_client_ip();

        /*
         * 非法推送IP
         */
        if (!in_array($client_ip, $this->clientIp)) {
            cache('timing_push_error', 'Err: 403 forbidden,Illegal IP:[' . $client_ip . '] ');
            die("Err: 403 forbidden\n\n");
        }

        /*
         * 非法请求（非POST请求）
         */
        if (!request()->isPost()) {
            cache('timing_push_error', 'Err: 403 forbidden,Illegal Method:[' . request()->method() . '] ');
            die("Err: 403 forbidden\n\n");
        }

        $to_uid = input('post.to_uid/d', 0, 'intval');
        if (empty($to_uid)) {
            $this->ajaxReturn(500, '缺少参数【tu_id】');
        }

        $from_uid = input('post.from_uid/d', 0, 'intval');
        if (empty($from_uid)) {
            $this->ajaxReturn(500, '缺少参数【fu_id】');
        }

        $job_id = input('post.job_id/d', 0, 'intval');

        $stick = input('post.stick/d', 0, 'intval');

        $chat_id = input('post.chat_id/s', '', 'trim');
        if (empty($chat_id)) {
            $this->ajaxReturn(500, '缺少参数【c_id】');
        }

        $message = input('post.message/s', 0, 'trim');
        if (empty($message)) {
            $this->ajaxReturn(500, '缺少参数【m_info】');
        }

        $keyword = input('post.keyword/s', 0, 'trim');
        if (empty($keyword)) {
            $this->ajaxReturn(500, '缺少参数【k_word】');
        }

        $message_id = input('post.message_id/s', 0, 'trim');
        if (empty($message_id)) {
            $this->ajaxReturn(500, '缺少参数【m_id】');
        }

        $type = input('post.type/s', 0, 'trim');
        if (empty($type)) {
            $this->ajaxReturn(500, '缺少参数【m_type】');
        }

        $add_time = input('post.add_time/s', 0, 'trim');
        if (empty($add_time)) {
            $this->ajaxReturn(500, '缺少参数【push_time】');
        }

        /**
         * 第二步：
         * 判断未读消息用户提醒类型
         * 1.是否绑定微信公众号【绑定：微信模板消息通知；未绑定进入[2]】
         * 2.判断是否开启短信提醒
         */
        // 1.判断消息接收人信息
        $to_member_info = model('Member')
            ->find($to_uid);
        if (null === $to_member_info) {
            $this->ajaxReturn(500, '消息接收人平台会员信息异常');
        }

        // 2.判断消息发送人信息
        $from_member_name = model('Member')
            ->getMemberNickNameByUid($from_uid);
        if (false === $from_member_name) {
            $this->ajaxReturn(500, '消息发送人平台会员信息异常');
        }

        // 3.消息接收人绑定信息
        $member_bind = model('MemberBind')
            ->where('uid', $to_uid)
            ->where('type', 'weixin')
            ->find();

        // 4.获取未读消息提醒配置
        $im_unread_config = model('ImRule')
            ->getCache(0);
        $unread_reminder = $im_unread_config['unread_reminder'];
        $unread_templateid = $im_unread_config['unread_templateid'];

        /**
         * 5.拼接跳转地址
         */
        // 5.1 jobname
        $jobName = model('Job')
            ->where('id', $job_id)
            ->value('jobname');
        // 5.2 name
        $name = $from_member_name;
        // 5.3 resumeId
        if ($to_member_info['utype'] === 2) {
            $resumeId = model('Resume')
                ->where('uid', $to_uid)
                ->value('id');
        } else {
            $resumeId = model('Resume')
                ->where('uid', $from_uid)
                ->value('id');
        }

        // 跳转地址
        $url = config('global_config.mobile_domain') . 'im/' . $chat_id .
            '?jobname=' . $jobName
            . '&name=' . $name
            . '&jobid=' . $job_id
            . '&resumeid=' . $resumeId;

        try {
            $m = new ImShortUrl();
            $short_url = $m->gen($url, $to_uid);
        } catch (\Exception $e) {
            $this->ajaxReturn(500, $e->getMessage());
        }

        $remind_data = [
            'to_uid' => $to_uid,
            'from_uid' => $from_uid,
            'chat_id' => $chat_id,
            'job_id' => $job_id,
            'stick' => $stick,
            'message_id' => $message_id,
            'message' => $message,
            'keyword' => $keyword,
            'type' => $type,
            'add_time' => $add_time,
        ];

        /**
         * 判断用户是否绑定微信公众号
         * 判断平台是否配置`微信模板消息通知ID`
         */
        if (!empty($member_bind) && !empty($unread_templateid)) {
            if (empty($keyword)) {
                $keyword = '点击查看详情';
            }
            $first = '您有一条留言待处理';
            // 判断未读消息类型
            switch ($type) {
                case 'text':
                case 'system':
                case 'wechat':
                case 'image':
                case 'invite':
                case 'job':
                case 'resume':
                case 'map':
                case 'mobile':
                    $keyword1 = "留言会员：【{$from_member_name}】\r\n留言内容：【{$keyword}】";
                    break;

                default:
                    $keyword1 = "留言会员：【{$from_member_name}】\r\n留言内容：【点击查看详情】";
                    break;
            }

            $instance = new Wechat();
            $res = $instance->tmp_message(
                $member_bind['openid'],
                $unread_templateid,
                [
                    'first' => [
                        'value' => $first
                    ],
                    'keyword1' => [
                        'value' => $keyword1
                    ],
                    'keyword2' => [
                        'value' => date('Y-m-d H:i:s', $add_time)
                    ],
                    'remark' => [
                        'value' => '点击立即查看留言，建议及时回复，不错过每个好机遇！',
                        'color' => '#CC6600'
                    ]
                ],
                $short_url
            );

            if (isset($res['errcode']) && 0 === $res['errcode']) {
                try {
                    $remind_data['remind_mode'] = 1;
                    $insert_log = model('im.ImUnreadRemind')
                        ->allowField(true)
                        ->isUpdate(false)
                        ->save($remind_data);
                    if (false === $insert_log) {
                        throw new \Exception(model('im.ImUnreadRemind')->getError());
                    }

                    $this->ajaxReturn(200, 'ok');

                } catch (\Exception $e) {
                    $this->ajaxReturn(500, '入库失败:' . $e->getMessage());
                }
            } else {
                $this->ajaxReturn(500, '微信公众号提醒失败：' . $instance->getError());
            }
        }

        /**
         * 未绑定公众号，则发送短信提醒
         * 绑定公众号，但平台未配置`微信模板消息通知ID`，也发送短信提醒
         * ‘在线聊未读消息短信通知’关闭不发送短信
         */
        if ($unread_reminder && !empty($to_member_info['mobile'])) {
            $sms_message = "您有【{$from_member_name}】的留言，点击查看{$url}";
            $type_name = config('global_config.sendsms_type');

            $smsClass = new Sms();
            $sms_send_result = $smsClass->send(
                $to_member_info['mobile'],
                'SMS_14',
                [
                    'membername' => $from_member_name,
                    'url' => $short_url
                ]
            );
            if (false === $sms_send_result) {
                $this->ajaxReturn(500, '短信提醒失败' . $smsClass->getError());
            }

            try {
                $remind_data['remind_mode'] = 2;
                $insert_log = model('im.ImUnreadRemind')
                    ->allowField(true)
                    ->isUpdate(false)
                    ->save($remind_data);
                if (false === $insert_log) {
                    throw new \Exception(model('im.ImUnreadRemind')->getError());
                }

                $this->ajaxReturn(200, 'ok');

            } catch (\Exception $e) {
                $this->ajaxReturn(500, '入库失败:' . $e->getMessage());
            }

        } else {
            $this->ajaxReturn(500, '消息接收人未绑定微信公众号，且平台未开启短信通知');
        }
    }
}