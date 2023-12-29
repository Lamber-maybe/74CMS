<?php

namespace app\common\model;

class JobApply extends BaseModel
{
    public $map_status = [
        0 => '待处理',
        1 => '同意面试',
        2 => '拒绝面试'
    ];
    protected $readonly = [
        'id',
        'comid',
        'companyname',
        'company_uid',
        'jobid',
        'jobname',
        'personal_uid',
        'resume_id',
        'fullname',
        'note',
        'addtime'
    ];
    protected $type = [
        'id' => 'integer',
        'comid' => 'integer',
        'company_uid' => 'integer',
        'jobid' => 'integer',
        'personal_uid' => 'integer',
        'resume_id' => 'integer',
        'addtime' => 'integer',
        'is_look' => 'integer',
        'handle_status' => 'integer'
    ];

    public function jobApplyAdd($data, $personal_uid)
    {
        if (!isset($data['jobid']) || !$data['jobid']) {
            $this->error = '请选择职位';
            return false;
        }
        $jobid = intval($data['jobid']);
        $member_info = model('Member')
            ->where('uid', $personal_uid)
            ->find();
        if ($member_info['status'] == 0) {
            $this->error =
                '您的账号处于暂停状态，请联系管理员设为正常后进行操作';
            return false;
        }
        $job_info = model('Job')
            ->field('id,uid,jobname,audit,is_display')
            ->where('id', 'eq', $jobid)
            ->find();
        if (null === $job_info) {
            $this->error = '没有找到职位信息';
            return false;
        }
        if ($job_info['audit'] != 1) {
            $this->error = '该职位还没有审核通过，无法继续此操作';
            return false;
        }
        if ($job_info['is_display'] != 1) {
            $this->error = '该职位已停止招聘，无法继续此操作';
            return false;
        }
        $global_config = config('global_config');
        //检测每天申请的职位数是否超限
        if (
            $global_config['apply_jobs_max_perday'] > 0 &&
            $this->where([
                'personal_uid' => $personal_uid,
                'addtime' => ['egt', strtotime('today')]
            ])->count() >= $global_config['apply_jobs_max_perday']
        ) {
            $this->error =
                '今天投递职位数已达上限（' .
                $global_config['apply_jobs_max_perday'] .
                '次），请明天再试';
            return false;
        }
        //检测是否重复申请职位
        if ($global_config['apply_jobs_space'] > 0) {
            $check_applyed = $this->where([
                'personal_uid' => $personal_uid,
                'jobid' => $jobid,
                'addtime' => [
                    'egt',
                    strtotime('-' . $global_config['apply_jobs_space'] . 'day')
                ]
            ])
                ->field('id')
                ->find();
        } else {
            $check_applyed = $this->where([
                'personal_uid' => $personal_uid,
                'jobid' => $jobid
            ])
                ->field('id')
                ->find();
        }
        if (null !== $check_applyed) {
            $this->error = '你已经投递过该职位了';
            return false;
        }
        $resume_info = model('Resume')
            ->field('id,fullname,audit,enter_job_time,sex,education')
            ->where('uid', $personal_uid)
            ->find();
        if (null === $resume_info) {
            $this->error = '没有找到简历信息';
            return false;
        }
        if ($resume_info['audit'] != 1) {
            $this->error =
                '您的简历正在审核中，请耐心等待…';
            return false;
        }
        if (
            model('Resume')->countCompletePercent($resume_info['id']) <
            $global_config['apply_job_min_percent']
        ) {
            $this->error =
                '你的简历完整度不足' .
                $global_config['apply_job_min_percent'] .
                '%，请完善后再进行操作';
            return false;
        }
        $company_info = model('Company')
            ->field('id,companyname,uid')
            ->where('uid', 'eq', $job_info['uid'])
            ->find();
        if (null === $company_info) {
            $this->error = '没有找到企业信息';
            return false;
        }
        $input_data['comid'] = $company_info['id'];
        $input_data['companyname'] = $company_info['companyname'];
        $input_data['company_uid'] = $company_info['uid'];
        $input_data['jobid'] = $job_info['id'];
        $input_data['jobname'] = $job_info['jobname'];
        $input_data['personal_uid'] = $personal_uid;
        $input_data['resume_id'] = $resume_info['id'];
        $input_data['fullname'] = $resume_info['fullname'];
        $input_data['note'] = isset($data['note']) ? $data['note'] : '';
        $input_data['addtime'] = time();
        $input_data['is_look'] = 0;
        $input_data['handle_status'] = 0;
        $input_data['source'] = 0;
        $input_data['platform'] = config('platform');
        $result = $this->save($input_data);
        $job_apply_id = $this->getLastInsID();
        if (false !== $result) {
            model('Task')->doTask($personal_uid, 2, 'apply_job');
            //通知
            model('NotifyRule')->notify(
                $company_info['uid'],
                1,
                'job_apply',
                [
                    'fullname' => $resume_info['fullname'],
                    'jobname' => $job_info['jobname'],
                    'resumeurl' => url('index/resume/show', ['id' => $resume_info['id']], '', config('global_config.sitedomain'))
                ],
                $resume_info['id'],
                '',
                [
                    'job_id' => $jobid
                ]
            );
            $sex_text = isset(model('Resume')->map_sex[$resume_info['sex']]) ? model('Resume')->map_sex[$resume_info['sex']] : '';
            $education = isset(model('Resume')->map_education[$resume_info['education']]) ? model('Resume')->map_education[$resume_info['education']] : '';
            $experience = $resume_info['enter_job_time'] == 0
                ? '无经验'
                : format_date($resume_info['enter_job_time']);

            /**
             * 区分微信新旧版本模板
             * cy 2023-10-17
             */
            $wechatTplType = config('global_config.wechat_tpl_type');
            $wechatTplType = !empty($wechatTplType) ? $wechatTplType : 1;
            $wechatTplAlias = 'job_apply';
            if ($wechatTplType == 2) {
                $wechatTplAlias = 'job_apply_notify';
            }
            $wechatTplData = [
                $resume_info['fullname'] . '(' . $sex_text . ',' . $education . ',' . $experience . ')',
                $job_info['jobname']
            ];
            // 微信通知
            model('WechatNotifyRule')->notify(
                $company_info['uid'],
                1,
                $wechatTplAlias,
                $wechatTplData,
                'resume/' . $resume_info['id'] . '?company_uid=' . $company_info['uid'] . '&job_apply_id=' . $job_apply_id
            );
        }
        return $result;
    }
}
