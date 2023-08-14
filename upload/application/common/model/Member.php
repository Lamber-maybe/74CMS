<?php
namespace app\common\model;

class Member extends \app\common\model\BaseModel
{
    protected $readonly = [
        'uid',
        'reg_time',
        'reg_ip',
        'reg_address',
        'robot',
    ];
    protected $type = [
        'uid' => 'integer',
        'utype' => 'integer',
        'reg_time' => 'integer',
        'last_login_time' => 'integer',
        'status' => 'integer',
        'robot' => 'integer',
    ];
    protected $insert = [
        'email' => '',
        'reg_time',
        'reg_ip',
        'reg_address',
        'last_login_time' => 0,
        'last_login_ip' => '',
        'last_login_address' => '',
        'status' => 1,
        'avatar' => '',
        'robot' => 0,
    ];

    protected function setRegTimeAttr($value = null)
    {
        return $value === null ? time() : $value;
    }
    protected function setRegIpAttr($value = null)
    {
        return $value === null
        ? get_client_ip() . ':' . get_client_port()
        : $value;
    }
    protected function setRegAddressAttr($value = null)
    {
        if ($value === null) {
            $ip = get_client_ip();
            return get_client_ipaddress($ip);
        } else {
            return $value;
        }
    }
    public function setStatus($uid, $status)
    {
        $model = self::find($uid);
        $model->status = $status;
        if ($model->save()) {
            if ($model->utype == 1) {
                model('Company')->setUserStatus($uid, $status);
                $jobid_arr = model('Job')
                    ->where('uid', $uid)
                    ->column('id');
                foreach ($jobid_arr as $key => $value) {
                    model('Job')->refreshSearch($value, $model);
                }
                model('Job')->setUserStatus($uid, $status);
            } else {
                model('Resume')->refreshSearch(0, $uid, $model);
            }
        } else {
            $this->error = $model->getError();
            return false;
        }
        return;
    }
    public function makePassword($password, $randstr)
    {
        $encrypt_method =
        API_LIB_PATH .
        'encrypt_method/' .
        config('sys.pwd_encrypt_method') .
            '.php';
        require $encrypt_method;
        return $return;
    }
    /**
     * 开通企业套餐
     * $data = ['uid','setmeal_id','note']
     */
    public function setMemberSetmeal($data)
    {
        $setmeal_info = model('Setmeal')
            ->where(['id' => ['eq', intval($data['setmeal_id'])]])
            ->find();

        $data['jobs_meanwhile'] = $setmeal_info['jobs_meanwhile'];
        $data['refresh_jobs_free_perday'] =
            $setmeal_info['refresh_jobs_free_perday'];
        $data['download_resume_max_perday'] =
            $setmeal_info['download_resume_max_perday'];
        $data['service_added_discount'] =
            $setmeal_info['service_added_discount'];
        $data['enable_video_interview'] =
            $setmeal_info['enable_video_interview'];
        $data['enable_poster'] = $setmeal_info['enable_poster'];
        $data['show_apply_contact'] = $setmeal_info['show_apply_contact'];

        $check_setmeal = model('MemberSetmeal')
            ->where('uid', $data['uid'])
            ->find();
        if ($check_setmeal === null) {
            $data['deadline'] =
            $setmeal_info['days'] == 0
            ? 0
            : strtotime('+' . $setmeal_info['days'] . ' day');
            $data['download_resume_point'] =
                $setmeal_info['download_resume_point'];
            model('MemberSetmeal')
                ->allowField(true)
                ->save($data);
        } else {
            if ($check_setmeal['deadline'] > time()) {
                //没过期,时间叠加到原有套餐
                $data['deadline'] =
                $setmeal_info['days'] == 0
                ? 0
                : $check_setmeal['deadline'] +
                    $setmeal_info['days'] * 3600 * 24;
            } else {
                //已过期或者之前是无限期的，从现在开始算起
                $data['deadline'] =
                $setmeal_info['days'] == 0
                ? 0
                : strtotime('+' . $setmeal_info['days'] . ' day');
            }
            $data['download_resume_point'] =
                $check_setmeal['download_resume_point'] +
                $setmeal_info['download_resume_point'];
            model('MemberSetmeal')
                ->allowField(true)
                ->save($data, ['uid' => $data['uid']]);
        }

        $log['uid'] = $data['uid'];
        $log['content'] =
            '开通【' . $setmeal_info['name'] . '】' . $data['note'];
        $log['addtime'] = time();
        model('MemberSetmealLog')
            ->allowField(true)
            ->save($log);

        $lognote = '';
        //如果后台开通套餐时赠送了积分，备注为注册赠送
        if (isset($data['points'])) {
            if ($data['points'] > 0) {
                $lognote = '注册赠送';
            }
            //如果后台开通套餐时赠送了积分，赠送积分总和为赠送积分+套餐内积分
            $data['points'] =
                intval($data['points']) + $setmeal_info['gift_point'];
            if ($data['points'] > 0) {
                //如果后台开通套餐时赠送了积分，备注为套餐内赠送+注册赠送
                $lognote =
                $lognote == '' ? '套餐内赠送' : $lognote . '+套餐内赠送';
                $this->setMemberPoints([
                    'uid' => $data['uid'],
                    'points' => $data['points'],
                    'note' => $lognote . $data['note'],
                ]);
            }
        }else if($setmeal_info['gift_point']>0){
            $lognote = '套餐内赠送';
            $this->setMemberPoints([
                'uid' => $data['uid'],
                'points' => $setmeal_info['gift_point'],
                'note' => $lognote . $data['note'],
            ]);
        }
        //将企业、职位中的套餐id改为新的
        model('Job')
            ->where('uid', $data['uid'])
            ->setField('setmeal_id', $setmeal_info['id']);
        model('JobSearchRtime')
            ->where('uid', $data['uid'])
            ->setField('setmeal_id', $setmeal_info['id']);
        model('JobSearchKey')
            ->where('uid', $data['uid'])
            ->setField('setmeal_id', $setmeal_info['id']);
        model('Company')
            ->where('uid', $data['uid'])
            ->setField('setmeal_id', $setmeal_info['id']);
        //通知
        model('NotifyRule')->notify($data['uid'], 1, 'new_setmeal', [
            'setmeal_name' => $setmeal_info['name'],
            'overtime' =>
            $data['deadline'] == 0
            ? '无限期'
            : date('Y-m-d', $data['deadline']),
        ]);
    }
    /**
     * 设置积分
     * $data = ['uid','points','note']
     */
    public function setMemberPoints($data, $op = 1)
    {
        $param_points = $data['points'];
        $check_points = model('MemberPoints')
            ->where('uid', $data['uid'])
            ->find();
        if ($check_points === null) {
            if ($op == 2) {
                $data['points'] = 0;
            }
            model('MemberPoints')
                ->allowField(true)
                ->save($data);
        } else {
            if ($op == 1) {
                $data['points'] = $check_points['points'] + $data['points'];
            } else {
                $data['points'] = $check_points['points'] - $data['points'];
            }
            if ($data['points'] < 0) {
                $data['points'] = 0;
            }

            model('MemberPoints')
                ->allowField(true)
                ->save($data, ['uid' => $data['uid']]);
        }
        if ($data['points'] > 0) {
            $log['uid'] = $data['uid'];
            $log['op'] = $op;
            $log['points'] = $param_points;
            $log['content'] = $data['note'];
            $log['addtime'] = time();
            model('MemberPointsLog')->save($log);
        }
    }
    /**
     * 获取用户套餐内容
     */
    public function getMemberSetmeal($uid)
    {
        $info = model('MemberSetmeal')
            ->where(['uid' => ['eq', $uid]])
            ->field('id,uid', true)
            ->find();
        if ($info['deadline'] != 0 && $info['deadline'] < time()) {
            $overtime_config = config('global_config.setmeal_overtime_conf');
            $info['jobs_meanwhile'] = $overtime_config['jobs_meanwhile'];
            $info['refresh_jobs_free_perday'] =
                $overtime_config['refresh_jobs_free_perday'];
            $info['download_resume_max_perday'] =
                $overtime_config['download_resume_max_perday'];
            $info['service_added_discount'] =
                $overtime_config['service_added_discount'];
            $info['enable_video_interview'] =
                $overtime_config['enable_video_interview'];
            $info['enable_poster'] = $overtime_config['enable_poster'];
            $info['show_apply_contact'] =
                $overtime_config['show_apply_contact'];
            $info['overtime'] = 1;
        } else {
            $info['overtime'] = 0;
        }
        return $info;
    }
    /**
     * 获取用户积分数
     */
    public function getMemberPoints($uid)
    {
        $info = model('MemberPoints')
            ->where('uid', $uid)
            ->find();
        if ($info === null) {
            $return = 0;
        } else {
            $return = $info->points;
        }
        return $return;
    }
    /**
     * 注册企业会员
     */
    public function regCompany($input_data)
    {
        $insert_data_member = [
            'username' =>
            config('global_config.reg_prefix') . $input_data['mobile'],
            'password' => isset($input_data['password'])
            ? $input_data['password']
            : '',
            'mobile' => $input_data['mobile'],
            'utype' => 1,
            'platform' => config('platform'),
        ];

        \think\Db::startTrans();
        try {
            //新增到会员表
            $insert_data_member['pwd_hash'] = randstr();
            if ($insert_data_member['password'] != '') {
                $insert_data_member['password'] = $this->makePassword(
                    $insert_data_member['password'],
                    $insert_data_member['pwd_hash']
                );
            }
            if (
                false ===
                $this->validate('Member.add')
                ->allowField(true)
                ->save($insert_data_member)
            ) {
                throw new \Exception($this->getError());
            }
            //新增到企业表
            if(isset($input_data['companyname']) && $input_data['companyname'] != ''){
                $insert_data_company['uid'] = $this->uid;
                $insert_data_company['companyname'] = $input_data['companyname'];
                $insert_data_company['short_name'] = '';
                $insert_data_company['nature'] = 0;
                $insert_data_company['trade'] = 0;
                $insert_data_company['district1'] = 0;
                $insert_data_company['district2'] = 0;
                $insert_data_company['district3'] = 0;
                $insert_data_company['district'] = 0;
                $insert_data_company['scale'] = 0;
                $insert_data_company['registered'] = 0;
                $insert_data_company['currency'] = 0;
                $insert_data_company['tag'] = '';
                $insert_data_company['map_lat'] = 0.0;
                $insert_data_company['map_lng'] = 0.0;
                $insert_data_company['map_zoom'] = 0;
                $insert_data_company['logo'] = 0;
                $insert_data_company['addtime'] = time();
                $insert_data_company['refreshtime'] =
                    $insert_data_company['addtime'];
                $insert_data_company[
                    'cs_id'
                ] = $this->distributionCustomerService();
                $insert_data_company['platform'] = config('platform');
            
                if (
                    false ===
                    model('Company')
                    ->allowField(true)
                    ->save($insert_data_company)
                ) {
                    throw new \Exception(model('Company')->getError());
                }
                //新增到企业信息表
                $insert_data_company_info['comid'] = model('Company')->id;
                $insert_data_company_info['uid'] = $this->uid;
                $insert_data_company_info['website'] = '';
                $insert_data_company_info['short_desc'] = '';
                $insert_data_company_info['content'] = '';
                $insert_data_company_info['address'] = '';
                if (
                    false ===
                    model('CompanyInfo')
                    ->allowField(true)
                    ->save($insert_data_company_info)
                ) {
                    throw new \Exception(model('CompanyInfo')->getError());
                }
                if (isset($input_data['contact']) && $input_data['contact'] != '') {
                    //新增到企业联系方式表
                    $insert_data_company_contact['comid'] = model('Company')->id;
                    $insert_data_company_contact['uid'] = $this->uid;
                    $insert_data_company_contact['contact'] = $input_data['contact'];
                    $insert_data_company_contact['mobile'] = $input_data['mobile'];
                    $insert_data_company_contact['weixin'] = '';
                    $insert_data_company_contact['telephone'] = '';
                    $insert_data_company_contact['qq'] = '';
                    $insert_data_company_contact['email'] = '';
                    $insert_data_company_contact['address'] = '';
                    if (
                        false ===
                        model('CompanyContact')
                        ->allowField(true)
                        ->save($insert_data_company_contact)
                    ) {
                        throw new \Exception(model('CompanyContact')->getError());
                    }
                }
            }

            //赠送套餐
            $data_setmeal['uid'] = $this->uid;
            $data_setmeal['note'] = '';
            $data_setmeal['setmeal_id'] = config('global_config.reg_service');
            $this->setMemberSetmeal($data_setmeal);
            model('Task')->doTask($this->uid, 1, 'reg');
            //赠送优惠券
            $coupon_config = config('global_config.coupon_config');
            if (
                $coupon_config['is_open']==1 && $coupon_config['is_reg_gift'] == 1 &&
                count($coupon_config['reg_gift_list']) > 0
            ) {
                model('Coupon')->send([
                    'setmeal_id' => -1,
                    'uid' => $this->uid,
                    'coupon_id' => $coupon_config['reg_gift_list'],
                ]);
            }
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }

        model('ImToken')->regToken($this->uid,1);

        if(isset($insert_data_company['cs_id'])){
            $customer_service = model('CustomerService')
                ->where('id', $insert_data_company['cs_id'])
                ->find();
            model('NotifyRule')->notify($this->uid, 1, 'reg', [
                'sitename' => config('global_config.sitename'),
                'contact' => isset($customer_service['name'])
                ? $customer_service['name']
                : '',
                'mobile' => isset($customer_service['mobile'])
                ? $customer_service['mobile']
                : '',
                'weixin' => isset($customer_service['weixin'])
                ? $customer_service['weixin']
                : '',
            ]);
        }
        
        return [
            'uid' => $this->uid,
            'utype' => 1,
            'mobile' => $input_data['mobile'],
        ];
    }
    /**
     * 注册个人会员
     */
    public function regPersonal($input_data)
    {
        $insert_data_member = [
            'username' =>
            config('global_config.reg_prefix') . $input_data['mobile'],
            'password' => isset($input_data['password'])
            ? $input_data['password']
            : '',
            'mobile' => $input_data['mobile'],
            'utype' => 2,
            'platform' => config('platform'),
        ];

        //新增到会员表
        $insert_data_member['pwd_hash'] = randstr();
        if ($insert_data_member['password'] != '') {
            $insert_data_member['password'] = $this->makePassword(
                $insert_data_member['password'],
                $insert_data_member['pwd_hash']
            );
        } else {
            $insert_data_member['password'] = '';
        }
        if (
            false ===
            $this->validate('Member.add')
            ->allowField(true)
            ->save($insert_data_member)
        ) {
            $this->error = $this->getError();
            return false;
        }
        model('Task')->doTask($this->uid, 2, 'reg');
        model('ImToken')->regToken($this->uid,2);
        model('NotifyRule')->notify($this->uid, 2, 'reg', [
            'sitename' => config('global_config.sitename'),
            'mobile' => config('global_config.contact_tel'),
        ]);
        return [
            'uid' => $this->uid,
            'utype' => 2,
            'mobile' => $input_data['mobile'],
        ];
    }
    /**
     * 分配客服
     */
    public function distributionCustomerService()
    {
        $idarr = model('CustomerService')
            ->where('status', 1)
            ->column('id');
        if (!empty($idarr)) {
            shuffle($idarr);
            return $idarr[0];
        }
        return 0;
    }
    /**
     * 根据uid删除会员相关的所有信息
     * type $uid = array
     */
    public function deleteMemberByUids($uid)
    {
        model('Company')->where('uid', 'in', $uid)->delete();
        model('CompanyAuth')
            ->where('uid', 'in', $uid)
            ->delete();
        model('CompanyAuthLog')
            ->where('uid', 'in', $uid)
            ->delete();
        model('CompanyContact')
            ->where('uid', 'in', $uid)
            ->delete();
        model('CompanyDownResume')
            ->where('uid', 'in', $uid)
            ->delete();
        model('CompanyImg')
            ->where('uid', 'in', $uid)
            ->delete();
        model('CompanyInfo')
            ->where('uid', 'in', $uid)
            ->delete();
        model('CompanyInterview')
            ->where('uid', 'in', $uid)
            ->delete();
        model('CompanyInterviewVideo')
            ->where('uid', 'in', $uid)
            ->delete();
        model('CompanyReport')
            ->where('uid', 'in', $uid)
            ->delete();
        model('CouponRecord')
            ->where('uid', 'in', $uid)
            ->delete();
        model('CustomerServiceComplaint')
            ->where('uid', 'in', $uid)
            ->delete();
        model('Entrust')
            ->where('uid', 'in', $uid)
            ->delete();
        model('FavJob')
            ->where('company_uid', 'in', $uid)
            ->delete();
        model('FavJob')
            ->where('personal_uid', 'in', $uid)
            ->delete();
        model('FavResume')
            ->where('company_uid', 'in', $uid)
            ->delete();
        model('FavResume')
            ->where('personal_uid', 'in', $uid)
            ->delete();
        model('Feedback')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ImToken')
            ->where('uid', 'in', $uid)
            ->delete();
        model('JobApply')
            ->where('company_uid', 'in', $uid)
            ->delete();
        model('JobApply')
            ->where('personal_uid', 'in', $uid)
            ->delete();
        model('MarketQueue')
            ->where('uid', 'in', $uid)
            ->delete();
        model('Member')->where('uid','in',$uid)->delete();
        model('MemberBind')
            ->where('uid', 'in', $uid)
            ->delete();
        model('MemberActionLog')
            ->where('uid', 'in', $uid)
            ->delete();
        model('MemberSetmeal')
            ->where('uid', 'in', $uid)
            ->delete();
        model('MemberSetmealLog')
            ->where('uid', 'in', $uid)
            ->delete();
        model('MemberPoints')
            ->where('uid', 'in', $uid)
            ->delete();
        model('MemberPointsLog')
            ->where('uid', 'in', $uid)
            ->delete();
        model('Message')
            ->where('uid', 'in', $uid)
            ->delete();
        model('RefreshJobLog')
            ->where('uid', 'in', $uid)
            ->delete();
        model('RefreshResumeLog')
            ->where('uid', 'in', $uid)
            ->delete();
        model('RefreshjobQueue')
            ->where('uid', 'in', $uid)
            ->delete();
        model('TaskRecord')
            ->where('uid', 'in', $uid)
            ->delete();
        model('Resume')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeAuditLog')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeCertificate')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeComplete')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeContact')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeEducation')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeImg')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeIntention')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeLanguage')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeProject')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeSearchKey')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeSearchRtime')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeTraining')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ResumeWork')
            ->where('uid', 'in', $uid)
            ->delete();
        model('Shield')
            ->where('company_uid', 'in', $uid)
            ->delete();
        model('Shield')
            ->where('personal_uid', 'in', $uid)
            ->delete();
        model('StatViewJob')
            ->where('company_uid', 'in', $uid)
            ->delete();
        model('StatViewJob')
            ->where('personal_uid', 'in', $uid)
            ->delete();
        model('SubscribeJob')
            ->where('uid', 'in', $uid)
            ->delete();
        model('TaskRecord')
            ->where('uid', 'in', $uid)
            ->delete();
        model('Tipoff')
            ->where('uid', 'in', $uid)
            ->delete();
        model('ViewJob')
            ->where('company_uid', 'in', $uid)
            ->delete();
        model('ViewJob')
            ->where('personal_uid', 'in', $uid)
            ->delete();
        model('ViewResume')
            ->where('company_uid', 'in', $uid)
            ->delete();
        model('ViewResume')
            ->where('personal_uid', 'in', $uid)
            ->delete();
        model('Job')->deleteJobByUids($uid);
        return;
    }
}
