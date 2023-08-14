<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;
use app\common\model\b2bcrm\CrmCustomer;

class Points extends Backend
{
    /*
     * 会员积分记录
     * */
    public function pointsLog()
    {
        $company_uid = input('get.company_uid/d',0,'intval');
        $type = input('get.type/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        if ($company_uid <= 0)
        {
            $this->ajaxReturn(500, '请选择企业');
        }
        $where['uid'] = $company_uid;
        switch ($type) {
            case 1:
                $where['op'] = 1;
                break;
            case 2:
                $where['op'] = 2;
                break;
            default:
                break;
        }
        $total = model('MemberPointsLog')->where($where)->count();
        $return['list'] = model('MemberPointsLog')
            ->field('id,uid', true)
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /*
     * 简历点记录
     * */
    public function consumeLog(){
        $cc = new CrmCustomer();
        $id =  input('get.company_uid/d', 0, 'intval');
        $page = input('get.page/d', 1, 'intval');
        $pageSize = input('get.page_size/d', 10, 'intval');
        $res = $cc->getConsumeLog($id, 1, $page, $pageSize);
        $this->ajaxReturn(200, '', $res);
    }
}