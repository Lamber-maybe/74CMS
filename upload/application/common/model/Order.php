<?php

namespace app\common\model;

class Order extends BaseModel
{
    public $map_status = [0 => '待支付', 1 => '已支付', 2 => '已取消'];
    public $map_payment = [
        'free' => '免费开通',
        'wxpay' => '微信支付',
        'alipay' => '支付宝',
        'coupon' => '优惠券兑换',
        'points' => '积分兑换',
        'backend' => '后台开通',
    ];
    public $map_service_type_company = [
        'setmeal' => '开通套餐',
        'points' => '充值积分',
        'jobstick' => '职位置顶',
        'emergency' => '职位紧急',
        'resume_package' => '简历增值包',
        'im' => '职聊增值包',
        'refresh_job_package' => '职位智能刷新',
        'single_job_refresh' => '快捷支付-刷新职位',
        'single_resume_down' => '快捷支付-下载简历',
    ];
    public $map_service_type_personal = [
        'stick' => '简历置顶',
        'tag' => '简历醒目标签',
    ];

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->map_payment['points'] = str_replace("积分", config('global_config.points_byname'), $this->map_payment['points']);
        $this->map_service_type_company['points'] = str_replace("积分", config('global_config.points_byname'), $this->map_service_type_company['points']);
    }

    /**
     * 添加套餐订单并返回支付信息
     */
    public function addSetmealOrder($data)
    {
        $timestamp = time();
        $service_info = model('Setmeal')
            ->where('id', 'eq', $data['service_id'])
            ->find();
        if ($service_info === null) {
            $this->error = '未获取到服务信息';
            return false;
        }
        $coupon_info = null;
        if ($data['coupon_id'] > 0) {
            $coupon_map = [
                'id' => $data['coupon_id'],
                'uid' => $data['uid'],
                'coupon_bind_setmeal_id' => $data['service_id'],
                'usetime' => 0,
                'deadline' => ['gt', time()],
            ];
            $coupon_info = model('CouponRecord')
                ->where($coupon_map)
                ->find();
        }
        $order_arr['code'] = isset($data['code']) ? $data['code'] : '';
        $order_arr['openid'] = isset($data['openid']) ? $data['openid'] : '';
        $order_arr['return_url'] = $data['return_url'];
        $order_arr['payment'] = '';
        $order_arr['utype'] = 1;
        $order_arr['uid'] = $data['uid'];
        $order_arr['service_type'] = $data['service_type'];
        $order_arr['service_name'] = $service_info['name'];
        $order_arr['service_amount'] = $service_info['expense'];
        $order_arr['deadline'] = strtotime('+15day');
        //=======================获取优惠后的金额=============================
        if (
            $service_info['preferential_open'] == 1 &&
            $service_info['preferential_expense_start'] < $timestamp &&
            $service_info['preferential_expense_end'] > $timestamp
        ) {
            $order_arr['service_amount_after_discount'] =
                $service_info['preferential_expense'];
            $order_arr['deadline'] = $order_arr['deadline'] > $service_info['preferential_expense_end'] ? $service_info['preferential_expense_end'] : $order_arr['deadline'];
        } else {
            $order_arr['service_amount_after_discount'] =
                $service_info['expense'];
        }
        //===================================================================

        //=======================获取最终需要支付的金额========================
        //抵扣优惠券
        if ($coupon_info !== null && $coupon_info['coupon_face_value'] > 0) {
            if (
                $order_arr['service_amount_after_discount'] >=
                $coupon_info['coupon_face_value']
            ) {
                $order_arr['deduct_amount'] = $coupon_info['coupon_face_value'];
                $order_arr['amount'] =
                    $order_arr['service_amount_after_discount'] -
                    $coupon_info['coupon_face_value'];
            } else {
                $order_arr['deduct_amount'] = $coupon_info['coupon_face_value'];
                $order_arr['amount'] = 0;
            }

            $order_arr['amount'] == 0 && ($data['payment'] = 'coupon');
        } else {
            $order_arr['deduct_amount'] = 0;
            $order_arr['amount'] = $order_arr['service_amount_after_discount'];
            $order_arr['amount'] == 0 && ($data['payment'] = 'free');
        }
        //===================================================================

        $order_arr['deduct_points'] = 0;
        $order_arr['oid'] =
            date('Ymd') .
            msectime() .
            substr(md5($order_arr['uid']), rand(0, 27), 4);

        $order_arr['addtime'] = time();
        if ($order_arr['amount'] == 0) {
            $order_arr['paytime'] = $order_arr['addtime'];
            $order_arr['status'] = 1;
        } else {
            $order_arr['paytime'] = 0;
            $order_arr['status'] = 0;
        }
        $order_arr['extra'] = [
            'setmeal_id' => $service_info['id'],
            'days' => $service_info['days'],
        ];
        if (
            $service_info['preferential_open'] == 1 &&
            $service_info['preferential_expense_start'] < $timestamp &&
            $service_info['preferential_expense_end'] > $timestamp
        ) {
            $order_arr['extra']['preferential_info'] = [
                'amount' => $service_info['preferential_expense'],
                'starttime' => $service_info['preferential_expense_start'],
                'endtime' => $service_info['preferential_expense_end'],
            ];
        }
        if ($coupon_info !== null && $coupon_info['coupon_face_value'] > 0) {
            $order_arr['extra']['coupon_info'] = [
                'face_value' => $coupon_info['coupon_face_value'],
                'name' => $coupon_info['coupon_name'],
                'id' => $coupon_info['id'],
            ];
        }

        $order_arr['extra'] = json_encode(
            $order_arr['extra'],
            JSON_UNESCAPED_UNICODE
        );

        $return = [
            'order_id' => 0,
            'order_oid' => $order_arr['oid'],
            'order_amount' => $order_arr['amount'],
            'pay_status' => 1,
            'parameter' => [],
        ];

        \think\Db::startTrans();
        try {
            $order_arr['note'] = '';
            $order_arr['add_platform'] = config('platform');
            $order_arr['pay_platform'] = '';
            $order_arr['service_id'] = $service_info['id'];
            if (false === $this->allowField(true)->save($order_arr)) {
                return false;
            }
            $return['order_id'] = $this->id;
            if ($order_arr['status'] == 0) {
                $rst = $this->executePay(
                    $data['platform'],
                    $data['payment'],
                    $order_arr
                );
                if ($rst === false) {
                    return false;
                }
                $return['pay_status'] = 0;
                $return['parameter'] = $rst;
            } else {
                $this->orderPaid(
                    $order_arr['oid'],
                    $data['payment'],
                    $order_arr['addtime']
                );
            }
            //把优惠券设置为已使用
            if ($coupon_info !== null) {
                $coupon_info->usetime = $timestamp;
                $coupon_info->save();
            }

            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }

        return $return;
    }

    /**
     * 添加积分订单并返回支付信息
     */
    public function addPointsOrder($data)
    {
        $timestamp = time();
        $service_info = model('CompanyServicePoints')
            ->where('id', 'eq', $data['service_id'])
            ->find();
        if ($service_info === null) {
            $this->error = '未获取到服务信息';
            return false;
        }
        $order_arr['code'] = isset($data['code']) ? $data['code'] : '';
        $order_arr['openid'] = isset($data['openid']) ? $data['openid'] : '';
        $order_arr['return_url'] = $data['return_url'];
        $order_arr['payment'] = '';
        $order_arr['utype'] = 1;
        $order_arr['uid'] = $data['uid'];
        $order_arr['service_type'] = $data['service_type'];
        $order_arr['service_name'] = $service_info['name'];
        $order_arr['service_amount'] = $service_info['expense'];
        $order_arr['service_amount_after_discount'] = $service_info['expense'];
        $order_arr['deduct_amount'] = 0;
        $order_arr['amount'] = $order_arr['service_amount_after_discount'];
        if ($order_arr['amount'] == 0) {
            $data['payment'] = 'free';
        }
        $order_arr['deduct_points'] = 0;
        $order_arr['oid'] =
            date('Ymd') .
            msectime() .
            substr(md5($order_arr['uid']), rand(0, 27), 4);

        $order_arr['addtime'] = time();
        $order_arr['deadline'] = strtotime('+15day');
        if ($order_arr['amount'] == 0) {
            $order_arr['paytime'] = $order_arr['addtime'];
            $order_arr['status'] = 1;
        } else {
            $order_arr['paytime'] = 0;
            $order_arr['status'] = 0;
        }
        $order_arr['extra'] = [
            'add_points' => $service_info['points'],
        ];
        $order_arr['extra'] = json_encode(
            $order_arr['extra'],
            JSON_UNESCAPED_UNICODE
        );

        $return = [
            'order_id' => 0,
            'order_oid' => $order_arr['oid'],
            'order_amount' => $order_arr['amount'],
            'pay_status' => 1,
            'parameter' => [],
        ];

        \think\Db::startTrans();
        try {
            $order_arr['note'] = '';
            $order_arr['add_platform'] = config('platform');
            $order_arr['pay_platform'] = '';
            $order_arr['service_id'] = $service_info['id'];
            if (false === $this->allowField(true)->save($order_arr)) {
                return false;
            }
            $return['order_id'] = $this->id;
            if ($order_arr['status'] == 0) {
                $rst = $this->executePay(
                    $data['platform'],
                    $data['payment'],
                    $order_arr
                );
                if ($rst === false) {
                    return false;
                }
                $return['pay_status'] = 0;
                $return['parameter'] = $rst;
            } else {
                $this->orderPaid(
                    $order_arr['oid'],
                    $data['payment'],
                    $order_arr['addtime']
                );
            }
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }

        return $return;
    }

    /**
     * 添加快捷消费订单并返回支付信息
     */
    public function addOrderSingleServiceOrder($data)
    {
        $global_config = config('global_config');
        $service_list = [
            'single_resume_down' => [
                'service_name' => '快捷消费-下载单份简历',
                'service_amount' => 0,
                'single_service_open' => $global_config['single_resume_download_open'],
                'enable_points_deduct' => $global_config['single_resume_download_enable_points_deduct'],
                'deduce_points' => 0,
            ],
            'single_job_refresh' => [
                'service_name' => '快捷消费-刷新职位',
                'service_amount' => $global_config['single_job_refresh_expense'],
                'single_service_open' => 1,
                'enable_points_deduct' => $global_config['single_job_refresh_enable_points_deduct'],
                'deduce_points' => $global_config['single_job_refresh_deduce_points'],
            ],
        ];
        if ($data['service_type'] == 'single_resume_down') {
            $resume_info = model('Resume')
                ->field('high_quality,uid,refreshtime,fullname')
                ->where('id', 'eq', $data['resumeid'])
                ->find();

            if ($resume_info['high_quality'] == 1) {
                $need_points = $global_config['single_resume_download_points_talent'];
            } else {
                $down_resume_points_config_arr = $global_config['single_resume_download_points_conf'];
                $down_resume_points_config = [];
                foreach ($down_resume_points_config_arr as $key => $value) {
                    $down_resume_points_config[$value['alias']] = $value['value'];
                }
                if ($resume_info['refreshtime'] >= strtotime('-1 day')) {
                    //刷新时间1天之内
                    $need_points = $down_resume_points_config[1];
                } elseif ($resume_info['refreshtime'] >= strtotime('-3 day')) {
                    //刷新时间3天之内
                    $need_points = $down_resume_points_config[3];
                } elseif ($resume_info['refreshtime'] >= strtotime('-5 day')) {
                    //刷新时间5天之内
                    $need_points = $down_resume_points_config[5];
                } else {
                    //刷新时间5天以上
                    $need_points = $down_resume_points_config[0];
                }
            }
            $service_list['single_resume_down']['deduce_points'] = $need_points;
            // 快捷支付 zdq 2022.07.06
            if ($resume_info['high_quality'] == 1) {
                $need_expense = $global_config['single_resume_download_expense_talent'];
            } else {
                $down_resume_expense_config_arr = $global_config['single_resume_download_expense_conf'];
                $down_resume_expense_config = [];
                foreach ($down_resume_expense_config_arr as $key => $value) {
                    $down_resume_expense_config[$value['alias']] = $value['value'];
                }
                if ($resume_info['refreshtime'] >= strtotime('-1 day')) {
                    //刷新时间1天之内
                    $need_expense = $down_resume_expense_config[1];
                } elseif ($resume_info['refreshtime'] >= strtotime('-3 day')) {
                    //刷新时间3天之内
                    $need_expense = $down_resume_expense_config[3];
                } elseif ($resume_info['refreshtime'] >= strtotime('-5 day')) {
                    //刷新时间5天之内
                    $need_expense = $down_resume_expense_config[5];
                } else {
                    //刷新时间5天以上
                    $need_expense = $down_resume_expense_config[0];
                }
            }
            $service_list['single_resume_down']['service_amount'] = $need_expense;
        }
        $service_info = $service_list[$data['service_type']];

        if ($service_info['single_service_open'] == 0) {
            $this->error = '服务未开启';
            return false;
        }

        $timestamp = time();
        $order_arr['code'] = isset($data['code']) ? $data['code'] : '';
        $order_arr['openid'] = isset($data['openid']) ? $data['openid'] : '';
        $order_arr['return_url'] = $data['return_url'];
        $order_arr['payment'] = '';
        $order_arr['utype'] = 1;
        $order_arr['uid'] = $data['uid'];
        $order_arr['service_type'] = $data['service_type'];
        $order_arr['service_name'] = $service_info['service_name'];
        $order_arr['service_amount'] = $service_info['service_amount'];
        $order_arr['service_amount_after_discount'] = $order_arr['service_amount'];
        $order_arr['deduct_amount'] = 0;
        $order_arr['amount'] = $order_arr['service_amount_after_discount'];
        $order_arr['deduct_points'] = 0;
        $order_arr['oid'] =
            date('Ymd') .
            msectime() .
            substr(md5($order_arr['uid']), rand(0, 27), 4);

        if ($data['deduct_points'] > 0) {
            if ($service_info['enable_points_deduct'] == 0) {
                //不允许抵扣
                $this->error = '服务未开启' . $global_config['points_byname'] . '抵扣';
                return false;
            } else {
                //允许抵扣
                $order_arr['amount'] = 0;
                $order_arr['deduct_amount'] =
                    $order_arr['service_amount_after_discount'];
                $order_arr['deduct_points'] = $service_info['deduce_points'];
                $data['payment'] = 'points';
            }
            if (
                $order_arr['deduct_points'] >
                model('Member')->getMemberPoints($order_arr['uid'])
            ) {
                $this->error = $global_config['points_byname'] . '不足';
                return false;
            }
        }
        $order_arr['addtime'] = time();
        $order_arr['deadline'] = strtotime('+15day');
        if ($order_arr['amount'] == 0) {
            $order_arr['paytime'] = $order_arr['addtime'];
            $order_arr['status'] = 1;
        } else {
            $order_arr['paytime'] = 0;
            $order_arr['status'] = 0;
        }
        if ($data['service_type'] == 'single_resume_down') {
            $order_arr['extra'] = [
                'resumeid' => $data['resumeid'],
            ];
        } else {
            $jobinfo = model('Job')
                ->where('id', $data['jobid'])
                ->field('jobname')
                ->find();
            $order_arr['extra'] = [
                'jobid' => $data['jobid'],
                'jobname' => $jobinfo['jobname']
            ];
        }

        $order_arr['extra'] = json_encode(
            $order_arr['extra'],
            JSON_UNESCAPED_UNICODE
        );

        $return = [
            'order_id' => 0,
            'order_oid' => $order_arr['oid'],
            'order_amount' => $order_arr['amount'],
            'pay_status' => 1,
            'parameter' => [],
        ];

        \think\Db::startTrans();
        try {
            $order_arr['note'] = '';
            $order_arr['add_platform'] = config('platform');
            $order_arr['pay_platform'] = '';
            $order_arr['service_id'] = 0;
            if (false === model('OrderTmp')->allowField(true)->save($order_arr)) {
                return false;
            }
            if ($order_arr['status'] == 0) {
                $rst = $this->executePay(
                    $data['platform'],
                    $data['payment'],
                    $order_arr
                );
                if ($rst === false) {
                    return false;
                }
                $return['pay_status'] = 0;
                $return['parameter'] = $rst;
            } else {
                $this->orderPaid(
                    $order_arr['oid'],
                    $data['payment'],
                    $order_arr['addtime']
                );
            }
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }

        return $return;
    }

    /**
     * 添加订单并返回支付信息
     */
    public function addOrder($data)
    {
        $addToQueue = 0;
        switch ($data['service_type']) {
            case 'stick':
                $service_model = model('PersonalServiceStick');
                $addToQueue = 1;
                break;
            case 'jobstick':
                $service_model = model('CompanyServiceStick');
                $addToQueue = 1;
                break;
            case 'emergency':
                $service_model = model('CompanyServiceEmergency');
                $addToQueue = 1;
                break;
            case 'resume_package':
                $service_model = model('CompanyServiceResumePackage');
                break;
            case 'im':
                $service_model = model('CompanyServiceIm');
                break;
            case 'refresh_job_package':
                $service_model = model('CompanyServiceRefreshJobPackage');
                break;
            case 'tag':
                $service_model = model('PersonalServiceTag');
                $addToQueue = 1;
                break;
            default:
                $this->error = '服务类型错误';
                return false;
        }
        $service_info = $service_model
            ->where('id', 'eq', $data['service_id'])
            ->find();
        if ($service_info === null) {
            $this->error = '未获取到服务信息';
            return false;
        }
        $order_arr['code'] = isset($data['code']) ? $data['code'] : '';
        $order_arr['openid'] = isset($data['openid']) ? $data['openid'] : '';
        $order_arr['return_url'] = $data['return_url'];
        $order_arr['utype'] = $data['utype'];
        $order_arr['uid'] = $data['uid'];
        $order_arr['service_type'] = $data['service_type'];
        $order_arr['service_name'] = $service_info['name'];
        $order_arr['service_amount'] = $service_info['expense'];
        $order_arr['extra'] = [];
        //=======================计算优惠后的金额=======================
        if ($data['utype'] == 1) {
            $member_setmeal = model('Member')->getMemberSetmeal($data['uid']);
            if ($member_setmeal['service_added_discount'] > 0) {
//                $order_arr['service_amount_after_discount'] =
//                    ($order_arr['service_amount'] / 10) *
//                    $member_setmeal['service_added_discount'];
                $order_arr['service_amount_after_discount'] =
                    bcadd(
                        bcmul(
                            ($order_arr['service_amount'] / 10),
                            $member_setmeal['service_added_discount'],
                            2),
                        0,
                        2);
                if ($order_arr['service_amount_after_discount'] < 0.01) {
                    $order_arr['service_amount_after_discount'] = 0.01;
                }
            } else {
                $order_arr['service_amount_after_discount'] =
                    $order_arr['service_amount'];
            }
            $order_arr['extra']['service_added_discount'] =
                $member_setmeal['service_added_discount'];
        } else {
            $order_arr['service_amount_after_discount'] =
                $order_arr['service_amount'];
        }
        //=============================================================

        $order_arr['deduct_points'] = 0;
        $order_arr['amount'] = $order_arr['service_amount_after_discount'];
        $order_arr['deduct_amount'] = 0;
        $order_arr['payment'] = '';
        $order_arr['oid'] =
            date('Ymd') .
            msectime() .
            substr(md5($order_arr['uid']), rand(0, 27), 4);
        if ($order_arr['service_amount_after_discount'] == 0) {
            $order_arr['amount'] = 0;
            $order_arr['deduct_points'] = 0;
            $order_arr['deduct_amount'] = 0;
            $data['payment'] = 'free';
        } elseif ($data['deduct_points'] > 0) {
            if ($service_info['enable_points_deduct'] == 0) {
                //不允许抵扣
                $order_arr['deduct_points'] = 0;
                $order_arr['amount'] =
                    $order_arr['service_amount_after_discount'];
                $order_arr['deduct_amount'] = 0;
            } elseif ($service_info['enable_points_deduct'] == 1) {
                //允许全额抵扣
                $order_arr['amount'] = 0;
                $order_arr['deduct_amount'] =
                    $order_arr['service_amount_after_discount'];
                $order_arr['deduct_points'] = ceil(
                    $order_arr['service_amount_after_discount'] *
                    config('global_config.payment_rate')
                );
                $data['payment'] = 'points';
            } else {
                //允许部分抵扣
                $order_arr['deduct_points'] = ceil(
                    $service_info['deduct_max'] *
                    config('global_config.payment_rate')
                );
                $order_arr['deduct_amount'] = $service_info['deduct_max'];
                $order_arr['amount'] =
                    $order_arr['service_amount_after_discount'] -
                    $order_arr['deduct_amount'];
                if ($order_arr['amount'] < 0) {
                    $order_arr['amount'] = 0;
                    $order_arr['deduct_amount'] =
                        $order_arr['service_amount_after_discount'];
                }
            }

            if (
                $order_arr['deduct_points'] >
                model('Member')->getMemberPoints($order_arr['uid'])
            ) {
                $this->error = config('global_config.points_byname') . '不足';
                return false;
            }
        }
        $order_arr['addtime'] = time();
        $order_arr['deadline'] = strtotime('+15day');
        if ($order_arr['amount'] == 0) {
            $order_arr['paytime'] = $order_arr['addtime'];
            $order_arr['status'] = 1;
            $data['payment'] = '';
        } else {
            $order_arr['paytime'] = 0;
            $order_arr['status'] = 0;
        }
        if (isset($data['tag_text']) && $data['tag_text'] != '') {
            $order_arr['extra']['tag_text'] = $data['tag_text'];
        }
        if (isset($data['jobid']) && $data['jobid'] > 0) {
            $jobinfo = model('Job')
                ->where('id', $data['jobid'])
                ->field('jobname')
                ->find();
            $order_arr['extra']['jobid'] = $data['jobid'];
            $order_arr['extra']['jobname'] = $jobinfo['jobname'];
        }
        if (isset($data['starttime']) && $data['starttime'] != '') {
            $order_arr['extra']['starttime'] = $data['starttime'];
        }
        if (isset($data['timerange']) && $data['timerange'] > 0) {
            $order_arr['extra']['timerange'] = $data['timerange'];
        }
        if (isset($service_info['days'])) {
            $order_arr['extra']['days'] = $service_info['days'];
        }
        if (isset($service_info['times'])) {
            $order_arr['extra']['times'] = $service_info['times'];
        }
        if (isset($service_info['download_resume_point'])) {
            $order_arr['extra']['download_resume_point'] =
                $service_info['download_resume_point'];
        }
        $order_arr['extra'] = json_encode(
            $order_arr['extra'],
            JSON_UNESCAPED_UNICODE
        );

        $return = [
            'order_id' => 0,
            'order_oid' => $order_arr['oid'],
            'order_amount' => $order_arr['amount'],
            'pay_status' => 1,
            'parameter' => [],
        ];

        \think\Db::startTrans();
        try {
            $order_arr['note'] = '';
            $order_arr['add_platform'] = config('platform');
            $order_arr['pay_platform'] = '';
            $order_arr['service_id'] = $service_info['id'];
            if (false === $this->allowField(true)->save($order_arr)) {
                return false;
            }
            $return['order_id'] = $this->id;
            if ($order_arr['status'] == 0) {
                $rst = $this->executePay(
                    $data['platform'],
                    $data['payment'],
                    $order_arr
                );
                if ($rst === false) {
                    return false;
                }
                $return['pay_status'] = 0;
                $return['parameter'] = $rst;
            } else {
                $this->orderPaid(
                    $order_arr['oid'],
                    $data['payment'],
                    $order_arr['addtime']
                );
            }

            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }
        if ($order_arr['status'] == 1 && $addToQueue == 1) {
            $this->recordToQueue($order_arr);
        }

        return $return;
    }

    /**
     * 请求支付参数
     */
    public function executePay($platform, $payment, $order)
    {
        $pay = new \app\common\lib\Pay($platform, $payment);
        $rst = $pay->callPay($order);
        if ($rst === false) {
            $this->error = $pay->getError();
            return false;
        }
        return $rst;
    }

    /**
     * 关闭订单
     */
    public function orderClose($id, $uid)
    {
        $order = $this->where([
            'id' => ['eq', $id],
            'uid' => ['eq', $uid],
            'status' => 0,
        ])->find();
        if ($order === null) {
            $this->error = '未找到订单';
            return false;
        }
        $order->status = 2;
        $order->save();
        if ($order['deduct_points'] > 0) {
            model('Member')->setMemberPoints([
                'uid' => $uid,
                'points' => $order['deduct_points'],
                'note' => '关闭订单退回',
            ]);
        }
        //如果已经抵扣了优惠券，把优惠券还原成未使用
        $extra = json_decode($order['extra'], true);
        if (isset($extra['coupon_info'])) {
            $coupon_info = model('CouponRecord')
                ->where('id', $extra['coupon_info']['id'])
                ->find();
            if ($coupon_info !== null) {
                $coupon_info->usetime = 0;
                $coupon_info->save();
            }
        }
        return true;
    }

    /**
     * 设置订单状态为已支付
     */
    public function orderPaid($oid, $payment, $time, $note = '', $admin_id = 0)
    {
        $order = $this->where('oid', 'eq', $oid)->find();
        if ($order === null) {
            $order = model('OrderTmp')->where('oid', 'eq', $oid)->find();
        }
        if ($payment == '') {
            if ($order['deduct_points'] > 0) {
                $payment = 'points';
            } else {
                $payment = 'free';
            }
        }
        $points_log = '';
        $addToQueue = 0; //是否需要添加到计划任务队列，如简历的置顶、标签、职位的置顶等
        //简历置顶
        if ($order['utype'] == 2 && $order['service_type'] == 'stick') {
            model('Resume')
                ->where('uid', 'eq', $order['uid'])
                ->setField('stick', 1);
            model('ResumeSearchRtime')
                ->where('uid', 'eq', $order['uid'])
                ->setField('stick', 1);
            model('ResumeSearchKey')
                ->where('uid', 'eq', $order['uid'])
                ->setField('stick', 1);
            $addToQueue = 1;
            $points_log = '简历置顶';
        }
        //简历标签
        if ($order['utype'] == 2 && $order['service_type'] == 'tag') {
            $extra = json_decode($order['extra'], true);
            model('Resume')
                ->where('uid', 'eq', $order['uid'])
                ->setField('service_tag', $extra['tag_text']);
            $addToQueue = 1;
            $points_log = '简历标签';
        }
        //职位置顶
        if ($order['utype'] == 1 && $order['service_type'] == 'jobstick') {
            $extra = json_decode($order['extra'], true);
            model('Job')
                ->where('id', 'eq', $extra['jobid'])
                ->setField('stick', 1);
            model('JobSearchRtime')
                ->where('id', 'eq', $extra['jobid'])
                ->setField('stick', 1);
            model('JobSearchKey')
                ->where('id', 'eq', $extra['jobid'])
                ->setField('stick', 1);
            $addToQueue = 1;
            $points_log = '职位置顶';
        }
        //职位紧急
        if ($order['utype'] == 1 && $order['service_type'] == 'emergency') {
            $extra = json_decode($order['extra'], true);
            model('Job')
                ->where('id', 'eq', $extra['jobid'])
                ->setField('emergency', 1);
            model('JobSearchRtime')
                ->where('id', 'eq', $extra['jobid'])
                ->setField('emergency', 1);
            model('JobSearchKey')
                ->where('id', 'eq', $extra['jobid'])
                ->setField('emergency', 1);
            $addToQueue = 1;
            $points_log = '职位紧急';
        }
        //简历包
        if (
            $order['utype'] == 1 &&
            $order['service_type'] == 'resume_package'
        ) {
            $extra = json_decode($order['extra'], true);
            if ($extra['download_resume_point'] > 0) {
                model('MemberSetmeal')
                    ->where('uid', $order['uid'])
                    ->setInc(
                        'purchase_resume_point',//简历包增加
                        $extra['download_resume_point']
                    );
            }
            $points_log = '简历包';
        }
        //职聊包
        if (
            $order['utype'] == 1 &&
            $order['service_type'] == 'im'
        ) {
            $extra = json_decode($order['extra'], true);
            if ($extra['times'] > 0) {
                model('MemberSetmeal')
                    ->where('uid', $order['uid'])
                    ->setInc(
                        'im_added_package', // yx - 2023.07.14 im_total => im_added_package
                        $extra['times']
                    );
            }
            $points_log = '职聊包';
        }
        //职位智能刷新
        if (
            $order['utype'] == 1 &&
            $order['service_type'] == 'refresh_job_package'
        ) {
            $extra = json_decode($order['extra'], true);
            if (
                $extra['times'] > 0 &&
                $extra['jobid'] > 0 &&
                $extra['starttime'] != '' &&
                $extra['timerange'] > 0
            ) {
                $starttime = strtotime($extra['starttime']);
                $timerange = $extra['timerange'];
                $queue = [];
                $arr = ['jobid' => $extra['jobid'], 'uid' => $order['uid']];
                for ($i = 0; $i < $extra['times']; $i++) {
                    $arr['execute_time'] = $starttime + $timerange * $i;
                    $queue[] = $arr;
                }
                if (!empty($queue)) {
                    model('RefreshjobQueue')->saveAll($queue);
                }
            }
            $points_log = '职位智能刷新';
        }
        //企业套餐
        if ($order['utype'] == 1 && $order['service_type'] == 'setmeal') {
            $extra = json_decode($order['extra'], true);
            model('Member')->setMemberSetmeal([
                'uid' => $order['uid'],
                'setmeal_id' => $extra['setmeal_id'],
                'note' => '',
            ], $order['id'], $admin_id);
            $points_log = '企业套餐';
        }
        //企业充积分
        if ($order['utype'] == 1 && $order['service_type'] == 'points') {
            $extra = json_decode($order['extra'], true);
            model('Member')->setMemberPoints([
                'uid' => $order['uid'],
                'points' => $extra['add_points'],
                'note' => '充值' . config('global_config.points_byname'),
            ]);
        }
        //快捷支付-刷新职位
        if ($order['utype'] == 1 && $order['service_type'] == 'single_job_refresh') {
            $extra = json_decode($order['extra'], true);
            // 刷新职位信息 chenyang 2022年3月21日15:13:24
            $refreshParams = [
                'id' => $extra['jobid'],
                'platform' => $order['add_platform'],
            ];
            model('Job')->refreshJobMind($refreshParams, 5);
            $points_log = '刷新职位';
        }
        //快捷支付-下载简历
        if ($order['utype'] == 1 && $order['service_type'] == 'single_resume_down') {
            $extra = json_decode($order['extra'], true);
            model('CompanyDownResume')->downResumeAddSingleService($extra['resumeid'], $order['uid'], $order['add_platform']);
            $points_log = '下载简历';
        }
        if ($order['deduct_points'] > 0) {
            model('Member')->setMemberPoints(['uid' => $order['uid'], 'points' => $order['deduct_points'], 'note' => config('global_config.points_byname') . '抵扣-' . $points_log], 2);
        }
        $order->payment = $payment;
        $order->status = 1;
        $order->paytime = $time;
        $order->note = $note;
        $order->pay_platform = config('platform') ? config('platform') : $order['add_platform'];
        $order->save();
        if ($addToQueue == 1) {
            $this->recordToQueue($order);
        }
        //通知
        model('NotifyRule')->notify(
            $order['uid'],
            $order['utype'],
            'order_pay',
            [
                'service_name' => $order['service_name'],
                'oid' => $order['oid'],
            ]
        );
        $name = '';
        if ($order['utype'] == 1) {
            $company = model('Company')->where('uid', $order['uid'])->field('companyname')->find();
            $name = !empty($company['companyname']) ? mb_substr($company['companyname'], 0, 10) : '';
        }
        if ($order['utype'] == 2) {
            $resume = model('Resume')->where('uid', $order['uid'])->field('fullname')->find();
            $name = !empty($resume['fullname']) ? mb_substr($resume['fullname'], 0, 10) : '';
        }
        //微信通知
        model('WechatNotifyRule')->notify(
            $order['uid'],
            $order['utype'],
            'order_pay',
            [
                '亲，您的订单已支付成功',
                $order['oid'],
                $name . '-' . $order['service_name'],
                $order['amount'] . '元',
                $this->map_payment[$payment],
                date('Y年m月d日 H:i:s', $time),
                '点击查看订单详情'
            ],
            'member/order/' . $order->id
        );
        if ($order['service_type'] == 'single_resume_down' || $order['service_type'] == 'single_job_refresh') {
            $order = $order->toArray();
            model('OrderTmp')->where('id', $order['id'])->delete();
            unset($order['id']);
            model('Order')->save($order);
        }
        if ($payment == 'wxpay') {
            cache('wxpay_' . $order['oid'], 'ok', 60);
        }
        return true;
    }

    /**
     * 订单完成时记录到服务队列
     */
    public function recordToQueue($order)
    {
        $queue['type'] = $order['service_type'];
        $extra = json_decode($order['extra'], true);
        if (
            in_array($queue['type'], ['stick', 'tag']) &&
            $order['utype'] == 2
        ) {
            $resume = model('Resume')
                ->where('uid', $order['uid'])
                ->find();
            $queue['pid'] = $resume['id'];
        }
        if (
            in_array($queue['type'], ['jobstick', 'emergency']) &&
            $order['utype'] == 1
        ) {
            $queue['pid'] = $extra['jobid'];
        }
        $queue['utype'] = $order['utype'];
        $queue['addtime'] = time();
        $queue['deadline'] = strtotime('+' . $extra['days'] . 'day');
        model('ServiceQueue')->save($queue);
    }
}
