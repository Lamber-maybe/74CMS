<?php
namespace app\apiadmin\controller;

class Index extends \app\common\controller\Backend
{
    /**
     * 首页基本信息
     */
    public function index()
    {
        $return['today_data'] = $this->getTodayData();
        $return['pending_data'] = $this->getPendingData();
        $return['server_info'] = $this->getSystemInfo();
        $return['version'] = config('version.version');
        $return['warning'] = [
            'rewrite'=>$this->checkRewrite(),
            'install'=>$this->checkInstall(),
        ];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 检测安装文件
     */
    protected function checkInstall(){
        if(is_dir(PUBLIC_PATH.'install')){
            return 1;
        }else{
            return 0;
        }
    }
    /**
     * 检测伪静态
     */
    protected function checkRewrite(){
        $result = file_get_contents(config('global_config.sitedomain').config('global_config.sitedir').'apiadmin/login/userinfo');
        if($result===false){
            return 1;
        }else{
            return 0;
        }
    }
    /**
     * 图表统计
     */
    public function chart(){
        $type = input('get.type/d', 0, 'intval');
        if($type==1){
            $data = $this->chartDownResume(30);
        }else if($type==2){
            $data = $this->chartJobApply(30);
        }else if($type==3){
            $data = $this->chartIncome(30);
        }else{
            $data = $this->chartReg(15);
        }
        $this->ajaxReturn(200, '获取数据成功', $data);
    }
    /**
     * 官方数据
     */
    public function officialData(){
        $http = new \app\common\lib\Http;
        $result = $http->get('https://www.74cms.com/api/official_data?domain='.urlencode($_SERVER['HTTP_HOST']));
        $result = json_decode($result,1);
        if($result['code']==200){
            $return = [
                'upgrade_log'=>$result['data']['upgrade_log'],
                'authorize_info'=>$result['data']['authorize_info'],
                'official_news'=>$result['data']['official_news'],
                'latest_version_text'=>$result['data']['latest_version_text']
            ];
            $ver = explode('.', config('version.version'));
            $version_int_1 = str_pad($ver[0], 2, '0', STR_PAD_LEFT);
            $version_int_2 = str_pad($ver[1], 2, '0', STR_PAD_LEFT);
            $version_int_3 = str_pad($ver[2], 3, '0', STR_PAD_LEFT);
            $current_version = $version_int_1 . $version_int_2 . $version_int_3;
            $return['new_version_notice'] = $result['data']['latest_version']>$current_version?1:0;
            $this->ajaxReturn(200, '获取数据成功', $return);
        }else{
            $this->ajaxReturn(200, '获取数据成功', []);
        }
    }
    /**
     * 获取今日统计信息
     */
    protected function getTodayData(){
        $timestampToday = strtotime('today');
        $timestampYesterday = $timestampToday-3600*24;
        $return['reg_personal_today'] = model('Member')->where('utype',2)->where('reg_time','egt',$timestampToday)->count();
        $return['reg_personal_yesterday'] = model('Member')->where('utype',2)->where('reg_time','between',[$timestampYesterday,$timestampToday])->count();
        $return['resume_add_today'] = model('Resume')->where('addtime','egt',$timestampToday)->count();
        $return['resume_add_yesterday'] = model('Resume')->where('addtime','between',[$timestampYesterday,$timestampToday])->count();
        $return['resume_refresh_today'] = model('RefreshResumeLog')->where('addtime','egt',$timestampToday)->count('distinct uid');
        $return['resume_refresh_yesterday'] = model('RefreshResumeLog')->where('addtime','between',[$timestampYesterday,$timestampToday])->count('distinct uid');
        $return['job_apply_today'] = model('JobApply')->where('addtime','egt',$timestampToday)->count();
        $return['job_apply_yesterday'] = model('JobApply')->where('addtime','between',[$timestampYesterday,$timestampToday])->count();
        $return['orderpay_personal_today'] = model('Order')->where('utype',2)->where('paytime','egt',$timestampToday)->count();
        $return['orderpay_personal_yesterday'] = model('Order')->where('utype',2)->where('paytime','between',[$timestampYesterday,$timestampToday])->count();

        $return['reg_company_today'] = model('Member')->where('utype',1)->where('reg_time','egt',$timestampToday)->count();
        $return['reg_company_yesterday'] = model('Member')->where('utype',1)->where('reg_time','between',[$timestampYesterday,$timestampToday])->count();
        $return['job_add_today'] = model('Job')->where('addtime','egt',$timestampToday)->count();
        $return['job_add_yesterday'] = model('Job')->where('addtime','between',[$timestampYesterday,$timestampToday])->count();
        $return['job_refresh_today'] = model('RefreshJobLog')->where('addtime','egt',$timestampToday)->count('distinct jobid');
        $return['job_refresh_yesterday'] = model('RefreshJobLog')->where('addtime','between',[$timestampYesterday,$timestampToday])->count('distinct jobid');
        $return['down_resume_today'] = model('CompanyDownResume')->where('addtime','egt',$timestampToday)->count();
        $return['down_resume_yesterday'] = model('CompanyDownResume')->where('addtime','between',[$timestampYesterday,$timestampToday])->count();
        $return['orderpay_company_today'] = model('Order')->where('utype',1)->where('paytime','egt',$timestampToday)->count();
        $return['orderpay_company_yesterday'] = model('Order')->where('utype',1)->where('paytime','between',[$timestampYesterday,$timestampToday])->count();
        return $return;
    }
    /**
     * 获取待办信息
     */
    protected function getPendingData(){
        /**
         * 【ID1000407】【bug】首页提示举报信息与实际数量不对应
         * yx - 2022.11.08
         * [旧]：
         * $tipoff_num = model('Tipoff')->where('status', 0)->->count();
         */
        $job_tip = model('Tipoff')
            ->alias('t')
            ->join('member m', 'm.uid = t.uid', 'LEFT')
            ->join('job_search_rtime j', 'j.id = t.target_id', 'LEFT')
            ->where('t.type', 1)
            ->where('t.status', 0)
            ->where('m.uid', 'NOT NULL')
            ->where('j.id', 'NOT NULL')
            ->count('t.id');
        $resume_tip = model('Tipoff')
            ->alias('t')
            ->join('member m', 'm.uid = t.uid', 'LEFT')
            ->join('resume_search_rtime r', 'r.id = t.target_id', 'LEFT')
            ->where('t.type', 2)
            ->where('t.status', 0)
            ->where('m.uid', 'NOT NULL')
            ->where('r.id', 'NOT NULL')
            ->count('t.id');
        $tipoff_num = $job_tip + $resume_tip;
        $return[] = ['title'=>'全部待审核企业','num'=>model('Company')->alias('a')->join(config('database.prefix').'company_auth b','b.uid=a.uid','LEFT')->where('a.audit',0)->where('b.id','not null')->count(),'alias'=>'all_company_audit'];
        $return[] = ['title'=>'我的待审核企业','num'=>model('Company')->alias('a')->join(config('database.prefix').'company_auth b','b.uid=a.uid','LEFT')->where('admin_id',$this->admininfo->id)->where('a.audit',0)->where('b.id','not null')->count(),'alias'=>'my_company_audit'];
        $return[] = ['title'=>'待审核职位','num'=>model('Job')->where('audit',0)->count(),'alias'=>'job_audit'];
        $return[] = ['title'=>'待审核简历','num'=>model('Resume')->where('audit',0)->count(),'alias'=>'resume_audit'];
        $return[] = ['title'=>'注销账号申请','num'=>model('MemberCancelApply')->where('status',0)->count(),'alias'=>'cancel_apply'];
        $return[] = ['title'=>'举报信息','num'=>$tipoff_num,'alias'=>'tipoff'];
        $return[] = ['title'=>'意见建议','num'=>model('Feedback')->where('status',0)->count(),'alias'=>'feedback'];
        return $return;
    }
    /**
     * 获取服务器信息
     */
    protected function getSystemInfo(){
        $return['os'] = PHP_OS;
        $return['php_version'] = PHP_VERSION;
        $version = \think\Db::query("select version() as ver");
        $return['mysql_version'] = $version[0]['ver'];
        $return['web_server'] = $_SERVER['SERVER_SOFTWARE'];
        $return['upload_max'] = get_cfg_var("file_uploads") ? get_cfg_var("upload_max_filesize"):'未知';
        $curl = @curl_version();
        $return['curl_version'] = $curl['version'] ? $curl['version']:'未知';
        $return['framework_version'] = THINK_VERSION;
        $return['server_time'] = date('Y-m-d H:i');
        return $return;
    }
    /**
     * 注册量趋势
     */
    protected function chartReg($days)
    {
        $return = [
            'legend' => ['个人','企业'],
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime('today');
        $starttime = $endtime - 86400 * $days;
        $daterange = [$starttime, $endtime + 86400 - 1];
        //添加utype=2条件，解决后台首页注册统计图(当前是否为个人数 = 个人+企业数)问题
        $resume_data = model('Member')
            ->where(['reg_time'=>['between time',$daterange],'utype'=>2])
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`reg_time`, "%Y%m%d")) as time,count(*) as num'
            );

