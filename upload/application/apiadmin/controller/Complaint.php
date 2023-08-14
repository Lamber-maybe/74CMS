<?php
namespace app\apiadmin\controller;
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
            ->join(config('database.prefix').'member b','a.uid=b.uid','LEFT')
            ->join(config('database.prefix').'customer_service c','a.cs_id=c.id','LEFT')
            ->where($where)
            ->where('b.uid','not null')
            ->where('c.id','not null')
            ->count();
        $list = model('CustomerServiceComplaint')->alias('a')
        ->join(config('database.prefix').'member b','a.uid=b.uid','LEFT')
        ->join(config('database.prefix').'customer_service c','a.cs_id=c.id','LEFT')
            ->field('a.*,b.mobile,c.name')
            ->where($where)
            ->where('b.uid','not null')
            ->where('c.id','not null')
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
        $status = input('post.status/d',0,'intval');
        model('CustomerServiceComplaint')->whereIn('id',$id)->setField('status',$status);
        model('AdminLog')->record(
            '处理投诉客服信息。投诉客服信息ID【' .
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
        model('CustomerServiceComplaint')->destroy($id);
        model('AdminLog')->record(
            '删除投诉客服信息。投诉客服信息ID【' .
                implode(',', $id) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
