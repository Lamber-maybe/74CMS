<?php
namespace app\apiadmin\controller;

class StatJobOverview extends \app\common\controller\Backend
{
    /**
     * 职位总览-学历要求分布
     */
    public function edu()
    {
        $return = [
            'dataset' => []
        ];
        $datalist = model('Job')
            ->group('education')
            ->column('education,count(*) as total');
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['name'] = isset(model('BaseModel')->map_education[$key])
                    ? model('BaseModel')->map_education[$key]
                    : '不限';
                $arr['value'] = $value;
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 职位总览-经验要求分布
     */
    public function exp()
    {
        $return = [
            'dataset' => []
        ];
        $datalist = model('Job')
            ->group('experience')
            ->column('experience,count(*) as total');
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['name'] = isset(model('BaseModel')->map_experience[$key])
                    ? model('BaseModel')->map_experience[$key]
                    : '不限';
                $arr['value'] = $value;
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 职位总览-工作性质分布
     */
    public function nature()
    {
        $return = [
            'dataset' => []
        ];
        $datalist = model('Job')
            ->group('nature')
            ->column('nature,count(*) as total');
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['name'] = isset(model('Job')->map_nature[$key])
                    ? model('Job')->map_nature[$key]
                    : '不限';
                $arr['value'] = $value;
                $return['dataset'][] = $arr;
            }
        }
        
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 职位总览-薪资分布
     */
    public function wage()
    {
        $field = 'count(*) as num,CASE 
                WHEN negotiable=1 
                THEN "面议"
            WHEN maxwage<1500
                THEN "1500以下"
            WHEN maxwage>=1500 and minwage<3000
                THEN "1500-3000"
            WHEN maxwage>=3000 and minwage<5000
                THEN "3000-5000"
            WHEN maxwage>=5000 and minwage<8000
                THEN "5000-8000"
            WHEN maxwage>=8000 and minwage<10000
                THEN "8000-10000"
            WHEN maxwage>=10000 and minwage<15000
                THEN "10000-15000"
            ELSE "15000以上"
            END AS wage_cn';
        $return = [
            'dataset' => []
        ];
        $datalist = model('Job')
            ->field($field)
            ->group('wage_cn')
            ->select();
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['name'] = $value['wage_cn'];
                $arr['value'] = $value['num'];
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 职位地区分布
     */
    public function district()
    {
        $return = [
            'label' => [],
            'dataset' => []
        ];
        $datalist = model('Job')
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
     * 职能分类分布TOP10
     */
    public function jobcategory()
    {
        $return = [
            'label' => [],
            'dataset' => []
        ];
        $datalist = model('Job')
            ->group('category')
            ->order('total desc')
            ->column('category,count(*) as total');
        if (!empty($datalist)) {
            $categoryt_job_data = model('CategoryJob')->getCache();
            $number = 1;
            foreach ($datalist as $key => $value) {
                if ($number > 10) {
                    break;
                }
                $arr['number'] = $number;
                $arr['name'] = isset($categoryt_job_data[$key])
                    ? $categoryt_job_data[$key]
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
     * 职位趋势
     */
    public function jobAdd()
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

        $refresh_job_data = model('RefreshResumeLog')->where(
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

        $job_stick_data = model('Order')
            ->where('service_type', 'jobstick')
            ->where('status', 1)
            ->where('paytime', 'between time', $daterange);
        if ($platform != '') {
            $job_stick_data = $job_stick_data->where('pay_platform', $platform);
        }
        $job_stick_data = $job_stick_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,count(*) as num'
            );

        $job_emergency_data = model('Order')
            ->where('service_type', 'emergency')
            ->where('status', 1)
            ->where('paytime', 'between time', $daterange);
        if ($platform != '') {
            $job_emergency_data = $job_emergency_data->where(
                'pay_platform',
                $platform
            );
        }
        $job_emergency_data = $job_emergency_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`paytime`, "%Y%m%d")) as time,count(*) as num'
            );

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][0][] = isset($job_add_data[$i])
                ? $job_add_data[$i]
                : 0;
            $return['series'][1][] = isset($refresh_job_data[$i])
                ? $refresh_job_data[$i]
                : 0;
            $return['series'][2][] = isset($job_stick_data[$i])
                ? $job_stick_data[$i]
                : 0;
            $return['series'][3][] = isset($job_emergency_data[$i])
                ? $job_emergency_data[$i]
                : 0;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 职位活跃度分析
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

        $apply_data = model('JobApply')->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $apply_data = $apply_data->where('platform', $platform);
        }
        $apply_data = $apply_data
            ->group('time')
            ->column(
                'UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d")) as time,count(*) as num'
            );

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][0][] = isset($refresh_job_data[$i])
                ? $refresh_job_data[$i]
                : 0;
            $return['series'][1][] = isset($apply_data[$i])
                ? $apply_data[$i]
                : 0;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
