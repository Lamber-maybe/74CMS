<?php

namespace app\common\model;

class Job extends \app\common\model\BaseModel
{
    public $map_audit = [
        0 => '待审核',
        1 => '已通过',
        2 => '未通过',
    ];
    public $map_sex = [0 => '不限', 1 => '男', 2 => '女'];
    public $map_nature = [1 => '全职', 2 => '实习'];
    public $map_display = [1 => '招聘中', 0 => '已暂停'];

    protected $insert = ['updatetime'];
    protected $update = ['updatetime'];

    protected static function init()
    {
        Job::afterInsert(function ($info) {
            if (is_object($info)) {
                $info = $info->toArray();
            }
            if (isset($info['company_id'])) {
                model('Company')->where('id', $info['company_id'])->setField('updatetime', time());
            } else if (isset($info['uid'])) {
                model('Company')->where('uid', $info['uid'])->setField('updatetime', time());
            }
        });
        Job::afterUpdate(function ($info) {
            if (is_object($info)) {
                $info = $info->toArray();
            }
            if (isset($info['company_id'])) {
                model('Company')->where('id', $info['company_id'])->setField('updatetime', time());
            } else if (isset($info['uid'])) {
                model('Company')->where('uid', $info['uid'])->setField('updatetime', time());
            }
        });
        Job::afterDelete(function ($info) {
            if (is_object($info)) {
                $info = $info->toArray();
            }
            if (isset($info['company_id'])) {
                model('Company')->where('id', $info['company_id'])->setField('updatetime', time());
            } else if (isset($info['uid'])) {
                model('Company')->where('uid', $info['uid'])->setField('updatetime', time());
            }
        });
    }

    protected function setUpdatetimeAttr($value = null)
    {
        return $value === null ? time() : $value;
    }

    public function setUserStatus($uid, $status)
    {
        $model = $this->where('uid', $uid)->setField('user_status', $status);
        return;
    }

    /**
     * 更新索引表
     */
    public function refreshSearch($id, $userinfo = [])
    {
        $jobinfo = self::find($id);
        if (!$jobinfo) {
            return;
        }

        $job_status = true; //标记信息是否有效

        if (empty($userinfo)) {
            $userinfo = model('Member')->find($jobinfo['uid']);
        }
        $companyinfo = model('Company')
            ->where('id', $jobinfo['company_id'])
            ->find();
        if (
            !$userinfo ||
            $userinfo['status'] == 0 ||
            $jobinfo['audit'] != 1 ||
            $jobinfo['is_display'] == 0
            || $companyinfo === null
            || $companyinfo['is_display'] == 0
        ) {
            $job_status = false; //无效信息，不进索引表
        }
        if ($job_status) {
            $membersetmeal = model('MemberSetmeal')
                ->where('uid', $jobinfo['uid'])
                ->find();
            $category_company_nature = model('Category')->getCache(
                'QS_company_type'
            );
            $search_rtime_data['id'] = $jobinfo['id'];
            $search_rtime_data['uid'] = $jobinfo['uid'];
            $search_rtime_data['company_id'] = $jobinfo['company_id'];
            $search_rtime_data['company_nature_id'] = $companyinfo['nature'];
            $search_rtime_data['emergency'] = $jobinfo['emergency'];
            $search_rtime_data['license'] = $companyinfo['audit'] == 1 ? 1 : 0;
            $search_rtime_data['stick'] = $jobinfo['stick'];
            $search_rtime_data['setmeal_id'] = $membersetmeal['setmeal_id'];
            $search_rtime_data['nature'] = $jobinfo['nature'];
            $search_rtime_data['category1'] = $jobinfo['category1'];
            $search_rtime_data['category2'] = $jobinfo['category2'];
            $search_rtime_data['category3'] = $jobinfo['category3'];
            $search_rtime_data['category'] = $jobinfo['category'];
            $search_rtime_data['trade'] = $companyinfo['trade'];
            $search_rtime_data['scale'] = $companyinfo['scale'];
            $search_rtime_data['district1'] = $jobinfo['district1'];
            $search_rtime_data['district2'] = $jobinfo['district2'];
            $search_rtime_data['district3'] = $jobinfo['district3'];
            $search_rtime_data['district'] = $jobinfo['district'];
            $search_rtime_data['tag'] = $jobinfo['tag'];
            $search_rtime_data['education'] = $jobinfo['education'];
            $search_rtime_data['experience'] = $jobinfo['experience'];
            $search_rtime_data['minwage'] = $jobinfo['minwage'];
            $search_rtime_data['maxwage'] = $jobinfo['maxwage'];
            $search_rtime_data['refreshtime'] = $jobinfo['refreshtime'];
            $search_rtime_data['map_lat'] = $jobinfo['map_lat'];
            $search_rtime_data['map_lng'] = $jobinfo['map_lng'];
            $search_key_data = $search_rtime_data;
            $search_key_data['jobname'] = $jobinfo['jobname'];
            $search_key_data['companyname'] = $companyinfo['companyname'];
            $search_key_data['company_nature'] = isset(
                $category_company_nature[$companyinfo['nature']]
            )
                ? $category_company_nature[$companyinfo['nature']]
                : '';
        }

        \think\Db::startTrans();
        try {
            model('JobSearchRtime')->destroy($id);
            model('JobSearchKey')->destroy($id);
            if ($job_status) {
                if (
                    false ===
                    model('JobSearchRtime')
                        ->allowField(true)
                        ->save($search_rtime_data)
                ) {
                    throw new \Exception(model('JobSearchRtime')->getError());
                }
                if (
                    false ===
                    model('JobSearchKey')
                        ->allowField(true)
                        ->save($search_key_data)
                ) {
                    throw new \Exception(model('JobSearchKey')->getError());
                }
            }
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }
        return;
    }

