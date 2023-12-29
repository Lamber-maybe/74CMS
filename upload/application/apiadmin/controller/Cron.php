<?php

namespace app\apiadmin\controller;

use think\Db;

class Cron extends \app\common\controller\Backend
{
    public function index()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $total = model('Cron')->count();
        $list = model('Cron')
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['rule'] = $this->resolutionRule($value);
            $list[$key]['runUrl'] = config('global_config.sitedomain') . config('global_config.sitedir') . 'v1_0/home/cron/outer?id=' . $value['id'];
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /**
     * 解析执行规则
     */
    protected function resolutionRule($info)
    {
        $return = '';
        $str_day = '';
        $str_hour = '';
        $str_minute = '';
        if ($info['weekday'] != -1) {
            switch ($info['weekday']) {
                case 1:
                    $str_day = '每周一';
                    break;
                case 2:
                    $str_day = '每周二';
                    break;
                case 3:
                    $str_day = '每周三';
                    break;
                case 4:
                    $str_day = '每周四';
                    break;
                case 5:
                    $str_day = '每周五';
                    break;
                case 6:
                    $str_day = '每周六';
                    break;
                default:
                    $str_day = '每周日';
                    break;
            }
        } elseif ($info['day'] != -1) {
            $str_day = '每月' . $info['day'] . '号';
        } else {
            $str_day = '每天';
        }
        if ($info['hour'] != -1) {
            $str_hour = $info['hour'] . '时';
        }
        if (false === stripos($info['minute'], '/')) {
            if ($str_hour == '') {
                $str_minute = '每小时第' . $info['minute'] . '分钟';
            } else {
                $str_minute = $info['minute'] . '分';
            }
        } else {
            /**
             * 【BUG】 定时任务每天每几分钟执行有问题
             * zdq 2022.08.11
             * 【删除】
             *  $str_hour = '';
             */
            $minute_arr = explode('/', $info['minute']);
            $str_minute = '每' . $minute_arr[1] . '分钟';
        }
        return $str_day . $str_hour . $str_minute;
    }

