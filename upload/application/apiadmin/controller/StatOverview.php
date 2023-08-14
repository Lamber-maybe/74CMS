<?php
namespace app\apiadmin\controller;

class StatOverview extends \app\common\controller\Backend
{
    /**
     * 总览数据统计
     */
    public function total()
    {
        $return = $this->numTotal();
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 数字统计
     */
    protected function numTotal(){
        $return['company'] = model('Company')->count();
        $return['job'] = model('Job')->count();
        $return['job_amount'] = model('Job')->sum('amount');
        $return['resume'] = model('Resume')->count();
        return $return;
    }
    /**
     * 总览-已完成订单
     */
    public function order()
    {
        $utype = input('get.utype/d', 1, 'intval');
        $daterange = input('get.daterange/a', []);
        $return = [
            'label' => [],
            'dataset' => []
        ];
        $datalist = model('Order')
            ->where('utype', $utype)
            ->where('status', 1);

        if (!empty($daterange)) {
            $starttime = strtotime($daterange[0]);
            $endtime = strtotime($daterange[1]);
            $daterange = [$starttime, $endtime + 86400 - 1];
            $datalist = $datalist->where('paytime', 'between time', $daterange);
        }
        $datalist = $datalist
            ->group('service_type')
            ->column('service_type,sum(amount) as total');
        if (!empty($datalist)) {
            $service_type_arr = array_merge(
                model('Order')->map_service_type_company,
                model('Order')->map_service_type_personal
            );
            foreach ($datalist as $key => $value) {
                $arr['name'] = $service_type_arr[$key];
                $arr['value'] = $value;
                $return['label'][] = $service_type_arr[$key];
                $return['dataset'][] = $arr;
            }
        }

        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 注册量趋势
     */
    public function reg()
    {
        $platform = input('get.platform/s', '', 'trim');
        $daterange = input('get.daterange/a', []);
        $return = $this->_reg($platform,$daterange);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    protected function _reg($platform='',$daterange=[]){
        $return = [
            'legend' => ['个人数','简历数','企业数','职位数'],
            'xAxis' => [],
            'series' => []
        ];
        if (!empty($daterange)) {
            $starttime = strtotime($daterange[0]);
            $endtime = strtotime($daterange[1]);
        } else {
            $endtime = strtotime('today');
            $starttime = $endtime - 86400 * 30;
        }
        $daterange = [$starttime, $endtime + 86400 - 1];
        $member_data = model('Member')->where(
            'reg_time',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $member_data = $member_data->where('platform', $platform);
        }
        $member_data = $member_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`reg_time`, "%Y%m%d")) as time,count(*) as num'
            );

        $resume_data = model('Resume')->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $resume_data = $resume_data->where('platform', $platform);
        }
        $resume_data = $resume_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        $company_data = model('Company')->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $company_data = $company_data->where('platform', $platform);
        }
        $company_data = $company_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        $job_data = model('Job')->where('addtime', 'between time', $daterange);
        if ($platform != '') {
            $job_data = $job_data->where('platform', $platform);
        }
        $job_data = $job_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][0][] = isset($member_data[$i])
                ? $member_data[$i]
                : 0;
            $return['series'][1][] = isset($resume_data[$i])
                ? $resume_data[$i]
                : 0;
            $return['series'][2][] = isset($company_data[$i])
                ? $company_data[$i]
                : 0;
            $return['series'][3][] = isset($job_data[$i]) ? $job_data[$i] : 0;
        }
        return $return;
    }
    /**
     * 活跃度分析
     */
    public function active()
    {
        $platform = input('get.platform/s', '', 'trim');
        $daterange = input('get.daterange/a', []);
        $return = $this->_active($platform,$daterange);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    protected function _active($platform='',$daterange=[])
    {
        $return = [
            'legend' => ['刷新简历','发布职位','刷新职位','投递简历','下载简历','会员登录'],
            'xAxis' => [],
            'series' => []
        ];
        if (!empty($daterange)) {
            $starttime = strtotime($daterange[0]);
            $endtime = strtotime($daterange[1]);
        } else {
            $endtime = strtotime('today');
            $starttime = $endtime - 86400 * 30;
        }
        $daterange = [$starttime, $endtime + 86400 - 1];
        $refresh_resume_data = model('RefreshResumeLog')->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $refresh_resume_data = $refresh_resume_data->where(
                'platform',
                $platform
            );
        }
        $refresh_resume_data = $refresh_resume_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        $job_add_data = model('Job')->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $job_add_data = $job_add_data->where('platform', $platform);
        }
        $job_add_data = $job_add_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        $refresh_job_data = model('RefreshJobLog')->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $refresh_job_data = $refresh_job_data->where('platform', $platform);
        }
        $refresh_job_data = $refresh_job_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        $job_apply_data = model('JobApply')->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $job_apply_data = $job_apply_data->where('platform', $platform);
        }
        $job_apply_data = $job_apply_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        $down_resume_data = model('CompanyDownResume')->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $down_resume_data = $down_resume_data->where('platform', $platform);
        }
        $down_resume_data = $down_resume_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        $member_login_data = model('MemberActionLog')->where('is_login',1)->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $member_login_data = $member_login_data->where(
                'platform',
                $platform
            );
        }
        $member_login_data = $member_login_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );
        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][0][] = isset($refresh_resume_data[$i])
                ? $refresh_resume_data[$i]
                : 0;
            $return['series'][1][] = isset($job_add_data[$i])
                ? $job_add_data[$i]
                : 0;
            $return['series'][2][] = isset($refresh_job_data[$i])
                ? $refresh_job_data[$i]
                : 0;
            $return['series'][3][] = isset($job_apply_data[$i])
                ? $job_apply_data[$i]
                : 0;
            $return['series'][4][] = isset($down_resume_data[$i])
                ? $down_resume_data[$i]
                : 0;
            $return['series'][5][] = isset($member_login_data[$i])
                ? $member_login_data[$i]
                : 0;
        }
        return $return;
    }
}
