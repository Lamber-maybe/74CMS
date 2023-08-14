<?php
namespace app\apiadmin\controller;

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
        model('MemberAppeal')->whereIn('id',$id)->setField('status',1);
        model('AdminLog')->record(
            '处理账号申诉。申诉ID【' .
                implode(',', $id) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '处理成功');
    }
    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('MemberAppeal')->destroy($id);
        model('AdminLog')->record(
            '删除账号申诉。申诉ID【' .
                implode(',', $id) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
