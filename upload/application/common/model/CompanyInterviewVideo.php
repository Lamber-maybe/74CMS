<?php
namespace app\common\model;

class CompanyInterviewVideo extends \app\common\model\BaseModel
{
    protected $readonly = [
        'id',
        'comid',
        'uid',
        'personal_uid',
        'resume_id',
        'addtime'
    ];
    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'comid' => 'integer',
        'personal_uid' => 'integer',
        'resume_id' => 'integer',
        'interview_time' => 'integer',
        'addtime' => 'integer',
        'is_look' => 'integer'
    ];
    public function interviewAdd($data, $company_uid)
    {
        $company_profile = model('Company')
            ->field('id,uid,companyname')
            ->where('uid', $company_uid)
            ->find();
        if ($company_profile === null) {
            $this->error = '企业信息为空';
            return false;
        }
        $member_info = model('Member')
            ->where('uid', $company_uid)
            ->find();
        if ($member_info['status'] == 0) {
            $this->error =
                '您的账号处于暂停状态，请联系管理员设为正常后进行操作';
            return false;
        }
		$setmeal = model('Member')->getMemberSetmeal($company_uid);
		if ($setmeal['enable_video_interview'] != 1) {
            $this->error =
                '当前套餐等级不能使用视频面试，请先升级套餐';
            return false;
		}
        $jobinfo = model('Job')
            ->field('id,audit,jobname,minwage,maxwage,negotiable')
            ->where(['id' => ['eq', $data['jobid']], 'uid' => $company_uid])
            ->find();
        if ($jobinfo === null) {
            $this->error = '职位信息为空';
            return false;
        }
        if ($jobinfo['audit'] != 1) {
            $this->error = '职位信息未审核通过';
            return false;
        }
        $resumeinfo = model('Resume')
            ->field('uid,fullname,audit')
            ->where(['id' => ['eq', $data['resume_id']]])
            ->find();
        if ($resumeinfo === null) {
            $this->error = '简历信息为空';
            return false;
        }
        if($resumeinfo['audit']!=1){
            $this->error = '该简历还没有审核通过，无法继续此操作';
            return false;
        }
        $check_unique_map['uid'] = $company_uid;
		$check_unique_map['personal_uid'] = $resumeinfo['uid'];
		$check_unique_map['jobid'] = $jobinfo['id'];
		$check_unique_map['deadline'] = array('gt', time());
		$check_unique = $this->where($check_unique_map)->find();
		if ($check_unique) {
            $this->error = '您已对该简历进行过面试邀请,不能重复邀请';
            return false;
        }

        $global_config = config('global_config');
        
        $pass = false;
        if(config('platform')=='web'){
            if(config('global_config.showresumecontact')==0 || config('global_config.showresumecontact')==1){//游客可见或已登录可见
                $pass = true;
            }else{//下载后可见
                $setmeal = model('Member')->getMemberSetmeal($company_uid);
                $check_apply = model('JobApply')
                    ->field('id')
                    ->where([
                        'company_uid' => ['eq', $company_uid],
                        'resume_id' => ['eq', $data['resume_id']]
                    ])
                    ->find();
                if ($setmeal['show_apply_contact'] == 1 && $check_apply !== null) {
                    $pass = true;
                }
                if ($pass === false) {
                    $check_download = model('CompanyDownResume')
                        ->field('id')
                        ->where([
                            'uid' => ['eq', $company_uid],
                            'resume_id' => ['eq', $data['resume_id']]
                        ])
                        ->find();
                    $check_download !== null && ($pass = true);
                }
                if ($pass === false) {
                    $this->error = '请先下载简历';
                    return false;
                }
            }
        }else{
            if(config('global_config.showresumecontact_mobile')==0 || config('global_config.showresumecontact_mobile')==1){//游客可见或已登录可见
                $pass = true;
            }else{//下载后可见
                $setmeal = model('Member')->getMemberSetmeal($company_uid);
                $check_apply = model('JobApply')
                    ->field('id')
                    ->where([
                        'company_uid' => ['eq', $company_uid],
                        'resume_id' => ['eq', $data['resume_id']]
                    ])
                    ->find();
                if ($setmeal['show_apply_contact'] == 1 && $check_apply !== null) {
                    $pass = true;
                }
                if ($pass === false) {
                    $check_download = model('CompanyDownResume')
                        ->field('id')
                        ->where([
                            'uid' => ['eq', $company_uid],
                            'resume_id' => ['eq', $data['resume_id']]
                        ])
                        ->find();
                    $check_download !== null && ($pass = true);
                }
                if ($pass === false) {
                    $this->error = '请先下载简历';
                    return false;
                }
            }
        }
        
        $input_data = [
            'comid' => $company_profile['id'],
            'companyname' => $company_profile['companyname'],
            'uid' => $company_profile['uid'],
            'jobname' => $jobinfo['jobname'],
            'personal_uid' => $resumeinfo['uid'],
            'fullname' => $resumeinfo['fullname'],
            'interview_time' => strtotime(
                $data['interview_date'] . ' ' . $data['interview_time']
            ),
            'addtime' => time(),
            'is_look' => 0
        ];
        $input_data['deadline'] = $input_data['interview_time'] + 3600 * 24 * 15;//过期时间设置为面试时间之后的第15天
        $input_data = array_merge($data, $input_data);
        $result = $this->allowField(true)->save($input_data);
        if (false !== $result) {
            //通知
            model('NotifyRule')->notify($resumeinfo['uid'], 2, 'interview_video', [
                'companyname' => $company_profile['companyname'],
                'interview_time' =>
                    $data['interview_date'] . ' ' . $data['interview_time'],
                'jobname' => $jobinfo['jobname'],
                'wage' => model('BaseModel')->handle_wage(
                    $jobinfo['minwage'],
                    $jobinfo['maxwage'],
                    $jobinfo['negotiable']
                )
            ]);
            /**
             * 区分微信新旧版本模板
             * cy 2023-10-17
             */
            $wechatTplType = config('global_config.wechat_tpl_type');
            $wechatTplType = !empty($wechatTplType) ? $wechatTplType : 1;
            $wechatTplAlias = 'interview_video';
            $wechatTplData = [
                $company_profile['companyname'],
                $jobinfo['jobname'],
                date('Y年m月d日 H:i'). ' 参加视频面试'
            ];
            if ($wechatTplType == 2) {
                // 公司名称,职位名称,面试时间,面试地点,联系人
                $wechatTplAlias = 'interview_video_notify';
                $wechatTplData = [
                    $company_profile['companyname'],
                    $jobinfo['jobname'],
                    date('Y年m月d日 H:i'),
                    '视频面试',
                    $input_data['contact'] . '(' . $input_data['tel'] . ')'
                ];
            }
            // 微信通知
            model('WechatNotifyRule')->notify(
                $resumeinfo['uid'],
                2,
                $wechatTplAlias,
                $wechatTplData,
                'member/personal/interview_video'
            );
        }
        return $result;
    }
}