    public function add()
    {
        $input_data = [
            'name' => input('post.name/s', '', 'trim'),
            'action' => input('post.action/s', '', 'trim'),
            'weekday' => input('post.weekday/d', 0, 'intval'),
            'day' => input('post.day/d', 0, 'intval'),
            'hour' => input('post.hour/d', 0, 'intval'),
            'minute' => input('post.minute/s', '', 'trim'),
            'status' => input('post.status/d', 1, 'intval')
        ];
        $instance = new \app\common\lib\Cron();
        $input_data['next_execute_time'] = $instance->getNextExecuteTime(
            $input_data
        );
        $input_data['last_execute_time'] = 0;
        $input_data['is_sys'] = 0;

        Db::startTrans();
        try {
            if (
                false ===
                model('Cron')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('Cron')->getError());
            }

            $rule = $this->resolutionRule($input_data);
            $log_field = '添加' . $input_data['name'] . '计划任务，执行间隔:' . $rule . '；启用状态:' . ($input_data['status'] == 1 ? '启动' : '停用');
            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                2
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '保存成功');
    }

    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('Cron')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'action' => input('post.action/s', '', 'trim'),
                'weekday' => input('post.weekday/d', 0, 'intval'),
                'day' => input('post.day/d', 0, 'intval'),
                'hour' => input('post.hour/d', 0, 'intval'),
                'minute' => input('post.minute/s', '', 'trim'),
                'status' => input('post.status/d', 1, 'intval'),
                'refresh_quantity' => input('post.refresh_quantity/d',0,'intval')
            ];
            $instance = new \app\common\lib\Cron();
            $input_data['next_execute_time'] = $instance->getNextExecuteTime(
                $input_data
            );
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            Db::startTrans();
            try {
                $info = model('Cron')->find($id);
                if (!$info) {
                    $this->ajaxReturn(500, '要修改的计划任务不存在');
                }

                if (
                    false ===
                    model('Cron')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('Cron')->getError());
                }

                $log_field = '修改' . $input_data['name'] . '计划任务，';

                if ($input_data['name'] != $info['name']) {
                    $log_field .= '计划任务名称:' . $info['name'] . '->' . $input_data['name'] . '；';
                } else {
                    $log_field .= '计划任务名称:' . $info['name'];
                }

                if ($input_data['action'] != $info['action']) {
                    $log_field .= '任务脚本类名称:' . $info['action'] . '->' . $input_data['action'] . '；';
                }

                if ($input_data['weekday'] != $info['weekday'] ||
                    $input_data['day'] != $info['day'] ||
                    $input_data['hour'] != $info['hour'] ||
                    $input_data['minute'] != $info['minute']) {
                    $rule_old = $this->resolutionRule($info);
                    $rule_new = $this->resolutionRule($input_data);
                    $log_field .= '执行间隔:' . $rule_old . '->' . $rule_new . '；';
                }

                if ($input_data['status'] != $info['status']) {
                    $log_field .= '启用状态:' . ($info['status'] == 1 ? '启动' : '停用') . '->' . ($input_data['status'] == 1 ? '启动' : '停用') . '；';
                }

                // 日志
                $log_result = model('AdminLog')->writeLog(
                    $log_field,
                    $this->admininfo,
                    0,
                    3
                );
                if (false === $log_result) {
                    throw new \Exception(model('AdminLog')->getError());
                }

                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollBack();
                $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
            }

            $this->ajaxReturn(200, '保存成功');
        }
    }

    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id == 0) {
            $this->ajaxReturn(500, '请选择数据');
        }

        Db::startTrans();
        try {
            $info = model('Cron')
                ->where('id', $id)
                ->find();
            if (null === $info) {
                $this->ajaxReturn(500, '请选择数据');
            }

            $log_field = '删除' . $info['name'] . '计划任务';

            $info->delete();

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }

    public function setStatus()
    {
        $id = input('post.id/d', 0, 'intval');
        $status = input('post.status/d', 1, 'intval');
        if ($id == 0) {
            $this->ajaxReturn(500, '请选择数据');
        }

        Db::startTrans();
        try {
            $info = model('Cron')
                ->where('id', $id)
                ->find();
            if (null === $info) {
                $this->ajaxReturn(500, '要设置的计划任务不存在');
            }

            model('Cron')
                ->where('id', 'eq', $id)
                ->setField('status', $status);

            if ($status == 1) {
                $log_field = '启用' . $info['name'] . '计划任务';
            } else {
                $log_field = '停用' . $info['name'] . '计划任务';
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                3
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '设置失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '设置成功');
    }

    public function run()
    {
        $id = input('post.id/d', 0, 'intval');

        $info = model('Cron')
            ->where('id', $id)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '请选择数据');
        }

        $instance = new \app\common\lib\Cron();
        if (false === ($return_data = $instance->runOne($id))) {
            $this->ajaxReturn(500, $instance->getError());
        }

        model('AdminLog')->writeLog(
            '手动执行' . $info['name'] . '计划任务，下次执行时间' . date('Y-m-d H:i', $return_data['next_execute_time']),
            $this->admininfo,
            0,
            1
        );

        $this->ajaxReturn(200, '执行成功', $return_data);
    }

    public function loglist()
    {
        $where = [];
        $cron_id = input('get.cron_id/d', 0, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($cron_id > 0) {
            $where['cron_id'] = $cron_id;
        }

        $list = model('CronLog')
            ->where($where)
            ->order('id desc')
            ->paginate($pagesize)
            ->toArrays();
        if (!empty($list['items'])) {
            foreach ($list['items'] as &$item) {
                // 保留小数点后四位，防止超出
                $item['seconds'] = round($item['seconds'], 4);
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
}
