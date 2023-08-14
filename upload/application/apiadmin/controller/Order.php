<?php

namespace app\apiadmin\controller;

use think\Db;

class Order extends \app\common\controller\Backend
{
    /**
     * 订单列表
     */
    public function index()
    {
        $utype = input('get.utype/d', 1, 'intval');
        if ($utype == 1) {
            $this->index_company();
        } else {
            $this->index_personal();
        }
    }
    /**
     * 企业订单列表
     */
    protected function index_company()
    {
        $where['a.utype'] = 1;
        $status = input('get.status/s', '', 'trim');
        $payment = input('get.payment/s', '', 'trim');
        $service_type = input('get.service_type/s', '', 'trim');
        $add_settr = input('get.add_settr/d', 0, 'intval');
        $pay_settr = input('get.pay_settr/d', 0, 'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $sort = input('get.sort/d', 0, 'intval');

        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['c.id'] = ['eq', intval($keyword)];
                    break;
                case 2:
                    $where['c.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 3:
                    $where['a.uid'] = ['eq', intval($keyword)];
                    break;
                case 4:
                    $where['m.mobile'] = ['like', '%' . $keyword . '%'];
                    break;
                case 5:
                    $where['a.oid'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    break;
            }
        }
        if ($status != '') {
            $where['a.status'] = intval($status);
        }
        if ($payment != '') {
            $where['a.payment'] = $payment;
        }
        if ($service_type != '') {
            $where['a.service_type'] = $service_type;
        }
        if ($add_settr > 0) {
            $where['a.addtime'] = ['egt', strtotime('-' . $add_settr . ' day')];
        }
        if ($pay_settr > 0) {
            $where['a.paytime'] = ['egt', strtotime('-' . $pay_settr . ' day')];
        }
        $order = 'a.addtime desc';
        if($sort>0){
            if($sort==1){
                $order = 'a.paytime desc';
            }
            if($sort==2){
                $order = 'a.status asc';
            }
        }
        $total = model('Order')
            ->alias('a')
            ->join(
                config('database.prefix') . 'member m',
                'a.uid=m.uid',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'company c',
                'a.uid=c.uid',
                'LEFT'
            )
            ->where($where)
            ->count();
        $list = model('Order')
            ->alias('a')
            ->join(
                config('database.prefix') . 'member m',
                'a.uid=m.uid',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'company c',
                'a.uid=c.uid',
                'LEFT'
            )
            ->field('a.*,m.mobile as member_mobile,c.companyname')
            ->where($where)
            ->order($order)
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $value['amount_detail'] = '';
            if (
                $value['service_amount_after_discount'] !=
                $value['service_amount']
            ) {
                $value['amount_detail'] .=
                    '折扣价' . $value['service_amount_after_discount'] . '元';
            }
            if ($value['deduct_amount'] > 0 && $value['deduct_points'] == 0) {
                $value['amount_detail'] =
                    ($value['amount_detail'] == ''
                        ? '原价' . $value['service_amount']
                        : $value['amount_detail']) .
                    ' - 优惠券抵扣' .
                    $value['deduct_amount'] .
                    '元';
            }
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 个人订单列表
     */
    protected function index_personal()
    {
        $where['a.utype'] = 2;
        $status = input('get.status/s', '', 'trim');
        $payment = input('get.payment/s', '', 'trim');
        $service_type = input('get.service_type/s', '', 'trim');
        $add_settr = input('get.add_settr/d', 0, 'intval');
        $pay_settr = input('get.pay_settr/d', 0, 'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $sort = input('get.sort/d', 0, 'intval');

        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['a.uid'] = ['eq', intval($keyword)];
                    break;
                case 2:
                    $where['m.mobile'] = ['like', '%' . $keyword . '%'];
                    break;
                case 3:
                    $where['a.oid'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    break;
            }
        }
        if ($status != '') {
            $where['a.status'] = intval($status);
        }
        if ($payment != '') {
            $where['a.payment'] = $payment;
        }
        if ($service_type != '') {
            $where['a.service_type'] = $service_type;
        }
        if ($add_settr > 0) {
            $where['a.addtime'] = ['egt', strtotime('-' . $add_settr . ' day')];
        }
        if ($pay_settr > 0) {
            $where['a.paytime'] = ['egt', strtotime('-' . $pay_settr . ' day')];
        }
        $order = 'a.addtime desc';
        if($sort>0){
            if($sort==1){
                $order = 'a.paytime desc';
            }
            if($sort==2){
                $order = 'a.status asc';
            }
        }
        $total = model('Order')
            ->alias('a')
            ->join(
                config('database.prefix') . 'member m',
                'a.uid=m.uid',
                'LEFT'
            )
            ->where($where)
            ->count();
        $list = model('Order')
            ->alias('a')
            ->join(
                config('database.prefix') . 'member m',
                'a.uid=m.uid',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'resume r',
                'a.uid=r.uid',
                'LEFT'
            )
            ->field('a.*,m.mobile as member_mobile,r.fullname')
            ->where($where)
            ->order($order)
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $value['amount_detail'] = '';
            if (
                $value['service_amount_after_discount'] !=
                $value['service_amount']
            ) {
                $value['amount_detail'] .=
                    '折扣价' . $value['service_amount_after_discount'] . '元';
            }
            if ($value['deduct_amount'] > 0 && $value['deduct_points'] > 0) {
                $value['amount_detail'] =
                    ($value['amount_detail'] == ''
                        ? '原价' . $value['service_amount']
                        : $value['amount_detail']) .
                    ' - ' .
                    config('global_config.points_byname') .
                    '抵扣' .
                    $value['deduct_amount'] .
                    '元';
            }
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 确认收款
     */
    public function confirm()
    {
        $id = input('post.id/d', 0, 'intval');
        $order_detail = model('Order')->find($id);
        if ($order_detail === null) {
            $this->ajaxReturn(500, '没有找到订单信息');
        }
        if ($order_detail['status'] != 0) {
            $this->ajaxReturn(500, '该订单不是待支付状态');
        }
        $note = input('post.note/s', '', 'trim');

        Db::startTrans();
        try {
            model('Order')->orderPaid(
                $order_detail['oid'],
                'backend',
                time(),
                $note,
                $this->admininfo->id
            );

            $uid = $order_detail['uid'];
            $member_name = model('Member')->getMemberNickNameByUid($uid, '', false);
            if ($order_detail['utype'] === 1) {
                $comId = model('Company')->where('uid', $uid)->value('id');
                $log_field = '后台设置{' . $member_name . '}(企业ID:' . $comId . ')';
            } else {
                $log_field = '后台设置{' . $member_name . '}(会员ID:' . $uid . ')';
            }

            $log_field .= '订单确认付款，业务开通成功，订单号:'
                . $order_detail['oid']
                . '；服务内容:'
                . $order_detail['service_name']
                . '；订单金额 :￥'
                . $order_detail['amount'];

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                5
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '订单确认收款失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '订单确认收款成功');
    }
    /**
     * 取消订单
     */
    public function cancel()
    {
        $id = input('post.id/d', 0, 'intval');
        $order_detail = model('Order')->find($id);
        if ($order_detail === null) {
            $this->ajaxReturn(500, '没有找到订单信息');
        }
        if ($order_detail['status'] != 0) {
            $this->ajaxReturn(500, '该订单不是待支付状态');
        }

        Db::startTrans();
        try {
            model('Order')->orderClose($order_detail['id'], $order_detail['uid']);

            $uid = $order_detail['uid'];
            $member_name = model('Member')->getMemberNickNameByUid($uid, '', false);
            if ($order_detail['utype'] === 1) {
                $comId = model('Company')->where('uid', $uid)->value('id');
                $log_field = '后台设置{' . $member_name . '}(企业ID:' . $comId . ')';
            } else {
                $log_field = '后台设置{' . $member_name . '}(会员ID:' . $uid . ')';
            }

            $log_field .= '订单关闭，订单号:'
                . $order_detail['oid'];

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                5
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '关闭订单失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '关闭订单成功');
    }
    public function detail(){
        $id = input('get.id/d', 0, 'intval');
        $order = model('Order')->find($id);
        if ($order === null) {
            $this->ajaxReturn(500, '没有找到订单信息');
        }
        $order['amount_detail'] = '';
        if (
            $order['service_amount_after_discount'] !=
            $order['service_amount']
        ) {
            $order['amount_detail'] .=
                '折扣价' . $order['service_amount_after_discount'] . '元';
        }
        if ($order['deduct_amount'] > 0 && $order['deduct_points'] == 0) {
            $order['amount_detail'] =
                ($order['amount_detail'] == ''
                    ? '原价' . $order['service_amount']
                    : $order['amount_detail']) .
                ' - 优惠券抵扣' .
                $order['deduct_amount'] .
                '元';
        }
        $this->ajaxReturn(200, '获取数据成功',$order);
    }
}
