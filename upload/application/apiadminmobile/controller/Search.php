<?php

namespace app\apiadminmobile\controller;

class Search extends \app\common\controller\Backend
{
    public function company(){
        $where = [];
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['e.mobile'] = $keyword;
                    break;
                case 2:
                    $where['a.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    $where['a.id'] = 0;
                    break;
            }
        }else{
            $where['a.id'] = 0;
        }
        $total = model('Company')->alias('a')->join(config('database.prefix').'member e','a.uid=e.uid','LEFT')->where($where)->count();
        $list = model('Company')
            ->alias('a')
            ->join(config('database.prefix').'member_setmeal b','a.uid=b.uid','LEFT')
            ->join(config('database.prefix').'setmeal c','b.setmeal_id=c.id','LEFT')
            ->join(config('database.prefix').'member_points d','a.uid=d.uid','LEFT')
            ->join(config('database.prefix').'member e','a.uid=e.uid','LEFT')
            ->field('a.id,a.uid,a.companyname,c.name as setmeal_name,d.points,e.mobile')
            ->where($where)
            ->order('a.uid desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function job(){
        $where = [];
        $company_id = input('get.company_id/d', 0, 'intval');
        if ($company_id>0) {
            $where['company_id'] = $company_id;
        }else{
            $this->ajaxReturn(500,'请选择企业');
        }
        $list = model('Job')->field('id,jobname,audit,is_display')->where('audit',1)->where('is_display',1)->where($where)->order('id desc')->select();
        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function resume(){
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        if ($keyword != '') {
            $list = model('Resume')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'member b',
                    'a.uid=b.uid',
                    'LEFT'
                )
                ->where('a.id', 'eq', $keyword)
                ->whereOr('a.fullname', 'like', '%' . $keyword . '%')
                ->whereOr('b.mobile', 'like', '%' . $keyword . '%')
                ->order('a.uid desc')
                ->page($current_page . ',' . $pagesize)
                ->column('a.id,a.uid,a.fullname,b.mobile');
            $list = array_values($list);
        } else {
            $list = [];
        }

        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
