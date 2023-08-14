<?php
/**
 * 视频面试邀请
 */
namespace app\v1_0\controller\company;

class InterviewVideo extends \app\v1_0\controller\common\Base
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

        $list = model('CompanyInterviewVideo')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
            ->field('a.id,a.comid,a.companyname,a.jobid,a.jobname,a.resume_id,a.fullname,a.interview_time,a.contact,a.deadline,a.tel,a.note,a.addtime,a.is_look,a.company_donotice_time,a.personal_donotice_time,b.high_quality,b.display_name,b.birthday,b.sex,b.education,b.enter_job_time,b.photo_img')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.id desc')
            ->page($current_page, $pagesize)
            ->select();
        $photo_id_arr = [];
        $photo_data = [];
        foreach ($list as $key => $value) {
            $value['photo_img'] > 0 && ($photo_id_arr[] = $value['photo_img']);
        }
        if (!empty($photo_id_arr)) {
            $photo_data = model('Uploadfile')->getFileUrlBatch(
                $photo_id_arr
            );
        }

        foreach ($list as $key => $value) {
            if ($value['display_name'] == 0) {
                if ($value['sex'] == 1) {
                    $value['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '先生'
                    );
                } elseif ($value['sex'] == 2) {
                    $value['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '女士'
                    );
                } else {
                    $value['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '**'
                    );
                }
            }
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

            $value['age'] = date('Y') - intval($value['birthday']);
            
            if ($value['deadline'] < time()) {
                $value['room_status'] = 'overtime';
            } else {
                $interview_daytime = strtotime(date('Y-m-d', $value['interview_time']));
                if (time() < $interview_daytime) {
                    $value['room_status'] = 'nostart';
                } else {
                    $value['room_status'] = 'opened';
                }
            }
            $value['photo_img_src'] = isset(
                $photo_data[$value['photo_img']]
            )
                ? $photo_data[$value['photo_img']]
                : default_empty('photo');
            $value['link_url'] = config('global_config.sitedomain').url('index/video/main',['id'=>$value['id']]);
            $value['resume_link_url_web'] = config('global_config.sitedomain').url('index/resume/show',['id'=>$value['resume_id']]);
            $value['job_link_url_web'] = config('global_config.sitedomain').url('index/job/show',['id'=>$value['jobid']]);
            $list[$key] = $value;
        }

        //查询出所有职位
        $option_jobs = model('Job')
            ->field('id,jobname')
            ->where('uid', $this->userinfo->uid)
            ->select();

        $return['items'] = $list;
        $return['option_jobs'] = $option_jobs;
        $return['test_link'] = config('global_config.sitedomain').url('index/video/test');
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
        $total = model('CompanyInterviewVideo')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
            ->where($where)
            ->where('b.id','not null')
            ->count();
        $this->ajaxReturn(200, '获取数据成功', $total);
    }
    public function notice(){
        $id = input('post.id/d',0,'intval');
        !$id && $this->ajaxReturn(500, '请正确选择面试信息！');
        $interview = model('CompanyInterviewVideo')->where('id',$id)->where('uid',$this->userinfo->uid)->find();
        !$interview && $this->ajaxReturn(500, '面试信息不存在！');
        if($interview['company_donotice_time']>0 && (time()-$interview['company_donotice_time'])<3600){//提醒间隔必须大于1小时
            $this->ajaxReturn(500,'操作太频繁了，请稍候再试');
        }
        model('NotifyRule')->notify($interview['personal_uid'], 2, 'cron_interview_video', []);
        $interview->company_donotice_time = time();
        $interview->save();
        $this->ajaxReturn(200, '提醒成功');
    }
}
