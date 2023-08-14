<?php
namespace app\apiadmin\controller;

use think\Db;

class Appeal extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $status = input('get.status/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($status != '') {
            $where['status'] = ['eq', intval($status)];
        }

        $total = model('MemberAppeal')
            ->where($where)
            ->count();
        $list = model('MemberAppeal')
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
    public function handler()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        Db::startTrans();
        try {
            $list = model('MemberAppeal')
                ->where('id', 'in', $id)
                ->column('id,realname,mobile,description');

            $handler_result = model('MemberAppeal')->whereIn('id', $id)->setField('status', 1);
            if (false === $handler_result) {
                throw new \Exception(model('MemberAppeal')->getError());
            }

            $log_field = '处理账号申诉申请，';
            foreach ($list as $appeal) {
                $log_field .= '姓名:'.$appeal['realname'].'；申诉描述:'.$appeal['description'].'；联系手机:'.$appeal['mobile'].'，';
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                rtrim($log_field, '，'),
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
            $list = model('MemberAppeal')
                ->where('id', 'in', $id)
                ->column('id,realname,mobile,description');

            $del_result = model('MemberAppeal')->destroy($id);
            if (false === $del_result) {
                throw new \Exception(model('MemberAppeal')->getError());
            }

            $log_field = '删除账号申诉申请，';
            foreach ($list as $appeal) {
                $log_field .= '姓名:' . $appeal['realname'] . '；申诉描述:' . $appeal['description'] . '；联系手机:' . $appeal['mobile'] . '，';
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                rtrim($log_field, '，'),
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