    /**
     * 更新索引表(批量)
     */
    public function refreshSearchBatch($idarr)
    {
        if (!is_array($idarr) || empty($idarr)) {
            $this->error = '参数错误';
            return false;
        }
        //查出所有的职位信息
        $jobinfo_list = $this->where('id', 'in', $idarr)->select();
        //查出所有的uid
        $uid_arr = [];
        foreach ($jobinfo_list as $key => $jobinfo) {
            $uid_arr[] = $jobinfo['uid'];
        }
        //查出所有的member信息，用于判断用户是否是暂停状态
        $membeninfo_list = [];
        if (!empty($uid_arr)) {
            $membeninfo_list = model('Member')
                ->where('uid', 'in', $uid_arr)
                ->column('uid,status,mobile', 'uid');
        }
        //查出所有的company信息，用于判断企业是否是不显示状态
        $companyinfo_list = model('Company')
            ->where('uid', 'in', $uid_arr)
            ->column('id,uid,nature,audit,trade,scale,companyname,is_display', 'id');
        //整理出需要整理索引的职位信息(所有的有效职位)
        $valid_jobinfo_list = [];
        foreach ($jobinfo_list as $key => $jobinfo) {
            if (
                isset($membeninfo_list[$jobinfo['uid']]) &&
                $membeninfo_list[$jobinfo['uid']]['status'] == 1 &&
                $jobinfo['audit'] == 1 &&
                $jobinfo['is_display'] == 1 &&
                isset($companyinfo_list[$jobinfo['company_id']]) &&
                $companyinfo_list[$jobinfo['company_id']]['is_display'] == 1
            ) {
                $valid_jobinfo_list[] = $jobinfo->toArray();
            }
        }
        //如果没有有效职位，就只删除索引表中数据即可
        if (empty($valid_jobinfo_list)) {
            model('JobSearchRtime')->destroy($idarr);
            model('JobSearchKey')->destroy($idarr);
            return;
        }

        //------------------开始整理索引更新数据-----------------------
        //查出所有的会员套餐信息
        $member_setmeal_list = model('MemberSetmeal')
            ->where('uid', 'in', $uid_arr)
            ->column('uid,id,setmeal_id', 'uid');

        $insert_rtime_data = [];
        $insert_key_data = [];
        foreach ($valid_jobinfo_list as $key => $jobinfo) {
            $companyinfo = isset($companyinfo_list[$jobinfo['company_id']])
                ? $companyinfo_list[$jobinfo['company_id']]
                : [];
            if (empty($companyinfo)) {
                continue;
            }
            $membersetmeal = isset($member_setmeal_list[$jobinfo['uid']])
                ? $member_setmeal_list[$jobinfo['uid']]
                : [];
            if (empty($membersetmeal)) {
                continue;
            }
            $search_rtime_data = [];
            $search_rtime_data['id'] = $jobinfo['id'];
            $search_rtime_data['uid'] = $jobinfo['uid'];
            $search_rtime_data['company_id'] = $jobinfo['company_id'];
            $search_rtime_data['company_nature_id'] = $companyinfo['nature'];
            $search_rtime_data['emergency'] = $jobinfo['emergency'];
            $search_rtime_data['license'] = $companyinfo['audit'] == 1 ? 1 : 0;
            $search_rtime_data['stick'] = $jobinfo['stick'];
            $search_rtime_data['setmeal_id'] = $membersetmeal['setmeal_id'];
            $search_rtime_data['nature'] = $jobinfo['nature'];
            $search_rtime_data['category1'] = $jobinfo['category1'];
            $search_rtime_data['category2'] = $jobinfo['category2'];
            $search_rtime_data['category3'] = $jobinfo['category3'];
            $search_rtime_data['category'] = $jobinfo['category'];
            $search_rtime_data['trade'] = $companyinfo['trade'];
            $search_rtime_data['scale'] = $companyinfo['scale'];
            $search_rtime_data['district1'] = $jobinfo['district1'];
            $search_rtime_data['district2'] = $jobinfo['district2'];
            $search_rtime_data['district3'] = $jobinfo['district3'];
            $search_rtime_data['district'] = $jobinfo['district'];
            $search_rtime_data['tag'] = $jobinfo['tag'];
            $search_rtime_data['education'] = $jobinfo['education'];
            $search_rtime_data['experience'] = $jobinfo['experience'];
            $search_rtime_data['minwage'] = $jobinfo['minwage'];
            $search_rtime_data['maxwage'] = $jobinfo['maxwage'];
            $search_rtime_data['refreshtime'] = $jobinfo['refreshtime'];
            $search_rtime_data['map_lat'] = $jobinfo['map_lat'];
            $search_rtime_data['map_lng'] = $jobinfo['map_lng'];

            $search_key_data = [];
            $search_key_data = $search_rtime_data;
            $search_key_data['jobname'] = $jobinfo['jobname'];
            $search_key_data['companyname'] = $companyinfo['companyname'];
            $search_key_data['company_nature'] = isset(
                $category_company_nature[$companyinfo['nature']]
            )
                ? $category_company_nature[$companyinfo['nature']]
                : '';
            $insert_rtime_data[] = $search_rtime_data;
            $insert_key_data[] = $search_key_data;
        }
        if (!empty($insert_rtime_data) && !empty($insert_key_data)) {
            \think\Db::startTrans();
            try {
                model('JobSearchRtime')->destroy($idarr);
                model('JobSearchKey')->destroy($idarr);
                if (
                    false ===
                    model('JobSearchRtime')->saveAll($insert_rtime_data, false)
                ) {
                    throw new \Exception(model('JobSearchRtime')->getError());
                }
                if (
                    false ===
                    model('JobSearchKey')->saveAll($insert_key_data, false)
                ) {
                    throw new \Exception(model('JobSearchKey')->getError());
                }
                \think\Db::commit();
            } catch (\Exception $e) {
                \think\Db::rollBack();
                $this->error = $e->getMessage();
                return false;
            }
        }
        return;
    }

