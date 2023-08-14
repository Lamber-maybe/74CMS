<?php

namespace app\apiadmin\controller;

use think\Db;

class Complaint extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $status = input('get.status/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        if ($status != '') {
            $where['a.status'] = ['eq', intval($status)];
        }

        $total = model('CustomerServiceComplaint')->alias('a')
            ->join(config('database.prefix') . 'member b', 'a.uid=b.uid', 'LEFT')
            ->join(config('database.prefix') . 'crm_customer_service c', 'a.cs_id=c.id', 'LEFT')
            ->where($where)
            ->where('b.uid', 'not null')
            ->where('c.id', 'not null')
            ->count();
        $list = model('CustomerServiceComplaint')->alias('a')
            ->join(config('database.prefix') . 'member b', 'a.uid=b.uid', 'LEFT')
            ->join(config('database.prefix') . 'crm_customer_service c', 'a.cs_id=c.id', 'LEFT')
            ->join(config('database.prefix') . 'crm_customer_service cs', 'cs.id=a.cs_id', 'LEFT')
            ->join(config('database.prefix') . 'admin i', 'i.id=cs.admin_id', 'LEFT')
            ->field('a.*,b.mobile,c.name,i.username')
            ->where($where)
            ->where('b.uid', 'not null')
            ->where('c.id', 'not null')
            ->order('a.id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function handler()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $status = input('post.status/d', 0, 'intval');


        Db::startTrans();
        try {
            $list = model('CustomerServiceComplaint')
                ->where('id', 'in', $id)
                ->column('id,uid,cs_id,content,status');
            if (empty($list)) {
                $this->ajaxReturn(500, '没有要处理的数据');
            }

            $status_set = model('Feedback')->map_status[$status];

            $log_field = '处理投诉客服，';

            foreach ($list as $complaint) {
                $cs_info = model('b2bcrm.CrmCustomerService')->field('admin_id,name')->where('id', $complaint['cs_id'])->find();
                $log_field .= '被投诉客服:' . $cs_info['name'] . '(管理员ID:' . $cs_info['admin_id'] . ')；';
                $log_field .= '投诉内容:' . $complaint['content'] . '；';
                $member_mobile = model('Member')->where('uid', $complaint['uid'])->value('mobile');
                $log_field .= '投诉人:' . $member_mobile . '(UID:' . $complaint['uid'] . ')，';
                $status_original = model('CustomerServiceComplaint')->map_status[$complaint['status']];
            }

            if (count($list) === 1) {
                $log_field .= '处理状态:' . $status_original . '->' . $status_set;
            } else {
                $log_field .= '处理状态:' . $status_set;
            }

            $handler_result = model('CustomerServiceComplaint')->whereIn('id', $id)->setField('status', $status);
            if (false === $handler_result) {
                throw new \Exception(model('CustomerServiceComplaint')->getError());
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                1
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '处理失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '处理成功');
    }

    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        Db::startTrans();
        try {
            $list = model('CustomerServiceComplaint')
                ->where('id', 'in', $id)
                ->column('id,uid,cs_id,content,status');
            if (empty($list)) {
                $this->ajaxReturn(500, '没有要处理的数据');
            }

            $log_field = '删除投诉客服，';

            foreach ($list as $complaint) {
                $cs_info = model('b2bcrm.CrmCustomerService')->field('admin_id,name')->where('id', $complaint['cs_id'])->find();
                $log_field .= '被投诉客服:' . $cs_info['name'] . '(管理员ID:' . $cs_info['admin_id'] . ')；';
                $log_field .= '投诉内容:' . $complaint['content'] . '；';
                $member_mobile = model('Member')->where('uid', $complaint['uid'])->value('mobile');
                $log_field .= '投诉人:' . $member_mobile . '(UID:' . $complaint['uid'] . ')，';
            }

            $del_result = model('CustomerServiceComplaint')->destroy($id);
            if (false === $del_result) {
                throw new \Exception(model('CustomerServiceComplaint')->getError());
            }

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
}
