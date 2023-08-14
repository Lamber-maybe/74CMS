<?php
namespace app\apiadmin\controller;

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
            $list[$key]['runUrl'] = config('global_config.sitedomain').config('global_config.sitedir').'v1_0/home/cron/outer?id='.$value['id'];
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
        if (
            false ===
            model('Cron')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('Cron')->getError());
        }
        model('AdminLog')->record(
            '添加计划任务。计划任务ID【' .
                model('Cron')->id .
                '】;计划任务名称【' .
                $input_data['name'] .
                '】',
            $this->admininfo
        );
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
                'status' => input('post.status/d', 1, 'intval')
            ];
            $instance = new \app\common\lib\Cron();
            $input_data['next_execute_time'] = $instance->getNextExecuteTime(
                $input_data
            );
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if (
                false ===
                model('Cron')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('Cron')->getError());
            }
            model('AdminLog')->record(
                '编辑计划任务。计划任务ID【' .
                    $id .
                    '】;计划任务名称【' .
                    $input_data['name'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id == 0) {
            $this->ajaxReturn(500, '请选择数据');
        }

        $info = model('Cron')
            ->where('id', $id)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info->delete();
        model('AdminLog')->record(
            '删除计划任务。计划任务ID【' .
                $id .
                '】;计划任务名称【' .
                $info['name'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function setStatus()
    {
        $id = input('post.id/d', 0, 'intval');
        $status = input('post.status/d', 1, 'intval');
        if ($id == 0) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('Cron')
            ->where('id', 'eq', $id)
            ->setField('status', $status);
        model('AdminLog')->record(
            '变更计划任务状态为' .
                ($status == 1 ? '可用' : '不可用') .
                '。计划任务ID【' .
                $id .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '设置成功');
    }
    public function run()
    {
        $id = input('post.id/d', 0, 'intval');
        $instance = new \app\common\lib\Cron();
        if (false === ($return_data = $instance->runOne($id))) {
            $this->ajaxReturn(500, $instance->getError());
        }
        model('AdminLog')->record(
            '执行计划任务。计划任务ID【' . $id . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '执行成功', $return_data);
    }
    public function loglist()
    {
        $where = [];
        $cron_id = input('get.cron_id/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($cron_id > 0) {
            $where['cron_id'] = $cron_id;
        }

        $total = model('CronLog')
            ->where($where)
            ->count();
        $list = model('CronLog')
            ->where($where)
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
}
