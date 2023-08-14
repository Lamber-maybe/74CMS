<?php

namespace app\apiadmin\controller;

class NewDataStatistics extends \app\common\controller\Backend
{

    /**
     * 实时数据
     */
    public function realTimeData()
    {
        $today_data = $this->getTodayData();
        $this->ajaxReturn(200, '获取数据成功', $today_data);
    }

    /**
     * 顶部数据统计
     */
    public function generalstatistics()
    {
        $general_statistics = $this->getGeneralstatistics();
        $this->ajaxReturn(200, '获取数据成功', $general_statistics);
    }

    /**
     * 获取显示数据
     * @return void
     */
    public function index()
    {
        $pending_data = $this->getPendingData();
        $system_info = $this->getSystemInfo();
        $return = [
            'pending_data' => $pending_data,//待办事项
            'system_info' => $system_info,//获取服务器信息
            'version' => config('version.version'),
            'warning' => [
                'rewrite' => $this->checkRewrite(),
                'install' => $this->checkInstall(),
            ]
        ];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /**
     * 检测安装文件
     */
    protected function checkInstall()
    {
        if (is_dir(PUBLIC_PATH . 'install')) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 检测伪静态
     */
    protected function checkRewrite()
    {
        $result = https_request(config('global_config.sitedomain') . config('global_config.sitedir') . 'apiadmin/login/userinfo');
        if ($result === false) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * 获取图表统计
     */
    public function chart()
    {
        $type = input('post.type/s', '', 'trim');
        $start_time = input('post.start_time/s', '', 'trim');
        $end_time = input('post.end_time/s', '', 'trim');
        switch ($type) {
            case 'reg':
                $data = $this->chartReg($start_time, $end_time, '');
                break;
            case 'income':
                $data = $this->chartIncome($start_time, $end_time);
                break;
            case 'personal':
                $data = $this->charPersonal($start_time, $end_time);
                break;
            case 'company':
                $data = $this->charCompany($start_time, $end_time);
                break;
            default:
                $data = [];
                break;
        }
        $this->ajaxReturn(200, '获取数据成功', $data);
    }

    /**
     * 企业活跃度
     */
    protected function charCompany($start_time, $end_time)
    {
        $return = [
            'legend' => ['登录数', '发布职位数', '刷新职位数', '下载简历数'],
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime($end_time);
        $starttime = strtotime($start_time);
        $daterange = [$starttime, $endtime + 86400 - 1];

        $member_login_data = model('MemberActionLog')
            ->where('is_login', 1)
            ->where('utype', 1)
            ->where('addtime', 'between time', $daterange)
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(DISTINCT uid) as num'
            );

        $add_job_data = model('Job')->where(
            'addtime',
            'between time',
            $daterange
        );
        $add_job_data = $add_job_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        $refresh_job_data = model('RefreshJobLog')->where(
            'addtime',
            'between time',
            $daterange
        );
        $refresh_job_data = $refresh_job_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        $down_resume_data = model('CompanyDownResume')->where(
            'addtime',
            'between time',
            $daterange
        );
        $down_resume_data = $down_resume_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][0][] = isset($member_login_data[$i])
                ? $member_login_data[$i]
                : 0;
            $return['series'][1][] = isset($add_job_data[$i])
                ? $add_job_data[$i]
                : 0;
            $return['series'][2][] = isset($refresh_job_data[$i])
                ? $refresh_job_data[$i]
                : 0;
            $return['series'][3][] = isset($down_resume_data[$i])
                ? $down_resume_data[$i]
                : 0;
        }
        return $return;
    }

    /**
     * 个人活跃度
     */
    protected function charPersonal($start_time, $end_time)
    {
        $return = [
            'legend' => ['登录数', '简历刷新（次数）', '简历投递'],
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime($end_time);
        $starttime = strtotime($start_time);
        $daterange = [$starttime, $endtime + 86400 - 1];

        $member_login_data = model('MemberActionLog')
            ->where('is_login', 1)
            ->where('utype', 2)
            ->where('addtime', 'between time', $daterange)
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(DISTINCT uid) as num'
            );

        $refresh_resume_times_data = model('RefreshResumeLog')->where(
            'addtime',
            'between time',
            $daterange
        );
        $refresh_resume_times_data = $refresh_resume_times_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        $job_apply_data = model('JobApply')->where(
            'addtime',
            'between time',
            $daterange
        );
        $job_apply_data = $job_apply_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][0][] = isset($member_login_data[$i])
                ? $member_login_data[$i]
                : 0;
            $return['series'][1][] = isset($refresh_resume_times_data[$i])
                ? $refresh_resume_times_data[$i]
                : 0;
            $return['series'][2][] = isset($job_apply_data[$i])
                ? $job_apply_data[$i]
                : 0;
        }
        return $return;
    }

    /**
     * 收入趋势
     */
    protected function chartIncome($start_time, $end_time)
    {
        $return = [
            'legend' => ['收入趋势'],
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime($end_time);
        $starttime = strtotime($start_time);
        $daterange = [$starttime, $endtime + 86400 - 1];
        $payment_data = model('Order')
            ->where('paytime', 'between time', $daterange)
            ->group('time')
            ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,count(*) as num');

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $payment_data_arr[] = isset($payment_data[$i])
                ? $payment_data[$i]
                : 0;
        }
        if($payment_data_arr != 0){
            $return['series'][] = $payment_data_arr;
        }else{
            $return['series'][] = [];
        }
        return $return;
    }

    /**
     * 系统信息
     */
    public function officialData()
    {
        $http = new \app\common\lib\Http;
        $result = $http->get('https://www.74cms.com/api/official_data?domain=' . urlencode($_SERVER['HTTP_HOST']));
        $result = json_decode($result, 1);
        if ($result['code'] == 200) {
            foreach ($result['data']['upgrade_log'] as &$log) {
                $log['time_cn'] = date('Y年m月d日', strtotime($log['time']));
            }
            $return = [
                'upgrade_log' => $result['data']['upgrade_log'],
                'authorize_info' => $result['data']['authorize_info'],
                'official_news' => $result['data']['official_news'],
                'latest_version_text' => $result['data']['latest_version_text']
            ];
            $ver = explode('.', config('version.version'));
            $version_int_1 = str_pad($ver[0], 2, '0', STR_PAD_LEFT);
            $version_int_2 = str_pad($ver[1], 2, '0', STR_PAD_LEFT);
            $version_int_3 = str_pad($ver[2], 3, '0', STR_PAD_LEFT);
            $current_version = $version_int_1 . $version_int_2 . $version_int_3;
            $return['new_version_notice'] = $result['data']['latest_version'] > $current_version ? 1 : 0;
            $account_sms = config('global_config.account_sms');
            $sms_result = $http->post('http://sms.74cms.com/Api/total',['username'=>$account_sms['app_key'],'password'=>$account_sms['secret_key'],'method'=>'total']);
            $sms_result_arr = json_decode($sms_result, 1);
            if($sms_result_arr['status'] == 1){
                $return['sms_count'] = $sms_result_arr['data']['sms_count'];
            }
            $this->ajaxReturn(200,'获取数据成功',$return);
        } else {
            $this->ajaxReturn(500,'获取数据失败',[]);
        }
    }

    /**
     * 总收入/支付订单/企业会员数/个人会员数统计
     */
    protected function getGeneralstatistics()
    {
        $timestamp_today_start = strtotime(date('Y-m-d H:i', time()) . ' 00:00:00');
        $timestamp_today_end = strtotime(date('Y-m-d H:i', time()) . ' 23:59:59');
        $month_start = strtotime(date('Y-m', time()) . '-01 00:00:00');
        /**
         * 总收入统计
         */
        $total_revenue = model('Order')
            ->where('status', 1)
            ->sum('amount');
        $today_revenue = model('Order')
            ->where('status', 1)
            ->where('paytime', '>', $timestamp_today_start)
            ->where('paytime', '<=', $timestamp_today_end)
            ->sum('amount');
        $month_revenue = model('Order')
            ->where('status', 1)
            ->where('paytime', '>', $month_start)
            ->sum('amount');
        $revenue_list = $this->chartOrder('amount');
        $return['revenue'] = [
            'total' => $total_revenue,
            'today' => $today_revenue,
            'month' => $month_revenue,
            'list' => $revenue_list
        ];

        /**
         * 支付订单
         */
        $total_order_num = model('Order')
            ->where('status', 1)
            ->count();
        $today_order_num = model('Order')
            ->where('status', 1)
            ->where('paytime', '>', $timestamp_today_start)
            ->where('paytime', '<=', $timestamp_today_end)
            ->count();
        $month_order_num = model('Order')
            ->where('status', 1)
            ->where('paytime', '>', $month_start)
            ->count();
        $order_num_list = $this->chartOrder('num');
        $return['order_num'] = [
            'total' => $total_order_num,
            'today' => $today_order_num,
            'month' => $month_order_num,
            'list' => $order_num_list
        ];
        /**
         * 企业会员数
         */
        $total_company = model('Member')
            ->where('utype', 1)
            ->count();
        $total_Job = model('Job')
            ->count();
        $month_company = model('Member')
            ->where('reg_time', '>', $month_start)
            ->where('utype', 1)
            ->count();
        $company_list = $this->chartReg('', '', 'company');
        $return['company'] = [
            'total' => $total_company,
            'total_job' => $total_Job,
            'month' => $month_company,
            'list' => $company_list
        ];
        /**
         * 个人会员数
         */
        $total_personal = model('Member')
            ->where('utype', 2)
            ->count();
        $total_resume = model('Resume')
            ->count();
        $month_personal = model('Member')
            ->where('reg_time', '>', $month_start)
            ->where('utype', 2)
            ->count();
        $personal_list = $this->chartReg('', '', 'personal');
        $return['personal'] = [
            'total' => $total_personal,
            'total_resume' => $total_resume,
            'month' => $month_personal,
            'list' => $personal_list
        ];
        return $return;
    }

    protected function chartReg($start_time = '', $end_time = '', $type)
    {
        if($start_time != '' && $end_time != '' && $type == ''){
            $return = [
                'xAxis' => [],
                'series' => [
                    [],[],[],[]
                ]
            ];
        }else{
            $return = [
                'xAxis' => [],
                'series' => []
            ];
        }
        if ($start_time != '' && $end_time != '') {
            $return['legend'] = ['注册企业', '新增职位', '注册个人', '新增简历'];
            $endtime = strtotime($end_time);
            $starttime = strtotime($start_time);
        } else {
            $endtime = strtotime('today');
            $starttime = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), 1, date("Y"))));
        }
        $daterange = [$starttime, $endtime + 86400 - 1];
        //添加utype=2条件，解决后台首页注册统计图(当前是否为个人数 = 个人+企业数)问题
        if ($start_time != '' && $end_time != '' && $type == '') {
            $personal_data = model('Member')
                ->where(['reg_time' => ['between time', $daterange], 'utype' => 2])
                ->group('time')
                ->column(
                    'UNIX_TIMESTAMP(FROM_UNIXTIME(`reg_time`, "%Y%m%d")) as time,count(*) as num'
                );
            $job_data = model('Job')
                ->where(['addtime' => ['between time', $daterange]])
                ->group('time')
                ->column(
                    'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
                );
            $resume_data = model('Resume')
                ->where(['addtime' => ['between time', $daterange]])
                ->group('time')
                ->column(
                    'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
                );
            //修改企业数量错误问题
            $company_data = model('Member')
                ->where(['reg_time' => ['between time', $daterange], 'utype' => 1])
                ->group('time')
                ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`reg_time`, "%Y%m%d")) as time,count(*) as num');
        } else {
            if ($type == 'company') {
                $company_data = model('Member')
                    ->where(['reg_time' => ['between time', $daterange], 'utype' => 1])
                    ->group('time')
                    ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`reg_time`, "%Y%m%d")) as time,count(*) as num');
            }
            if ($type == 'personal') {
                $personal_data = model('Member')
                    ->where(['reg_time' => ['between time', $daterange], 'utype' => 2])
                    ->group('time')
                    ->column(
                        'UNIX_TIMESTAMP(FROM_UNIXTIME(`reg_time`, "%Y%m%d")) as time,count(*) as num'
                    );
            }
        }

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            if ($start_time == '' && $end_time == '' && $type == 'personal') {
                $return['series'][] = isset($personal_data[$i])
                    ? $personal_data[$i]
                    : 0;
            } else if ($start_time == '' && $end_time == '' && $type == 'company') {
                $return['series'][] = isset($company_data[$i])
                    ? $company_data[$i]
                    : 0;
            } else {
                $return['series'][2][] = isset($personal_data[$i])
                    ? $personal_data[$i]
                    : 0;
                $return['series'][0][] = isset($company_data[$i])
                    ? $company_data[$i]
                    : 0;
                $return['series'][1][] = isset($job_data[$i])
                    ? $job_data[$i]
                    : 0;
                $return['series'][3][] = isset($resume_data[$i])
                    ? $resume_data[$i]
                    : 0;
            }
        }
        return $return;
    }

    protected function chartOrder($type)
    {
        $return = [
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime('today');
        $starttime = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), 1, date("Y"))));
        $daterange = [$starttime, $endtime + 86400 - 1];
        if ($type == 'num') {
            $order = model('order')
                ->where('paytime', 'between time', $daterange)
                ->group('time')
                ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,count(*) as num');
        } else {
            $order = model('order')
                ->where('paytime', 'between time', $daterange)
                ->group('time')
                ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,sum(amount) as amount');
        }
        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][] = isset($order[$i])
                ? $order[$i]
                : 0;
        }
        return $return;
    }

    /**
     * 待办事项
     */
    protected function getPendingData()
    {
        /**
         * 【ID1000407】【bug】首页提示举报信息与实际数量不对应
         * yx - 2022.11.08
         * [旧]：
         * $tipoff_num = model('Tipoff')->where('status', 0)->->count();
         */

        $join_tablename = 'job';
        $tipoff_job_num = model('Tipoff')->alias('a')
            ->join(config('database.prefix').$join_tablename.' b','a.target_id=b.id','LEFT')
            ->join(config('database.prefix').'member c','a.uid=c.uid','LEFT')
            ->where('a.status', 0)
            ->where('a.type',1)
            ->where('b.id','not null')
            ->where('c.uid','not null')
            ->count();
        $join_tablename = 'resume';
        $tipoff_resume_num = model('Tipoff')->alias('a')
            ->join(config('database.prefix').$join_tablename.' b','a.target_id=b.id','LEFT')
            ->join(config('database.prefix').'member c','a.uid=c.uid','LEFT')
            ->where('a.status', 0)
            ->where('a.type',2)
            ->where('b.id','not null')
            ->where('c.uid','not null')
            ->count();

        $tipoff_num = $tipoff_job_num + $tipoff_resume_num;

        $return = [
            ['title' => '全部待审核企业', 'num' => model('Company')->alias('a')->join(config('database.prefix') . 'company_auth b', 'b.uid=a.uid', 'LEFT')->where('a.audit', 0)->where('b.id', 'not null')->count(), 'alias' => 'all_company_audit'],
            ['title' => '我的待审核企业', 'num' => model('Company')->alias('a')->join(config('database.prefix') . 'company_auth b', 'b.uid=a.uid', 'LEFT')->where('admin_id', $this->admininfo->id)->where('a.audit', 0)->where('b.id', 'not null')->count(), 'alias' => 'my_company_audit'],
            ['title' => '待审核职位', 'num' => model('Job')->where('audit', 0)->count(), 'alias' => 'job_audit'],
            ['title' => '待审核简历', 'num' => model('Resume')->where('audit', 0)->count(), 'alias' => 'resume_audit'],
            ['title' => '企业注销申请', 'num' => model('MemberCancelApply')->where('status', 0)->where('utype', 1)->count(), 'alias' => 'commpany_cancel_apply'],
            ['title' => '个人注销申请', 'num' => model('MemberCancelApply')->where('status', 0)->where('utype', 2)->count(), 'alias' => 'person_cancel_apply'],
            ['title' => '举报信息', 'num' => $tipoff_num, 'alias' => 'tipoff'],
            ['title' => '意见建议', 'num' => model('Feedback')->where('status', 0)->count(), 'alias' => 'feedback']
        ];
        return $return;
    }

    /**
     * 获取服务器信息
     */
    protected function getSystemInfo()
    {
        $return['os'] = PHP_OS;
        $return['php_version'] = PHP_VERSION;
        $version = \think\Db::query("select version() as ver");
        $return['mysql_version'] = $version[0]['ver'];
        $return['web_server'] = $_SERVER['SERVER_SOFTWARE'];
        $return['upload_max'] = get_cfg_var("file_uploads") ? get_cfg_var("upload_max_filesize") : '未知';
        $curl = @curl_version();
        $return['curl_version'] = $curl['version'] ? $curl['version'] : '未知';
        $return['framework_version'] = THINK_VERSION;
        $return['server_time'] = date('Y-m-d H:i');
        return $return;
    }

    /**
     * 实时数据
     */
    protected function getTodayData()
    {
        $timestampToday = strtotime('today');
        $timestampYesterday = $timestampToday - 3600 * 24;
        $return['reg_personal_today'] = model('Member')->where('utype', 2)->where('reg_time', 'egt', $timestampToday)->count();
        $return['reg_personal_yesterday'] = model('Member')->where('utype', 2)->where('reg_time', 'between', [$timestampYesterday, $timestampToday])->count();
        $return['resume_add_today'] = model('Resume')->where('addtime', 'egt', $timestampToday)->count();
        $return['resume_add_yesterday'] = model('Resume')->where('addtime', 'between', [$timestampYesterday, $timestampToday])->count();
        $return['resume_refresh_today'] = model('RefreshResumeLog')->where('addtime', 'egt', $timestampToday)->count('distinct uid');
        $return['resume_refresh_yesterday'] = model('RefreshResumeLog')->where('addtime', 'between', [$timestampYesterday, $timestampToday])->count('distinct uid');
        $return['job_apply_today'] = model('JobApply')->where('addtime', 'egt', $timestampToday)->count();
        $return['job_apply_yesterday'] = model('JobApply')->where('addtime', 'between', [$timestampYesterday, $timestampToday])->count();
        $return['orderpay_personal_today'] = model('Order')->where('utype', 2)->where('paytime', 'egt', $timestampToday)->count();
        $return['orderpay_personal_yesterday'] = model('Order')->where('utype', 2)->where('paytime', 'between', [$timestampYesterday, $timestampToday])->count();

        $return['reg_company_today'] = model('Member')->where('utype', 1)->where('reg_time', 'egt', $timestampToday)->count();
        $return['reg_company_yesterday'] = model('Member')->where('utype', 1)->where('reg_time', 'between', [$timestampYesterday, $timestampToday])->count();
        $return['job_add_today'] = model('Job')->where('addtime', 'egt', $timestampToday)->count();
        $return['job_add_yesterday'] = model('Job')->where('addtime', 'between', [$timestampYesterday, $timestampToday])->count();
        $return['job_refresh_today'] = model('RefreshJobLog')->where('addtime', 'egt', $timestampToday)->count('distinct jobid');
        $return['job_refresh_yesterday'] = model('RefreshJobLog')->where('addtime', 'between', [$timestampYesterday, $timestampToday])->count('distinct jobid');
        $return['down_resume_today'] = model('CompanyDownResume')->where('addtime', 'egt', $timestampToday)->count();
        $return['down_resume_yesterday'] = model('CompanyDownResume')->where('addtime', 'between', [$timestampYesterday, $timestampToday])->count();
        $return['orderpay_company_today'] = model('Order')->where('utype', 1)->where('paytime', 'egt', $timestampToday)->count();
        $return['orderpay_company_yesterday'] = model('Order')->where('utype', 1)->where('paytime', 'between', [$timestampYesterday, $timestampToday])->count();
        return $return;
    }

}
