<?php
namespace app\v1_0\controller\home;

class Screen extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $screen_token = input('get.token/s','','trim');
        if(config('global_config.screen_token')=='' || $screen_token!=config('global_config.screen_token')){
            $this->ajaxReturn(500,'token无效，请联系网站管理员');
        }
        $return['title'] = config('global_config.screen_title');
        $return['sex'] = $this->sex();
        $return['age'] = $this->age();
        $return['edu'] = $this->edu();
        $return['exp'] = $this->exp();
        $return['intention'] = $this->intentionJobcategory();
        $return['total'] = $this->numTotal();
        $return['active'] = $this->active();
        $return['personal_event'] = $this->personalEventList();
        $return['company_event'] = $this->companyEventList();
        $return['nature'] = $this->nature();
        $return['scale'] = $this->scale();
        $return['hotjob'] = $this->hotjob();
        $return['district'] = $this->companyDistrict();
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
    /**
     * 数字统计
     */
    protected function numTotal(){
        $screen_base = config('global_config.screen_base');
        $screen_base = explode(",",$screen_base);
        $return['company'] = model('Company')->count() + $screen_base[0];
        $return['job'] = model('Job')->count() + $screen_base[1];
        $return['job_amount'] = model('Job')->sum('amount') + $screen_base[2];
        $return['resume'] = model('Resume')->count() + $screen_base[3];
        return $return;
    }
    /**
     * 性别分布
     */
    protected function sex()
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
        return $return;
    }
    /**
     * 年龄分布
     */
    protected function age()
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
        return $return;
    }
    /**
     * 学历分布
     */
    protected function edu()
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
        return $return;
    }
    /**
     * 经验分布
     */
    protected function exp()
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
            ELSE "其他"
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
                    'name' => '其他',
                    'value' => isset($list['其他']) ? $list['其他'] : 0
                ]
            ]
        ];
        return $return;
    }
    /**
     * 意向岗位分布
     */
    protected function intentionJobcategory()
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
                if ($number > 8) {
                    break;
                }
                $arr['name'] = isset($category_job_data[$key])
                    ? $category_job_data[$key]
                    : '';
                $arr['value'] = $value;
                $return['dataset'][] = $arr;
                $number++;
            }
        }
        return $return;
    }
    /**
     * 活跃度分析
     */
    protected function active()
    {
        $return = [
            'legend' => ['刷新简历','发布职位','刷新职位','投递简历','下载简历','会员登录'],
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime('today');
        $starttime = $endtime - 86400 * 30;
        $daterange = [$starttime, $endtime + 86400 - 1];
        $refresh_resume_data = model('RefreshResumeLog')->where(
            'addtime',
            'between time',
            $daterange
        );
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
    /**
     * 企业性质分布
     */
    protected function nature()
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
        return $return;
    }
    /**
     * 企业规模分布
     */
    protected function scale()
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
        return $return;
    }
    /**
     * 热门职位排行
     */
    protected function hotjob()
    {
        $where = [];
        $return = [
            'dataset' => []
        ];
        $datalist = model('JobApply')
            ->alias('a')
            ->join(
                config('database.prefix') . 'job b',
                'a.jobid=b.id',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'company c',
                'a.comid=c.id',
                'LEFT'
            )
            ->where($where)
            ->group('a.jobid')
            ->order('total desc,b.refreshtime desc')
            ->limit(10)
            ->column(
                'b.jobname,c.companyname,count(*) as total'
            );

        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['jobname'] = $value['jobname'];
                $arr['companyname'] = $value['companyname'];
                $arr['total'] = $value['total'];
                $return['dataset'][] = $arr;
            }
        }
        return $return;
    }
    /**
     * 企业地区分布
     */
    protected function companyDistrict()
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
                if ($number > 6) {
                    break;
                }
                $arr['name'] = isset($category_district_data[$key])
                    ? $category_district_data[$key]
                    : '';
                $arr['value'] = $value;
                $return['dataset'][] = $arr;
                $number++;
            }
        }
        return $return;
    }
    /**
     * 求职者动态
     */
    protected function personalEventList(){
        //申请职位
        $list1 = model('JobApply')->alias('a')->join(config('database.prefix').'resume b','a.resume_id=b.id','LEFT')->order('a.id desc')->limit(15)->column('a.addtime,a.resume_id,a.jobid,a.jobname,b.fullname,b.sex,b.display_name','a.id');
        //刷新简历
        $list2 = model('ResumeSearchRtime')->alias('a')->join(config('database.prefix').'resume b','a.id=b.id','LEFT')->order('a.refreshtime desc')->where('addtime','egt',strtotime('-1 hour'))->limit(15)->column('a.refreshtime,a.id,b.fullname,b.sex,b.display_name','a.id');
        $list = [];
        foreach ($list1 as $key => $value) {
            $arr = [];
            $arr['type'] = 'jobapply';
            $arr['jobname'] = $value['jobname'];
            $arr['fullname'] = $value['fullname'];
            if ($value['display_name'] == 0) {
                if ($value['sex'] == 1) {
                    $arr['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '先生'
                    );
                } elseif ($value['sex'] == 2) {
                    $arr['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '女士'
                    );
                } else {
                    $arr['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '**'
                    );
                }
            }
            $arr['time'] = $value['addtime'];
            $list[] = $arr;
        }
        foreach ($list2 as $key => $value) {
            $arr = [];
            $arr['type'] = 'resume_refresh';
            $arr['fullname'] = $value['fullname'];
            if ($value['display_name'] == 0) {
                if ($value['sex'] == 1) {
                    $arr['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '先生'
                    );
                } elseif ($value['sex'] == 2) {
                    $arr['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '女士'
                    );
                } else {
                    $arr['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '**'
                    );
                }
            }
            $arr['time'] = $value['refreshtime'];
            $list[] = $arr;
        }
        $sortArr = array_column($list, 'time');
        array_multisort($sortArr, SORT_DESC, $list);
        $list = array_slice($list,0,15);
        return $list;
    }
    /**
     * 企业动态
     */
    protected function companyEventList(){
        //发布职位
        $list1 = model('Job')->alias('a')->join(config('database.prefix').'company c','a.uid=c.uid','LEFT')->where('c.id','not null')->order('a.addtime desc')->limit(15)->column('a.addtime,a.id,a.company_id,a.jobname,c.companyname','a.id');
        //刷新职位
        $list2 = model('JobSearchRtime')->alias('a')->join(config('database.prefix').'job b','a.id=b.id','LEFT')->join(config('database.prefix').'company c','a.uid=c.uid','LEFT')->where('c.id','not null')->order('a.refreshtime desc')->limit(15)->column('a.refreshtime,a.id,a.company_id,b.jobname,c.companyname','a.id');
        $list = [];
        foreach ($list1 as $key => $value) {
            $arr = [];
            $arr['type'] = 'jobadd';
            $arr['jobname'] = $value['jobname'];
            $arr['companyname'] = $value['companyname'];
            $arr['time'] = $value['addtime'];
            $list[] = $arr;
        }foreach ($list2 as $key => $value) {
            $arr = [];
            $arr['type'] = 'jobrefresh';
            $arr['jobname'] = $value['jobname'];
            $arr['companyname'] = $value['companyname'];
            $arr['time'] = $value['refreshtime'];
            $list[] = $arr;
        }
        $sortArr = array_column($list, 'time');
        array_multisort($sortArr, SORT_DESC, $list);
        $list = array_slice($list,0,15);
        return $list;
    }
}
