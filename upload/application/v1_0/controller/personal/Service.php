<?php
/**
 * 会员服务
 */
namespace app\v1_0\controller\personal;

class Service extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(2);
        $this->interceptPersonalResume();
    }
    public function serviceList()
    {
        $type = input('get.type/s', '', 'trim');
        switch ($type) {
            case 'stick':
                $model = model('PersonalServiceStick');
                break;
            case 'tag':
                $model = model('PersonalServiceTag');
                break;
            default:
                $this->ajaxReturn(500, '请选择服务类型');
                break;
        }
        $list = $model
            ->field('is_display', true)
            ->where(['is_display' => 1])
            ->order('sort_id desc')
            ->select();
        $member_points = model('Member')->getMemberPoints($this->userinfo->uid);
        foreach ($list as $key => $value) {
            $value['deduct_type'] = $value['enable_points_deduct'];
            if ($value['enable_points_deduct'] == 2) {
                $need_points = ceil(
                    $value['deduct_max'] * config('global_config.payment_rate')
                );
                if ($need_points > $member_points) {
                    $value['enable_points_deduct'] = 0;
                    $value['enable_points_deduct_points'] = 0;
                    $value['enable_points_deduct_expense'] = 0;
                } else {
                    $value['enable_points_deduct'] = 1;
                    $value['enable_points_deduct_points'] = $need_points;
                    $value['enable_points_deduct_expense'] =
                        $value['deduct_max'];
                }
            } elseif ($value['enable_points_deduct'] == 1) {
                $need_points = ceil(
                    $value['expense'] * config('global_config.payment_rate')
                );
                if ($need_points > $member_points) {
                    $value['enable_points_deduct'] = 0;
                    $value['enable_points_deduct_points'] = 0;
                    $value['enable_points_deduct_expense'] = 0;
                } else {
                    $value['enable_points_deduct'] = 1;
                    $value['enable_points_deduct_points'] = $need_points;
                    $value['enable_points_deduct_expense'] = $value['expense'];
                }
            } else {
                $value['enable_points_deduct'] = 0;
                $value['enable_points_deduct_points'] = 0;
                $value['enable_points_deduct_expense'] = 0;
            }
            $value['after_deduct_expense'] =
            $value['expense'] == 0
            ? 0
            : $value['expense'] -
                $value['enable_points_deduct_expense'];
            $list[$key] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', [
            'items' => $list,
            'member_points' => model('Member')->getMemberPoints(
                $this->userinfo->uid
            ),
        ]);
    }
    public function pay()
    {
        $input_data = [
            'utype' => $this->userinfo->utype,
            'uid' => $this->userinfo->uid,
            'platform' => config('platform'),
            'service_type' => input('post.service_type/s', '', 'trim'),
            'service_id' => input('post.service_id/d', 0, 'intval'),
            'deduct_points' => input('post.deduct_points/d', 0, 'intval'),
            'payment' => input('post.payment/s', '', 'trim'),
            'tag_text' => input('post.tag_text/s', '', 'trim'),
            'return_url' => input('post.return_url/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'openid' => input('post.openid/s', '', 'trim'),
        ];
        if ($input_data['service_type'] == 'tag' && $input_data['tag_text'] == '') {
            $this->ajaxReturn(500, '请选择标签');
        }

        $validate = new \think\Validate([
            'service_type' => 'require|checkServiceType',
            'service_id' => 'require|number|gt:0',
            'payment' => 'require'
        ]);
        $validate->extend('checkServiceType', function ($value) {
            $white_list = ['stick', 'tag'];
            if (in_array($value, $white_list)) {
                return true;
            } else {
                return '请选择正确的服务类型';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        
        $resume = model('Resume')->where('uid',$this->userinfo->uid)->find();
        if($input_data['service_type']=='stick'){
            if($resume['stick']==1){
                $this->ajaxReturn(500, '该简历已经在推广状态，不能重复推广');
            }
        }
        if($input_data['service_type']=='tag'){
            if($resume['service_tag']!=''){
                $this->ajaxReturn(500, '该简历已经在推广状态，不能重复推广');
            }
        }

        $result = model('Order')->addOrder($input_data);
        if (false === $result) {
            $this->ajaxReturn(500, model('Order')->getError());
        }
        $this->writeMemberActionLog($this->userinfo->uid,'下订单【订单号：'.$result['order_oid'].'】');

        $this->ajaxReturn(200, '下单成功', $result);
    }
    /**
     * 继续支付
     */
    public function repay()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'payment' => input('post.payment/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'openid' => input('post.openid/s', '', 'trim')
        ];
        $validate = new \think\Validate([
            'id' => 'require',
            'payment' => 'require',
        ]);
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $order = model('Order')
            ->where('id', 'eq', $input_data['id'])
            ->where('status', 0)
            ->find();
        if ($order === null) {
            $this->ajaxReturn(500, '未找到订单');
        }
        $order = $order->toArray();
        if($order['deadline']!=0 && $order['deadline']<time()){
            model('Order')->orderClose($order['id'], $this->userinfo->uid);
            $this->ajaxReturn(500, '订单已失效，请重新下单');
        }
        $order['code'] = $input_data['code'];
        $order['openid'] = $input_data['openid'];
        $result = model('Order')->executePay(
            config('platform'),
            $input_data['payment'],
            $order
        );
        if ($result === false) {
            $this->ajaxReturn(500, model('Order')->getError());
        }
        $return['order_id'] = $order['id'];
        $return['order_oid'] = $order['oid'];
        $return['order_amount'] = $order['amount'];
        $return['pay_status'] = 0;
        $return['parameter'] = $result;
        $this->ajaxReturn(200, '获取支付信息成功', $return);
    }
    public function orderList()
    {
        $where['uid'] = $this->userinfo->uid;
        $service_type = input('get.service_type/s', '', 'trim');
        $status = input('get.status/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');

        if ($service_type != '') {
            $where['service_type'] = $service_type;
        }
        if ($status != '') {
            $where['status'] = $status;
        }

        $list = model('Order')
            ->field('uid,utype,extra', true)
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['service_type_text'] = model(
                'Order'
            )->map_service_type_personal[$value['service_type']];
        }

        $return['items'] = $list;
        $return['filter_type'] = [];
        $return['filter_status'] = [];
        $filter_type = model('Order')->map_service_type_personal;
        foreach ($filter_type as $key => $value) {
            $return['filter_type'][] = ['value' => $key, 'label' => $value];
        }
        $filter_status = model('Order')->map_status;
        foreach ($filter_status as $key => $value) {
            $return['filter_status'][] = ['value' => $key, 'label' => $value];
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function orderListTotal()
    {
        $where['uid'] = $this->userinfo->uid;
        $service_type = input('get.service_type/s', '', 'trim');
        $status = input('get.status/s', '', 'trim');
        
        if ($service_type != '') {
            $where['service_type'] = $service_type;
        }
        if ($status != '') {
            $where['status'] = $status;
        }
        $total = model('Order')
            ->where($where)
            ->count();
        
        $this->ajaxReturn(200, '获取数据成功', $total);
    }
    public function orderDetail()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id == 0) {
            $this->ajaxReturn(500, '请选择订单');
        }
        $info = model('Order')
            ->field('uid,utype', true)
            ->where('id', $id)
            ->find();

        $info['service_type_text'] = model('Order')->map_service_type_personal[
            $info['service_type']
        ];
        $info['status_text'] = model('Order')->map_status[$info['status']];
        $info['payment_text'] =
        $info['payment'] == ''
        ? ''
        : model('Order')->map_payment[$info['payment']];
        $info['extra'] = json_decode($info['extra'], true);

        $this->ajaxReturn(200, '获取数据成功', $info);
    }
    public function orderCancel()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id == 0) {
            $this->ajaxReturn(500, '请选择订单');
        }
        if (false === model('Order')->orderClose($id, $this->userinfo->uid)) {
            $this->ajaxReturn(500, model('Order')->getError());
        }
        $this->writeMemberActionLog($this->userinfo->uid,'取消订单【订单ID：'.$id.'】');

        $this->ajaxReturn(200, '取消订单成功');
    }
    public function orderDel()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id == 0) {
            $this->ajaxReturn(500, '请选择订单');
        }
        $order = model('Order')
            ->where('id', $id)
            ->where('uid', $this->userinfo->uid)
            ->find();
        if ($order === null) {
            $this->ajaxReturn(500, '没有找到订单信息');
        }
        if ($order['status'] != 2) {
            $this->ajaxReturn(500, '只能删除已取消的订单');
        }
        $order->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'删除订单【订单ID：'.$id.'】');
        $this->ajaxReturn(200, '删除订单成功');
    }
}
