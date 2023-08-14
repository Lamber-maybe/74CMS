<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;

class CompanyOrder extends Backend
{
    /*
     * 我的订单
     * */
    public function orderList()
    {
        $company_uid = input('get.company_uid/d',0,'intval');
        if ($company_uid <= 0)
        {
            $this->ajaxReturn(500, '请选择企业');
        }
        $service_type = input('get.service_type/s', '', 'trim');
        $payment = input('get.payment/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');

        $where['uid'] = $company_uid;
        if (!empty($service_type)) {
            $where['service_type'] = $service_type;
        }
        if (!empty($payment)) {
            $where['payment'] = $payment;
        }
        $total = model('Order')->where($where)->count();
        $list = model('Order')
            ->field('id,oid,service_amount,addtime,paytime,service_type,payment,service_name')
            ->where($where)
            ->order('id desc')
            ->page($current_page, $pagesize)
            ->select();
        foreach ($list as $key => $value) {

            $list[$key]['payment_name'] = isset(model('Order')->map_payment[$value['payment']]) ? model('Order')->map_payment[$value['payment']] : '';
            unset($list[$key]['service_type'],$list[$key]['payment']);
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);

        $return['filter_type'] = [];
        $return['filter_status'] = [];
        $filter_type = model('Order')->map_service_type_company;
        foreach ($filter_type as $key => $value) {
            $return['filter_type'][] = ['value' => $key, 'label' => $value];
        }
        $filter_status = model('Order')->map_status;
        foreach ($filter_status as $key => $value) {
            $return['filter_status'][] = ['value' => $key, 'label' => $value];
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}