    public function backendEdit($data)
    {
        $job_id = $data['id'];
        $data_contact = $data['contact'];
        unset($data['contact']);
        $data_basic = $data;
        $data_basic['category'] =
            $data_basic['category3'] != 0
                ? $data_basic['category3']
                : ($data_basic['category2'] != 0
                ? $data_basic['category2']
                : $data_basic['category1']);
        $data_basic['district'] =
            $data_basic['district3'] != 0
                ? $data_basic['district3']
                : ($data_basic['district2'] != 0
                ? $data_basic['district2']
                : $data_basic['district1']);
        if (isset($data_basic['tag'])) {
            $data_basic['tag'] = !empty($data_basic['tag'])
                ? implode(',', $data_basic['tag'])
                : '';
        }
        //开启事务
        \think\Db::startTrans();
        try {
            if (
                false ===
                model('Job')
                    ->validate(true)
                    ->allowField(true)
                    ->save($data_basic, ['id' => $job_id])
            ) {
                throw new \Exception(model('Job')->getError());
            }
            if ($data_contact['use_company_contact'] == 1) {
                $result = model('JobContact')
                    ->allowField(true)
                    ->save($data_contact, [
                        'jid' => $job_id
                    ]);
            } else {
                $result = model('JobContact')
                    ->validate(true)
                    ->allowField(true)
                    ->save($data_contact, [
                        'jid' => $job_id
                    ]);
            }
            if (
                false === $result
            ) {
                throw new \Exception(model('JobContact')->getError());
            }
            //提交事务
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }
        $this->refreshSearch($job_id);

        return true;
    }

    /**
     * 根据uid删除职位相关的所有信息
     * type $uid = array
     */
    public function deleteJobByUids($uid)
    {
        $this->where('uid', 'in', $uid)->delete();
        model('JobContact')
            ->where('uid', 'in', $uid)
            ->delete();
        model('JobSearchKey')
            ->where('uid', 'in', $uid)
            ->delete();
        model('JobSearchRtime')
            ->where('uid', 'in', $uid)
            ->delete();

        return;
    }

    /**
     * 根据id删除职位相关的所有信息
     * type $id = array
     */
    public function deleteJobByIds($id)
    {
        \think\Db::startTrans();
        try {
            $this->where('id', 'in', $id)->delete();
            model('JobContact')
                ->where('jid', 'in', $id)
                ->delete();
            model('JobSearchKey')
                ->where('id', 'in', $id)
                ->delete();
            model('JobSearchRtime')
                ->where('id', 'in', $id)
                ->delete();
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }
        return;
    }

    /**
     * 审核职位
     */
    public function setAudit($idarr, $audit, $reason = '')
    {
        $timestamp = time();
        $this->where('id', 'in', $idarr)->setField('audit', $audit);
        $audit_log = [];
        $joblist = $this->where('id', 'in', $idarr)
            ->field('id,uid,jobname')
            ->select();
        foreach ($joblist as $key => $value) {
            $uid_arr[] = $value['uid'];
            $arr['jobid'] = $value['id'];
            $arr['audit'] = $audit;
            $arr['reason'] = $reason;
            $arr['addtime'] = $timestamp;
            $audit_log[] = $arr;
        }
        model('JobAuditLog')->saveAll($audit_log);
        $this->refreshSearchBatch($idarr);

        //通知
        if ($audit == 1) {
            foreach ($joblist as $key => $value) {
                model('NotifyRule')->notify(
                    $value['uid'],
                    1,
                    'job_audit_success',
                    [
                        'jobname' => $value['jobname'],
                    ],
                    0,
                    '',
                    [
                        'job_id' => $value['id']
                    ]
                );
                //微信通知
                model('WechatNotifyRule')->notify(
                    $value['uid'],
                    1,
                    'job_audit_success',
                    [
                        '您发布的' . $value['jobname'] . '已通过审核',
                        '通过审核',
                        date('Y年m月d日 H:i'),
                        '点击开启招聘加速通道，省心快招人'
                    ],
                    'member/order/add/common?type=service'
                );
            }

        }
        if ($audit == 2) {
            foreach ($joblist as $key => $value) {
                model('NotifyRule')->notify(
                    $value['uid'],
                    1,
                    'job_audit_fail',
                    [
                        'jobname' => $value['jobname'],
                        'reason' => $reason,
                    ],
                    0,
                    '',
                    [
                        'job_id' => $value['id']
                    ]
                );
                //微信通知
                model('WechatNotifyRule')->notify(
                    $value['uid'],
                    1,
                    'job_audit_fail',
                    [
                        '您发布的' . $value['jobname'] . '未通过审核',
                        '审核未通过',
                        date('Y年m月d日 H:i'),
                        $reason,
                        '请修改后再次发布，点击去修改。'
                    ],
                    'member/company/jobedit/' . $value['id']
                );
            }
        }

        return;
    }

    /**
     * 刷新职位
     */
    public function refreshJob($jobid, $uid)
    {
        $jobid = intval($jobid);
        $uid = intval($uid);
        $timestamp = time();

        model('Job')
            ->where('id', 'eq', $jobid)
            ->setField('refreshtime', $timestamp);
        model('JobSearchKey')
            ->where('id', 'eq', $jobid)
            ->setField('refreshtime', $timestamp);
        model('JobSearchRtime')
            ->where('id', 'eq', $jobid)
            ->setField('refreshtime', $timestamp);
        model('Company')
            ->where('uid', 'eq', $uid)
            ->setField('refreshtime', $timestamp);
        model('RefreshJobLog')->save([
            'uid' => $uid,
            'jobid' => $jobid,
            'addtime' => $timestamp,
            'platform' => config('platform'),
        ]);

        return true;
    }

    /**
     * 批量刷新职位
     */
    public function refreshJobBatch($jobid, $uid)
    {
        $jobid_arr = is_array($jobid) ? $jobid : [$jobid];
        $uid_arr = is_array($uid) ? $uid : [$uid];
        $timestamp = time();
        model('Job')
            ->where('id', 'in', $jobid_arr)
            ->setField('refreshtime', $timestamp);
        model('JobSearchKey')
            ->where('id', 'in', $jobid_arr)
            ->setField('refreshtime', $timestamp);
        model('JobSearchRtime')
            ->where('id', 'in', $jobid_arr)
            ->setField('refreshtime', $timestamp);
        model('Company')
            ->where('uid', 'in', $uid_arr)
            ->setField('refreshtime', $timestamp);
        $model = model('RefreshJobLog');
        foreach ($jobid_arr as $key => $value) {
            $_model = clone $model;
            $_model->save([
                'uid' => is_array($uid) ? $uid[$key] : $uid,
                'jobid' => $value,
                'addtime' => $timestamp,
                'platform' => config('platform'),
            ]);
            unset($_model);
        }
        return true;
    }

