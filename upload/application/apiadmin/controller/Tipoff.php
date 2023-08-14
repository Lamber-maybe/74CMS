<?php
namespace app\apiadmin\controller;
class Tipoff extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $status = input('get.status/s', '', 'trim');
        $reason = input('get.reason/s', '', 'trim');
        $type = input('get.type/d', 1, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        
        if ($status != '') {
            $where['a.status'] = ['eq', intval($status)];
        }
        if ($reason != '') {
            $where['a.reason'] = ['eq', intval($reason)];
        }
        if ($type==2) {
            $where['a.type'] = 2;
        }else{
            $where['a.type'] = 1;
        }
        $join_tablename = 'job';
        $field = 'a.*,b.jobname,c.mobile';
        if($type==2){
            $join_tablename = 'resume';
            $field = 'a.*,b.fullname,c.mobile';
        }

        $total = model('Tipoff')->alias('a')
            ->join(config('database.prefix').$join_tablename.' b','a.target_id=b.id','LEFT')
            ->join(config('database.prefix').'member c','a.uid=c.uid','LEFT')
            ->where($where)
            ->where('b.id','not null')
            ->where('c.uid','not null')
            ->count();
        $list = model('Tipoff')->alias('a')
            ->join(config('database.prefix').$join_tablename.' b','a.target_id=b.id','LEFT')
            ->join(config('database.prefix').'member c','a.uid=c.uid','LEFT')
            ->field($field)
            ->where($where)
            ->where('b.id','not null')
            ->where('c.uid','not null')
            ->order('a.id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            if($value['type']==1){
                $value['target'] = $value['jobname'];
                $value['link'] = config('global_config.sitedomain').url('index/job/show',['id'=>$value['target_id']]);
                $value['reason_cn'] = model('Tipoff')->map_type_job[$value['reason']];
            }else{
                $value['target'] = $value['fullname'];
                $value['link'] = config('global_config.sitedomain').url('index/resume/show',['id'=>$value['target_id']]);
                $value['reason_cn'] = model('Tipoff')->map_type_resume[$value['reason']];
            }
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
        model('Tipoff')->whereIn('id',$id)->setField('status',$status);
        model('AdminLog')->record(
            '处理举报信息。举报信息ID【' .
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
        model('Tipoff')->destroy($id);
        model('AdminLog')->record(
            '删除举报信息。举报信息ID【' .
                implode(',', $id) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
