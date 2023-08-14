<?php
namespace app\apiadmin\controller;

class StatCompanyOverview extends \app\common\controller\Backend
{
    /**
     * 企业总览-企业性质分布
     */
    public function nature()
    {
        $return = [
            'dataset' => []
        ];
        $datalist = model('Company')
            ->where('nature', 'GT', 0)
            ->group('nature')
            ->column('nature,count(*) as total');
        if (!empty($datalist)) {
            $category_data = model('Category')->getCache();
            foreach ($datalist as $key => $value) {
                $arr['name'] = isset($category_data['QS_company_type'][$key])
                    ? $category_data['QS_company_type'][$key]
                    : '';
                $arr['value'] = $value;
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 企业总览-企业规模分布
     */
    public function scale()
    {
        $return = [
            'dataset' => []
        ];
        $datalist = model('Company')
            ->where('scale', 'GT', 0)
            ->group('scale')
            ->column('scale,count(*) as total');
        if (!empty($datalist)) {
            $category_data = model('Category')->getCache();
            foreach ($datalist as $key => $value) {
                $arr['name'] = isset($category_data['QS_scale'][$key])
                    ? $category_data['QS_scale'][$key]
                    : '';
                $arr['value'] = $value;
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 企业总览-企业认证分布
     */
    public function audit()
    {
        $return = [
            'dataset' => []
        ];
        $datalist = model('Company')
            ->group('audit')
            ->column('audit,count(*) as total');
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['name'] = isset(model('Company')->map_audit[$key])
                    ? model('Company')->map_audit[$key]
                    : '';
                $arr['value'] = $value;
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 企业总览-会员企业分布
     */
    public function setmeal()
    {
        $return = [
            'dataset' => []
        ];
        $datalist = model('MemberSetmeal')
            ->alias('a')
            ->join(
                config('database.prefix') . 'setmeal b',
                'a.setmeal_id=b.id',
                'LEFT'
            )
            ->group('a.setmeal_id')
            ->column('a.setmeal_id,b.name,count(*) as total');
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['name'] = $value['name'];
                $arr['value'] = $value['total'];
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 企业地区分布
     */
    public function district()
    {
        $return = [
            'label' => [],
            'dataset' => []
        ];
        $datalist = model('Company')
            ->group('district')
            ->order('total desc')
            ->column('district,count(*) as total');
        if (!empty($datalist)) {
            $category_district_data = model('CategoryDistrict')->getCache();
            $number = 1;
            foreach ($datalist as $key => $value) {
                if ($number > 30) {
                    break;
                }
                $arr['number'] = $number;
                $arr['name'] = isset($category_district_data[$key])
                    ? $category_district_data[$key]
                    : '';
                $arr['value'] = $value;
                $return['label'][] = $arr['name'];
                $return['dataset'][] = $arr;
                $number++;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 企业行业分布TOP10
     */
    public function trade()
    {
        $return = [
            'label' => [],
            'dataset' => []
        ];
        $datalist = model('Company')
            ->group('trade')
            ->order('total desc')
            ->column('trade,count(*) as total');
        if (!empty($datalist)) {
            $categoryt_data = model('Category')->getCache();
            $number = 1;
            foreach ($datalist as $key => $value) {
                if ($number > 10) {
                    break;
                }
                $arr['number'] = $number;
                $arr['name'] = isset($categoryt_data['QS_trade'][$key])
                    ? $categoryt_data['QS_trade'][$key]
                    : '';
                $arr['value'] = $value;
                $return['label'][] = $arr['name'];
                $return['dataset'][] = $arr;
                $number++;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 企业新增趋势
     */
    public function comAdd()
    {
        $platform = input('get.platform/s', '', 'trim');
        $daterange = input('get.daterange/a', []);
        $return = [
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

        $member_data = model('Member')
            ->where('utype', 1)
            ->where('reg_time', 'between time', $daterange);
        if ($platform != '') {
            $member_data = $member_data->where('platform', $platform);
        }
        $member_data = $member_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`reg_time`, "%Y%m%d")) as time,count(*) as num'
            );

        $audit_data = model('CompanyAuthLog')
            ->where('audit', 1)
            ->where('addtime', 'between time', $daterange)
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][0][] = isset($member_data[$i])
                ? $member_data[$i]
                : 0;
            $return['series'][1][] = isset($audit_data[$i])
                ? $audit_data[$i]
                : 0;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 企业活跃度分析
     */
    public function active()
    {
        $platform = input('get.platform/s', '', 'trim');
        $daterange = input('get.daterange/a', []);
        $return = [
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

        $member_login_data = model('MemberActionLog')
            ->where('is_login', 1)
            ->where('utype', 1)
            ->where('addtime', 'between time', $daterange);
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

        $add_job_data = model('Job')->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $add_job_data = $add_job_data->where('platform', $platform);
        }
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
        if ($platform != '') {
            $refresh_job_data = $refresh_job_data->where('platform', $platform);
        }
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
        if ($platform != '') {
            $down_resume_data = $down_resume_data->where('platform', $platform);
        }
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
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
