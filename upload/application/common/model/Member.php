<?php

namespace app\common\model;

class Member extends BaseModel
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
            if (!intval($status)) {
                model('IdentityToken')->where(['uid' => $uid])->delete(); //锁定即删除token,
                /**
                 * 【ID1000411】
                 * 【优化】后台锁定账号时增加 清马甲/千帆绑定的表字段
                 * yx - 2022.11.09
                 * [新增]
                 */
                model('MemberBind')
                    ->where([
                        'uid' => $uid,
                        'type' => ['in', ['qianfanyunapp', 'magapp']]
                    ])
                    ->delete();
            }
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

    public function setIm($uid, $disable_im, $reason)
    {
        $model = self::find($uid);
        $model->disable_im = $disable_im;
        if ($model->save()) {
            if (!intval($disable_im)) {
                model('ImForbid')->where(['uid' => $uid])->delete(); //解除禁聊即删除禁聊记录,
            } else {
                $data['uid'] = $uid;
                $data['addtime'] = time();
                $data['reason'] = $reason;
                model('ImForbid')->insert($data);
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
    public function setMemberSetmeal($data, $order_id = 0, $admin_id = 0)
    {
        $setmeal_info = model('Setmeal')
            ->where(['id' => ['eq', intval($data['setmeal_id'])]])
            ->find();

        $data['jobs_meanwhile'] = $setmeal_info['jobs_meanwhile'];
        $data['refresh_jobs_free_perday'] =
            $setmeal_info['refresh_jobs_free_perday'];
        $data['download_resume_max_perday'] =
            $setmeal_info['download_resume_max_perday'];
        $data['im_max_perday'] = $setmeal_info['im_max_perday'];
        $data['service_added_discount'] =
            $setmeal_info['service_added_discount'];
        $data['enable_video_interview'] =
            $setmeal_info['enable_video_interview'];
        $data['show_apply_contact'] = $setmeal_info['show_apply_contact'];
//        $data['resume_view_num'] = $setmeal_info['resume_view_num'];
        $data['expired'] = 0;
        $data['opening_time'] = time();
        $check_setmeal = model('MemberSetmeal')
            ->where('uid', $data['uid'])
            ->find();
        if ($check_setmeal === null) {
            $data['deadline'] =
                $setmeal_info['days'] == 0
                    ? 0
                    : strtotime('+' . $setmeal_info['days'] . ' day');
            $data['download_resume_point'] = $setmeal_info['download_resume_point'];
            $data['im_total'] = $setmeal_info['im_total'];
            model('MemberSetmeal')
                ->allowField(true)
                ->save($data);
        } else {
            if ($check_setmeal['deadline'] > time()) {

                //-------------------没过期,时间叠加到原有套餐 ---------------------> 旧逻辑，已废弃，以下为新逻辑

                //没过期，根据配置信息决定是否叠加时间
                if ($setmeal_info['days'] == 0) {
                    $data['deadline'] = 0;
                } else if (config('global_config.reopen_setmeal_deadline') == 1) {
                    $data['deadline'] = $check_setmeal['deadline'] + $setmeal_info['days'] * 3600 * 24;
                } else {
                    $data['deadline'] = strtotime('+' . $setmeal_info['days'] . ' day');
                }
            } else {
                //已过期或者之前是无限期的，从现在开始算起
                $data['deadline'] =
                    $setmeal_info['days'] == 0
                        ? 0
                        : strtotime('+' . $setmeal_info['days'] . ' day');
            }
            //重开套餐资源处理
            if (config('global_config.reopen_setmeal_resource') == 1) {
                //叠加
                $data['download_resume_point'] = $check_setmeal['download_resume_point'] + $setmeal_info['download_resume_point'];
                $data['im_total'] = $check_setmeal['im_total'] + $setmeal_info['im_total'];
            } else {
                //不叠加
                $data['download_resume_point'] = $setmeal_info['download_resume_point'];
                $data['im_total'] = $setmeal_info['im_total'];
            }

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
        //开通日志
        $openLogData = [
            'uid' => $data['uid'],
            'setmeal_id' => $setmeal_info['id'],
            'setmeal_name' => $setmeal_info['name'],
            'order_id' => $order_id,
            'admin_id' => $admin_id
        ];
        model('MemberSetmealOpenLog')->record($openLogData);

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
        } else if ($setmeal_info['gift_point'] > 0) {
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
            } elseif ($op == 2) {
                $data['points'] = $check_points['points'] - $data['points'];
            }
            if ($data['points'] < 0) {
                $data['points'] = 0;
            }

            model('MemberPoints')
                ->where(['uid' => $data['uid']])
                ->update(['points' => $data['points']]);
        }
        if ($data['points'] > 0) {
            $log['uid'] = $data['uid'];
            $log['op'] = $op;
            $log['points'] = $param_points;
            $log['content'] = $data['note'];
            $log['addtime'] = time();
            model('MemberPointsLog')->insert($log);
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

        //套餐简历包修改 zch
        if ($info['deadline'] != 0 && $info['deadline'] < time()) {
            //如果过期，看配置参数中资源是保留还是清空
            if (config('global_config.overtime_setmeal_resource') == 0) {
                //清空
                $info['download_resume_point'] = $info['purchase_resume_point']; // 总和
                $info['setmeal_resume_point'] = 0; // 套餐

                $info['im_total'] = $info['im_added_package']; // 总和
                $info['setmeal_im_total'] = 0; // 套餐

            } else {
                /**
                 * 【ID1000401】
                 * 【bug】简历点下载问题，代码顺序错误(L:310和L:311调换顺序即可)
                 * yx - 2022.11.04
                 */
                $info['setmeal_resume_point'] = $info['download_resume_point'];
                $info['download_resume_point'] += $info['purchase_resume_point']; // 简历包购买 + 购买套餐赠送简历点

                $info['setmeal_im_total'] = $info['im_total'];
                $info['im_total'] += $info['im_added_package']; // 简历包购买 + 购买套餐赠送简历点
            }
            $overtime_config = config('global_config.setmeal_overtime_conf');
            $info['jobs_meanwhile'] = $overtime_config['jobs_meanwhile'];
            $info['refresh_jobs_free_perday'] =
                $overtime_config['refresh_jobs_free_perday'];
            $info['download_resume_max_perday'] =
                $overtime_config['download_resume_max_perday'];
            $info['im_max_perday'] =
                $overtime_config['im_max_perday'];
            $info['service_added_discount'] =
                $overtime_config['service_added_discount'];
            $info['enable_video_interview'] =
                $overtime_config['enable_video_interview'];
            $info['show_apply_contact'] =
                $overtime_config['show_apply_contact'];
//            $info['resume_view_num'] =
//                (isset($overtime_config['resume_view_num'])) ? $overtime_config['resume_view_num'] : 0;
            $info['overtime'] = 1;
        } else {
            $info['overtime'] = 0;
            $info['setmeal_resume_point'] = $info['download_resume_point'];
            $info['download_resume_point'] += $info['purchase_resume_point'];
            $info['setmeal_im_total'] = $info['im_total'];
            $info['im_total'] += $info['im_added_package'];
        }

        /**
         * 今日收到简历免费查看数
         */
//        $todayLook = model('JobApply')
//            ->where('company_uid', $uid)
//            ->where('free_viewing', 1)
//            ->whereTime('free_viewing_time', 'today')
//            ->count();
//        if ($info['show_apply_contact'] == 0) {
//            $info['resume_view_num_today'] = 0;
//        } else {
//            if ($info['resume_view_num'] == 0) {
//                $info['resume_view_num_today'] = -1;
//            } else {
//                if ($todayLook >= $info['resume_view_num']) {
//                    $info['resume_view_num_today'] = 0;
//                } else {
//                    $info['resume_view_num_today'] = $info['resume_view_num'] - $todayLook;
//                }
//            }
//        }

        $setmeal = model('Setmeal')->where('id', $info['setmeal_id'])->find();
        if ($setmeal === null) {
            $info['name'] = '';
        } else {
            $info['name'] = $info['overtime'] == 1 ? ($setmeal['name'] . '已过期') : $setmeal['name'];
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
            if (isset($input_data['companyname']) && $input_data['companyname'] != '') {
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
                $insert_data_company['cs_id'] = $this->distributionCustomerService();
                $insert_data_company['platform'] = config('platform');
                $insert_data_company['is_display'] = config(
                    'global_config.display_new_com'
                );
                $insert_data_company['audit'] = config(
                    'global_config.audit_new_com'
                );

                /**
                 * 【新增】
                 * 增加CRM所属销售自动分配
                 */
                $is_rule = model('b2bcrm.CrmSysConfig')->getConfigByKey('customer_allocation_rule');
                if (1 === intval($is_rule)) {
                    $crm_auto_assign = model('b2bcrm.CrmAutoAssign')->getAutoAssignAdminId();
                    if (!empty($crm_auto_assign)) {
                        $insert_data_company['admin_id'] = $crm_auto_assign;
                        $insert_data_company['collection_time'] = time(); // 添加领取时间
                        model('b2bcrm.CrmAutoAssign')
                            ->where('admin_id', $crm_auto_assign)
                            ->setInc('assign_num');
                    }
                }

                if (
                    false ===
                    model('Company')
                        ->allowField(true)
                        ->save($insert_data_company)
                ) {
                    throw new \Exception(model('Company')->getError());
                }

                if (isset($insert_data_company['admin_id']) && !empty($insert_data_company['admin_id'])) {
                    # 设置管理员销售客户总数上限
                    $exceed_result = model('Admin')->setCustomerExceed($crm_auto_assign);
                    if (false === $exceed_result) {
                        throw new \Exception(model('Admin')->getError());
                    }
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
                $coupon_config['is_open'] == 1 && $coupon_config['is_reg_gift'] == 1 &&
                count($coupon_config['reg_gift_list']) > 0
            ) {
                model('Coupon')->send([
                    'setmeal_id' => -1,
                    'uid' => $this->uid,
                    'coupon_id' => $coupon_config['reg_gift_list'],
                ]);
            }

            // 千帆马甲注册进行会员绑定
            $userInfo = cookie('members_bind_info');
            if (!empty($userInfo) && ($userInfo['type'] == 'qianfanyunapp' || $userInfo['type'] == 'magapp')) {
                $userInfo_array = [
                    'uid' => $this->uid,
                    'type' => $userInfo['type'],
                    'openid' => '',
                    'unionid' => '',
                    'nickname' => $userInfo['username'],
                    'avatar' => $userInfo['avatar'],
                    'bindtime' => time()
                ];
                if ($userInfo['type'] == 'qianfanyunapp') {
                    $userInfo_array['qianfanyunapp_uid'] = $userInfo['id'];
                } else {
                    $userInfo_array['magapp_uid'] = $userInfo['id'];
                }
                model('MemberBind')->insert($userInfo_array);
            }

            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }


        if (!empty($insert_data_company['admin_id'])) {
            /**
             * 【bug】注册发送短信，微信号获取不到的问题
             * 【ID1000362】
             * yx - 2022.10.13
             */
            $customer_service = model('b2bcrm.CrmCustomerService')
                ->where('admin_id', $insert_data_company['admin_id'])
                ->field('name, mobile, tel')
                ->find();

            if (!empty($customer_service)) {
                if (!empty($customer_service['mobile']) || !empty($customer_service['tel'])) {
                    model('NotifyRule')->notify($this->uid, 1, 'reg', [
                        'sitename' => config('global_config.sitename'),
                        'contact' => isset($customer_service['name'])
                            ? $customer_service['name']
                            : '',
                        'mobile' => !empty($customer_service['mobile'])
                            ? $customer_service['mobile']
                            : $customer_service['tel']
                    ]);
                }
            }
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
        // 千帆马甲注册进行会员绑定
        $userInfo = cookie('members_bind_info');
        if (!empty($userInfo) && ($userInfo['type'] == 'qianfanyunapp' || $userInfo['type'] == 'magapp')) {
            $userInfo_array = [
                'uid' => $this->uid,
                'type' => $userInfo['type'],
                'openid' => '',
                'unionid' => '',
                'nickname' => $userInfo['username'],
                'avatar' => $userInfo['avatar'],
                'bindtime' => time()
            ];
            if ($userInfo['type'] == 'qianfanyunapp') {
                $userInfo_array['qianfanyunapp_uid'] = $userInfo['id'];
            } else {
                $userInfo_array['magapp_uid'] = $userInfo['id'];
            }
            model('MemberBind')->insert($userInfo_array);
        }
        model('Task')->doTask($this->uid, 2, 'reg');
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
        $admin_id_arr = model('Company')
            ->where('uid', 'in', $uid)
            ->where('admin_id', '>', 0)
            ->column('admin_id');
        $admin_ids = array_unique($admin_id_arr);

        model('Company')->where('uid', 'in', $uid)->delete();

        if (!empty($admin_ids)) {
            foreach ($admin_ids as $adminId) {
                # 设置管理员销售客户总数上限
                model('Admin')->setCustomerExceed($adminId);
            }
        }

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
        model('JobApply')
            ->where('company_uid', 'in', $uid)
            ->delete();
        model('JobApply')
            ->where('personal_uid', 'in', $uid)
            ->delete();
        model('MarketQueue')
            ->where('uid', 'in', $uid)
            ->delete();
        model('Member')
            ->where('uid','in',$uid)
            ->delete();
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
        model('b2bcrm.CrmClue')
            ->where('uid', 'in', $uid)
            ->delete();
        model('b2bcrm.CrmClueRelease')
            ->where('uid', 'in', $uid)
            ->delete();
        model('b2bcrm.CrmCompanyContact')
            ->where('uid', 'in', $uid)
            ->delete();
        model('b2bcrm.CrmFollowUp')
            ->where('uid', 'in', $uid)
            ->delete();

        /*
         * 【bug】后台删除账号后，前台仍为登录状态，操作提示请登录
         * zch 2022.10.13
         * 【新增】
         * model('IdentityToken')
            ->where('uid', 'in', $uid)
            ->delete();
         * */
        model('IdentityToken')
            ->where('uid', 'in', $uid)
            ->delete();
        return;
    }

    /**
     * 通过UID获取用户名称
     * @param $uid
     * @return false|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMemberNickNameByUid($uid, $fault = false, $display = true)
    {
        $member = $this->find($uid);
        if (null === $member) {
            return $fault;
        }

        switch ($member['utype']) {
            case 1:
                return $this->getCompanyNameByUid($uid, $fault);

            case 2:
                return $this->getMemberNameByUid($uid, $fault, $display);

            default:
                return $fault;
        }
    }

    /**
     * 获取个人会员名称
     * @param $uid
     * @return void
     */
    public function getMemberNameByUid($uid, $fault = false, $display = true)
    {
        $resume = model('Resume')
            ->field('display_name, fullname, sex')
            ->where('uid', $uid)
            ->find();
        if (null == $resume) {
            return $fault;
        }

        if (!$display) {
            return $resume['fullname'];
        }

        if ($resume['display_name'] == 0) {
            if ($resume['sex'] == 1) {
                $resume['fullname'] = cut_str(
                    $resume['fullname'],
                    1,
                    0,
                    '先生'
                );
            } elseif ($resume['sex'] == 2) {
                $resume['fullname'] = cut_str(
                    $resume['fullname'],
                    1,
                    0,
                    '女士'
                );
            } else {
                $resume['fullname'] = cut_str(
                    $resume['fullname'],
                    1,
                    0,
                    '**'
                );
            }
        }

        return $resume['fullname'];
    }

    /**
     * 获取企业会员名称
     * @param $uid
     * @return void
     */
    public function getCompanyNameByUid($uid, $fault = false)
    {
        $company_name = model('Company')
            ->where('uid', $uid)
            ->value('companyname');
        if (null == $company_name) {
            return $fault;
        }

        return $company_name;
    }
}
