<?php
/**
 * 面试邀请
 */
namespace app\v1_0\controller\company;

class Interview extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
    }
    public function index()
    {
        $where['a.uid'] = $this->userinfo->uid;
        $jobid = input('get.jobid/d', 0, 'intval');
        $settr = input('get.settr/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        if ($jobid != 0) {
            $where['a.jobid'] = intval($jobid);
        }
        if ($settr != 0) {
            $where['a.addtime'] = ['egt', strtotime('-' . $settr . 'day')];
        }

        $list = model('CompanyInterview')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
            ->join('job j','j.id=a.jobid','left')
            ->field('a.id,a.comid,a.companyname,a.jobid,a.jobname,a.resume_id,a.fullname,a.interview_time,a.contact,a.address,a.tel,a.note,a.addtime,a.is_look,b.high_quality,b.display_name,b.birthday,b.sex,b.education,b.enter_job_time,b.photo_img,j.minwage,j.maxwage,j.negotiable,j.map_lat,j.map_lng')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.id desc')
            ->page($current_page, $pagesize)
            ->select();
        $photo_id_arr = [];
        $photo_data = [];
        $resumeid_arr = [];
        foreach ($list as $key => $value) {
            $value['photo_img'] > 0 && ($photo_id_arr[] = $value['photo_img']);
            $resumeid_arr[] = $value['resume_id'];
        }
        if (!empty($photo_id_arr)) {
            $photo_data = model('Uploadfile')->getFileUrlBatch(
                $photo_id_arr
            );
        }
        $fullname_arr = model('Resume')->formatFullname($resumeid_arr,$this->userinfo);

        foreach ($list as $key => $value) {
            $value['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            $value['fullname'] = $fullname_arr[$value['resume_id']];
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
                    : (format_date($value['enter_job_time']) . '经验');

            $value['age'] = date('Y') - intval($value['birthday']);
            
            $value['photo_img_src'] = isset(
                $photo_data[$value['photo_img']]
            )
                ? $photo_data[$value['photo_img']]
                : default_empty('photo');
            $value['resume_link_url_web'] = url('index/resume/show', ['id' => $value['resume_id']], true, $this->sub_site_domain);
            $value['job_link_url_web'] = url('index/job/show', ['id' => $value['jobid']], true, $this->sub_site_domain);
            $list[$key] = $value;
        }

        //查询出所有职位
        $option_jobs = model('Job')
            ->field('id,jobname')
            ->where('uid', $this->userinfo->uid)
            ->select();

        $return['items'] = $list;
        $return['option_jobs'] = $option_jobs;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function total()
    {
        $where['a.uid'] = $this->userinfo->uid;
        $jobid = input('get.jobid/d', 0, 'intval');
        $settr = input('get.settr/d', 0, 'intval');
        if ($jobid != 0) {
            $where['a.jobid'] = intval($jobid);
        }
        if ($settr != 0) {
            $where['a.addtime'] = ['egt', strtotime('-' . $settr . 'day')];
        }

        $total = model('CompanyInterview')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
            ->where($where)
            ->where('b.id','not null')
            ->count();
        $this->ajaxReturn(200, '获取数据成功', $total);
    }
}