    /**
     * 后台刷新职位
     */
    public function refreshJobBackend($jobid, $uid)
    {
        $jobid_arr = is_array($jobid) ? $jobid : [$jobid];
        $uid_arr = is_array($uid) ? $uid : [$uid];
        $timestamp = time();
        model('Job')
            ->where('id', 'in', $jobid_arr)
            ->setField('refreshtime', $timestamp);
        model('JobSearchKey')
            ->where('id', 'in', $jobid_arr)
            ->setField('refreshtime', $timestamp);
        model('JobSearchRtime')
            ->where('id', 'in', $jobid_arr)
            ->setField('refreshtime', $timestamp);
        model('Company')
            ->where('uid', 'in', $uid_arr)
            ->setField('refreshtime', $timestamp);
        return true;
    }

    /**
     * 增加查看数
     */
    public function addViewLog($jobid, $company_uid = 0, $personal_uid = 0)
    {
        $rand_click = config('global_config.rand_click_job');
        $rand_click = intval($rand_click);
        if ($rand_click <= 1) {
            $rand_click = 1;
        } else {
            $rand_click = rand(1, $rand_click);
        }
        $this->where('id', 'eq', $jobid)->setInc('click', $rand_click);
        if ($company_uid > 0 && $personal_uid > 0) {
            $resume_info = model('Resume')
                ->field('id,fullname')
                ->where('uid', $personal_uid)
                ->find();
            if ($resume_info === null) {
                return false;
            }
            $view_data['personal_uid'] = $personal_uid;
            $view_data['jobid'] = $jobid;
            $view_history = model('ViewJob')
                ->where($view_data)
                ->find();
            if ($view_history === null) {
                $view_data['company_uid'] = $company_uid;
                $view_data['addtime'] = time();
                model('ViewJob')->save($view_data);
                $stat_view_data['company_uid'] = $company_uid;
                $stat_view_data['personal_uid'] = $personal_uid;
                $stat_view_data['jobid'] = $jobid;
                $stat_view_data['addtime'] = strtotime('today');
                model('StatViewJob')->save($stat_view_data);
            } else {
                $view_history->addtime = time();
                $view_history->save();
            }
            //通知
            $job_info = model('Job')
                ->field('id,jobname')
                ->where('id', $jobid)
                ->find();
            model('NotifyRule')->notify(
                $company_uid,
                1,
                'job_view',
                [
                    'fullname' => $resume_info['fullname'],
                    'jobname' => $job_info['jobname'],
                ],
                $resume_info['id']
            );
        }
    }

    /**
     * 获取可发布职位数
     */
    public function getEnableJobaddNum($uid)
    {
        $setmeal = model('Member')->getMemberSetmeal($uid);
        $setmeal_joball = $setmeal['jobs_meanwhile'];
        if ($setmeal_joball == 0) {
            return 0;
        }
        $published_joball = model('Job')->where('uid', $uid)->where('is_display', 1)->count();
        $counter = $setmeal_joball - $published_joball;
        return $counter < 0 ? 0 : $counter;
    }

