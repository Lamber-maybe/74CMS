<?php
/**
 * 收到的简历
 */
namespace app\v1_0\controller\company;

class JobApply extends \app\v1_0\controller\common\Base
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
        $where['a.company_uid'] = $this->userinfo->uid;
        $jobid = input('get.jobid/d', 0, 'intval');
        $status = input('get.status/s', '', 'trim');
        $source = input('get.source/s', '', 'trim');
        $is_look = input('get.is_look/s', '', 'trim');
        $settr = input('get.settr/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        if ($jobid != 0) {
            $where['a.jobid'] = intval($jobid);
        }
        if ($source != '') {
            $where['a.source'] = intval($source);
        }
        if ($is_look != '') {
            $where['a.is_look'] = intval($is_look);
        }
        if ($settr > 0) {
            $where['a.addtime'] = [
                'egt',
                strtotime('-' . intval($settr) . ' day')
            ];
        }
        if ($status != '') {
            switch ($status) {
                case 0: //待处理
                    $where['a.handle_status'] = 0;
                    break;
                case 1: //已同意
                    $where['a.handle_status'] = 1;
                    break;
                case 2: //已拒绝
                    $where['a.handle_status'] = 2;
                    break;
            }
        }

        $list = model('JobApply')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
            ->join(config('database.prefix') . 'resume_remark rr', 'a.resume_id=rr.resume_id and a.comid=rr.comid', 'left')
            ->field('a.id,a.comid,a.companyname,a.jobid,a.jobname,a.resume_id,a.fullname,a.note,a.addtime,a.is_look,a.handle_status,a.source,a.platform,b.display_name,b.high_quality,b.birthday,b.sex,b.education,b.enter_job_time,b.photo_img,b.current,IFNULL(rr.remark,"") as remark,b.audit')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.id desc')
            ->page($current_page, $pagesize)
            ->select();
        $resumeid_arr = [];
        $resumelist = [];
        $intention_arr = [];
        $photo_id_arr = [];
        $photo_data = [];
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
        foreach ($list as $key => $value) {
            $value['high_quality'] = $value['high_quality'];
            $value['fullname'] = isset($fullname_arr[$value['resume_id']])?$fullname_arr[$value['resume_id']]:$value['fullname'];
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
                : default_empty('photo');

            $value['resume_link_url_web'] = url('index/resume/show',['id'=>$value['resume_id']]);
            $value['job_link_url_web'] = url('index/job/show',['id'=>$value['jobid']]);
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
        $where['a.company_uid'] = $this->userinfo->uid;
        $jobid = input('get.jobid/d', 0, 'intval');
        $status = input('get.status/s', '', 'trim');
        $source = input('get.source/s', '', 'trim');
        $settr = input('get.settr/d', 0, 'intval');
        if ($jobid != 0) {
            $where['a.jobid'] = intval($jobid);
        }
        if ($source != '') {
            $where['a.source'] = intval($source);
        }
        if ($settr > 0) {
            $where['a.addtime'] = [
                'egt',
                strtotime('-' . intval($settr) . ' day')
            ];
        }
        if ($status != '') {
            switch ($status) {
                case 0: //待处理
                    $where['a.handle_status'] = 0;
                    break;
                case 1: //已同意
                    $where['a.handle_status'] = 1;
                    break;
                case 2: //已拒绝
                    $where['a.handle_status'] = 2;
                    break;
            }
        }
        $total = model('JobApply')
            ->alias('a')
            ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
            ->where($where)
            ->where('b.id','not null')
            ->count();
        $this->ajaxReturn(200, '获取数据成功', $total);
    }
    /**
     * 简历处理情况
     */
    public function watchSituation()
    {
        $where['company_uid'] = $this->userinfo->uid;
        $endtime = time();
        $starttime = $endtime - 3600 * 24 * 14;
        $where['addtime'] = ['between', [$starttime, $endtime]];
        $apply_data = model('JobApply')
            ->field('id,is_look')
            ->where($where)
            ->select();
        $total = $looked = 0;
        if (!empty($apply_data)) {
            foreach ($apply_data as $key => $value) {
                $value['is_look'] == 1 && $looked++;
                $total++;
            }
            $return['watch_percent'] = round($looked / $total, 2) * 100 . '%';
        } else {
            $return['watch_percent'] = '100%';
        }
        $return['total'] = $total;
        $return['noWatch'] = $total-$looked;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 拒绝
     */
    public function setRefuse()
    {
        $id = input('post.id/d', 0, 'intval');
        $apply_info = model('JobApply')
            ->where('id', 'eq', $id)
            ->find();
        if ($apply_info === null) {
            $this->ajaxReturn(500, '没有找到信息');
        }
        $apply_info->is_look = 1;
        $apply_info->handle_status = 2;
        $apply_info->save();
        if (time() - $apply_info['addtime'] < 259200) {
            //3天内
            model('Task')->doTask($this->userinfo->uid, 1, 'handle_resume');
        }
        //通知
        model('NotifyRule')->notify(
            $apply_info['personal_uid'],
            2,
            'job_apply_handle',
            [
                'jobname' => $apply_info->jobname
            ]
        );
        $this->writeMemberActionLog($this->userinfo->uid,'处理收到的简历【处理状态：拒绝，简历id：'.$apply_info->resume_id.'】');
        $this->ajaxReturn(200, '设置成功');
    }
    /**
     * 同意面试
     */
    public function setAgree()
    {
        $type = input('post.type/d',1,'intval');//1普通面试邀请 2视频面试邀请
        if($type==1){
            $this->_interviewCommonAdd();
        }else{
            $this->_interviewVideoAdd();
        }

    }

    protected function _interviewCommonAdd(){
        $input_data = [
            'apply_id' => input('post.apply_id/d', 0, 'intval'),
            'interview_date' => input('post.interview_date/s', '', 'trim'),
            'interview_time' => input('post.interview_time/s', '', 'trim'),
            'address' => input('post.address/s', '', 'trim'),
            'contact' => input('post.contact/s', '', 'trim'),
            'tel' => input('post.tel/s', '', 'trim'),
            'note' => input('post.note/s', '', 'trim')
        ];
        $validate = new \think\Validate(
            [
                'apply_id' => 'require|number|gt:0',
                'interview_date' => 'require',
                'interview_time' => 'require',
                'address' => 'require|max:50',
                'contact' => 'require|max:10',
                'tel' => 'require|max:15',
                'note' => 'max:50'
            ],
            [
                'apply_id' => '请选择要面试的简历',
                'interview_date' => '请选择面试日期',
                'interview_time' => '请选择面试时间',
                'address' => '请输入50字内的面试地点',
                'contact' => '请输入6字内的联系人',
                'tel' => '请输入最多15位的联系电话',
                'note' => '备注最多50字'
            ]
        );
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $apply_info = model('JobApply')
            ->where('id', 'eq', $input_data['apply_id'])
            ->find();
        if ($apply_info === null) {
            $this->ajaxReturn(500, '没有找到信息');
        }
        $input_data['resume_id'] = $apply_info['resume_id'];
        $input_data['jobid'] = $apply_info['jobid'];
        $result = model('CompanyInterview')->interviewAdd(
            $input_data,
            $this->userinfo->uid
        );
        if ($result === false) {
            $this->ajaxReturn(500, model('CompanyInterview')->getError());
        }
        $apply_info->is_look = 1;
        $apply_info->handle_status = 1;
        $apply_info->save();
        if ($apply_info['addtime'] >= strtotime('-3 day')) {
            //3天内
            model('Task')->doTask($this->userinfo->uid, 1, 'handle_resume');
        }
        //通知
        model('NotifyRule')->notify(
            $apply_info['personal_uid'],
            2,
            'job_apply_handle',
            [
                'jobname' => $apply_info->jobname
            ]
        );
        $this->writeMemberActionLog($this->userinfo->uid,'面试邀请【简历id：'.$apply_info->resume_id.'】');
        $this->ajaxReturn(200, '邀请面试成功');
    }
    protected function _interviewVideoAdd(){
        $input_data = [
            'apply_id' => input('post.apply_id/d', 0, 'intval'),
            'interview_date' => input('post.interview_date/s', '', 'trim'),
            'interview_time' => input('post.interview_time/s', '', 'trim'),
            'contact' => input('post.contact/s', '', 'trim'),
            'tel' => input('post.tel/s', '', 'trim'),
            'note' => input('post.note/s', '', 'trim')
        ];
        $validate = new \think\Validate([
            'apply_id' => 'require|number|gt:0',
            'interview_date' => 'require',
            'interview_time' => 'require',
            'contact' => 'require|max:10',
            'tel' => 'require|max:15',
            'note' => 'max:100'
        ]);
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $apply_info = model('JobApply')
            ->where('id', 'eq', $input_data['apply_id'])
            ->find();
        if ($apply_info === null) {
            $this->ajaxReturn(500, '没有找到信息');
        }
        $input_data['resume_id'] = $apply_info['resume_id'];
        $input_data['jobid'] = $apply_info['jobid'];
        $result = model('CompanyInterviewVideo')->interviewAdd(
            $input_data,
            $this->userinfo->uid
        );
        if ($result === false) {
            $this->ajaxReturn(500, model('CompanyInterviewVideo')->getError());
        }
        $apply_info->is_look = 1;
        $apply_info->handle_status = 1;
        $apply_info->save();
        if ($apply_info['addtime'] >= strtotime('-3 day')) {
            //3天内
            model('Task')->doTask($this->userinfo->uid, 1, 'handle_resume');
        }
        //通知
        model('NotifyRule')->notify(
            $apply_info['personal_uid'],
            2,
            'job_apply_handle',
            [
                'jobname' => $apply_info->jobname
            ]
        );
        $this->writeMemberActionLog($this->userinfo->uid,'视频面试邀请【简历id：'.$apply_info->resume_id.'】');
        $this->ajaxReturn(200, '邀请面试成功');
    }
    /**
     * 同意面试前置检查
     */
    public function setAgreePre()
    {
        $apply_id = input('post.id/d', 0, 'intval');
        $apply_info = model('JobApply')
            ->where('id', 'eq', $apply_id)
            ->find();
        if ($apply_info === null) {
            $this->ajaxReturn(500, '没有找到信息');
        }

        $input_data['resume_id'] = $apply_info['resume_id'];
        $input_data['jobid'] = $apply_info['jobid'];
        $global_config = config('global_config');

        if ($global_config['apply_jobs_space'] > 0) {
            $check_interview = model('CompanyInterview')
                ->field('id')
                ->where([
                    'jobid' => ['eq', $input_data['jobid']],
                    'resume_id' => ['eq', $input_data['resume_id']],
                    'addtime' => [
                        'egt',
                        strtotime(
                            '-' . $global_config['apply_jobs_space'] . 'day'
                        )
                    ]
                ])
                ->find();
        } else {
            $check_interview = model('CompanyInterview')
                ->field('id')
                ->where([
                    'jobid' => ['eq', $input_data['jobid']],
                    'resume_id' => ['eq', $input_data['resume_id']]
                ])
                ->find();
        }
        if ($check_interview === null) {
            $this->ajaxReturn(200, '获取数据成功', ['finish' => 0]);
        }
        $apply_info->is_look = 1;
        $apply_info->handle_status = 1;
        $apply_info->save();

        if ($apply_info['addtime'] >= strtotime('-3 day')) {
            //3天内
            model('Task')->doTask($this->userinfo->uid, 1, 'handle_resume');
        }
        //通知
        model('NotifyRule')->notify(
            $apply_info['personal_uid'],
            2,
            'job_apply_handle',
            [
                'jobname' => $apply_info->jobname
            ]
        );
        $this->writeMemberActionLog($this->userinfo->uid,'同意面试【简历id：'.$apply_info->resume_id.'】');
        $this->ajaxReturn(200, '设置成功', ['finish' => 1]);
    }
    public function setLook()
    {
        $id = input('post.id/d', 0, 'intval');
        $info = model('JobApply')->where(['id' => ['eq', $id]])->find();
        $arr = ['is_look'=>1];
        $free_viewing_count = model('JobApply')
            ->where('free_viewing',1)
            ->whereTime('free_viewing_time', 'today')
            ->where('company_uid',$this->userinfo->uid)
            ->count();
        $resume_view_num = model('MemberSetmeal')->where('uid',$this->userinfo->uid)->value('resume_view_num');
        if ($free_viewing_count < $resume_view_num){
            $arr['free_viewing'] = 1;
            $arr['free_viewing_time'] = time();
        }
        model('JobApply')
            ->where(['id' => ['eq', $id]])
            ->update($arr);
        $this->writeMemberActionLog($this->userinfo->uid,'收到的简历设为已查看【简历id：'.$info->resume_id.'】');
        $this->ajaxReturn(200, '设置成功');
    }
    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        $info = model('JobApply')->where(['id' => ['eq', $id]])->find();
        model('JobApply')
            ->where([
                'id' => ['eq', $id],
                'company_uid' => $this->userinfo->uid
            ])
            ->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'删除收到的简历【简历id：'.$info->resume_id.'】');
        $this->ajaxReturn(200, '删除成功');
    }
}
