<?php
namespace app\apiadmin\controller;

use think\Db;

class PromotionJob extends \app\common\controller\Backend
{
    public function index()
    {
        $where['a.utype'] = 1;
        $type = input('get.type/s', '', 'trim');
        $settr = input('get.settr/d', 0, 'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $sort = input('get.sort/d', 0, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['b.jobname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['c.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 3:
                    $where['b.id'] = ['eq', $keyword];
                    break;
                case 4:
                    $where['b.company_id'] = ['eq', $keyword];
                    break;
                case 5:
                    $where['b.uid'] = ['eq', $keyword];
                    break;
                default:
                    break;
            }
        }
        if ($type != '') {
            $where['a.type'] = ['eq', $type];
        }
        if ($settr) {
            $where['a.deadline'] = ['lt', strtotime('+' . $settr . ' day')];
        }
        $order = 'a.addtime desc';
        if ($sort > 0) {
            $order = 'a.deadline asc';
        }

        $total = model('ServiceQueue')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.pid=b.id', 'LEFT')
            ->join(
                config('database.prefix') . 'company c',
                'b.company_id=c.id',
                'LEFT'
            )
            ->where('b.audit',1)
            ->where('b.is_display',1)
            ->where('b.id', 'not null')
            ->where($where)
            ->count();
        $list = model('ServiceQueue')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.pid=b.id', 'LEFT')
            ->join(
                config('database.prefix') . 'company c',
                'b.company_id=c.id',
                'LEFT'
            )
            ->field('a.*,b.uid,b.jobname,c.companyname')
            ->where('b.audit',1)
            ->where('b.is_display',1)
            ->where($where)
            ->where('b.id', 'not null')
            ->order($order)
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['days'] = ($value['deadline'] - $value['addtime']) / 3600 / 24;
            $list[$key]['days'] = ceil($list[$key]['days']);
        }

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function searchCompany()
    {
        // 【优化】后台添加推广数据，数据较大时搜索慢 zch 2022/7/25
        $keyword = input('get.keyword/s', '', 'trim');
        if (!empty($keyword)) {
            // 企业推广时，暂停企业或者职位还在选择列表里 zch 2022.07.06
            $company_obj = model('Company')
                ->alias('c')
                ->join('jobSearchRtime j', 'j.company_id=c.id', 'left')
                ->where('j.id', 'not null');
            $company_obj->where(function ($qr) use ($keyword) {
                $qr->where(['c.id' => $keyword, 'c.is_display' => 1]);
            });
            $company_obj->whereOr(function ($qr) use ($keyword) {
                $qr->where(['c.companyname' => ['like', '%' . $keyword . '%'], 'c.is_display' => 1]);
            });
            $list = $company_obj
                ->field('c.id,c.uid,c.companyname')
                ->group('c.id')
                ->select();
        }
        $return = [
            'items' => $list
        ];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function searchJob()
    {
        $company_id = input('get.company_id/d', 0, 'intval');
        // 【优化】后台添加推广，企业职位为暂停（关闭中）还显示 zch 2022/7/25
        if ($company_id > 0) {
            $list = model('jobSearchKey')
                ->where('company_id', 'eq', $company_id)
                ->column('id,uid,jobname');
        } else {
            $list = [];
        }
        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function add()
    {
        $input_data = [
            'pid' => input('post.pid/s', '', 'trim'),
            'days' => input('post.days/d', 0, 'intval'),
            'type' => input('post.type/s', '', 'trim')
        ];
        $rule = [
            'pid' => 'require',
            'days' => 'require',
            'type' => 'require'
        ];
        $msg = [
            'pid.require' => '请选择职位',
            'days.require' => '请输入推广天数',
            'type.require' => '请选择推广类型'
        ];
        $validate = new \think\Validate($rule, $msg);
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $check_has = model('ServiceQueue')
            ->where('pid', 'eq', intval($input_data['pid']))
            ->where('type', $input_data['type'])
            ->find();
        if ($check_has !== null) {
            $this->ajaxReturn(500, '该职位已经在推广状态，不能重复推广');
        }
        $data['type'] = $input_data['type'];
        $data['utype'] = 1;
        $data['pid'] = $input_data['pid'];
        $data['addtime'] = time();
        $data['deadline'] = strtotime('+' . $input_data['days'] . ' day');

        Db::startTrans();
        try {
            if (
                false ===
                model('ServiceQueue')
                    ->allowField(true)
                    ->save($data)
            ) {
                throw new \Exception(model('ServiceQueue')->getError());
            }
            if ($data['type'] == 'jobstick') {
                model('Job')
                    ->where('id', 'eq', $data['pid'])
                    ->setField('stick', 1);
                model('JobSearchRtime')
                    ->where('id', 'eq', $data['pid'])
                    ->setField('stick', 1);
                model('JobSearchKey')
                    ->where('id', 'eq', $data['pid'])
                    ->setField('stick', 1);
            } elseif ($data['type'] == 'emergency') {
                model('Job')
                    ->where('id', 'eq', $data['pid'])
                    ->setField('emergency', 1);
                model('JobSearchRtime')
                    ->where('id', 'eq', $data['pid'])
                    ->setField('emergency', 1);
                model('JobSearchKey')
                    ->where('id', 'eq', $data['pid'])
                    ->setField('emergency', 1);
            }

            $job_info = model('Job')
                ->alias('j')
                ->join('company c', 'j.company_id=c.id', 'LEFT')
                ->field('j.id as jobid,j.jobname,c.id as comid,c.companyname')
                ->where('j.id', $input_data['pid'])
                ->find();

            $log_field = '添加推广，企业:{'
                . $job_info['companyname']
                . '}(企业ID:'
                . $job_info['comid']
                . ')；职位:'
                . $job_info['jobname']
                . '(ID:'
                . $job_info['jobid']
                . ')，推广天数:'
                . $input_data['days']
                . '；推广方案:'
                . model('ServiceQueue')->map_type[$data['type']];

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                5
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
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'days' => input('post.days/d', 0, 'intval')
        ];
        $rule = [
            'id' => 'require',
            'days' => 'require|>:0'
        ];
        $msg = [
            'id.require' => '请选择推广记录',
            'days.require' => '请输入延长推广天数',
            'days.gt' => '请输入大于0的延长推广天数'
        ];
        $validate = new \think\Validate($rule, $msg);
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }

        Db::startTrans();
        try {
            $info = model('ServiceQueue')
                ->where('id', $input_data['id'])
                ->find();

            $job_info = model('Job')
                ->alias('j')
                ->join('company c', 'j.company_id=c.id', 'LEFT')
                ->field('j.id as jobid,j.jobname,c.id as comid,c.companyname')
                ->where('j.id', $info['pid'])
                ->find();

            $old_deadline = $info->deadline;
            $info->deadline = strtotime(
                '+' . $input_data['days'] . ' day',
                $info->deadline
            );
            if (false === $info->save()) {
                throw new \Exception(model('ServiceQueue')->getError());
            }

            $log_field = '修改推广，企业:{'
                . $job_info['companyname']
                . '}(企业ID:'
                . $job_info['comid']
                . ')；职位:'
                . $job_info['jobname']
                . '(ID:'
                . $job_info['jobid']
                . ')，推广类型:'
                . model('ServiceQueue')->map_type[$info['type']]
                . '；原推广期限:'
                . date('Y-m-d H:i:s', $info['addtime'])
                . ' ~ '
                . date('Y-m-d H:i:s', $old_deadline)
                . '；延长推广天数: '
                . $input_data['days'] .
                '；延长至: '
                . date('Y-m-d H:i:s', $info->deadline);

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                5
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

    public function cancel()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        Db::startTrans();
        try {
            $info = model('ServiceQueue')
                ->where('id', 'eq', $id)
                ->find();

            $job_info = model('Job')
                ->alias('j')
                ->join('company c', 'j.company_id=c.id', 'LEFT')
                ->field('j.id as jobid,j.jobname,c.id as comid,c.companyname')
                ->where('j.id', $info['pid'])
                ->find();

            $days = ($info['deadline'] - $info['addtime']) / 3600 / 24;
            $days = ceil($days);

            $log_field = '取消推广状态，企业:{'
                . $job_info['companyname']
                . '}(企业ID:'
                . $job_info['comid']
                . ')；职位:'
                . $job_info['jobname']
                . '(ID:'
                . $job_info['jobid']
                . ')，推广类型:'
                . model('ServiceQueue')->map_type[$info['type']]
                . '；推广天数:'
                . $days
                . '天；开始时间:' . date('Y-m-d', $info['addtime']);

            if ($info['type'] == 'jobstick') {
                model('Job')
                    ->where('id', 'eq', $info['pid'])
                    ->setField('stick', 0);
                model('JobSearchRtime')
                    ->where('id', 'eq', $info['pid'])
                    ->setField('stick', 0);
                model('JobSearchKey')
                    ->where('id', 'eq', $info['pid'])
                    ->setField('stick', 0);
            } elseif ($info['type'] == 'emergency') {
                model('Job')
                    ->where('id', 'eq', $info['pid'])
                    ->setField('emergency', 0);
                model('JobSearchRtime')
                    ->where('id', 'eq', $info['pid'])
                    ->setField('emergency', 0);
                model('JobSearchKey')
                    ->where('id', 'eq', $info['pid'])
                    ->setField('emergency', 0);
            }

            $info->delete();

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                5
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '取消失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '取消成功');
    }
}
