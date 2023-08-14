<?php
namespace app\v1_0\controller\member;

use app\common\lib\AliCloud;

class Index extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function statAfterLogin(){
        $data = [];
        if($this->userinfo){
            if($this->userinfo->utype==1){
                $data['jobapply'] = model('JobApply')->where('company_uid',$this->userinfo->uid)->count();
                $data['interview'] = model('CompanyInterview')->where('uid',$this->userinfo->uid)->count();
                $data['view'] = model('ViewResume')->alias('a')
                                ->join(config('database.prefix').'resume b','a.personal_uid=b.uid','LEFT')
                                ->where('a.company_uid',$this->userinfo->uid)
                                ->where('b.id','not null')
                                ->count();
                $companyinfo = model('Company')
                    ->field('logo')
                    ->where('uid', $this->userinfo->uid)
                    ->find();
                $data['photo'] =
                    $companyinfo['logo'] > 0
                    ? model('Uploadfile')->getFileUrl($companyinfo['logo'])
                    : default_empty('logo');
            }
            if($this->userinfo->utype==2){
                $data['jobapply'] = model('JobApply')->where('personal_uid',$this->userinfo->uid)->count();
                $data['interview'] = model('CompanyInterview')->where('personal_uid',$this->userinfo->uid)->count();
                $data['view'] = model('ViewJob')->alias('a')
                            ->join(config('database.prefix').'company b','a.company_uid=b.uid','LEFT')
                            ->join(config('database.prefix').'job c','a.jobid=c.id','LEFT')
                            ->where('a.personal_uid', $this->userinfo->uid)
                            ->where('b.companyname','not null')
                            ->where('c.jobname','not null')
                            ->count();
                $basic = model('Resume')
                    ->field('photo_img')
                    ->where('uid', $this->userinfo->uid)
                    ->find();
                $data['photo'] =
                    $basic['photo_img'] > 0
                        ? model('Uploadfile')->getFileUrl($basic['photo_img'])
                        : default_empty('photo');
            }
        }
        
