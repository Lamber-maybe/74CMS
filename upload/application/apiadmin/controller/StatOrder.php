<?php
namespace app\apiadmin\controller;

class StatOrder extends \app\common\controller\Backend
{
    /**
     * 总览数据统计
     */
    public function total()
    {
        $return['per_service'] = model('Order')
            ->where('utype', 2)
            ->where('status', 1)
            ->sum('amount');
        $return['com_setmeal'] = model('Order')
            ->where('service_type', 'eq', 'setmeal')
            ->where('status', 1)
            ->sum('amount');
        $return['com_promotion'] = model('Order')
            ->where('service_type', 'in', ['jobstick', 'emergency'])
            ->where('status', 1)
            ->sum('amount');
        $return['com_service'] = model('Order')
            ->where('service_type', 'in', [
                'resume_package',
                'im',
                'refresh_job_package',
            ])
            ->where('status', 1)
            ->sum('amount');
        foreach ($return as $key => $value) {
            $return[$key] = number_format($value, 2);
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 个人订单成交额
     */
    public function personal()
    {
        $where = [];
        $platform = input('get.platform/s', '', 'trim');
        $daterange = input('get.daterange/a', []);
        if (!empty($daterange)) {
            $starttime = strtotime($daterange[0]);
            $endtime = strtotime($daterange[1]);
            $daterange = [$starttime, $endtime + 86400 - 1];
            $where['paytime'] = ['between time', $daterange];
        }
        if ($platform != '') {
            $where['pay_platform'] = $platform;
        }

        $return = [
            'dimensions' => ['订单类型', '已完成', '待支付', '已取消'],
            'source' => [],
        ];
        $datalist_finish = model('Order')
            ->where($where)
            ->where('utype', 2)
            ->where('status', 1)
            ->group('service_type')
            ->column('service_type,sum(amount) as total');
        $datalist_noyet = model('Order')
            ->where($where)
            ->where('utype', 2)
            ->where('status', 0)
            ->group('service_type')
            ->column('service_type,sum(amount) as total');
        $datalist_close = model('Order')
            ->where($where)
            ->where('utype', 2)
            ->where('status', 2)
            ->group('service_type')
            ->column('service_type,sum(amount) as total');
        $return['source'] = [
            [
                '订单类型' => '简历置顶',
                '已完成' => isset($datalist_finish['stick'])
                ? $datalist_finish['stick']
                : 0,
                '待支付' => isset($datalist_noyet['stick'])
                ? $datalist_noyet['stick']
                : 0,
                '已取消' => isset($datalist_close['stick'])
                ? $datalist_close['stick']
                : 0,
            ],
            [
                '订单类型' => '醒目标签',
                '已完成' => isset($datalist_finish['tag'])
                ? $datalist_finish['tag']
                : 0,
                '待支付' => isset($datalist_noyet['tag'])
                ? $datalist_noyet['tag']
                : 0,
                '已取消' => isset($datalist_close['tag'])
                ? $datalist_close['tag']
                : 0,
            ],
        ];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /**
     * 企业订单成交额
     */
    public function company()
    {
        $where = [];
        $platform = input('get.platform/s', '', 'trim');
        $daterange = input('get.daterange/a', []);
        if (!empty($daterange)) {
            $starttime = strtotime($daterange[0]);
            $endtime = strtotime($daterange[1]);
            $daterange = [$starttime, $endtime + 86400 - 1];
            $where['paytime'] = ['between time', $daterange];
        }
        if ($platform != '') {
            $where['pay_platform'] = $platform;
        }
        $return = [
            'dimensions' => ['订单类型', '已完成', '待支付', '已取消'],
            'source' => [],
        ];
        $datalist_finish = model('Order')
            ->where($where)
            ->where('utype', 1)
            ->where('status', 1)
            ->group('service_type')
            ->column('service_type,sum(amount) as total');
        $datalist_noyet = model('Order')
            ->where($where)
            ->where('utype', 1)
            ->where('status', 0)
            ->group('service_type')
            ->column('service_type,sum(amount) as total');
        $datalist_close = model('Order')
            ->where($where)
            ->where('utype', 1)
            ->where('status', 2)
            ->group('service_type')
            ->column('service_type,sum(amount) as total');
        $return['source'] = [
            [
                '订单类型' => '开通套餐',
                '已完成' => isset($datalist_finish['setmeal'])
                ? $datalist_finish['setmeal']
                : 0,
                '待支付' => isset($datalist_noyet['setmeal'])
                ? $datalist_noyet['setmeal']
                : 0,
                '已取消' => isset($datalist_close['setmeal'])
                ? $datalist_close['setmeal']
                : 0,
            ],
            [
                '订单类型' => '职位置顶',
                '已完成' => isset($datalist_finish['jobstick'])
                ? $datalist_finish['jobstick']
                : 0,
                '待支付' => isset($datalist_noyet['jobstick'])
                ? $datalist_noyet['jobstick']
                : 0,
                '已取消' => isset($datalist_close['jobstick'])
                ? $datalist_close['jobstick']
                : 0,
            ],
            [
                '订单类型' => '职位紧急',
                '已完成' => isset($datalist_finish['emergency'])
                ? $datalist_finish['emergency']
                : 0,
                '待支付' => isset($datalist_noyet['emergency'])
                ? $datalist_noyet['emergency']
                : 0,
                '已取消' => isset($datalist_close['emergency'])
                ? $datalist_close['emergency']
                : 0,
            ],
            [
                '订单类型' => '简历增值包',
                '已完成' => isset($datalist_finish['resume_package'])
                ? $datalist_finish['resume_package']
                : 0,
                '待支付' => isset($datalist_noyet['resume_package'])
                ? $datalist_noyet['resume_package']
                : 0,
                '已取消' => isset($datalist_close['resume_package'])
                ? $datalist_close['resume_package']
                : 0,
            ],
            [
                '订单类型' => '职聊增值包',
                '已完成' => isset($datalist_finish['im'])
                ? $datalist_finish['im']
                : 0,
                '待支付' => isset($datalist_noyet['im'])
                ? $datalist_noyet['im']
                : 0,
                '已取消' => isset($datalist_close['im'])
                ? $datalist_close['im']
                : 0,
            ],
            [
                '订单类型' => '职位智能刷新',
                '已完成' => isset($datalist_finish['refresh_job_package'])
                ? $datalist_finish['refresh_job_package']
                : 0,
                '待支付' => isset($datalist_noyet['refresh_job_package'])
                ? $datalist_noyet['refresh_job_package']
                : 0,
                '已取消' => isset($datalist_close['refresh_job_package'])
                ? $datalist_close['refresh_job_package']
                : 0,
            ],
            [
                '订单类型' => '充值' . config('global_config.points_byname'),
                '已完成' => isset($datalist_finish['point'])
                ? $datalist_finish['point']
                : 0,
                '待支付' => isset($datalist_noyet['point'])
                ? $datalist_noyet['point']
                : 0,
                '已取消' => isset($datalist_close['point'])
                ? $datalist_close['point']
                : 0,
            ],
        ];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 各订单支付方式结构
     */
    public function payType()
    {
        $platform = input('get.platform/s', '', 'trim');
        $daterange = input('get.daterange/a', []);
        $return = [
            'xAxis' => [],
            'series' => [],
        ];
        if (!empty($daterange)) {
            $starttime = strtotime($daterange[0]);
            $endtime = strtotime($daterange[1]);
        } else {
            $endtime = strtotime('today');
            $starttime = $endtime - 86400 * 30;
        }
        $daterange = [$starttime, $endtime + 86400 - 1];
        $data_alipay = model('Order')
            ->where('payment', 'alipay')
            ->where('paytime', 'between time', $daterange);
        if ($platform != '') {
            $data_alipay = $data_alipay->where('pay_platform', $platform);
        }
        $data_alipay = $data_alipay
            ->group('paytime')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,sum(amount) as num'
            );

        $data_wxpay = model('Order')
            ->where('payment', 'wxpay')
            ->where('paytime', 'between time', $daterange);
        if ($platform != '') {
            $data_wxpay = $data_wxpay->where('pay_platform', $platform);
        }
        $data_wxpay = $data_wxpay
            ->group('paytime')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,sum(amount) as num'
            );

        $data_backend = model('Order')
            ->where('payment', 'backend')
            ->where('paytime', 'between time', $daterange);
        if ($platform != '') {
            $data_backend = $data_backend->where('pay_platform', $platform);
        }
        $data_backend = $data_backend
            ->group('paytime')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,sum(amount) as num'
            );

        $data_other = model('Order')
            ->where('payment', 'not in', ['alipay', 'wxpay', 'backend'])
            ->where('paytime', 'between time', $daterange);
        if ($platform != '') {
            $data_other = $data_other->where('pay_platform', $platform);
        }
        $data_other = $data_other
            ->group('paytime')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,sum(amount) as num'
            );

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][0][] = isset($data_alipay[$i])
            ? $data_alipay[$i]
            : 0;
            $return['series'][1][] = isset($data_wxpay[$i])
            ? $data_wxpay[$i]
            : 0;
            $return['series'][2][] = isset($data_backend[$i])
            ? $data_backend[$i]
            : 0;
            $return['series'][3][] = isset($data_other[$i])
            ? $data_other[$i]
            : 0;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /**
     * 新增业务成交趋势
     */
    public function payTotal()
    {
        $platform = input('get.platform/s', '', 'trim');
        $daterange = input('get.daterange/a', []);
        $return = [
            'xAxis' => [],
            'series' => [],
        ];
        if (!empty($daterange)) {
            $starttime = strtotime($daterange[0]);
            $endtime = strtotime($daterange[1]);
        } else {
            $endtime = strtotime('today');
            $starttime = $endtime - 86400 * 30;
        }
        $daterange = [$starttime, $endtime + 86400 - 1];
        $data_personal = model('Order')
            ->where('utype', 2)
            ->where('paytime', 'between time', $daterange);
        if ($platform != '') {
            $data_personal = $data_personal->where('pay_platform', $platform);
        }
        $data_personal = $data_personal
            ->group('paytime')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,sum(amount) as num'
            );

