<?php

namespace app\v1_0\controller\company;

class ViewBeBrowsed extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
    }
    public function index(){
        $where['a.company_uid'] = $this->userinfo->uid;
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        $list = model('ViewJob')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.personal_uid=b.uid', 'left')
            ->field('a.id,a.jobid,a.personal_uid,a.addtime,b.fullname,b.display_name,b.high_quality,
            b.birthday,b.sex,b.education,b.enter_job_time,b.photo_img,b.current,b.refreshtime,b.id as resume_id,b.specialty,b.tag')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.addtime desc') // 【优化】企业会员中心求职管理-浏览记录 排序方式
            ->page($current_page, $pagesize)
            ->select();
        $resumeid_arr = [];
        $intention_arr = [];
        $photo_id_arr = [];
        $photo_data = [];
        $work_list = [];
        $fullname_arr = [];
        foreach ($list as $key => $value) {
            $resumeid_arr[] = $value['resume_id'];
            $value['photo_img'] > 0 && ($photo_id_arr[] = $value['photo_img']);
        }
        if (!empty($photo_id_arr)) {
            $photo_data = model('Uploadfile')->getFileUrlBatch(
                $photo_id_arr
            );
        }
        if (!empty($resumeid_arr)) {
            $resume_work = model('ResumeWork')
                ->where('rid','in',$resumeid_arr)
                ->order('todate desc,endtime desc')
                ->group('rid')
                ->select();

            $intention_data = model('ResumeIntention')
                ->where('rid', 'in', $resumeid_arr)
                ->order('id asc')
                ->select();
            foreach ($intention_data as $key => $value) {
                $intention_arr[$value['rid']][] = $value;
            }
            $work_data = model('ResumeWork')
                ->where('rid', 'in', $resumeid_arr)
                ->order('id desc')
                ->select();
            foreach ($work_data as $key => $value) {
                if (isset($work_list[$value['rid']])) {
                    //只取第一份工作经历（最后填写的一份）
                    continue;
                }
                $work_list[$value['rid']] = $value;
            }
            $fullname_arr = model('Resume')->formatFullname($resumeid_arr,$this->userinfo);
        }

        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $resume_tag = model('Category')->getCache('QS_resumetag');
        foreach ($list as $key => $value) {

            $value['companyname'] = '';
            $value['jobname'] = '';
            foreach($resume_work as $k=>$v)
            {
                if ($v['rid'] == $value['resume_id'])
                {
                    $value['companyname'] = $v['companyname'];
                    $value['jobname'] = $v['jobname'];
                }
            }
            $tag = explode(',',$value);
            $tag_list = [];
            foreach($tag as $k=>$v)
            {
                $tag_name = isset($resume_tag[$v]) ? $resume_tag[$v] : '';
                if (!empty($tag_name))
                {
                    $tag_list[] = $tag_name;
                }
            }
            $value['tag_list'] = $tag_list;
            $value['fullname'] = $fullname_arr[$value['resume_id']];
            $value['high_quality'] = $value['high_quality'];
            $value['sex_text'] = model('Resume')->map_sex[
            $value['sex']
            ];
            $value['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[
                $value['education']
                ]
                : '';
            $value['experience_text'] =
                $value['enter_job_time'] == 0
                    ? '无经验'
                    : format_date($value['enter_job_time']) . '经验';
            $value['current_text'] = isset(
                $category_data['QS_current'][$value['current']]
            )
                ? $category_data['QS_current'][$value['current']]
                : '';
            if (isset($work_list[$value['resume_id']])) {
                $value['recent_work'] =
                    $work_list[$value['resume_id']]['jobname'];
            } else {
                $value['recent_work'] = '';
            }
            $value['age'] = date('Y') - intval($value['birthday']);
            $district_arr = $category_arr = [];
            if (isset($intention_arr[$value['resume_id']])) {
                foreach ($intention_arr[$value['resume_id']] as $k => $v) {
                    if ($v['trade']) {
                        $trade_arr[] =
                            $category_data['QS_trade'][$v['trade']];
                    }
                    if ($v['nature']) {
                        $nature_arr[] = model('Resume')->map_nature[
                        $v['nature']
                        ];
                    }
                    $wage_arr[0] = $v['minwage'] . '-' . $v['maxwage'];
                    if ($v['category']) {
                        $category_arr[] = isset(
                            $category_job_data[$v['category']]
                        )
                            ? $category_job_data[$v['category']]
                            : '';
                    }
                    if ($v['district']) {
                        $district_arr[] = isset(
                            $category_district_data[$v['district']]
                        )
                            ? $category_district_data[$v['district']]
                            : '';
                    }
                }
            }
            if (!empty($trade_arr)) {
                $trade_arr = array_unique($trade_arr);
                $value['intention_trade'] = implode(',', $trade_arr);
            } else {
                $value['intention_trade'] = '';
            }
            if (!empty($category_arr)) {
                $category_arr = array_unique($category_arr);
                $value['intention_jobs'] = implode(',', $category_arr);
            } else {
                $value['intention_jobs'] = '';
            }
            if (!empty($wage_arr)) {
                $wage_arr = array_unique($wage_arr);
                $value['intention_wage'] = implode(',', $wage_arr);
            } else {
                $value['intention_wage'] = '';
            }
            if (!empty($district_arr)) {
                $district_arr = array_unique($district_arr);
                $value['intention_district'] = implode(',', $district_arr);
            } else {
                $value['intention_district'] = '';
            }
            if (!empty($nature_arr)) {
                $nature_arr = array_unique($nature_arr);
                $value['intention_nature'] = implode(',', $nature_arr);
            } else {
                $value['intention_nature'] = '';
            }
            $value['photo_img_src'] = isset(
                $photo_data[$value['photo_img']]
            )
                ? $photo_data[$value['photo_img']]
                : default_empty('photo', $value['sex']);

            $value['resume_link_url_web'] = url('index/resume/show', ['id' => $value['resume_id']], true, $this->sub_site_domain);
            $value['jobname'] = model('job')->where('id',$value['jobid'])->value('jobname');
            $list[$key] = $value;
        }
       $this->ajaxReturn(200,'被浏览记录',['items'=>$list]);
    }
    public function total()
    {
        $where['a.company_uid'] = $this->userinfo->uid;
        $total = model('ViewJob')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.personal_uid=b.uid', 'left')
            ->where($where)
            ->where('b.id','not null')
            ->count();
        $this->ajaxReturn(200, '获取数据成功', $total);
    }

    public function countViewBe()
    {
        $company_id = $this->userinfo->uid;
        $return['resume_fullname'] = '';
        $return['addtime'] = '';
        $return['count'] = model('ViewJob')->where(['company_uid'=>$company_id])->count();
        if ($return['count'] > 0)
        {
            $resume = model('View_Job')->alias('a')
                ->join('resume b','b.uid=a.personal_uid','left')
                ->where(['a.company_uid'=>$company_id])
                ->order('a.addtime desc')
                ->field('b.fullname,a.addtime')
                ->find();
            if (!empty($resume)){
                $return['resume_fullname'] = $resume['fullname'];
                $return['addtime'] = $resume['addtime'];
            }
        }

        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}