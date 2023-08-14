<?php
namespace app\apiadmin\controller;

class StatResumeOverview extends \app\common\controller\Backend
{
    /**
     * 简历总览-性别分布
     */
    public function sex()
    {
        $return = [
            'dataset' => []
        ];
        $datalist = model('Resume')
            ->where('sex', 'GT', 0)
            ->group('sex')
            ->column('sex,count(*) as total');
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['name'] = $key == 1 ? '男' : '女';
                $arr['value'] = $value;
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 简历总览-年龄分布
     */
    public function age()
    {
        $year = date('Y');
        $field =
            'count(*) as num,CASE 
                WHEN CONVERT(birthday, UNSIGNED)<=' .
            ($year - 16) .
            ' AND CONVERT(birthday, UNSIGNED) >' .
            ($year - 20) .
            ' THEN "16-20岁"
            WHEN CONVERT(birthday, UNSIGNED)<=' .
            ($year - 20) .
            ' AND CONVERT(birthday, UNSIGNED) >' .
            ($year - 30) .
            ' THEN "21-30岁"
            WHEN CONVERT(birthday, UNSIGNED)<=' .
            ($year - 30) .
            ' AND CONVERT(birthday, UNSIGNED) >' .
            ($year - 40) .
            ' THEN "31-40岁"
            WHEN CONVERT(birthday, UNSIGNED)<=' .
            ($year - 40) .
            ' AND CONVERT(birthday, UNSIGNED) >' .
            ($year - 50) .
            ' THEN "41-50岁"
            ELSE "50岁以上"
            END AS age_cn';

        $list = [];
        $data = model('Resume')
            ->field($field)
            ->group('age_cn')
            ->select();
        if ($data) {
            $data = collection($data)->toArray();
            foreach ($data as $key => $value) {
                $list[$value['age_cn']] = $value['num'];
            }
        }
        $return = [
            'dataset' => [
                [
                    'name' => '16-20岁',
                    'value' => isset($list['16-20岁']) ? $list['16-20岁'] : 0
                ],
                [
                    'name' => '21-30岁',
                    'value' => isset($list['21-30岁']) ? $list['21-30岁'] : 0
                ],
                [
                    'name' => '31-40岁',
                    'value' => isset($list['31-40岁']) ? $list['31-40岁'] : 0
                ],
                [
                    'name' => '41-50岁',
                    'value' => isset($list['41-50岁']) ? $list['41-50岁'] : 0
                ],
                [
                    'name' => '50岁以上',
                    'value' => isset($list['50岁以上']) ? $list['50岁以上'] : 0
                ]
            ]
        ];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 简历总览-学历分布
     */
    public function edu()
    {
        $return = [
            'dataset' => []
        ];
        $datalist = model('Resume')
            ->group('education')
            ->column('education,count(*) as total');
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['name'] = isset(model('BaseModel')->map_education[$key])
                    ? model('BaseModel')->map_education[$key]
                    : '';
                $arr['value'] = $value;
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 简历总览-经验分布
     */
    public function exp()
    {
        $field =
            'count(*) as num,CASE 
                WHEN enter_job_time=0 
                THEN "应届生"
            WHEN enter_job_time>' .
            strtotime('-2 year') .
            ' THEN "一年"
            WHEN enter_job_time>' .
            strtotime('-3 year') .
            ' AND enter_job_time<=' .
            strtotime('-2 year') .
            ' THEN "二年"
            WHEN enter_job_time>' .
            strtotime('-4 year') .
            ' AND enter_job_time<=' .
            strtotime('-3 year') .
            ' THEN "三年"
            WHEN enter_job_time>' .
            strtotime('-5 year') .
            ' AND enter_job_time<=' .
            strtotime('-4 year') .
            ' THEN "三年-五年"
            WHEN enter_job_time>' .
            strtotime('-10 year') .
            ' AND enter_job_time<=' .
            strtotime('-5 year') .
            ' THEN "五年-十年"
            ELSE "十年以上"
            END AS exp_cn';

        $list = [];
        $data = model('Resume')
            ->field($field)
            ->group('exp_cn')
            ->select();
        if ($data) {
            $data = collection($data)->toArray();
            foreach ($data as $key => $value) {
                $list[$value['exp_cn']] = $value['num'];
            }
        }
        $return = [
            'dataset' => [
                [
                    'name' => '应届生',
                    'value' => isset($list['应届生']) ? $list['应届生'] : 0
                ],
                [
                    'name' => '一年',
                    'value' => isset($list['一年']) ? $list['一年'] : 0
                ],
                [
                    'name' => '二年',
                    'value' => isset($list['二年']) ? $list['二年'] : 0
                ],
                [
                    'name' => '三年',
                    'value' => isset($list['三年']) ? $list['三年'] : 0
                ],
                [
                    'name' => '三年-五年',
                    'value' => isset($list['三年-五年'])
                        ? $list['三年-五年']
                        : 0
                ],
                [
                    'name' => '五年-十年',
                    'value' => isset($list['五年-十年'])
                        ? $list['五年-十年']
                        : 0
                ],
                [
                    'name' => '十年以上',
                    'value' => isset($list['十年以上']) ? $list['十年以上'] : 0
                ]
            ]
        ];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 简历总览-意向地区分布
     */
    public function intentionDistrict()
    {
        $return = [
            'label' => [],
            'dataset' => []
        ];
        $datalist = model('ResumeIntention')
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
     * 简历总览-意向岗位分布
     */
    public function intentionJobcategory()
    {
        $return = [
            'label' => [],
            'dataset' => []
        ];
        $datalist = model('ResumeIntention')
            ->group('category')
            ->order('total desc')
            ->column('category,count(*) as total');
        if (!empty($datalist)) {
            $category_job_data = model('CategoryJob')->getCache();
            $number = 1;
            foreach ($datalist as $key => $value) {
                if ($number > 30) {
                    break;
                }
                $arr['number'] = $number;
                $arr['name'] = isset($category_job_data[$key])
                    ? $category_job_data[$key]
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
     * 简历总览-求职者活跃度分析
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
            ->where('utype', 2)
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

        $refresh_resume_person_data_tmp = model('RefreshResumeLog')->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $refresh_resume_person_data_tmp = $refresh_resume_person_data_tmp->where(
                'platform',
                $platform
            );
        }
        $refresh_resume_person_data_tmp = $refresh_resume_person_data_tmp
            ->group('time')
            ->column(
                'CONCAT(uid,"-",UNIX_TIMESTAMP(FROM_UNIXTIME(`addtime`, "%Y%m%d"))) as time,count(DISTINCT uid) as num'
            );
        $refresh_resume_person_data = [];
        foreach ($refresh_resume_person_data_tmp as $key => $value) {
            $arr = explode('-', $key);
            $refresh_resume_person_data[$arr[1]] = $value;
        }

        $refresh_resume_times_data = model('RefreshResumeLog')->where(
            'addtime',
            'between time',
            $daterange
        );
        if ($platform != '') {
            $refresh_resume_times_data = $refresh_resume_times_data->where(
                'platform',
                $platform
            );
        }
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
        if ($platform != '') {
            $job_apply_data = $job_apply_data->where('platform', $platform);
        }
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
            $return['series'][1][] = isset($refresh_resume_person_data[$i])
                ? $refresh_resume_person_data[$i]
                : 0;
            $return['series'][2][] = isset($refresh_resume_times_data[$i])
                ? $refresh_resume_times_data[$i]
                : 0;
            $return['series'][3][] = isset($job_apply_data[$i])
                ? $job_apply_data[$i]
                : 0;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 简历总览-简历新增趋势
     */
    public function resumeAdd()
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
            ->where('utype', 2)
            ->where('reg_time', 'between time', $daterange);
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

        for ($i = $starttime; $i <= $endtime; $i += 86400) {
            $return['xAxis'][] = date('m/d', $i);
            $return['series'][0][] = isset($member_data[$i])
                ? $member_data[$i]
                : 0;
            $return['series'][1][] = isset($resume_data[$i])
                ? $resume_data[$i]
                : 0;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