        $data_company = model('Order')
            ->where('utype', 1)
            ->where('paytime', 'between time', $daterange);
        if ($platform != '') {
            $data_company = $data_company->where('pay_platform', $platform);
        }
        $data_company = $data_company
            ->group('paytime')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,sum(amount) as num'
            );

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][0][] = isset($data_personal[$i])
            ? $data_personal[$i]
            : 0;
            $return['series'][1][] = isset($data_company[$i])
            ? $data_company[$i]
            : 0;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 企业新开通套餐趋势分布
     */
    public function paySetmeal()
    {
        $platform = input('get.platform/s', '', 'trim');
        $daterange = input('get.daterange/a', []);
        $return = $this->_paySetmeal($platform,$daterange);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function _paySetmeal($platform='',$daterange=[])
    {
        $return = [
            'legend' => [],
            'xAxis' => [],
            'series' => [],
        ];
        if (!empty($daterange)) {
            $starttime = strtotime($daterange[0]);
            $endtime = strtotime($daterange[1]);
        } else {
            $endtime = strtotime('today');
            $starttime = $endtime - 86400 * 30;
        }
        $daterange = [$starttime, $endtime + 86400 - 1];

        $setmeal_list = model('Setmeal')->select();
        $datalist = [];
        foreach ($setmeal_list as $key => $value) {
            $return['legend'][] = $value['name'];
            $tmp_list = model('Order')
                ->where('service_id', $value['id'])
                ->where('service_type', 'setmeal')
                ->where('paytime', 'between time', $daterange);
            if ($platform != '') {
                $tmp_list = $tmp_list->where('pay_platform', $platform);
            }
            $tmp_list = $tmp_list
                ->group('paytime')
                ->column(
                    'UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,count(*) as num'
                );
            $datalist[] = $tmp_list;
        }
        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            foreach ($datalist as $key => $value) {
                $return['series'][$key][] = isset($datalist[$key][$i])
                ? $datalist[$key][$i]
                : 0;
            }
        }
        return $return;
    }
}