    /**
     * 获取联系方式
     */
    public function getContact($jobinfo, $userinfo)
    {
        $return['show_contact'] = 1;
        $return['show_contact_note'] = '';
        $contact_info = model('JobContact')
            ->field('id,jid,uid', true)
            ->where(['jid' => ['eq', $jobinfo['id']]])
            ->find();
        //web端查看联系方式条件 0游客 1已登录 2已登录有简历 3投递后显示
        do {
            if (config('platform') == 'web') {
                if (config('global_config.showjobcontact') == 0) {
                    break;
                }
                if (config('global_config.showjobcontact') == 1) {
                    if ($userinfo === null) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_login';
                        break;
                    } else if ($userinfo->utype == 1) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_personal_login';
                        break;
                    }
                }
                if (config('global_config.showjobcontact') == 2) {
                    if ($userinfo === null) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_login';
                        break;
                    } else if ($userinfo->utype == 1) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_personal_login';
                        break;
                    } else if (model('Resume')->where('uid', $userinfo->uid)->field('id')->find() === null) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_resume';
                        break;
                    }
                }
                if (config('global_config.showjobcontact') == 3) {
                    if ($userinfo === null) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_login';
                        break;
                    } else if ($userinfo->utype == 1) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_personal_login';
                        break;
                    } else if (model('Resume')->where('uid', $userinfo->uid)->field('id')->find() === null) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_resume';
                        break;
                    } else if (model('JobApply')->where('personal_uid', $userinfo->uid)->where('jobid', $jobinfo['id'])->field('id')->find() === null) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_apply';
                        break;
                    }
                }
            } else {
                //移动端查看联系方式条件  0游客 1已登录 2已登录有简历 3投递后显示
                if (config('global_config.showjobcontact_mobile') == 0) {
                    break;
                }
                if (config('global_config.showjobcontact_mobile') == 1) {
                    if ($userinfo === null) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_login';
                        break;
                    } else if ($userinfo->utype == 1) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_personal_login';
                        break;
                    }
                }
                if (config('global_config.showjobcontact_mobile') == 2) {
                    if ($userinfo === null) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_login';
                        break;
                    } else if ($userinfo->utype == 1) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_personal_login';
                        break;
                    } else if (model('Resume')->where('uid', $userinfo->uid)->field('id')->find() === null) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_resume';
                        break;
                    }
                }
                if (config('global_config.showjobcontact_mobile') == 3) {
                    if ($userinfo === null) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_login';
                        break;
                    } else if ($userinfo->utype == 1) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_personal_login';
                        break;
                    } else if (model('Resume')->where('uid', $userinfo->uid)->field('id')->find() === null) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_resume';
                        break;
                    } else if (model('JobApply')->where('personal_uid', $userinfo->uid)->where('jobid', $jobinfo['id'])->field('id')->find() === null) {
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_apply';
                        break;
                    }
                }
            }
        } while (0);

        if ($return['show_contact'] == 1) {
            if ($contact_info['is_display'] == 0) {
                //企业关闭显示
                $return['show_contact'] = 0;
                $return['show_contact_note'] = 'company_close';
            }
        }

        /**
         * 【ID1000443】
         * 【新增】职位相关通知通知职位联系人及职位联系方式自主关闭显示联系手机号
         * yx - 2022.12.30
         * 1.当使用其他联系方式时，取其他联系方式下的隐私状态`is_secrecy`
         * 隐私状态[0-不对外显示仅接收通知|1-对外显示并接收通知]
         */
        $return['is_secrecy'] = $contact_info['is_secrecy'];

        /**
         * 隐藏联系方式，使用*号代替
         * 【旧】：
         *  if ($return['show_contact'] == 1)写在条件中
         */
        if ($contact_info['use_company_contact'] == 1) {
            $return['contact_info'] = model('CompanyContact')
                ->field('id,comid,uid', true)
                ->where('comid', $jobinfo['company_id'])
                ->find();
            /**
             * 【ID1000443】
             * 【新增】职位相关通知通知职位联系人及职位联系方式自主关闭显示联系手机号
             * yx - 2022.12.30
             * 2.当使用企业资料联系方式时，取企业资料`is_secrecy`
             * 隐私状态[0-不对外显示仅接收通知|1-对外显示并接收通知]
             */
            $return['is_secrecy'] = $return['contact_info']['is_secrecy'];
        } else {
            $return['contact_info'] = $contact_info;
            unset(
                $return['contact_info']['is_display'],
                $return['contact_info']['use_company_contact']
            );
        }

        if ($return['show_contact'] == 1) {
            return $return;
        } else {
            /**
             * 隐藏联系方式，使用*号代替
             * 【旧】：
             *  $return['contact_info'] = [];
             */
            $tmp = $return['contact_info']->toArray();
            $contact_return = [];
            foreach ($tmp as $contact_key => $contact_value) {
                if (!empty($contact_value)) {
                    $contact_return[$contact_key] = srtAsteriskReplace($contact_value, 4);
                } else {
                    $contact_return[$contact_key] = '*';
                }
            }
            $return['contact_info'] = $contact_return;
            return $return;
        }
    }

    /**
     * 刷新职位信息
     * @access public
     * @param array $params [请求参数]
     * @param integer $source [来源:1|系统级刷新,2|会员手动刷新,3|公众号一键刷新]
     * @return array
     * Date Time：2022年3月18日16:02:39
     * @author chenyang
     */
    public function refreshJobData($params, $source = 1)
    {
        $currentTime = time();

        switch ($source) {
            // 系统级刷新
            case 1:
                break;

            // 会员手动刷新
            case 2:
                if (!isset($params['uid']) || empty($params['uid'])) {
                    return callBack(false, '缺少UID');
                }

                // 校验简历刷新条件
                $validateParams = [
                    'id' => $params['id'],
                    'uid' => $params['uid'],
                    'id_total' => is_array($params['id']) ? count($params['id']) : 0,
                    'current_time' => $currentTime,
                ];
                $validateResult = $this->_validateRefreshJob($validateParams);
                if ($validateResult['status'] === false) {
                    return callBack(false, $validateResult['msg'], $validateResult['data']);
                }
                break;

            // 公众号一键刷新
            case 3:
                if (!isset($params['uid']) || empty($params['uid'])) {
                    return callBack(false, '缺少UID');
                }

                // 校验简历刷新条件
                $validateParams = [
                    'id' => $params['id'],
                    'uid' => $params['uid'],
                    'id_total' => is_array($params['id']) ? count($params['id']) : 0,
                    'current_time' => $currentTime,
                ];
                $validateResult = $this->_validateOneRefreshJob($validateParams);
                if ($validateResult['status'] === false) {
                    return callBack(false, $validateResult['msg'], $validateResult['data']);
                }
                break;

            default:
                return callBack(false, '刷新职位-来源参数有误');
        }

        if (!isset($params['id']) || empty($params['id'])) {
            return callBack(false, '缺少职位ID');
        }

        // 获取职位信息
        $condition = [
            'id' => $params['id'],
            'audit' => 1,
            'is_display' => 1,
        ];
        if (is_array($params['id'])) {
            $condition['id'] = ['in', $params['id']];
        }
        if (isset($params['uid']) && !empty($params['uid'])) {
            $condition['uid'] = $params['uid'];
        }
        $jobList = model('Job')->where($condition)->field('id,uid,jobname,refreshtime')->select();
        if (empty($jobList) || $jobList === null) {
            return callBack(false, '没有可刷新的职位');
        }
        $jobList = collection($jobList)->toArray();

        $platform = config('platform') ? config('platform') : $params['platform'];
        foreach ($jobList as $jobInfo) {
            $jobIdArr[] = $jobInfo['id'];
            $uidArr[] = $jobInfo['uid'];
            $saveData[] = [
                'uid' => $jobInfo['uid'],
                'jobid' => $jobInfo['id'],
                'addtime' => $currentTime,
                'platform' => $platform,
            ];
            $logData[] = [
                'uid' => $jobInfo['uid'],
                'content' => '套餐特权-免费刷新职位【' . $jobInfo['jobname'] . '】',
                'addtime' => $currentTime
            ];
        }

        $condition = [
            'id' => ['in', $jobIdArr]
        ];
        // 更新职位刷新时间
        model('Job')->where($condition)->setField('refreshtime', $currentTime);
        model('JobSearchKey')->where($condition)->setField('refreshtime', $currentTime);
        model('JobSearchRtime')->where($condition)->setField('refreshtime', $currentTime);
        // 更新公司刷新时间
        $uidArr = array_unique($uidArr);
        model('Company')->where(['uid' => ['in', $uidArr]])->setField('refreshtime', $currentTime);

        // 判断是否记录刷新职位log
        if (isset($params['refresh_log']) && $params['refresh_log'] == true) {
            model('RefreshJobLog')->saveAll($saveData);
        }

        ############### 系统级刷新不记录在log表中 ###############
        if ($source == 2) {
            model('MemberSetmealLog')->allowField(true)->saveAll($logData);
        }

        return callBack(true, 'SUCCESS', $jobList);
    }

    /**
     * 校验职位刷新条件
     * @access private
     * @param array $params [请求参数]
     * //     * @param integer ['id']           [职位ID]
     * //     * @param integer ['uid']          [会员ID]
     * //     * @param integer ['id_total']     [职位ID数]
     * //     * @param integer ['current_time'] [当前时间]
     * @return array
     * Date Time：2022年3月18日18:37:54
     * @author chenyang
     */
    private function _validateRefreshJob($params)
    {
        // 获取会员套餐
        $memberSetmeal = model('Member')->getMemberSetmeal($params['uid']);

        $done = 1;
        do {
            // 校验每天可刷新简历次数
            if ($memberSetmeal['refresh_jobs_free_perday'] <= 0) {
                $done = 0;
                break;
            }
            // 获取今天的职位刷新次数
            $refreshTotal = model('RefreshJobLog')
                ->whereTime('addtime', 'today')
                ->where('uid', $params['uid'])
                ->where('source', 'IN', [2, 3])
                ->count();
            // 校验每天可刷新简历次数
            if ($refreshTotal >= $memberSetmeal['refresh_jobs_free_perday']) {
                $done = 0;
                break;
            }
            // 校验如果是批量刷新，当天刷新次数 + 本次要刷新的次数 不能大于 可刷新次数
            if ($params['id_total'] >= 0 && $refreshTotal + $params['id_total'] > $memberSetmeal['refresh_jobs_free_perday']) {
                $done = 0;
                break;
            }
        } while (0);

        if ($params['id_total'] > 0 && $done == 0) {
            $errorMsg = '您当前共有' . $params['id_total'] . '条在招职位，今天免费刷新次数已用完，请前往职位列表单条刷新。';
            return callBack(false, $errorMsg, ['done' => 0]);
        }

        // 当批量查询时不校验快捷消费与刷新间隔数
        if ($params['id_total'] <= 0) {
            if ($done == 0) {
                ######################### 快捷消费 #########################
                $returnData['done'] = 0;
                // 判断是否设置刷新职位允许积分抵扣开关
                if (config('global_config.single_job_refresh_enable_points_deduct') == 1) {
                    // 获取当前用户积分数
                    $memberPoints = model('Member')->getMemberPoints($params['uid']);
                    // 获取刷新职位允许积分抵扣数
                    $deducePoints = config('global_config.single_job_refresh_deduce_points');
                    // 判断当前积分是否足够刷新职位抵扣
                    if ($memberPoints >= $deducePoints) {
                        $returnData['use_type'] = 'points';
                        $returnData['need_points'] = $deducePoints;
                    }
                }
                // 没有积分则使用价格支付
                if (!isset($returnData['use_type'])) {
                    $returnData['use_type'] = 'money';
                    $returnData['need_expense'] = config('global_config.single_job_refresh_expense');
                }
                $returnData['discount'] = $memberSetmeal['service_added_discount'];
                return callBack(false, '今日免费刷新次数不足', $returnData);
            }

            // 判断是否设置职位刷新间隔
            $refreshJobsSpace = config('global_config.refresh_jobs_space');
            if ($refreshJobsSpace > 0) {
                // 获取职位log刷新时间
                $resumeLogInfo = model('RefreshJobLog')
                    ->field('addtime')
                    ->where('uid', $params['uid'])
                    ->where('jobid', $params['id'])
                    ->whereTime('addtime', 'today')
                    ->order(['addtime' => 'desc'])
                    ->find();
                // 校验职位刷新间隔
                if (!empty($resumeLogInfo) && $params['current_time'] - $resumeLogInfo['addtime'] < $refreshJobsSpace * 60) {
                    $errorMsg = '刷新间隔不能小于' . $refreshJobsSpace . '分钟，请稍后再试';
                    return callBack(false, $errorMsg);
                }
            }
        }
        return callBack(true, 'SUCCESS');
    }

    /**
     * 校验职位刷新条件
     * @access private
     * @param array $params [请求参数]
     * //     * @param integer ['id']           [职位ID]
     * //     * @param integer ['uid']          [会员ID]
     * //     * @param integer ['id_total']     [职位ID数]
     * //     * @param integer ['current_time'] [当前时间]
     * @return array
     * Date Time：2022年3月18日18:37:54
     * @author chenyang
     */
    private function _validateOneRefreshJob($params)
    {
        // 获取会员套餐
        $memberSetmeal = model('Member')->getMemberSetmeal($params['uid']);

        $done = 1;
        do {
            // 校验每天可刷新简历次数
            if ($memberSetmeal['refresh_jobs_free_perday'] <= 0) {
                $done = 0;
                break;
            }
            // 获取今天的职位刷新次数
            $refreshTotal = model('RefreshJobLog')
                ->whereTime('addtime', 'today')
                ->where('uid', $params['uid'])
                ->where('source', 'IN', [2, 3])
                ->count();
            // 校验每天可刷新简历次数
            if ($refreshTotal >= $memberSetmeal['refresh_jobs_free_perday']) {
                $done = 0;
                break;
            }
            // 校验如果是批量刷新，当天刷新次数 + 本次要刷新的次数 不能大于 可刷新次数
            if ($params['id_total'] >= 0 && $refreshTotal + $params['id_total'] > $memberSetmeal['refresh_jobs_free_perday']) {
                $done = 0;
                break;
            }
        } while (0);

        if ($params['id_total'] > 0 && $done == 0) {
            $errorMsg = '您当前共有' . $params['id_total'] . '条在招职位，今天免费刷新次数已用完，请前往职位列表单条刷新。';
            return callBack(false, $errorMsg);
        }

        return callBack(true, 'SUCCESS');
    }


    /**
     * 刷新职位大脑方法
     * @param $params
     * @param $source [来源:1|后台刷新,2|免费刷新,3|公众号一键刷新,4|智能刷新,5|快捷支付刷新]
     * @return void
     * Date Time：2022年11月11日09:50:44
     * @author yx
     */
    public function refreshJobMind($params, $source = 1)
    {
        switch ($source) {
            // 后台刷新
            case 1:
                return $this->_adminRefresh($params);

            // 会员手动刷新
            case 2:
                return $this->_freeRefresh($params);

            // 公众号一键刷新
            case 3:
                return $this->_wechatRefresh($params);

            // 智能刷新
            case 4:
                return $this->_incrementRefresh($params);

            // 快捷支付刷新
            case 5:
                return $this->_quickRefresh($params);

            default:
                return callBack(false, '刷新职位-来源参数有误');
        }
    }


    /**
     * 后台刷新职位
     * @param $params
     * @return array
     * Date Time：2022年11月11日09:50:44
     * @author yx
     */
    private function _adminRefresh($params)
    {
        $currentTime = time();

        if (!isset($params['id']) || empty($params['id'])) {
            return callBack(false, '缺少职位ID');
        }

        // 获取职位信息
        $condition = [
            'id' => $params['id'],
            'audit' => 1,
            'is_display' => 1,
        ];
        if (is_array($params['id'])) {
            $condition['id'] = ['in', $params['id']];
        }
        if (isset($params['uid']) && !empty($params['uid'])) {
            $condition['uid'] = $params['uid'];
        }
        $jobList = model('Job')
            ->where($condition)
            ->field('id,uid,jobname,refreshtime')
            ->select();
        if (empty($jobList) || $jobList === null) {
            return callBack(false, '没有可刷新的职位');
        }
        $jobList = collection($jobList)->toArray();

        $platform = 'system';
        foreach ($jobList as $jobInfo) {
            $jobIdArr[] = $jobInfo['id'];
            $uidArr[] = $jobInfo['uid'];
            $saveData[] = [
                'uid' => $jobInfo['uid'],
                'jobid' => $jobInfo['id'],
                'addtime' => $currentTime,
                'platform' => $platform,
                'source' => 1
            ];
        }

        $condition = [
            'id' => ['in', $jobIdArr]
        ];
        // 更新职位刷新时间
        model('Job')->where($condition)->setField('refreshtime', $currentTime);
        model('JobSearchKey')->where($condition)->setField('refreshtime', $currentTime);
        model('JobSearchRtime')->where($condition)->setField('refreshtime', $currentTime);
        // 更新公司刷新时间
        $uidArr = array_unique($uidArr);
        model('Company')->where(['uid' => ['in', $uidArr]])->setField('refreshtime', $currentTime);

        model('RefreshJobLog')->saveAll($saveData);

        return callBack(true, 'SUCCESS', $jobList);
    }

    /**
     * 免费刷新职位
     * @param $params
     * @return array
     * Date Time：2022年11月11日09:50:44
     * @author yx
     */
    private function _freeRefresh($params)
    {
        $currentTime = time();

        if (!isset($params['uid']) || empty($params['uid'])) {
            return callBack(false, '缺少UID');
        }

        // 校验简历刷新条件
        $validateParams = [
            'id' => $params['id'],
            'uid' => $params['uid'],
            'id_total' => is_array($params['id']) ? count($params['id']) : 0,
            'current_time' => $currentTime,
        ];
        $validateResult = $this->_validateRefreshJob($validateParams);
        if ($validateResult['status'] === false) {
            return callBack(false, $validateResult['msg'], $validateResult['data']);
        }


        if (!isset($params['id']) || empty($params['id'])) {
            return callBack(false, '缺少职位ID');
        }

        // 获取职位信息
        $condition = [
            'id' => $params['id'],
            'audit' => 1,
            'is_display' => 1,
        ];
        if (is_array($params['id'])) {
            $condition['id'] = ['in', $params['id']];
        }
        if (isset($params['uid']) && !empty($params['uid'])) {
            $condition['uid'] = $params['uid'];
        }
        if (is_array($params['uid'])) {
            $condition['uid'] = ['in', $params['uid']];
        }
        $jobList = model('Job')
            ->where($condition)
            ->field('id,uid,jobname,refreshtime')
            ->select();
        if (empty($jobList) || $jobList === null) {
            return callBack(false, '没有可刷新的职位');
        }
        $jobList = collection($jobList)->toArray();

        $platform = config('platform') ? config('platform') : $params['platform'];
        foreach ($jobList as $jobInfo) {
            $jobIdArr[] = $jobInfo['id'];
            $uidArr[] = $jobInfo['uid'];
            $saveData[] = [
                'uid' => $jobInfo['uid'],
                'jobid' => $jobInfo['id'],
                'addtime' => $currentTime,
                'platform' => $platform,
                'source' => 2
            ];
            $logData[] = [
                'uid' => $jobInfo['uid'],
                'content' => '套餐特权-免费刷新职位【' . $jobInfo['jobname'] . '】',
                'addtime' => $currentTime
            ];
        }

        $condition = [
            'id' => ['in', $jobIdArr]
        ];
        // 更新职位刷新时间
        model('Job')->where($condition)->setField('refreshtime', $currentTime);
        model('JobSearchKey')->where($condition)->setField('refreshtime', $currentTime);
        model('JobSearchRtime')->where($condition)->setField('refreshtime', $currentTime);
        // 更新公司刷新时间
        $uidArr = array_unique($uidArr);
        model('Company')->where(['uid' => ['in', $uidArr]])->setField('refreshtime', $currentTime);

        model('RefreshJobLog')->saveAll($saveData);
        model('MemberSetmealLog')->allowField(true)->saveAll($logData);

        return callBack(true, 'SUCCESS', $jobList);
    }

    /**
     * 微信公众号一键刷新职位
     * @param $params
     * @return array
     * Date Time：2022年11月11日09:50:44
     * @author yx
     */
    private function _wechatRefresh($params)
    {
        $currentTime = time();

        if (!isset($params['uid']) || empty($params['uid'])) {
            return callBack(false, '缺少UID');
        }

        // 校验简历刷新条件
        $validateParams = [
            'id' => $params['id'],
            'uid' => $params['uid'],
            'id_total' => is_array($params['id']) ? count($params['id']) : 0,
            'current_time' => $currentTime,
        ];
        $validateResult = $this->_validateOneRefreshJob($validateParams);
        if ($validateResult['status'] === false) {
            return callBack(false, $validateResult['msg'], $validateResult['data']);
        }

        if (!isset($params['id']) || empty($params['id'])) {
            return callBack(false, '缺少职位ID');
        }

        // 获取职位信息
        $condition = [
            'id' => $params['id'],
            'audit' => 1,
            'is_display' => 1,
        ];
        if (is_array($params['id'])) {
            $condition['id'] = ['in', $params['id']];
        }
        if (isset($params['uid']) && !empty($params['uid'])) {
            $condition['uid'] = $params['uid'];
        }
        if (is_array($params['uid'])) {
            $condition['uid'] = ['in', $params['uid']];
        }
        $jobList = model('Job')
            ->where($condition)
            ->field('id,uid,jobname,refreshtime')
            ->select();
        if (empty($jobList) || $jobList === null) {
            return callBack(false, '没有可刷新的职位');
        }
        $jobList = collection($jobList)->toArray();

        $platform = config('platform') ? config('platform') : $params['platform'];
        foreach ($jobList as $jobInfo) {
            $jobIdArr[] = $jobInfo['id'];
            $uidArr[] = $jobInfo['uid'];
            $saveData[] = [
                'uid' => $jobInfo['uid'],
                'jobid' => $jobInfo['id'],
                'addtime' => $currentTime,
                'platform' => $platform,
                'source' => 3
            ];
            $logData[] = [
                'uid' => $jobInfo['uid'],
                'content' => '套餐特权-免费刷新职位【' . $jobInfo['jobname'] . '】',
                'addtime' => $currentTime
            ];
        }

        $condition = [
            'id' => ['in', $jobIdArr]
        ];
        // 更新职位刷新时间
        model('Job')->where($condition)->setField('refreshtime', $currentTime);
        model('JobSearchKey')->where($condition)->setField('refreshtime', $currentTime);
        model('JobSearchRtime')->where($condition)->setField('refreshtime', $currentTime);
        // 更新公司刷新时间
        $uidArr = array_unique($uidArr);
        model('Company')->where(['uid' => ['in', $uidArr]])->setField('refreshtime', $currentTime);

        model('RefreshJobLog')->saveAll($saveData);
        model('MemberSetmealLog')->allowField(true)->saveAll($logData);

        return callBack(true, 'SUCCESS', $jobList);
    }

    /**
     * 智能刷新职位
     * @param $params
     * @return array
     * Date Time：2022年11月11日09:50:44
     * @author yx
     */
    private function _incrementRefresh($params)
    {
        $currentTime = time();

        if (!isset($params['id']) || empty($params['id'])) {
            return callBack(false, '缺少职位ID');
        }

        if (is_array($params['id'])) {
            $params['id'] = ['in', $params['id']];
        }

        // 获取职位信息
        $condition = [
            'id' => $params['id'],
            'audit' => 1,
            'is_display' => 1,
        ];
        $jobList = model('Job')
            ->where($condition)
            ->field('id,uid,jobname,refreshtime')
            ->select();
        if (empty($jobList) || $jobList === null) {
            return callBack(false, '没有可刷新的职位');
        }

        $jobList = collection($jobList)->toArray();

        $platform = config('platform') ? config('platform') : $params['platform'];

        $ip = get_client_ip();
        $ip_addr = get_client_ipaddress($ip);
        $real_ip = $ip . ':' . get_client_port();

        foreach ($jobList as $jobInfo) {
            $jobIdArr[] = $jobInfo['id'];
            $uidArr[] = $jobInfo['uid'];
            $saveData[] = [
                'uid' => $jobInfo['uid'],
                'jobid' => $jobInfo['id'],
                'addtime' => $currentTime,
                'platform' => $platform,
                'source' => 4
            ];
            $actionData[] = [
                'utype' => 1,
                'uid' => $jobInfo['uid'],
                'content' => '增值服务-智能刷新职位【' . $jobInfo['jobname'] . '】',
                'addtime' => $currentTime,
                'ip' => $real_ip,
                'ip_addr' => $ip_addr,
                'platform' => $platform,
                'is_login' => 0
            ];
        }

        $condition = [
            'id' => ['in', $jobIdArr]
        ];
        // 更新职位刷新时间
        model('Job')->where($condition)->setField('refreshtime', $currentTime);
        model('JobSearchKey')->where($condition)->setField('refreshtime', $currentTime);
        model('JobSearchRtime')->where($condition)->setField('refreshtime', $currentTime);
        // 更新公司刷新时间
        $uidArr = array_unique($uidArr);
        model('Company')->where(['uid' => ['in', $uidArr]])->setField('refreshtime', $currentTime);

        model('RefreshJobLog')->saveAll($saveData);
        model('MemberActionLog')->allowField(true)->saveAll($actionData);

        return callBack(true, 'SUCCESS', $jobList);
    }

    /**
     * 快捷支付刷新职位
     * @param $params
     * @return array
     * Date Time：2022年11月11日09:50:44
     * @author yx
     */
    private function _quickRefresh($params)
    {
        $currentTime = time();

        if (!isset($params['id']) || empty($params['id'])) {
            return callBack(false, '缺少职位ID');
        }

        if (is_array($params['id'])) {
            $params['id'] = ['in', $params['id']];
        }

        // 获取职位信息
        $condition = [
            'id' => $params['id'],
            'audit' => 1,
            'is_display' => 1,
        ];
        $jobList = model('Job')
            ->where($condition)
            ->field('id,uid,jobname,refreshtime')
            ->select();
        if (empty($jobList) || $jobList === null) {
            return callBack(false, '没有可刷新的职位');
        }

        $jobList = collection($jobList)->toArray();

        $platform = config('platform') ? config('platform') : $params['platform'];

        $ip = get_client_ip();
        $ip_addr = get_client_ipaddress($ip);
        $real_ip = $ip . ':' . get_client_port();

        foreach ($jobList as $jobInfo) {
            $jobIdArr[] = $jobInfo['id'];
            $uidArr[] = $jobInfo['uid'];
            $saveData[] = [
                'uid' => $jobInfo['uid'],
                'jobid' => $jobInfo['id'],
                'addtime' => $currentTime,
                'platform' => $platform,
                'source' => 5
            ];
            $actionData[] = [
                'utype' => 1,
                'uid' => $jobInfo['uid'],
                'content' => '快捷支付-刷新职位【' . $jobInfo['jobname'] . '】',
                'addtime' => $currentTime,
                'ip' => $real_ip,
                'ip_addr' => $ip_addr,
                'platform' => $platform,
                'is_login' => 0
            ];
        }

        $condition = [
            'id' => ['in', $jobIdArr]
        ];
        // 更新职位刷新时间
        model('Job')->where($condition)->setField('refreshtime', $currentTime);
        model('JobSearchKey')->where($condition)->setField('refreshtime', $currentTime);
        model('JobSearchRtime')->where($condition)->setField('refreshtime', $currentTime);
        // 更新公司刷新时间
        $uidArr = array_unique($uidArr);
        model('Company')->where(['uid' => ['in', $uidArr]])->setField('refreshtime', $currentTime);

        model('RefreshJobLog')->saveAll($saveData);
        model('MemberActionLog')->allowField(true)->saveAll($actionData);

        return callBack(true, 'SUCCESS', $jobList);
    }
}