        //修改企业数量错误问题
        $company_data = model('Member')
            ->where(['reg_time'=>['between time',$daterange],'utype'=>1])
            ->group('time')
            ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`reg_time`, "%Y%m%d")) as time,count(*) as num');

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][0][] = isset($resume_data[$i])
                ? $resume_data[$i]
                : 0;
            $return['series'][1][] = isset($company_data[$i])
                ? $company_data[$i]
                : 0;
        }
        return $return;
    }
    /**
     * 下载简历趋势
     */
    protected function chartDownResume($days)
    {
        $return = [
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime('today');
        $starttime = $endtime - 86400 * $days;
        $daterange = [$starttime, $endtime + 86400 - 1];

        $down_resume_data = model('CompanyDownResume')
            ->where('addtime','between time',$daterange)
            ->group('time')
            ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num');

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][] = isset($down_resume_data[$i])
                ? $down_resume_data[$i]
                : 0;
        }
        return $return;
    }
    /**
     * 投递职位趋势
     */
    protected function chartJobApply($days)
    {
        $return = [
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime('today');
        $starttime = $endtime - 86400 * $days;
        $daterange = [$starttime, $endtime + 86400 - 1];

        $job_apply_data = model('JobApply')
            ->where('addtime','between time',$daterange)
            ->group('time')
            ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num');

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][] = isset($job_apply_data[$i])
                ? $job_apply_data[$i]
                : 0;
        }
        return $return;
    }
    /**
     * 收入趋势
     */
    protected function chartIncome($days)
    {
        $return = [
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime('today');
        $starttime = $endtime - 86400 * $days;
        $daterange = [$starttime, $endtime + 86400 - 1];

        $payment_data = model('Order')
            ->where('paytime','between time',$daterange)
            ->group('time')
            ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,count(*) as num');

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][] = isset($payment_data[$i])
                ? $payment_data[$i]
                : 0;
        }
        return $return;
    }
    /**
     * 今日收入
     */
    protected function todayIncomeTotal(){
        $timestampToday = strtotime('today');
        $return['personalTodayNewOrderNum'] = model('Order')->where('utype',2)->where('addtime','egt',$timestampToday)->count();
        $return['personalTodayPayOrderNum'] = model('Order')->where('utype',2)->where('paytime','egt',$timestampToday)->count();
        $return['personalTodayPayOrderAmount'] = model('Order')->where('utype',2)->where('paytime','egt',$timestampToday)->sum('amount');
        $return['companyTodayNewOrderNum'] = model('Order')->where('utype',1)->where('addtime','egt',$timestampToday)->count();
        $return['companyTodayPayOrderNum'] = model('Order')->where('utype',1)->where('paytime','egt',$timestampToday)->count();
        $return['companyTodayPayOrderAmount'] = model('Order')->where('utype',1)->where('paytime','egt',$timestampToday)->sum('amount');
        return $return;
    }
    /**
     * 下载和投递趋势(合并显示)
     */
    protected function chartDownAndApply($days)
    {
        $return = [
            'legend' => ['下载量','投递量'],
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime('today');
        $starttime = $endtime - 86400 * $days;
        $daterange = [$starttime, $endtime + 86400 - 1];

        $down_resume_data = model('CompanyDownResume')
            ->where('addtime','between time',$daterange)
            ->group('time')
            ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num');

        $job_apply_data = model('JobApply')
            ->where('addtime','between time',$daterange)
            ->group('time')
            ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num');

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][0][] = isset($down_resume_data[$i])
                ? $down_resume_data[$i]
                : 0;
            $return['series'][1][] = isset($job_apply_data[$i])
                ? $job_apply_data[$i]
                : 0;
        }
        return $return;
    }
}
