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
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 图表统计
     */
    public function chart(){
        $type = input('get.type/d', 0, 'intval');
        if($type==1){
            $data = $this->chartDownResume();
        }else if($type==2){
            $data = $this->chartJobApply();
        }else if($type==3){
            $data = $this->chartIncome();
        }else{
            $data = $this->chartReg();
        }
        $this->ajaxReturn(200, '获取数据成功', $data);
    }
    /**
     * 更新日志
     */
    public function upgradeLog(){
        $http = new \app\common\lib\Http;
        $result = $http->get('https://www.74cms.com/api/upgrade_log');
        $result = json_decode($result,1);
        if($result['code']==200){
            $this->ajaxReturn(200, '获取数据成功', $result['data']);
        }else{
            $this->ajaxReturn(200, '获取数据成功', []);
        }
    }
    /**
     * 授权/版权信息
     */
    public function authorize(){
        $http = new \app\common\lib\Http;
        $result = $http->get('https://www.74cms.com/api/authorize_info?domain='.urlencode($_SERVER['HTTP_HOST']));
        $result = json_decode($result,1);
        if($result['code']==200){
            $this->ajaxReturn(200, '获取数据成功', $result['data']);
        }else{
            $this->ajaxReturn(200, '获取数据成功', []);
        }
    }
    /**
     * 官方动态
     */
    public function officialNews(){
        $http = new \app\common\lib\Http;
        $result = $http->get('https://www.74cms.com/api/official_news');
        $result = json_decode($result,1);
        if($result['code']==200){
            $this->ajaxReturn(200, '获取数据成功', $result['data']);
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
        $return[] = ['title'=>'待认证企业','num'=>model('Company')->alias('a')->join(config('database.prefix').'company_auth b','b.uid=a.uid','LEFT')->where('a.audit',0)->where('b.id','not null')->count(),'alias'=>'company_audit'];
        $return[] = ['title'=>'待审核职位','num'=>model('Job')->where('audit',0)->count(),'alias'=>'job_audit'];
        $return[] = ['title'=>'待审核简历','num'=>model('Resume')->where('audit',0)->count(),'alias'=>'resume_audit'];
        $return[] = ['title'=>'注销账号申请','num'=>model('MemberCancelApply')->where('status',0)->count(),'alias'=>'cancel_apply'];
        $return[] = ['title'=>'举报信息','num'=>model('Tipoff')->where('status',0)->count(),'alias'=>'tipoff'];
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
    protected function chartReg()
    {
        $return = [
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime('today');
        $starttime = $endtime - 86400 * 15;
        $daterange = [$starttime, $endtime + 86400 - 1];

        $resume_data = model('Resume')
            ->where('addtime','between time',$daterange)
            ->group('time')
            ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num');

        $company_data = model('Company')
            ->where('addtime','between time',$daterange)
            ->group('time')
            ->column('UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num');

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
    protected function chartDownResume()
    {
        $return = [
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime('today');
        $starttime = $endtime - 86400 * 30;
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
    protected function chartJobApply()
    {
        $return = [
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime('today');
        $starttime = $endtime - 86400 * 30;
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
    protected function chartIncome()
    {
        $return = [
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime('today');
        $starttime = $endtime - 86400 * 30;
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
}
