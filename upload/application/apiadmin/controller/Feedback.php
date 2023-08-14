<?php
namespace app\apiadmin\controller;
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
            ->join(config('database.prefix').'member b','a.uid=b.uid','LEFT')
            ->where($where)
            ->where('b.uid','not null')
            ->count();
        $list = model('Feedback')->alias('a')
        ->join(config('database.prefix').'member b','a.uid=b.uid','LEFT')
            ->field('a.*,b.mobile')
            ->where($where)
            ->where('b.uid','not null')
            ->order('a.id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $value['type_cn'] = model('Feedback')->map_type[$value['type']];
            $value['content_short'] = cut_str($value['content'],50,0,'...');
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
        $status = input('post.status/d',0,'intval');
        model('Feedback')->whereIn('id',$id)->setField('status',$status);
        model('AdminLog')->record(
            '处理意见建议信息。意见建议信息ID【' .
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
        model('Feedback')->destroy($id);
        model('AdminLog')->record(
            '删除意见建议信息。意见建议信息ID【' .
                implode(',', $id) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
