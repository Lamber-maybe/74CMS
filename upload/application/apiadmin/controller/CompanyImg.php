<?php

namespace app\apiadmin\controller;

class CompanyImg extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $audit = input('get.audit/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        if ($audit != '') {
            $where['a.audit'] = ['eq', $audit];
        }
        $total = model('CompanyImg')->alias('a')
            ->where($where)
            ->count();
        $list = model('CompanyImg')->alias('a')
            ->join(config('database.prefix').'company b', 'a.comid=b.id')
            ->join(config('database.prefix').'company_contact c', 'b.id=c.comid')
            ->where($where)
            ->order('a.audit asc,a.id desc')
            ->page($current_page . ',' . $pagesize)
            ->field('a.*,b.companyname,c.mobile')
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
            $value['link_url'] = url('index/company/show',['id'=>$value['comid']]);
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
        $id = input('post.id/a',[]);
        $audit = input('post.audit/d', 0, 'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('CompanyImg')
            ->where('id','in',$id)
            ->setField('audit',$audit);
        
        //完成上传企业风采任务
        if ($audit == 1) {
            $list = model('CompanyImg')
                ->where('id','in',$id)
                ->select();
            foreach ($list as $key => $value) {
                model('Task')->doTask($value['uid'], 1, 'upload_img');
            }
        }
        
        model('AdminLog')->record(
            '将企业风采认证状态变更为【' .
                model('CompanyImg')->map_audit[$audit] .
                '】。企业风采ID【' .
                implode(",",$id) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '审核成功');
    }
    public function delete()
    {
        $id = input('post.id/a',[]);
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('CompanyImg')
            ->where('id','in', $id)
            ->delete();
        model('AdminLog')->record(
            '删除企业风采。企业风采ID【' . implode(",",$id). '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
