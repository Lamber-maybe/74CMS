<?php

namespace app\apiadmin\controller;

use think\Db;

class Market extends \app\common\controller\Backend
{
    public function tplList()
    {
        $list = model('MarketTpl')
            ->order('id asc')
            ->select();
        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function tplAdd()
    {
        $input_data = input('post.');
        if (
            false ===
            model('MarketTpl')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('MarketTpl')->getError());
        }
        $this->ajaxReturn(200, '保存成功');
    }

    public function tplEdit()
    {
        $input_data = input('post.');
        $id = intval($input_data['id']);
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        if (
            false ===
            model('MarketTpl')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id])
        ) {
            $this->ajaxReturn(500, model('MarketTpl')->getError());
        }
        $this->ajaxReturn(200, '保存成功');
    }

    public function tplDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('MarketTpl')
            ->where('id', 'eq', $id)
            ->find();
        model('MarketTpl')
            ->where('id', $id)
            ->delete();
        $this->ajaxReturn(200, '删除成功');
    }

    public function taskList()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $total = model('MarketTask')->count();
        $list = model('MarketTask')
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function taskAdd()
    {
        $input_data = [
            'title' => input('post.title/s', '', 'trim'),
            'content' => input('post.content/s', '', 'trim'),
            'send_type' => input('post.send_type/a', []),
            'target' => input('post.target/s', '', 'trim'),
            'condition' => input('post.condition/a', []),
            'total' => 0,
            'success' => 0,
            'status' => 0,
            'addtime' => time()
        ];
        $send_type = $input_data['send_type'];
        $input_data['send_type'] = implode(',', $send_type);
        $input_data['total'] = model('MarketTask')->countTotal(
            $input_data['target'],
            $input_data['condition']
        );
        $condition = $input_data['condition'];
        $input_data['condition'] = json_encode(
            $condition,
            JSON_UNESCAPED_UNICODE
        );

        try {
            Db::startTrans();

            switch ($input_data['target']) {
                case 'all':
                    // 按筛选条件群发(不限)
                    $log_field = '消息群发-按筛选条件群发，群发对象:不限；';
                    break;
                case 'resume':
                    // 按筛选条件群发(个人简历)
                    $log_field = '消息群发-按筛选条件群发，群发对象:个人简历；';
                    break;
                case 'company':
                    // 按筛选条件群发(企业信息)
                    $log_field = '消息群发-按筛选条件群发，群发对象:企业信息；';
                    break;
                case 'job':
                    // 按筛选条件群发(企业职位)
                    $log_field = '消息群发-按筛选条件群发，群发对象:企业职位；';
                    break;

                case 'uid':
                    $member_info = model('Member')
                        ->field('utype,mobile')
                        ->where('uid', $condition['uid'])
                        ->find();
                    // 指定会员发送
                    $utype = $member_info['utype'] == 1 ? '企业' : '个人';
                    $log_field = '消息群发-按指定会员发送，会员:' . $member_info['mobile'] . '(' . $utype . '会员,UID:' . $condition['uid'] . ')，';
                    break;

                case 'remind':
                    // 休眠用户提醒
                    $utype = $condition['utype'] == 1 ? '企业' : '个人';
                    $log_field = '消息群发-休眠用户提醒，会员类型:' . $utype . '；';
                    break;

                case 'setmeal':
                    // 企业过期套餐提醒
                    $log_field = '消息群发-企业过期套餐提醒，';
                    break;

                default:
                    $this->ajaxReturn(500, '错误的消息群发类型');
                    break;
            }

            if (
                false ===
                model('MarketTask')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('MarketTask')->getError());
            }

            model('MarketTask')->recordQueue(model('MarketTask')->id);

            foreach ($send_type as &$type) {
                switch ($type) {
                    case 'message':
                        $type = '站内信';
                        break;
                    case 'email':
                        $type = 'Email';
                        break;
                    default:
                        break;
                }
            }
            $send_type_str = implode(',', $send_type);
            $log_field .= '任务标题:' . $input_data['title'] . '；发送渠道:' . $send_type_str . '；消息内容:' . $input_data['content'];
            /**
             * 日志
             */
            $log_result = model('AdminLog')->writeLog(
                $log_field . '；已生成任务',
                $this->admininfo,
                0,
                1
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '添加失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '添加成功');
    }

    public function taskRun()
    {
        $id = input('post.id/d', 0, 'intval');
        $current_page = input('post.page/d', 1, 'intval');
        $pagesize = input('post.pagesize/d', 10, 'intval');
        $info = model('MarketTask')->find($id);
        if (empty($info)) {
            $this->ajaxReturn(500, '要执行的发送任务不存在');
        }
        $total = model('MarketQueue')
            ->where('task_id', $id)
            ->count();
        $list = model('MarketQueue')
            ->where('task_id', $id)
            ->order('id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        if (empty($list)) {
            if ($current_page == 1) {
                $this->ajaxReturn(500, '没有找到符合条件的任务');
            } else {
                model('MarketQueue')
                    ->where('task_id', 'eq', $id)
                    ->delete();

                switch ($info['target']) {
                    case 'all':
                        // 按筛选条件群发(不限)
                        $utype = '全部';
                        break;
                    case 'resume':
                        // 按筛选条件群发(个人简历)
                        $utype = '个人';
                        break;
                    case 'company':
                        // 按筛选条件群发(企业信息)
                        $utype = '企业';
                        break;
                    case 'job':
                        // 按筛选条件群发(企业职位)
                        $utype = '企业';
                        break;

                    case 'uid':
                        $condition = json_decode($info['condition'], true);
                        $member_info = model('Member')
                            ->field('utype,mobile')
                            ->where('uid', $condition['uid'])
                            ->find();
                        // 指定会员发送
                        $utype = $member_info['utype'] == 1 ? '企业' : '个人';
                        break;

                    case 'remind':
                        // 休眠用户提醒
                        $condition = json_decode($info['condition'], true);
                        $utype = $condition['utype'] == 1 ? '企业' : '个人';
                        break;

                    case 'setmeal':
                        // 企业过期套餐提醒
                        $utype = '企业';
                        break;

                    default:
                        $this->ajaxReturn(500, '错误的消息群发类型');
                        break;
                }

                $send_type = explode(',', $info['send_type']);
                foreach ($send_type as &$type) {
                    switch ($type) {
                        case 'message':
                            $type = '站内信';
                            break;
                        case 'email':
                            $type = 'Email';
                            break;
                        default:
                            break;
                    }
                }
                $send_type_str = implode(',', $send_type);

                $log_field = '消息群发-发送任务列表执行发送任务，任务名称:'
                    . $info['title']
                    . '；生成时间:'
                    . date('Y-m-d H:i:s', $info['addtime'])
                    . '；发送对象:' . $utype
                    . '会员(' . $info['total']
                    . ')；发送渠道:' . $send_type_str
                    . '；执行状态:执行完成(成功' . $info['success'] . ',失败' . ($info['total'] - $info['success']) . ')';
                /**
                 * 日志
                 */
                model('AdminLog')->writeLog(
                    $log_field,
                    $this->admininfo,
                    0,
                    1
                );

                $this->ajaxReturn(200, '任务执行完成', ['continue' => 0]);
            }
        }
        $count = 0;
        $mail_to_arr = $sms_to_arr = $message_to_arr = [];
        foreach ($list as $key => $value) {
            $value['message'] == 1 && ($message_to_arr[] = $value['uid']);
            if ($value['mobile']) {
                $sms_to_arr[] = $value['mobile'];
            }
            if ($value['email']) {
                $mail_to_arr[] = $value['email'];
            }
            $count++;
        }
        if (!empty($message_to_arr)) {
            $message_to_arr = array_unique($message_to_arr);
            model('Message')->sendMessage(
                $message_to_arr,
                $info['content'],
                \app\common\model\Message::TYPE_NOTICE
            );
        }
        if (!empty($sms_to_arr)) {
            $sms_to_arr = array_unique($sms_to_arr);
            $instance = new \app\common\lib\sms\qscms();
            $instance->sendDirect($sms_to_arr, $info['content']);
        }
        if (!empty($mail_to_arr)) {
            $mail_to_arr = array_unique($mail_to_arr);
            $instance = new \app\common\lib\Mail();
            $instance->send($mail_to_arr, $info['title'], $info['content']);
        }
        if ($count > 0) {
            $info->success = $info->success + $count;
            $info->status = 1;
            $info->save();

            switch ($info['target']) {
                case 'all':
                    // 按筛选条件群发(不限)
                    $utype = '全部';
                    break;
                case 'resume':
                    // 按筛选条件群发(个人简历)
                    $utype = '个人';
                    break;
                case 'company':
                    // 按筛选条件群发(企业信息)
                    $utype = '企业';
                    break;
                case 'job':
                    // 按筛选条件群发(企业职位)
                    $utype = '企业';
                    break;

                case 'uid':
                    $condition = json_decode($info['condition'], true);
                    $member_info = model('Member')
                        ->field('utype,mobile')
                        ->where('uid', $condition['uid'])
                        ->find();
                    // 指定会员发送
                    $utype = $member_info['utype'] == 1 ? '企业' : '个人';
                    break;

                case 'remind':
                    // 休眠用户提醒
                    $condition = json_decode($info['condition'], true);
                    $utype = $condition['utype'] == 1 ? '企业' : '个人';
                    break;

                case 'setmeal':
                    // 企业过期套餐提醒
                    $utype = '企业';
                    break;

                default:
                    $this->ajaxReturn(500, '错误的消息群发类型');
                    break;
            }

            $send_type = explode(',', $info['send_type']);
            foreach ($send_type as &$type) {
                switch ($type) {
                    case 'message':
                        $type = '站内信';
                        break;
                    case 'email':
                        $type = 'Email';
                        break;
                    default:
                        break;
                }
            }
            $send_type_str = implode(',', $send_type);

            $log_field = '消息群发-发送任务列表执行发送任务，任务名称:'
                . $info['title']
                . '；生成时间:'
                . date('Y-m-d H:i:s', $info['addtime'])
                . '；发送对象:' . $utype
                . '会员(' . $info['total']
                . ')；发送渠道:' . $send_type_str
                . '；执行状态:开始执行';
            /**
             * 日志
             */
            model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                1
            );

            $this->ajaxReturn(200, '执行任务成功', [
                'continue' => 1,
                'total' => $total,
                'success' => $count
            ]);
        } else {
            model('MarketQueue')
                ->where('task_id', 'eq', $id)
                ->delete();

            switch ($info['target']) {
                case 'all':
                    // 按筛选条件群发(不限)
                    $utype = '全部';
                    break;
                case 'resume':
                    // 按筛选条件群发(个人简历)
                    $utype = '个人';
                    break;
                case 'company':
                    // 按筛选条件群发(企业信息)
                    $utype = '企业';
                    break;
                case 'job':
                    // 按筛选条件群发(企业职位)
                    $utype = '企业';
                    break;

                case 'uid':
                    $condition = json_decode($info['condition'], true);
                    $member_info = model('Member')
                        ->field('utype,mobile')
                        ->where('uid', $condition['uid'])
                        ->find();
                    // 指定会员发送
                    $utype = $member_info['utype'] == 1 ? '企业' : '个人';
                    break;

                case 'remind':
                    // 休眠用户提醒
                    $condition = json_decode($info['condition'], true);
                    $utype = $condition['utype'] == 1 ? '企业' : '个人';
                    break;

                case 'setmeal':
                    // 企业过期套餐提醒
                    $utype = '企业';
                    break;

                default:
                    $this->ajaxReturn(500, '错误的消息群发类型');
                    break;
            }

            $send_type = explode(',', $info['send_type']);
            foreach ($send_type as &$type) {
                switch ($type) {
                    case 'message':
                        $type = '站内信';
                        break;
                    case 'email':
                        $type = 'Email';
                        break;
                    default:
                        break;
                }
            }
            $send_type_str = implode(',', $send_type);

            $log_field = '消息群发-发送任务列表执行发送任务，任务名称:'
                . $info['title']
                . '；生成时间:'
                . date('Y-m-d H:i:s', $info['addtime'])
                . '；发送对象:' . $utype
                . '会员(' . $info['total']
                . ')；发送渠道:' . $send_type_str
                . '；执行状态:执行完成(成功' . $info['success'] . ',失败' . ($info['total'] - $info['success']) . ')';
            /**
             * 日志
             */
            model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                1
            );

            $this->ajaxReturn(200, '任务执行完成', ['continue' => 0]);
        }
    }

    public function taskDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        try {
            $info = model('MarketTask')
                ->where('id', 'eq', $id)
                ->find();
            if (empty($info)) {
                $this->ajaxReturn(500, '要删除的发送任务不存在');
            }

            Db::startTrans();

            $task_del = model('MarketTask')
                ->where('id', $id)
                ->delete();
            if (false === $task_del) {
                throw new \Exception(model('MarketTask')->getError());
            }

            $queue_del = model('MarketQueue')
                ->where('task_id', $id)
                ->delete();
            if (false === $queue_del) {
                throw new \Exception(model('MarketQueue')->getError());
            }

            switch ($info['target']) {
                case 'all':
                    // 按筛选条件群发(不限)
                    $utype = '全部';
                    break;
                case 'resume':
                    // 按筛选条件群发(个人简历)
                    $utype = '个人';
                    break;
                case 'company':
                    // 按筛选条件群发(企业信息)
                    $utype = '企业';
                    break;
                case 'job':
                    // 按筛选条件群发(企业职位)
                    $utype = '企业';
                    break;

                case 'uid':
                    $condition = json_decode($info['condition'], true);
                    $member_info = model('Member')
                        ->field('utype,mobile')
                        ->where('uid', $condition['uid'])
                        ->find();
                    // 指定会员发送
                    $utype = $member_info['utype'] == 1 ? '企业' : '个人';
                    break;

                case 'remind':
                    // 休眠用户提醒
                    $condition = json_decode($info['condition'], true);
                    $utype = $condition['utype'] == 1 ? '企业' : '个人';
                    break;

                case 'setmeal':
                    // 企业过期套餐提醒
                    $utype = '企业';
                    break;

                default:
                    $this->ajaxReturn(500, '错误的消息群发类型');
                    break;
            }

            $send_type = explode(',', $info['send_type']);
            foreach ($send_type as &$type) {
                switch ($type) {
                    case 'message':
                        $type = '站内信';
                        break;
                    case 'email':
                        $type = 'Email';
                        break;
                    default:
                        break;
                }
            }
            $send_type_str = implode(',', $send_type);

            $log_field = '消息群发-删除发送任务，任务名称:' . $info['title'] . '；生成时间:' . date('Y-m-d H:i:s', $info['addtime']) . '；发送对象:' . $utype . '会员(' . $info['total'] . ')；发送渠道:' . $send_type_str;
            if ($info['status'] === 1) {
                $log_field .= '；执行状态:已执行(成功' . $info['success'] . ',失败' . ($info['total'] - $info['success']) . ')';
            } else {
                $log_field .= '；执行状态:未执行';
            }
            /**
             * 日志
             */
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }

    public function searchMember()
    {
        $keyword = input('get.keyword/s', '', 'trim');
        $list = [];
        if ($keyword != '') {
            $datalist = model('Member')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'company b',
                    'a.uid=b.uid',
                    'LEFT'
                )
                ->where('a.uid', 'eq', $keyword)
                ->whereOr('a.mobile', 'like', '%' . $keyword . '%')
                ->whereOr('b.companyname', 'like', '%' . $keyword . '%')
                ->field('a.uid,a.mobile,a.utype,b.companyname')
                ->select();
            foreach ($datalist as $key => $value) {
                $arr['uid'] = $value['uid'];
                $arr['mobile'] = $value['mobile'];
                $arr['utype'] = $value['utype'] == 1 ? '企业' : '个人';
                $arr['companyname'] =
                    $value['utype'] == 1 ? $value['companyname'] : '';
                $list[] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }
}
