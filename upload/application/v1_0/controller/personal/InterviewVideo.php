<?php
/**
 * 视频面试邀请列表
 */
namespace app\v1_0\controller\personal;

class InterviewVideo extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(2);
        $this->interceptPersonalResume();
    }
    public function index()
    {
        $where['a.personal_uid'] = $this->userinfo->uid;
        $is_look = input('get.is_look/d', '', 'trim');
        $settr = input('get.settr/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        if ($is_look != '') {
            $where['a.is_look'] = intval($is_look);
        }
        if ($settr != 0) {
            $where['a.interview_time'] = [['egt', strtotime('today')],['elt', strtotime('+' . $settr . 'day')],'and'];
        }

        $list = model('CompanyInterviewVideo')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.jobid=b.id', 'left')
            ->field('a.id,a.comid,a.companyname,a.jobid,a.jobname,a.resume_id,a.fullname,a.interview_time,a.contact,a.deadline,a.tel,a.note,a.addtime,a.is_look,a.company_donotice_time,a.personal_donotice_time,b.education,b.experience,b.nature,b.minwage,b.maxwage,b.negotiable')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.id desc')
            ->page($current_page, $pagesize)
            ->select();

        foreach ($list as $key => $value) {
            $value['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '';
            $value['experience_text'] = isset(
                model('BaseModel')->map_experience[$value['experience']]
            )
                ? model('BaseModel')->map_experience[$value['experience']]
                : '';
            $value['nature_text'] = isset(
                model('Job')->map_nature[$value['nature']]
            )
                ? model('Job')->map_nature[$value['nature']]
                : '';
            $value['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            
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
            $value['job_link_url_web'] = url('index/company/show',['id'=>$value['jobid']]);
            $value['company_link_url_web'] = url('index/company/show',['id'=>$value['comid']]);

            $list[$key] = $value;
        }

        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function total()
    {
        $where['a.personal_uid'] = $this->userinfo->uid;
        $is_look = input('get.is_look/d', '', 'trim');
        $settr = input('get.settr/d', 0, 'intval');
        if ($is_look != '') {
            $where['a.is_look'] = intval($is_look);
        }
        if ($settr != 0) {
            $where['a.interview_time'] = [['egt', strtotime('today')],['elt', strtotime('+' . $settr . 'day')],'and'];
        }

        $total = model('CompanyInterviewVideo')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.jobid=b.id', 'left')
            ->where($where)
            ->where('b.id','not null')
            ->count();

        $this->ajaxReturn(200, '获取数据成功', $total);
    }
    public function setLook()
    {
        $id = input('post.id/d', 0, 'intval');
        model('CompanyInterviewVideo')
            ->where(['id' => ['eq', $id]])
            ->setField('is_look', 1);
        $this->writeMemberActionLog($this->userinfo->uid,'视频面试邀请设为已查看【记录ID：'.$id.'】');
        $this->ajaxReturn(200, '设置成功');
    }
    public function notice(){
        $id = input('post.id/d',0,'intval');
        !$id && $this->ajaxReturn(500, '请正确选择面试信息！');
        $interview = model('CompanyInterviewVideo')->where('id',$id)->where('personal_uid',$this->userinfo->uid)->find();
        !$interview && $this->ajaxReturn(500, '面试信息不存在！');
        if($interview['personal_donotice_time']>0 && (time()-$interview['personal_donotice_time'])<3600){//提醒间隔必须大于1小时
            $this->ajaxReturn(500,'操作太频繁了，请稍候再试');
        }
        model('NotifyRule')->notify($interview['uid'], 1, 'cron_interview_video', []);
        $interview->personal_donotice_time = time();
        $interview->save();
        $this->writeMemberActionLog($this->userinfo->uid,'提醒视频面试【简历ID：'.$interview->resume_id.'】');
        $this->ajaxReturn(200, '提醒成功');
    }
}
