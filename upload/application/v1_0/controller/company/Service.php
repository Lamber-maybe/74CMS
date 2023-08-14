<?php
/**
 * 会员服务
 */

namespace app\v1_0\controller\company;

class Service extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
    }

    public function mysetmeal()
    {
        $member_setmeal = model('Member')->getMemberSetmeal($this->userinfo->uid);
        $company_info['companyname'] = $this->company_profile['companyname'];
        $company_info['logo_src'] =
            $this->company_profile['logo'] > 0
                ? model('Uploadfile')->getFileUrl(
                $this->company_profile['logo']
            )
                : default_empty('logo');
        $this->ajaxReturn(200, '获取数据成功', [
            'info' => $member_setmeal,
            'company_info' => $company_info,
            'points' => model('Member')->getMemberPoints($this->userinfo->uid)
        ]);
    }

    /**
     * 获取我的优惠券列表
     */
    public function couponList()
    {
        $map['uid'] = $this->userinfo->uid;
        $map['usetime'] = 0;
        $map['deadline'] = ['gt', time()];
        $coupon_config = config('global_config.coupon_config');
        if ($coupon_config['is_open'] == 0) {
            $this->ajaxReturn(200, '获取数据成功', ['items' => []]);
        }
        $setmeal_list = model('Setmeal')->column('id,name');
        $list = model('CouponRecord')
            ->field('log_id,uid', true)
            ->where($map)
            ->order('deadline asc')
            ->select();
        foreach ($list as $key => $value) {
            $value['setmeal_name'] = isset(
                $setmeal_list[$value['coupon_bind_setmeal_id']]
            )
                ? $setmeal_list[$value['coupon_bind_setmeal_id']]
                : '';
            $value['number'] = str_pad($value['id'], 10, '0', STR_PAD_LEFT);
            if ($coupon_config['remind_days'] > 0) {
                $value['overtime_soon'] =
                    $value['deadline'] - time() >
                    $coupon_config['remind_days'] * 86400
                        ? 0
                        : 1;
            } else {
                $value['overtime_soon'] = 0;
            }
            $list[$key] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }

    /**
     * 获取套餐列表
     */
    public function setmealList()
    {
        $list = model('Setmeal')
            ->field('is_display', true)
            ->where(['is_display' => 1]) // 【bug】后台企业套餐设置显示开关和是否允许申请开关关系
            ->order('sort_id desc')
            ->select();
        $timestamp = time();
        $coupon_config = config('global_config.coupon_config');
        if ($coupon_config['is_open'] == 0) {
            $coupon_data = [];
        } else {
            $coupon_data = model('Coupon')->enableList($this->userinfo->uid);
        }
        foreach ($list as $key => $value) {
            $value['couponList'] = isset($coupon_data[$value['id']])
                ? $coupon_data[$value['id']]
                : [];
            $value['original_expense'] = $value['expense'];
            if (
                $value['preferential_open'] == 1 &&
                $value['preferential_expense_start'] < $timestamp &&
                $value['preferential_expense_end'] > $timestamp
            ) {
                $value['expense'] = $value['preferential_expense'];
            } else {
                $value['preferential_open'] = 0;
                $value['preferential_expense_start'] = 0;
                $value['preferential_expense_end'] = 0;
            }
            unset($value['preferential_expense']);
            $list[$key] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }

    /**
     * 获取积分套餐列表
     */
    public function pointsList()
    {
        $list = model('CompanyServicePoints')
            ->field('is_display', true)
            ->where('is_display', 1)
            ->order('sort_id desc')
            ->select();
        $member_points = model('Member')->getMemberPoints($this->userinfo->uid);
        $this->ajaxReturn(200, '获取数据成功', [
            'items' => $list,
            'member_points' => $member_points,
        ]);
    }

    public function serviceList()
    {
        $type = input('get.type/s', '', 'trim');
        switch ($type) {
            case 'jobstick':
                $model = model('CompanyServiceStick');
                $joblist = model('Job')
                    ->field('id,jobname')
                    ->where([
                        'audit' => 1,
                        'uid' => $this->userinfo->uid,
                        'is_display' => 1,
                        'stick' => 0,
                    ])
                    ->select();
                break;
            case 'emergency':
                $model = model('CompanyServiceEmergency');
                $joblist = model('Job')
                    ->field('id,jobname')
                    ->where([
                        'audit' => 1,
                        'uid' => $this->userinfo->uid,
                        'is_display' => 1,
                        'emergency' => 0,
                    ])
                    ->select();
                break;
            case 'resume_package':
                $model = model('CompanyServiceResumePackage');
                $joblist = [];
                break;
            case 'im':
                $model = model('CompanyServiceIm');
                $joblist = [];
                break;
            case 'job_refresh':
                $model = model('CompanyServiceRefreshJobPackage');
                $joblist = model('Job')->alias('a')->join(config('database.prefix') . 'refreshjob_queue b', 'a.id=b.jobid', 'LEFT')
                    ->field('a.id,a.jobname')
                    ->where([
                        'a.audit' => 1,
                        'a.uid' => $this->userinfo->uid,
                        'a.is_display' => 1,
                    ])->whereNull('b.jobid')
                    ->select();
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
        $member_setmeal = model('Member')->getMemberSetmeal(
            $this->userinfo->uid
        );
        $member_points = model('Member')->getMemberPoints($this->userinfo->uid);
        foreach ($list as $key => $value) {
            if ($member_setmeal['service_added_discount'] > 0) {
                $value['discount'] = $member_setmeal['service_added_discount'];
                $value['expense'] =
                    ($value['expense'] / 10) *
                    $member_setmeal['service_added_discount'];
            } else {
                $value['discount'] = 0;
            }
            $value['expense'] = number_format($value['expense'], 2, ".", "");
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
            'joblist' => $joblist,
            'member_points' => $member_points,
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
            'coupon_id' => input('post.coupon_id/d', 0, 'intval'),
            'jobid' => input('post.jobid/d', 0, 'intval'),
            'starttime' => input('post.starttime/s', '', 'trim'),
            'timerange' => input('post.timerange/d', 0, 'intval'),
            'return_url' => input('post.return_url/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'openid' => input('post.openid/s', '', 'trim'),
        ];
        //【bug】Edge浏览器下连续点击支付生成多条订单信息
        $time = time() - 180;
        $order_count = model('Order')
            ->where([
                'service_type' => $input_data['service_type'],
                'service_id' => $input_data['service_id'],
                'status' => 0,
                'uid'=>$this->userinfo->uid,
                'addtime' => ['gt', $time],
                'payment' => $input_data['payment']
            ])
            ->count();

        if ($order_count > 0) {
            $this->ajaxReturn(500, '您已提交订单，请到我的订单进行支付');
        }
        $validate = new \think\Validate([
            'service_type' => 'require|checkServiceType',
            'service_id' => 'require|number|gt:0',
            'payment' => 'require'
        ]);
        $validate->extend('checkServiceType', function ($value) {
            $white_list = [
                'setmeal',
                'points',
                'jobstick',
                'emergency',
                'resume_package',
                'im',
                'refresh_job_package',
            ];
            if (in_array($value, $white_list)) {
                return true;
            } else {
                return '请选择正确的服务类型';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        if (in_array($input_data['service_type'], ['jobstick', 'emergency', 'refresh_job_package',]) && $input_data['jobid'] <= 0) {
            $this->ajaxReturn(500, '请选择职位');
        }
        if ($input_data['service_type'] == 'refresh_job_package' && $input_data['timerange'] == 0) {
            $this->ajaxReturn(500, '请选择刷新时间间隔');
        }
        if ($input_data['service_type'] == 'refresh_job_package' && $input_data['starttime'] == '') {
            $this->ajaxReturn(500, '请选择开始时间');
        }

        if ($input_data['service_type'] == 'setmeal') {
            $result = model('Order')->addSetmealOrder($input_data);
        } elseif ($input_data['service_type'] == 'points') {
            if (config('global_config.enable_com_buy_points') == 0) {
                $this->ajaxReturn(500, '网站已关闭' . config('global_config.points_byname') . '充值');
            }
            $result = model('Order')->addPointsOrder($input_data);
        } else {
            $result = model('Order')->addOrder($input_data);
        }

        if (false === $result) {
            $this->ajaxReturn(500, model('Order')->getError());
        }

        $this->writeMemberActionLog($this->userinfo->uid, '下订单【订单号：' . $result['order_oid'] . '】');

        $this->ajaxReturn(200, '下单成功', $result);
    }

    public function pay_direct_service()
    {
        $input_data = [
            'utype' => $this->userinfo->utype,
            'uid' => $this->userinfo->uid,
            'platform' => config('platform'),
            'service_type' => input('post.service_type/s', '', 'trim'),
            'deduct_points' => input('post.deduct_points/d', 0, 'intval'),
            'payment' => input('post.payment/s', '', 'trim'),
            'jobid' => input('post.jobid/d', 0, 'intval'),
            'resumeid' => input('post.resumeid/d', 0, 'intval'),
            'return_url' => input('post.return_url/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'openid' => input('post.openid/s', '', 'trim'),
        ];
        $validate = new \think\Validate([
            'service_type' => 'require|checkServiceType',
            'payment' => 'require'
        ]);
        $validate->extend('checkServiceType', function ($value) {
            $white_list = [
                'single_resume_down',
                'single_job_refresh',
            ];
            if (in_array($value, $white_list)) {
                return true;
            } else {
                return '请选择正确的服务类型';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        if ($input_data['service_type'] == 'single_job_refresh' && $input_data['jobid'] <= 0) {
            $this->ajaxReturn(500, '请选择职位');
        }
        if ($input_data['service_type'] == 'single_resume_down' && $input_data['resumeid'] <= 0) {
            $this->ajaxReturn(500, '请选择简历');
        }

        $result = model('Order')->addOrderSingleServiceOrder($input_data);

        if (false === $result) {
            $this->ajaxReturn(500, model('Order')->getError());
        }
        $this->writeMemberActionLog($this->userinfo->uid, '下订单【订单号：' . $result['order_oid'] . '】');

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
        if ($order['deadline'] != 0 && $order['deadline'] < time()) {
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
            ->page($current_page, $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['service_type_text'] = model(
                'Order'
            )->map_service_type_company[$value['service_type']];
            $list[$key]['payment_name'] = isset(model('Order')->map_payment[$value['payment']]) ? model('Order')->map_payment[$value['payment']] : '';
        }

        $return['items'] = $list;
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

        $info['service_type_text'] = model('Order')->map_service_type_company[$info['service_type']];
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

        $this->writeMemberActionLog($this->userinfo->uid, '取消订单【订单ID：' . $id . '】');
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
        $this->writeMemberActionLog($this->userinfo->uid, '删除订单【订单ID：' . $id . '】');
        $this->ajaxReturn(200, '删除订单成功');
    }
}
