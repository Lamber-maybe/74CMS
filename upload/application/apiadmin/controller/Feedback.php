<?php

namespace app\apiadmin\controller;

use think\Db;

class Feedback extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $status = input('get.status/s', '', 'trim');
        $type = input('get.type/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        if ($status != '') {
            $where['a.status'] = ['eq', intval($status)];
        }
        if ($type != '') {
            $where['a.type'] = ['eq', intval($type)];
        }

        $total = model('Feedback')->alias('a')
            ->join(config('database.prefix') . 'member b', 'a.uid=b.uid', 'LEFT')
            ->where($where)
            ->where('b.uid', 'not null')
            ->count();
        $list = model('Feedback')->alias('a')
            ->join(config('database.prefix') . 'member b', 'a.uid=b.uid', 'LEFT')
            ->field('a.*,b.mobile')
            ->where($where)
            ->where('b.uid', 'not null')
            ->order('a.id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $value['type_cn'] = model('Feedback')->map_type[$value['type']];
            $value['content_short'] = cut_str($value['content'], 50, 0, '...');
            $list[$key] = $value;
        }

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
            $list = model('Feedback')
                ->where('id', 'in', $id)
                ->column('id,type,uid,content,status');
            if (empty($list)) {
                $this->ajaxReturn(500, '没有要处理的数据');
            }

            $status_set = model('Feedback')->map_status[$status];

            $log_field = '处理意见建议，';

            foreach ($list as $feedback) {
                $log_field .= '类型:' . model('Feedback')->map_type[$feedback['type']] . '；';
                $log_field .= '内容:' . $feedback['content'] . '；';
                $member_mobile = model('Member')->where('uid', $feedback['uid'])->value('mobile');
                $log_field .= '举报者:' . $member_mobile . '(UID:' . $feedback['uid'] . ')，';
                $status_original = model('Feedback')->map_status[$feedback['status']];
            }

            if (count($list) === 1) {
                $log_field .= '处理情况:' . $status_original . '->' . $status_set;
            } else {
                $log_field .= '处理情况:' . $status_set;
            }

            $handler_result = model('Feedback')->whereIn('id', $id)->setField('status', $status);
            if (false === $handler_result) {
                throw new \Exception(model('Feedback')->getError());
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
            $list = model('Feedback')
                ->where('id', 'in', $id)
                ->column('id,type,uid,content,status');
            if (empty($list)) {
                $this->ajaxReturn(500, '没有要处理的数据');
            }

            $log_field = '删除意见建议，';

            foreach ($list as $feedback) {
                $log_field .= '类型:' . model('Feedback')->map_type[$feedback['type']] . '；';
                $log_field .= '内容:' . $feedback['content'] . '；';
                $member_mobile = model('Member')->where('uid', $feedback['uid'])->value('mobile');
                $log_field .= '举报者:' . $member_mobile . '(UID:' . $feedback['uid'] . ')，';
            }

            $del_result = model('Feedback')->destroy($id);
            if (false === $del_result) {
                throw new \Exception(model('Feedback')->getError());
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
