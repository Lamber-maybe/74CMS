<?php

namespace app\apiadmin\controller;

class ResumeImg extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $audit = input('get.audit/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        if ($audit != '') {
            $where['audit'] = ['eq', $audit];
        }
        $total = model('ResumeImg')
            ->where($where)
            ->count();
        $list = model('ResumeImg')
            ->where($where)
            ->order('audit asc,id desc')
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
        $id = input('post.id/d', 0, 'intval');
        $audit = input('post.audit/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('ResumeImg')
            ->where(['id' => ['eq', $id]])
            ->setField('audit', $audit);
        model('AdminLog')->record(
            '将简历照片/作品认证状态变更为【' .
                model('ResumeImg')->map_audit[$audit] .
                '】。简历照片/作品ID【' .
                $id .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '审核成功');
    }
    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('ResumeImg')
            ->where('id', $id)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info->delete();
        model('AdminLog')->record(
            '删除简历照片/作品。简历照片/作品ID【' . $id . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
