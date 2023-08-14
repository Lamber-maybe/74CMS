<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use Think\Db;

class ResumeImg extends Backend
{
    public function index()
    {
        $where = [];
        $audit = input('get.audit/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        /**
         * 【ID1000628】
         * 【优化】简历作品批量审核
         * zch - 2023.04.17
         */
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        if (!empty($keyword) && !empty($key_type)) {
            switch ($key_type) {
                case 1: // 简历名称
                    $where['b.fullname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2: // 简历ID
                    $where['a.rid'] = ['=', $keyword];
                    break;
                case 3: // 会员UID
                    $where['a.uid'] = ['=', $keyword];
                    break;
                default:
                    break;
            }
        }
        if ($audit != '') {
            $where['a.audit'] = ['eq', $audit];
        }
        $total = model('ResumeImg')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.uid=b.uid', 'left')
            ->where($where)
            ->count();
        $list = model('ResumeImg')
            ->alias('a')
            ->field('a.*,b.fullname,c.mobile')
            ->join(config('database.prefix') . 'resume b', 'a.uid=b.uid', 'left')
            ->join(config('database.prefix') . 'resume_contact c', 'a.uid=c.uid', 'left')
            ->where($where)
            ->order('a.audit asc,a.id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $img_id_arr = $img_src_data = [];
        foreach ($list as $key => $value) {
            $img_id_arr[] = $value['img'];
        }
        if (!empty($img_id_arr)) {
            $img_src_data = model('Uploadfile')->getFileUrlBatch($img_id_arr);
        }

        foreach ($list as $key => $value) {
            $value['img_src'] = isset($img_src_data[$value['img']])
                ? $img_src_data[$value['img']]
                : '';
            $value['link_url'] = url('index/resume/show', ['id' => $value['rid']]);
            $list[$key] = $value;
        }

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);

        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function setAudit()
    {
        $id = input('post.id/a', []);
        $audit = input('post.audit/d', 0, 'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择要审核的简历作品');
        }

        try {
            Db::startTrans();

            $list = model('ResumeImg')
                ->where('id', 'in', $id)
                ->select();
            if (null === $list) {
                throw new \Exception('没有要审核的简历作品');
            }

            $audit_result = model('ResumeImg')
                ->where('id', 'in', $id)
                ->setField('audit', $audit);
            if (false === $audit_result) {
                throw new \Exception(model('ResumeImg')->getError());
            }
            /**
             * 日志
             */
            $rIds = array_column($list, 'rid', 'rid');
            $resume_arr = model('Resume')->whereIn('id', $rIds)->column('id,fullname');
            $l_list = [];
            foreach ($resume_arr as $r_id => $fullname) {
                $l_list[] = '{' . $fullname . '}(简历ID:' . $r_id . ')';
            }
            $log_field = '批量审核'
                . implode('；', $l_list)
                . '上传的简历作品，审核结果:'
                . model('ResumeImg')->map_audit[$audit];
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                6
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '审核失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '审核成功');
    }

    public function delete()
    {
        $id = input('post.id/a', []);
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择要删除的简历作品');
        }

        try {
            Db::startTrans();

            $list = model('ResumeImg')
                ->where('id', 'in', $id)
                ->select();
            if (null === $list) {
                throw new \Exception('没有要删除的简历作品');
            }

            $del_result = model('ResumeImg')
                ->where('id', 'in', $id)
                ->delete();
            if (false === $del_result) {
                throw new \Exception(model('ResumeImg')->getError());
            }
            /**
             * 日志
             */
            $rIds = array_column($list, 'rid', 'rid');
            $resume_arr = model('Resume')->whereIn('id', $rIds)->column('id,fullname');
            $l_list = [];
            foreach ($resume_arr as $r_id => $fullname) {
                $l_list[] = '{' . $fullname . '}(简历ID:' . $r_id . ')';
            }
            $log_field = '删除'
                . implode('；', $l_list)
                . '上传的简历作品';
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
            $this->ajaxReturn(500, '审核失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }

    public function edit()
    {
        $id = input('post.id/d', 0, 'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择要修改的简历作品');
        }

        $input_data = [
            'img' => input('post.img/d', 0, 'intval'),
            'addtime' => time(),
            'audit' => 1
        ];

        try {
            $resumeImg = model('ResumeImg')
                ->find($id);
            if (null === $resumeImg) {
                $this->ajaxReturn(500, '要修改的简历作品不存在');
            }
            Db::startTrans();

            $edit_result = model('ResumeImg')
                ->allowField(true)
                ->isUpdate(true)
                ->save(
                    $input_data,
                    ['id' => $id]
                );
            if (false === $edit_result) {
                throw new \Exception(model('ResumeImg')->getError());
            }

            $fullname = model('Resume')->where('id', $resumeImg['rid'])->value('fullname');
            $log_result = model('AdminLog')->writeLog(
                '修改{' . $fullname . '}(简历ID:' . $resumeImg['rid'] . ')上传的简历作品',
                $this->admininfo,
                0,
                3
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            Db::commit();
            $this->ajaxReturn(200, '保存成功');
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }
    }
}