        $this->ajaxReturn(200,'获取数据成功',$data);
    }
    /**
     * 收藏职位
     */
    public function favJobAdd()
    {
        $this->checkLogin(2);
        $this->interceptPersonalResume();
        if (
            false ===
            model('FavJob')->favJobAdd(input('post.'), $this->userinfo->uid)
        ) {
            $this->ajaxReturn(500, model('FavJob')->getError());
        }
        $this->writeMemberActionLog($this->userinfo->uid,'收藏职位【职位ID：'.input('post.jobid').'】');
        $this->ajaxReturn(200, '收藏成功');
    }
    /**
     * 取消收藏职位
     */
    public function favJobCancel()
    {
        $this->checkLogin(2);
        $this->interceptPersonalResume();
        $jobid = input('post.jobid/d', 0, 'intval');
        model('FavJob')
            ->where([
                'jobid' => ['eq', $jobid],
                'personal_uid' => $this->userinfo->uid,
            ])
            ->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'取消收藏职位【职位ID：'.$jobid.'】');
        $this->ajaxReturn(200, '取消收藏成功');
    }
    /**
     * 投递简历
     */
    public function jobApplyAdd()
    {
        $this->checkLogin(2);
        $this->interceptPersonalResume();
        if (
            false ===
            model('JobApply')->jobApplyAdd(input('post.'), $this->userinfo->uid)
        ) {
            $this->ajaxReturn(500, model('JobApply')->getError());
        }
        $this->writeMemberActionLog($this->userinfo->uid,'投递简历【职位ID：'.input('post.jobid').'】');
        $this->ajaxReturn(200, '投递简历成功');
    }
    /**
     * 关注企业
     */
    public function attentionCompanyAdd()
    {
        $this->checkLogin(2);
        $this->interceptPersonalResume();
        if (
            false ===
            model('AttentionCompany')->attentionCompanyAdd(
                input('post.'),
                $this->userinfo->uid
            )
        ) {
            $this->ajaxReturn(500, model('AttentionCompany')->getError());
        }
        $this->writeMemberActionLog($this->userinfo->uid,'关注企业【企业ID：'.input('post.comid').'】');
        $this->ajaxReturn(200, '关注成功');
    }
    /**
     * 取消关注企业
     */
    public function attentionCompanyCancel()
    {
        $this->checkLogin(2);
        $this->interceptPersonalResume();
        $comid = input('post.comid/d', 0, 'intval');
        model('AttentionCompany')
            ->where([
                'comid' => ['eq', $comid],
                'personal_uid' => $this->userinfo->uid,
            ])
            ->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'取消关注企业【企业ID：'.$comid.'】');
        $this->ajaxReturn(200, '取消关注成功');
    }
    /**
     * 收藏简历
     */
    public function favResumeAdd()
    {
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
        if (
            false ===
            model('FavResume')->favResumeAdd(
                input('post.'),
                $this->userinfo->uid
            )
        ) {
            $this->ajaxReturn(500, model('FavResume')->getError());
        }
        $this->writeMemberActionLog($this->userinfo->uid,'收藏简历【简历ID：'.input('post.resume_id').'】');
        $this->ajaxReturn(200, '收藏成功');
    }
    /**
     * 取消收藏简历
     */
    public function favResumeCancel()
    {
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
        $resume_id = input('post.resume_id/d', 0, 'intval');
        model('FavResume')
            ->where([
                'resume_id' => ['eq', $resume_id],
                'company_uid' => $this->userinfo->uid,
            ])
            ->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'取消收藏简历【简历ID：'.$resume_id.'】');
        $this->ajaxReturn(200, '取消收藏成功');
    }
    /**
     * 下载简历
     */
    public function downResumeAdd()
    {
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
        $download_result = model('CompanyDownResume')->downResumeAdd(
            input('post.'),
            $this->userinfo->uid
        );
        if ($download_result['status'] == 0) {
            if ($download_result['done'] == 1) {
                $this->ajaxReturn(500, $download_result['msg']);
            } else {
                $global_config = config('global_config');
                $resume_info = $download_result['resume_info'];
                $return_data['done'] = 0;
                if ($global_config['single_resume_download_enable_points_deduct'] == 1) {
                    if ($resume_info['high_quality'] == 1) {
                        $need_points = $global_config['single_resume_download_points_talent'];
                    } else {
                        $down_resume_points_config_arr = $global_config['single_resume_download_points_conf'];
                        $down_resume_points_config = [];
                        foreach ($down_resume_points_config_arr as $key => $value) {
                            $down_resume_points_config[$value['alias']] = $value['value'];
                        }
                        if ($resume_info['refreshtime'] >= strtotime('-1 day')) {
                            //刷新时间1天之内
                            $need_points = $down_resume_points_config[1];
                        } elseif ($resume_info['refreshtime'] >= strtotime('-3 day')) {
                            //刷新时间3天之内
                            $need_points = $down_resume_points_config[3];
                        } elseif ($resume_info['refreshtime'] >= strtotime('-5 day')) {
                            //刷新时间5天之内
                            $need_points = $down_resume_points_config[5];
                        } else {
                            //刷新时间5天以上
                            $need_points = $down_resume_points_config[0];
                        }
                    }

                    $member_points = model('Member')->getMemberPoints($this->userinfo->uid);
                    if ($member_points >= $need_points) {
                        $return_data['use_type'] = 'points';
                        $return_data['need_points'] = $need_points;
                    }
                }
                if (!isset($return_data['use_type'])) {
                    $down_resume_expense_config_arr = $global_config['single_resume_download_expense_conf'];
                    $down_resume_expense_config = [];
                    foreach ($down_resume_expense_config_arr as $key => $value) {
                        $down_resume_expense_config[$value['alias']] = $value['value'];
                    }
                    if ($resume_info['refreshtime'] >= strtotime('-1 day')) {
                        //刷新时间1天之内
                        $need_expense = $down_resume_expense_config[1];
                    } elseif ($resume_info['refreshtime'] >= strtotime('-3 day')) {
                        //刷新时间3天之内
                        $need_expense = $down_resume_expense_config[3];
                    } elseif ($resume_info['refreshtime'] >= strtotime('-5 day')) {
                        //刷新时间5天之内
                        $need_expense = $down_resume_expense_config[5];
                    } else {
                        //刷新时间5天以上
                        $need_expense = $down_resume_expense_config[0];
                    }
                    $return_data['use_type'] = 'money';
                    $return_data['need_expense'] = $need_expense;
                }
                $member_setmeal = model('Member')->getMemberSetmeal($this->userinfo->uid);
                $return_data['discount'] = $member_setmeal['service_added_discount'];

                $this->ajaxReturn(200, $download_result['msg'], $return_data);
            }
        }
        $this->writeMemberActionLog($this->userinfo->uid,'下载简历【简历ID：'.input('post.resume_id').'】');
        $this->ajaxReturn(200, '下载成功');
    }
    /**
     * 邀请面试预加载数据
     */
    public function interviewAddPre()
    {
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
        $companyContact = model('CompanyContact')->where('uid',$this->userinfo->uid)->find();
        $companyInfo = model('CompanyInfo')->where('uid',$this->userinfo->uid)->find();
        $return = [
            'contact'=>$companyContact===null?'':$companyContact->contact,
            'tel'=>$companyContact===null?'':($companyContact->mobile?$companyContact->mobile:$companyContact->telephone),
            'address'=>$companyInfo===null?'':$companyInfo->address
        ];
        $this->ajaxReturn(200, '获取数据成功',$return);
    }
    /**
     * 邀请面试
     */
    public function interviewAdd()
    {
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
        $type = input('post.type/d',1,'intval');//1普通面试邀请 2视频面试邀请
        if($type==1){
            $this->_interviewCommonAdd();
        }else{
            $this->_interviewVideoAdd();
        }
    }
    protected function _interviewCommonAdd(){
        $input_data = [
            'resume_id' => input('post.resume_id/d', 0, 'intval'),
            'jobid' => input('post.jobid/d', 0, 'intval'),
            'interview_date' => input('post.interview_date/s', '', 'trim'),
            'interview_time' => input('post.interview_time/s', '', 'trim'),
            'address' => input('post.address/s', '', 'trim,badword_filter'),
            'contact' => input('post.contact/s', '', 'trim,badword_filter'),
            'tel' => input('post.tel/s', '', 'trim,badword_filter'),
            'note' => input('post.note/s', '', 'trim,badword_filter'),
        ];
        $validate = new \think\Validate([
            'resume_id' => 'require|number|gt:0',
            'jobid' => 'require|number|gt:0',
            'interview_date' => 'require',
            'interview_time' => 'require',
            'address' => 'require|max:100',
            'contact' => 'require|max:10',
            'tel' => 'require|max:15',
            'note' => 'max:100',
        ]);
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        if (
            false ===
            model('CompanyInterview')->interviewAdd(
                $input_data,
                $this->userinfo->uid
            )
        ) {
            $this->ajaxReturn(500, model('CompanyInterview')->getError());
        }
        $apply_info = model('JobApply')
            ->where([
                'company_uid' => $this->userinfo->uid,
                'jobid' => $input_data['jobid'],
                'resume_id' => $input_data['resume_id'],
                'handle_status' => 0,
            ])
            ->find();
        if ($apply_info !== null) {
            $apply_info->is_look = 1;
            $apply_info->handle_status = 1;
            $apply_info->save();
            if ($apply_info['addtime'] >= strtotime('-3 day')) {
                //3天内
                model('Task')->doTask($this->userinfo->uid, 1, 'handle_resume');
            }
        }
        $this->writeMemberActionLog($this->userinfo->uid,'面试邀请【简历ID：'.$input_data['resume_id'].'】');

        $this->ajaxReturn(200, '邀请面试成功');
    }
    
    protected function _interviewVideoAdd(){
        $input_data = [
            'resume_id' => input('post.resume_id/d', 0, 'intval'),
            'jobid' => input('post.jobid/d', 0, 'intval'),
            'interview_date' => input('post.interview_date/s', '', 'trim'),
            'interview_time' => input('post.interview_time/s', '', 'trim'),
            'contact' => input('post.contact/s', '', 'trim,badword_filter'),
            'tel' => input('post.tel/s', '', 'trim,badword_filter'),
            'note' => input('post.note/s', '', 'trim,badword_filter'),
        ];
        $validate = new \think\Validate([
            'resume_id' => 'require|number|gt:0',
            'jobid' => 'require|number|gt:0',
            'interview_date' => 'require',
            'interview_time' => 'require',
            'contact' => 'require|max:10',
            'tel' => 'require|max:15',
            'note' => 'max:100',
        ]);
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        if (
            false ===
            model('CompanyInterviewVideo')->interviewAdd(
                $input_data,
                $this->userinfo->uid
            )
        ) {
            $this->ajaxReturn(500, model('CompanyInterviewVideo')->getError());
        }
        $apply_info = model('JobApply')
            ->where([
                'company_uid' => $this->userinfo->uid,
                'jobid' => $input_data['jobid'],
                'resume_id' => $input_data['resume_id'],
                'handle_status' => 0,
            ])
            ->find();
        if ($apply_info !== null) {
            $apply_info->is_look = 1;
            $apply_info->handle_status = 1;
            $apply_info->save();
            if ($apply_info['addtime'] >= strtotime('-3 day')) {
                //3天内
                model('Task')->doTask($this->userinfo->uid, 1, 'handle_resume');
            }
        }
        $this->writeMemberActionLog($this->userinfo->uid,'视频面试邀请【简历ID：'.$input_data['resume_id'].'】');

        $this->ajaxReturn(200, '邀请面试成功');
    }

    public function secretPhone(){
        $job_id = input('get.job_id/d', 0, 'intval');
        $resume_id = input('get.resume_id/d', 0, 'intval');
        $aliCloud = new AliCloud();
        $timeout = 182;
        if(!intval(config('global_config.alicloud_phone_protect_open'))){
            $this->ajaxReturn(400, '非法请求');
        }
        $protect_targets = array_map('intval', explode(',', config('global_config.alicloud_phone_protect_target')));
        if(!$this->userinfo){
            $this->ajaxReturn(4010, '非法请求!');
        }
        $a = $this->getFinalMobile(['uid'=>$this->userinfo->uid, 'utype'=>$this->userinfo->utype]);
        if($job_id){
            if(!in_array(1, $protect_targets)){
                $this->ajaxReturn(401, '非法请求!');
            }
            $b = $this->getFinalMobile(['job_id'=>$job_id]);
        }
        if($resume_id){
            if(!in_array(2, $protect_targets)){
                $this->ajaxReturn(402, '非法请求!!');
            }
            $b = $this->getFinalMobile(['resume_id'=>$resume_id]);
        }

        if(intval(config('global_config.alicloud_phone_protect_type')) == 2){
            $timeout = 122;
            list($a, $b) = [$b, $a];
            $data = [
                'x' => $aliCloud->bindAxN($a, $b, $timeout),
                'type' => 'axn',
            ];
        }else{
            $data = [
                'x' => $aliCloud->bindAxb2($a, $b, $timeout),
                'type' => 'axb',
            ];
        }
        $data['a'] = $a;
        $data['time'] = time();
        $data['timeout'] = $timeout-2;
        if(config('app_debug')){
            $data['b'] = $b;
        }
        if(!$aliCloud->getError()){
            return $this->ajaxReturn(200, 'success', $data);
        }else{
            return $this->ajaxReturn(500, $aliCloud->getError(), $data);
        }
    }

    protected function getFinalMobile($param){
        if(isset($param['uid'])){
            if($param['utype'] == 1){//企业
                $company_contact = model('CompanyContact')->where('uid',$this->userinfo->uid)->find();
                if($company_contact){
                    $a = $company_contact['mobile'];//企业联系人
                }else{
                    $memberInfo = model('Member')->where('uid', $this->userinfo->uid)->find();
                    if($memberInfo){
                        $a = $memberInfo['mobile'];
                    }
                }
            }else{//个人
                $memberInfo = model('Member')->where('uid', $this->userinfo->uid)->find();
                if($memberInfo){
                    $a = $memberInfo['mobile'];
                }
            }
        }
        if(isset($param['resume_id'])){
            $resume_info = action('v1_0/home/resume/getDetail', ['id'=>$param['resume_id']]);
            //简历联系人/联系人
            if(isset($resume_info['contact_info']['mobile']) && !empty($resume_info['contact_info']['mobile'])){
                $a = $resume_info['contact_info']['mobile'];
            }else{
                $memberInfo = model('Member')->where('uid', $resume_info['base_info']['uid'])->find();
                if($memberInfo){
                    $a = $memberInfo['mobile'];
                }
            }
        }
        if(isset($param['job_id'])){
            $jobinfo = model('Job')->where('id', 'eq', $param['job_id'])->find();
            $getJobContact = model('Job')->getContact($jobinfo,$this->userinfo);
            $jobInfo = $getJobContact['contact_info'];
            $a = $jobInfo['mobile'];
        }
        return $a;
    }

}
