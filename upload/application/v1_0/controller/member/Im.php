<?php

namespace app\v1_0\controller\member;

use app\v1_0\controller\common\Base;

class Im extends Base
{
    protected $baseUrl = '';
    protected $config = [
        'app_key' => '',
        'app_secret' => ''
    ];

    public function _initialize()
    {
        parent::_initialize();
        $this->baseUrl = 'https://imserv.v2.74cms.com';
        $this->config = config('global_config.account_im');
    }

    protected function checkWindowGlobal()
    {
        if (config('global_config.im_open') != 1) {
            return ['code' => 0, 'next' => 'disabled', 'message' => '很抱歉，系统聊天功能暂不支持使用'];
        }
        $member = model('Member')->field('disable_im')->where('uid', $this->userinfo->uid)->find();
        if ($member['disable_im'] == 1) {
            return ['code' => 0, 'next' => 'disabled', 'message' => '抱歉，您暂时无法使用此功能哦'];
        }
        $rule = model('ImRule')->getCache($this->userinfo->utype);
        //是否绑定微信
        if ($rule['bind_weixin'] == 1) {
            if (config('global_config.wechat_login_open') != 1) {
                return ['code' => 0, 'next' => 'disabled', 'message' => '很抱歉，系统聊天功能暂不支持使用'];
            }
            $bind_data = model('MemberBind')->where('uid', $this->userinfo->uid)->where('type', 'weixin')->where('is_subscribe', 1)->find();
            if ($bind_data === null) {
                return ['code' => 0, 'next' => 'bind_weixin', 'message' => '您当前未绑定微信，绑定后可发起聊天。'];
            }
        }
        if ($this->userinfo->utype == 2) {
            //简历完整度
            if ($rule['complete_percent'] > 0) {
                $percent = model('Resume')->countCompletePercent(0, $this->userinfo->uid);
                if ($percent < $rule['complete_percent']) {
                    return ['code' => 0, 'next' => 'complete_resume', 'message' => '您的简历完整度较低，暂时无法与企业聊天，建议立即完善简历信息。'];
                }
            }
            //简历审核状态
            if ($rule['audit_status'] == 1) {
                $resume = model('Resume')->field('audit')->where('uid', $this->userinfo->uid)->find();
                if ($resume === null || $resume['audit'] != 1) {
                    return ['code' => 0, 'next' => 'disabled', 'message' => '您的简历未审核通过，暂时无法使用职聊功能'];
                }
            }
        } else {
            //企业显示要求
            if ($rule['display_status'] == 1) {
                $company = model('Company')->field('is_display')->where('uid', $this->userinfo->uid)->find();
                if ($company === null || $company['is_display'] == 0) {
                    return ['code' => 0, 'next' => 'disabled', 'message' => '您的企业目前为隐藏状态，暂时无法使用职聊功能！'];
                }
            }
        }
        return ['code' => 1, 'next' => '', 'message' => ''];
    }

    /**
     * 进入聊天界面后先请求全局检测
     */
    public function imWindowGlobal()
    {
        $this->checkLogin();
        $checkWindowGlobal = $this->checkWindowGlobal();
        if ($checkWindowGlobal['code'] == 0) {
            $this->ajaxReturn(200, $checkWindowGlobal['message'], ['next' => $checkWindowGlobal['next']]);
        }
        $this->ajaxReturn(200, '检测通过', ['next' => '']);
    }

    /**
     * 查询今日已发起聊天次数
     */
    protected function checkTodayStartTimes($token)
    {
        $url = $this->baseUrl . '/chat/getTodayStartTimes';
        $data = [
            'token' => $token
        ];
        $result = https_request($url, $data);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            return ['code' => 0, 'message' => $result['msg'], 'data' => 0];
        } else {
            return ['code' => 1, 'message' => $result['msg'], 'data' => $result['result']['total']];
        }
    }

    protected function checkAuth()
    {
        $appkey = input('get.appkey/s', '', 'trim');
        $appsecret = input('get.appsecret/s', '', 'trim');
        if (!$appkey) {
            $this->ajaxReturn(500, '职聊暂时无法使用，请联系客服反馈。', ['error' => 'appkey不能为空']);
        }
        if (!$appsecret) {
            $this->ajaxReturn(500, '职聊暂时无法使用，请联系客服反馈。', ['error' => 'appsecret不能为空']);
        }
        if ($appkey != $this->config['app_key'] || $appsecret != $this->config['app_secret']) {
            $this->ajaxReturn(500, '职聊暂时无法使用，请联系客服反馈。', ['error' => 'appkey或appsecret错误']);
        }
    }

    public function startConversation()
    {
        $this->checkLogin();
        /**
         * 全局检测
         */
        $checkWindowGlobal = $this->checkWindowGlobal();
        if ($checkWindowGlobal['code'] == 0) {
            $this->ajaxReturn(200, $checkWindowGlobal['message'], ['next' => $checkWindowGlobal['next']]);
        }
        $token = input('post.token/s', '', 'trim');
        if (!$token) {
            $this->ajaxReturn(500, 'token不能为空');
        }
        $hasConversation = false;
        $companyid = input('post.companyid/d', 0, 'intval');
        $jobid = input('post.jobid/d', 0, 'intval');
        if ($this->userinfo->utype == 2 && $companyid == 0) {
            if (!$jobid) {
                $this->ajaxReturn(500, '请选择职位');
            }
            $resumeinfo = model('Resume')->where('uid', $this->userinfo->uid)->find();
            if (null === $resumeinfo) {
                $this->ajaxReturn(500, '没有找到简历信息');
            }
            $jobinfo = model('Job')->where('id', $jobid)->find();
            if (null === $jobinfo) {
                $this->ajaxReturn(500, '没有找到职位信息');
            }
            $check_data = [
                'token' => $token,
                'uid' => $jobinfo['uid']
            ];
            $url = $this->baseUrl . '/chat/hasConversation';
            $result = https_request($url, $check_data);
            $result = json_decode($result, 1);
            if ($result['result']['status'] == 1) {
                //聊过
                $hasConversation = true;
            }
        } elseif ($this->userinfo->utype == 2 && $companyid > 0 && $jobid >= 0) {
            $companyinfo = model('Company')->where('id', $companyid)->find();
            if (null === $companyinfo) {
                $this->ajaxReturn(500, '没有找到企业信息');
            }
            $resumeinfo = model('Resume')->where('uid', $this->userinfo->uid)->find();
            if (null === $resumeinfo) {
                $this->ajaxReturn(500, '没有找到简历信息');
            }
            $joblist = model('job')->field('id')->where('uid', $companyinfo['uid'])->select();
            if (empty($joblist)) {
                $this->ajaxReturn(200, '您目前选择的企业没有招聘的职位，暂时无法发起聊天。');
            }
            if ($jobid > 0 || count($joblist) == 1) {
                if ($jobid > 0) {
                    $jobinfo = model('Job')->where('id', $jobid)->find();
                    if (null === $jobinfo) {
                        $this->ajaxReturn(500, '没有找到职位信息');
                    }
                } else {
                    $jobinfo = model('Job')->where('id', $joblist[0]['id'])->find();
                    $jobid = $jobinfo['id'];
                }
                $check_data = [
                    'token' => $token,
                    'uid' => $jobinfo['uid']
                ];
                $url = $this->baseUrl . '/chat/hasConversation';
                $result = https_request($url, $check_data);
                $result = json_decode($result, 1);
                if ($result['result']['status'] == 1) {
                    //聊过
                    $hasConversation = true;
                }
            } else {
                $this->ajaxReturn(200, '请选择职位', ['next' => 'choose_job']);
            }
            $check_data = [
                'token' => $token,
                'uid' => $companyinfo['uid']
            ];
            $url = $this->baseUrl . '/chat/hasConversation';
            $result = https_request($url, $check_data);
            $result = json_decode($result, 1);
            if ($result['result']['status'] == 1) {
                //聊过
                $hasConversation = true;
            }
        } else {
            $resumeid = input('post.resumeid/d', 0, 'intval');
            if (!$resumeid) {
                $this->ajaxReturn(500, '请选择');
            }
            $resumeinfo = model('Resume')->where('id', $resumeid)->find();
            if (null === $resumeinfo) {
                $this->ajaxReturn(500, '没有找到简历信息');
            }
            $check_data = [
                'token' => $token,
                'uid' => $resumeinfo['uid']
            ];
            $url = $this->baseUrl . '/chat/hasConversation';
            $result = https_request($url, $check_data);
            $result = json_decode($result, 1);
            if ($result['result']['status'] == 1) {
                //聊过
                $hasConversation = true;
                $jobid = $result['result']['jobid'];
            } else {
                //如果没有传职位id，则先选择职位，如果只有一条有效职位，直接跳过选择，默认选中这一条
                $jobid = input('post.jobid/d', 0, 'intval');
                if (!$jobid) {
                    $joblist = model('JobSearchRtime')->alias('a')->join(config('database.prefix') . 'job b', 'a.id=b.id', 'left')->field('a.id')->where('a.uid', $this->userinfo->uid)->select();
                    if (empty($joblist)) {
                        $this->ajaxReturn(200, '您目前没有招聘的职位，暂时无法发起聊天。建议立即发布招聘职位。', ['next' => 'disabled']);
                    }
                    if (count($joblist) == 1) {
                        $jobid = $joblist[0]['id'];
                    } else {
                        $this->ajaxReturn(200, '请选择职位', ['next' => 'choose_job']);
                    }
                }
            }
            $jobinfo = model('Job')->where('id', $jobid)->find();
            if (null === $jobinfo) {
                $this->ajaxReturn(500, '没有找到职位信息');
            }
        }
        $companyinfo = model('Company')->field('companyname')->where('uid', $jobinfo['uid'])->find();
        if (null === $companyinfo) {
            $this->ajaxReturn(500, '没有找到企业信息11');
        }
        if ($this->userinfo->utype == 1) {
            $target_uid = $resumeinfo['uid'];
        } else {
            $target_uid = $jobinfo['uid'];
        }

        /**
         * 查询今日已发起聊天多少次
         */
        if ($hasConversation === false) {
            $checkTodayStartTimes = $this->checkTodayStartTimes($token);
            if ($checkTodayStartTimes['code'] == 0) {
                $this->ajaxReturn(500, $checkTodayStartTimes['message']);
            }
            if ($this->userinfo->utype == 2) {
                $rule = model('ImRule')->getCache($this->userinfo->utype);
                if ($rule['max_per_day'] <= $checkTodayStartTimes['data']) {
                    $this->ajaxReturn(200, '您今天主动发起的聊天次数已达上限，请及时关注对方的消息回复哦。', ['next' => 'disabled']);
                }
            } else {

                //判断套餐 - 当前企业可聊次数
                /**
                 * - 次数足，不提示直接继续发起
                 * - 次数不足 - 走支付
                 *      - 快捷支付开关是否开启，走以下
                 *          - 引导购买增值服务
                 *      - 是否允许积分抵扣，成功抵扣后发起
                 */
                $member_setmeal = model('Member')->getMemberSetmeal($this->userinfo->uid);
                if ($member_setmeal['im_total'] <= 0) {
                    $this->ajaxReturn(200, '您的聊天次数不足，请升级套餐或购买聊天增值包', ['next' => 'pay']);
                }
                //判断套餐 - 企业套餐今日最大次数
                if ($member_setmeal['im_max_perday'] <= $checkTodayStartTimes['data']) {
                    $this->ajaxReturn(200, '您今天主动发起的聊天次数已达上限，请及时关注对方的消息回复哦。', ['next' => 'disabled']);
                }
            }
        }
        
        $category_district_data = model('CategoryDistrict')->getCache();
        $data = [
            'token' => $token,
            'jobid' => $jobid,
            'uid' => $target_uid,
            'jobname' => $jobinfo['jobname'],
            'companyname' => $companyinfo['companyname'],
            'district_text' => isset($category_district_data[$jobinfo['district']]) ? $category_district_data[$jobinfo['district']] : '',
            'wage_text' => model('BaseModel')->handle_wage($jobinfo['minwage'], $jobinfo['maxwage'], $jobinfo['negotiable']),
            'education_text' => isset(model('BaseModel')->map_education[$jobinfo['education']]) ? model('BaseModel')->map_education[$jobinfo['education']] : '学历不限',
            'experience_text' => isset(model('BaseModel')->map_experience[$jobinfo['experience']]) ? model('BaseModel')->map_experience[$jobinfo['experience']] : '经验不限',
            'fullname' => $resumeinfo['fullname']
        ];

        $url = $this->baseUrl . '/chat/startConversation';
        $result = https_request($url, $data);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        if ($this->userinfo->utype == 1 && $result['result']['first_time'] == 1) {

            if ($member_setmeal['setmeal_im_total'] >= 1) {
                model('MemberSetmeal')->where('uid', $this->userinfo->uid)->setDec('im_total', 1);
                $this->writeMemberActionLog($this->userinfo->uid,
                    '消耗职聊次数【简历ID：'
                    . $resumeinfo["id"]
                    . '】，职聊剩余次数：'
                    . ($member_setmeal['setmeal_im_total'] - 1)
                    . '职聊增值包剩余次数：'
                    . $member_setmeal['im_added_package']
                );
            } else {
                model('MemberSetmeal')->where('uid', $this->userinfo->uid)->setDec('im_added_package', 1);
                $this->writeMemberActionLog($this->userinfo->uid,
                    '消耗职聊增值包次数【简历ID：'
                    . $resumeinfo["id"]
                    . '】，职聊剩余次数：'
                    . $member_setmeal['setmeal_im_total']
                    . '职聊增值包剩余次数：'
                    . ($member_setmeal['im_added_package'] - 1)
                );
            }

        }

        $this->ajaxReturn(200, '开始聊天', ['next' => '', 'chatid' => $result['result']['chatid']]);
    }

    /**
     * 获取token
     */
    public function getToken()
    {
        $this->checkLogin();
        if (empty($this->config['app_key'])) {
            $this->ajaxReturn(500, '职聊暂时无法使用，请联系客服反馈。', ['error' => 'appkey不能为空']);
        }
        if (empty($this->config['app_secret'])) {
            $this->ajaxReturn(500, '职聊暂时无法使用，请联系客服反馈。', ['error' => 'appsecret不能为空']);
        }
        if ($this->userinfo->utype == 2) {
            $resume = model('Resume')->field('id,fullname,photo_img,sex')->where('uid', $this->userinfo->uid)->find();
            $resumeid = $resume === null ? 0 : $resume['id'];
            $avatar = model('Uploadfile')->getFileUrl($resume['photo_img']);
            $avatar = $avatar ? $avatar : default_empty('photo', $resume['sex']);
        } else {
            $company = model('Company')->field('id,companyname,logo')->where('uid', $this->userinfo->uid)->find();
            $resumeid = 0;
            $avatar = model('Uploadfile')->getFileUrl($company['logo']);
            $avatar = $avatar ? $avatar : default_empty('logo');
        }
        $url = $this->baseUrl . '/gettoken?appkey=' . $this->config['app_key'] . '&appsecret=' . $this->config['app_secret'] . '&userid=' . $this->userinfo->uid . '&utype=' . $this->userinfo->utype . '&resumeid=' . $resumeid . '&avatar=' . urlencode($avatar);
        $result = https_request($url);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, '职聊暂时无法使用，请联系客服反馈。', ['error' => $result['msg']]);
        }
        $this->ajaxReturn(200, '获取数据成功', $result['result']);
    }

    public function chatList()
    {
        $this->checkLogin();
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/chat/list';
        $result = https_request($url, ['token' => $token]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }

        $dataset = ($result['result'] && count($result['result']) > 0) ? $result['result'] : [];
        $relate_uid_arr = $self_uid_arr = $job_id_arr = [];
        foreach ($dataset as $key => $value) {
            $relate_uid_arr[] = $value['relate_uid'];
            $self_uid_arr[] = $value['owner_uid'];
            $job_id_arr[] = $value['jobid'];
        }

        $avatar_id_arr = $avatar_arr = $fullname_arr = [];

        $resumeinfo_arr = [];
        if ($this->userinfo->utype == 1 && !empty($relate_uid_arr)) {
            $resumeinfo_arr = model('Resume')->where('uid', 'in', $relate_uid_arr)
                ->column('uid,id,fullname as nickname,photo_img as avatar,id as resumeid,sex,birthday,education,enter_job_time');
            $resumeid_arr = [];
            foreach ($resumeinfo_arr as $key => $value) {
                $avatar_id_arr[] = $value['avatar'];
                $resumeid_arr[] = $value['resumeid'];
            }
            if (!empty($resumeid_arr)) {
                $fullname_arr = model('Resume')->formatFullname($resumeid_arr, $this->userinfo);
            }
        }

        $companyinfo_arr = [];
        $jobinfo_arr = [];

        if ($this->userinfo->utype == 2 && !empty($relate_uid_arr)) {
            $companyinfo_arr = model('Company')
                ->alias('b')
                ->join(config('database.prefix') . 'company_contact c', 'b.uid=c.uid', 'left')
                ->where('b.uid', 'in', $relate_uid_arr)
                ->where('c.id', 'not null')
                ->column('b.uid,c.contact as nickname,b.logo as avatar,b.companyname,b.id as companyid');
        } elseif ($this->userinfo->utype == 1 && !empty($self_uid_arr)) {
            $companyinfo_arr = model('Company')
                ->alias('b')
                ->join(config('database.prefix') . 'company_contact c', 'b.uid=c.uid', 'left')
                ->where('b.uid', 'in', $self_uid_arr)
                ->where('c.id', 'not null')
                ->column('b.uid,c.contact as nickname,b.logo as avatar,b.companyname,b.id as companyid');
        }


        foreach ($companyinfo_arr as $key => $value) {
            $avatar_id_arr[] = $value['avatar'];
        }
        if ($this->userinfo->utype == 2) {
            $jobinfo_arr = model('JobSearchRtime')
                ->alias('a')
                ->join(config('database.prefix') . 'job b', 'a.id=b.id', 'left')
                ->where('a.id', 'in', $job_id_arr)
                ->column('a.id,b.jobname,b.company_id');
        }


        if (!empty($avatar_id_arr)) {
            $avatar_arr = model('Uploadfile')->getFileUrlBatch(
                $avatar_id_arr
            );
        }

        $return = [];
        foreach ($dataset as $key => $value) {
            $arr['chat_id'] = $value['chat_id'];
            $arr['new'] = $value['new'];
            $arr['jobid'] = $value['jobid'];
            $arr['stick'] = $value['stick'];
            $arr['addtime'] = date('Y年m月d日', $value['addtime']);
            $arr['updatetime'] = im_daterange(time(), $value['updatetime']);
            /**
             * IM聊天列表最近/最新一条消息
             */
            $arr['last_message'] = $value['last_message'];

            if ($this->userinfo->utype == 1) {
                $companyinfo = isset($companyinfo_arr[$value['owner_uid']]) ? $companyinfo_arr[$value['owner_uid']] : null;
            } else {
                $companyinfo = isset($companyinfo_arr[$value['relate_uid']]) ? $companyinfo_arr[$value['relate_uid']] : null;
            }

            if ($companyinfo === null) {
                continue;
            }

            if ($this->userinfo->utype == 1) {
                $resumeinfo = isset($resumeinfo_arr[$value['relate_uid']]) ? $resumeinfo_arr[$value['relate_uid']] : null;
                if ($resumeinfo === null) {
                    continue;
                }
                $arr['avatar'] = isset($avatar_arr[$resumeinfo['avatar']]) ? $avatar_arr[$resumeinfo['avatar']] : default_empty('photo', $resumeinfo['sex']);
                $arr['nickname'] = $fullname_arr[$resumeinfo['resumeid']] ? htmlspecialchars_decode($fullname_arr[$resumeinfo['resumeid']], ENT_QUOTES) : '未知用户';
                $sex_text = model('Resume')->map_sex[$resumeinfo['sex']];
                $age_text = (date('Y') - intval($resumeinfo['birthday'])) . '岁';
                $education_text = isset(model('BaseModel')->map_education[$resumeinfo['education']]) ? model('BaseModel')->map_education[$resumeinfo['education']] : '';
                $experience_text = $resumeinfo['enter_job_time'] == 0 ? '尚未工作' : format_date($resumeinfo['enter_job_time']);
                $arr['detail'] = $age_text . ' · ' . $sex_text . ' · ' . $education_text . ' · ' . $experience_text;
                $arr['resumeid'] = $resumeinfo['id'];
                $arr['companyid'] = $companyinfo['companyid'];
                $arr['jobname'] = '';
            } else {
                $jobinfo = isset($jobinfo_arr[$value['jobid']]) ? $jobinfo_arr[$value['jobid']] : null;
                if ($jobinfo === null) {
                    continue;
                }
                $arr['avatar'] = isset($avatar_arr[$companyinfo['avatar']]) ? $avatar_arr[$companyinfo['avatar']] : default_empty('logo');
                $arr['nickname'] = $companyinfo['nickname'] ? htmlspecialchars_decode($companyinfo['nickname'], ENT_QUOTES) : '未知用户';
                $arr['detail'] = $companyinfo['companyname'];
                $arr['resumeid'] = 0;
                $arr['companyid'] = $companyinfo['companyid'];
                $arr['jobname'] = htmlspecialchars_decode($jobinfo['jobname'], ENT_QUOTES);
            }
            $return[] = $arr;
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }

    public function messageList()
    {
        $this->checkLogin();
        $page = input('post.page/d', 1, 'intval');
        $chat_id = input('post.chat_id/s', '', 'trim');
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/message/list';
        $result = https_request($url, ['token' => $token, 'chat_id' => $chat_id, 'page' => $page]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $target_uid = $result['result']['other_uid'];
        $dataset = ($result['result']['items'] && count($result['result']['items']) > 0) ? $result['result']['items'] : [];

        $return = [];
        if ($this->userinfo->utype == 1) {
            $self_avatar = model('Company')->where('uid', $this->userinfo->uid)->field('logo')->find();
            $self_avatar = model('Uploadfile')->getFileUrl($self_avatar['logo']);
            $self_avatar = $self_avatar ? $self_avatar : default_empty('logo');

            $other_avatar = model('Resume')->where('uid', $target_uid)->field('photo_img,sex')->find();
            $other_avatar = model('Uploadfile')->getFileUrl($other_avatar['photo_img']);
            $other_avatar = $other_avatar ? $other_avatar : default_empty('photo', $other_avatar['sex']);
        } else {
            $self_avatar = model('Resume')->where('uid', $this->userinfo->uid)->field('photo_img,sex')->find();
            $self_avatar = model('Uploadfile')->getFileUrl($self_avatar['photo_img']);
            $self_avatar = $self_avatar ? $self_avatar : default_empty('photo', $self_avatar['sex']);

            $other_avatar = model('Company')->where('uid', $target_uid)->field('logo')->find();
            $other_avatar = model('Uploadfile')->getFileUrl($other_avatar['logo']);
            $other_avatar = $other_avatar ? $other_avatar : default_empty('logo');
        }
        foreach ($dataset as $key => $value) {
            if (1 == $value['self_side']) {
                $value['avatar'] = $self_avatar;
            } else {
                $value['avatar'] = $other_avatar;
            }
            $return[] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }

    /**
     * 获取职位详情
     */
    public function jobDetail()
    {
        $this->checkAuth();
        $id = input('get.id/d', 0, 'intval');
        $jobinfo = model('Job')->alias('a')->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'left')->where('a.id', 'eq', $id)->field('a.jobname,b.companyname,a.district,a.category,a.minwage,a.maxwage,a.negotiable,a.education,a.experience')->find();
        $return = [
            'jobname' => htmlspecialchars_decode($jobinfo['jobname'], ENT_QUOTES),
            'companyname' => htmlspecialchars_decode($jobinfo['companyname'], ENT_QUOTES)
        ];

        $category_district_data = model('CategoryDistrict')->getCache();
        $category_job_data = model('CategoryJob')->getCache();

        $return['district_text'] = isset(
            $category_district_data[$jobinfo['district']]
        )
            ? $category_district_data[$jobinfo['district']]
            : '';
        $return['category_text'] = isset(
            $category_job_data[$jobinfo['category']]
        )
            ? $category_job_data[$jobinfo['category']]
            : '';
        $return['wage_text'] = model('BaseModel')->handle_wage(
            $jobinfo['minwage'],
            $jobinfo['maxwage'],
            $jobinfo['negotiable']
        );
        $return['education_text'] = isset(
            model('BaseModel')->map_education[$jobinfo['education']]
        )
            ? model('BaseModel')->map_education[$jobinfo['education']]
            : '学历不限';
        $return['experience_text'] = isset(
            model('BaseModel')->map_experience[$jobinfo['experience']]
        )
            ? model('BaseModel')->map_experience[$jobinfo['experience']]
            : '经验不限';
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /**
     * 职位联系方式
     */
    public function jobContact()
    {
        $this->checkAuth();
        $id = input('get.id/d', 0, 'intval');
        $jobinfo = model('Job')->where('id', $id)->find();
        $contact_info = model('JobContact')
            ->field('id,jid,uid', true)
            ->where(['jid' => ['eq', $id]])
            ->find();
        if ($contact_info['use_company_contact'] === null || $contact_info['use_company_contact'] == 1) {
            $contact_info = model('CompanyContact')
                ->field('id,comid,uid', true)
                ->where('comid', $jobinfo['company_id'])
                ->find();
        }
        $this->ajaxReturn(200, '获取数据成功', $contact_info);
    }

    /**
     * 企业联系方式
     */
    public function companyContact()
    {
        $this->checkAuth();
        $uid = input('get.uid/d', 0, 'intval');
        $contact_info = model('CompanyContact')->field('id,comid,uid', true)->where('uid', $uid)->find();
        $this->ajaxReturn(200, '获取数据成功', $contact_info);
    }

    /**
     * 简历联系方式
     */
    public function resumeContact()
    {
        $this->checkAuth();
        $uid = input('get.uid/d', 0, 'intval');
        $resumeinfo = model('Resume')->where('uid', $uid)->find();
        $contact_info = model('ResumeContact')
            ->field('id,rid,uid', true)
            ->where(['rid' => ['eq', $resumeinfo['id']]])
            ->find();
        $contact_info['fullname'] = htmlspecialchars_decode($resumeinfo['fullname'], ENT_QUOTES);
        $this->ajaxReturn(200, '获取数据成功', $contact_info);
    }

    /**
     * 获取简历详情
     */
    public function resumeDetail()
    {
        $this->checkAuth();
        $id = input('get.id/d', 0, 'intval');
        $resumeinfo = model('Resume')->where('id', 'eq', $id)->field('id,uid,fullname,sex,birthday,education,enter_job_time')->find();
        $return = [
            'id' => $id,
            'fullname' => htmlspecialchars_decode($resumeinfo['fullname'], ENT_QUOTES),
            'sex' => model('Resume')->map_sex[$resumeinfo['sex']],
            'age' => date('Y') - intval($resumeinfo['birthday']),
            'education' => isset(model('BaseModel')->map_education[$resumeinfo['education']]) ? model('BaseModel')->map_education[$resumeinfo['education']] : '',
            'experience' => $resumeinfo['enter_job_time'] == 0 ? '无经验' : (format_date($resumeinfo['enter_job_time']) . '经验'),
            'intention_category' => '',
            'intention_wage' => '',
            'work_companyname' => '',
            'work_jobname' => '',
            'education_school' => '',
            'education_major' => '',
            'show_contact' => 0
        ];
        $intention_data = model('ResumeIntention')
            ->field('id,rid,uid', true)
            ->where(['rid' => ['eq', $resumeinfo['id']]])
            ->order('id', 'desc')
            ->find();

        if ($intention_data !== null) {
            $category_job_data = model('CategoryJob')->getCache();
            $return['intention_category'] = isset($category_job_data[$intention_data['category']]) ? $category_job_data[$intention_data['category']] : '';
            $return['intention_wage'] = model('BaseModel')->handle_wage($intention_data['minwage'], $intention_data['maxwage'], 0);
        }

        $work_data = model('ResumeWork')
            ->field('id,rid,uid', true)
            ->where(['rid' => ['eq', $resumeinfo['id']]])
            ->order('id', 'desc')
            ->find();

        if ($work_data !== null) {
            $return['work_companyname'] = htmlspecialchars_decode($work_data['companyname'], ENT_QUOTES);
            $return['work_jobname'] = htmlspecialchars_decode($work_data['jobname'], ENT_QUOTES);
        }

        $edu_data = model('ResumeEducation')
            ->field('id,rid,uid', true)
            ->where(['rid' => ['eq', $resumeinfo['id']]])
            ->order('id', 'desc')
            ->find();

        if ($edu_data !== null) {
            $return['education_school'] = htmlspecialchars_decode($edu_data['school'], ENT_QUOTES);
            $return['education_major'] = htmlspecialchars_decode($edu_data['major'], ENT_QUOTES);
        }
        if ($this->userinfo !== null && $this->userinfo->utype == 1) {
            $getResumeContact = model('Resume')->getContact($resumeinfo, $this->userinfo);
            $return['show_contact'] = $getResumeContact['show_contact'];
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function removeChat()
    {
        $this->checkLogin();
        $chat_id = input('post.chat_id/s', '', 'trim');
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/chat/delete';
        $result = https_request($url, ['token' => $token, 'chat_id' => $chat_id]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '删除成功');
    }

    /**
     * 获取当前用户信息
     */
    public function userinfo()
    {
        if ($this->userinfo->utype == 1) {
            $avatar = model('Company')->where('uid', $this->userinfo->uid)->field('logo')->find();
            $avatar = model('Uploadfile')->getFileUrl($avatar['logo']);
            $avatar = $avatar ? $avatar : default_empty('logo');
        } else {
            $resume = model('Resume')->where('uid', $this->userinfo->uid)->field('fullname,photo_img,sex')->find();
            $avatar = model('Uploadfile')->getFileUrl($resume['photo_img']);
            $avatar = $avatar ? $avatar : default_empty('photo', $resume['sex']);
        }
        $return['avatar'] = $avatar;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function jobinfo()
    {
        $this->checkLogin();
        $id = input('post.jobid', 0, 'intval');
        $jobinfo = model('JobSearchRtime')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.id=b.id', 'left')
            ->where('a.id', 'eq', $id)
            ->field('b.*')
            ->find();
        if ($jobinfo === null) {
            $this->ajaxReturn(200, '请选择职位', ['next' => 'choose_job']);
        }
        $return['jobname'] = htmlspecialchars_decode($jobinfo['jobname'], ENT_QUOTES);
        $return['wage_text'] = model('BaseModel')->handle_wage(
            $jobinfo['minwage'],
            $jobinfo['maxwage'],
            $jobinfo['negotiable']
        );
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function resumeinfo()
    {
        $this->checkLogin();
        $resumeid = input('post.resumeid', 0, 'intval');
        $resumeinfo = model('Resume')
            ->where('id', 'eq', $resumeid)
            ->field(true)
            ->find();
        $return['id'] = $resumeinfo['id'];
        $return['fullname'] = model('Resume')->formatFullname([$resumeinfo['id']], $this->userinfo, true);
        $return['fullname'] = htmlspecialchars_decode($return['fullname'], ENT_QUOTES);
        $return['sex_text'] = model('Resume')->map_sex[$resumeinfo['sex']];
        $return['age'] = date('Y') - intval($resumeinfo['birthday']);
        $return['education_text'] = isset(
            model('BaseModel')->map_education[$resumeinfo['education']]
        )
            ? model('BaseModel')->map_education[$resumeinfo['education']]
            : '';
        $return['experience_text'] =
            $resumeinfo['enter_job_time'] == 0
                ? '无经验'
                : format_date($resumeinfo['enter_job_time']) . '经验';
        $intention_data = model('ResumeIntention')
            ->where('rid', '=', $resumeinfo['id'])
            ->order('id desc')
            ->find();
        if ($intention_data !== null) {
            $category_job_data = model('CategoryJob')->getCache();
            $category_district_data = model('CategoryDistrict')->getCache();
            $return['intention_wage'] = model('BaseModel')->handle_wage(
                $intention_data['minwage'],
                $intention_data['maxwage']
            );
            if ($intention_data['category']) {
                $return['intention_category'] = isset(
                    $category_job_data[$intention_data['category']]
                )
                    ? $category_job_data[$intention_data['category']]
                    : '';
            }
            if ($intention_data['district']) {
                $return['intention_district'] = isset(
                    $category_district_data[$intention_data['district']]
                )
                    ? $category_district_data[$intention_data['district']]
                    : '';
            }
        } else {
            $return['intention_category'] = '';
            $return['intention_district'] = '';
            $return['intention_wage'] = '';
        }
        $return['show_contact'] = 0;
        if ($this->userinfo->utype == 1) {
            $getResumeContact = model('Resume')->getContact($resumeinfo, $this->userinfo);
            $return['show_contact'] = $getResumeContact['show_contact'];
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function phraseList()
    {
        $this->checkLogin();
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/phrase/list';
        $result = https_request($url, ['token' => $token]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $result['result']]);
    }

    public function phraseEdit()
    {
        $this->checkLogin();
        $id = input('post.id/s', '', 'trim');
        $content = input('post.content/s', '', 'trim');
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/phrase/edit';
        $result = https_request($url, ['token' => $token, 'content' => $content, 'id' => $id]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '保存成功');
    }

    public function phraseAdd()
    {
        $this->checkLogin();
        $content = input('post.content/s', '', 'trim');
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/phrase/add';
        $result = https_request($url, ['token' => $token, 'content' => $content]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '保存成功', $result['result']);
    }

    public function phraseDel()
    {
        $this->checkLogin();
        $id = input('post.id/s', '', 'trim');
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/phrase/delete';
        $result = https_request($url, ['token' => $token, 'id' => $id]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '删除成功');
    }

    public function phraseSort()
    {
        $this->checkLogin();
        $id = input('post.id/s', '', 'trim');
        $isup = input('post.isup/d', 0, 'intval');
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/phrase/sort';
        $result = https_request($url, ['token' => $token, 'id' => $id, 'isup' => $isup]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '保存成功');
    }

    public function phraseSortAll()
    {
        $this->checkLogin();
        $sort_data = input('post.sort_data/a');
        $sort_data = json_encode($sort_data);
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/phrase/sortall';
        $result = https_request($url, ['token' => $token, 'sort_data' => $sort_data]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '保存成功');
    }

    public function joblist()
    {
        $this->checkLogin();
        $data = model('JobSearchRtime')
            ->alias('a')
            ->field('b.id,b.jobname,b.minwage,b.maxwage,b.negotiable,b.district1,b.district2,b.district3,b.education,b.experience')
            ->join(config('database.prefix') . 'job b', 'a.id=b.id', 'left');
        if ($this->userinfo->utype == 1) {
            $data = $data->where('a.uid', $this->userinfo->uid);
        } else {
            $company_id = input('get.company_id/d', 0, 'intval');
            $data = $data->where('a.company_id', $company_id);
        }
        $data = $data->order('a.refreshtime desc,a.id desc')->select();

        $list = [];
        $category_district_data = model('CategoryDistrict')->getCache();
        foreach ($data as $key => $value) {
            $arr['id'] = $value['id'];
            $arr['jobname'] = $value['jobname'];
            $arr['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            $arr['district_text'] = isset($category_district_data[$value['district1']]) ? $category_district_data[$value['district1']] : '';
            if ($arr['district_text'] != '' && $value['district2'] > 0) {
                $arr['district_text'] .= isset($category_district_data[$value['district2']]) ? ' / ' . $category_district_data[$value['district2']] : '';
            }
            if ($arr['district_text'] != '' && $value['district3'] > 0) {
                $arr['district_text'] .= isset($category_district_data[$value['district3']]) ? ' / ' . $category_district_data[$value['district3']] : '';
            }
            $arr['education_text'] = isset(model('BaseModel')->map_education[$value['education']]) ? model('BaseModel')->map_education[$value['education']] : '学历不限';
            $arr['experience_text'] = isset(model('BaseModel')->map_experience[$value['experience']]) ? model('BaseModel')->map_experience[$value['experience']] : '经验不限';
            $list[] = $arr;
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }

    public function changejob()
    {
        $this->checkLogin();
        $jobid = input('post.jobid/d', 0, 'intval');
        $chat_id = input('post.chat_id/s', '', 'trim');
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/chat/changejob';
        $result = https_request($url, ['token' => $token, 'chat_id' => $chat_id, 'jobid' => $jobid]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '操作成功');
    }

    public function chatStick()
    {
        $this->checkLogin();
        $stick = input('post.stick/d', 0, 'intval');
        $chat_id = input('post.chat_id/s', '', 'trim');
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/chat/stick';
        $result = https_request($url, ['token' => $token, 'chat_id' => $chat_id, 'stick' => $stick]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '操作成功');
    }

    public function hellomsgList()
    {
        $this->checkLogin();
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/hellomsg/list';
        $result = https_request($url, ['token' => $token]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $result['result']]);
    }

    public function hellomsgSelect()
    {
        $this->checkLogin();
        $id = input('post.id/s', '', 'trim');
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/hellomsg/select';
        $result = https_request($url, ['token' => $token, 'id' => $id]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '操作成功');
    }

    public function blacklist()
    {
        $this->checkLogin();
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/blacklist/list';
        $result = https_request($url, ['token' => $token]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }

        $dataset = ($result['result'] && count($result['result']) > 0) ? $result['result'] : [];
        $relate_uid_arr = [];
        foreach ($dataset as $key => $value) {
            $relate_uid_arr[] = $value['target_uid'];
        }

        $fullname_arr = [];

        $resumeinfo_arr = [];
        if ($this->userinfo->utype == 1 && !empty($relate_uid_arr)) {
            $resumeinfo_arr = model('Resume')->where('uid', 'in', $relate_uid_arr)
                ->column('uid,fullname as nickname,id as resumeid');
            $resumeid_arr = [];
            foreach ($resumeinfo_arr as $key => $value) {
                $resumeid_arr[] = $value['resumeid'];
            }
            if (!empty($resumeid_arr)) {
                $fullname_arr = model('Resume')->formatFullname($resumeid_arr, $this->userinfo);
            }
        }

        $companyinfo_arr = [];
        if ($this->userinfo->utype == 2 && !empty($relate_uid_arr)) {
            $companyinfo_arr = model('Company')
                ->where('uid', 'in', $relate_uid_arr)
                ->column('uid,companyname,id');
        }

        $return = [];
        foreach ($dataset as $key => $value) {
            $arr['id'] = $value['id'];
            $arr['jobname'] = $value['jobname'];
            $arr['addtime'] = date('Y-m-d', $value['addtime']);

            if ($this->userinfo->utype == 1) {
                $resumeinfo = isset($resumeinfo_arr[$value['target_uid']]) ? $resumeinfo_arr[$value['target_uid']] : null;
                if ($resumeinfo === null) {
                    continue;
                }
                $arr['showname'] = $fullname_arr[$resumeinfo['resumeid']] ? $fullname_arr[$resumeinfo['resumeid']] : '未知用户';
            } else {
                $companyinfo = isset($companyinfo_arr[$value['target_uid']]) ? $companyinfo_arr[$value['target_uid']] : null;
                if ($companyinfo === null) {
                    continue;
                }
                $arr['showname'] = $companyinfo['companyname'] ? $companyinfo['companyname'] : '未知用户';
            }
            $return[] = $arr;
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }

    public function blacklistDel()
    {
        $this->checkLogin();
        $id = input('post.id/s', '', 'trim');
        $chatid = input('post.chatid/s', '', 'trim');
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/blacklist/delete';
        $result = https_request($url, ['token' => $token, 'id' => $id, 'chatid' => $chatid]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '移出成功');
    }

    public function blacklistAdd()
    {
        $this->checkLogin();
        $jobname = input('post.jobname/s', '', 'trim');
        $chatid = input('post.chatid/s', '', 'trim');
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/blacklist/add';
        $result = https_request($url, ['token' => $token, 'chatid' => $chatid, 'jobname' => $jobname]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, '屏蔽成功');
    }

    public function blacklistCheck()
    {
        $this->checkLogin();
        $chatid = input('post.chatid/s', '', 'trim');
        $token = input('post.token/s', '', 'trim');
        $url = $this->baseUrl . '/blacklist/check';
        $result = https_request($url, ['token' => $token, 'chatid' => $chatid]);
        $result = json_decode($result, 1);
        if ($result['code'] == 500) {
            $this->ajaxReturn(500, $result['msg']);
        }
        $this->ajaxReturn(200, $result['msg'], $result['result']);
    }

    public function interviewInfo()
    {
        $this->checkLogin();
        $resumeid = input('post.resumeid/d', 0, 'intval');
        $jobid = input('post.jobid/d', 0, 'intval');
        if ($this->userinfo->utype == 1) {
            if (!$resumeid) {
                $this->ajaxReturn(500, '请选择');
            }
            $dataset = model('CompanyInterview')->where('resume_id', $resumeid)->where('uid', $this->userinfo->uid)->select();
        } else {
            if (!$jobid) {
                $this->ajaxReturn(500, '请选择');
            }
            $jobinfo = model('Job')->where('id', $jobid)->find();
            if (null === $jobinfo) {
                $this->ajaxReturn(500, '没有找到职位信息');
            }
            $dataset = model('CompanyInterview')->where('uid', $jobinfo['uid'])->where('personal_uid', $this->userinfo->uid)->select();
        }
        $return = [];
        foreach ($dataset as $key => $value) {
            $arr = [
                'jobname' => $value['jobname'],
                'companyname' => $value['companyname'],
                'interview_time' => date('Y-m-d H:i', $value['interview_time']),
                'address' => $value['address'],
                'contact' => $value['contact'],
                'tel' => $value['tel'],
                'note' => cut_str($value['note'], 14),
                'note_' => $value['note']
            ];
            $return[] = $arr;
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }

    public function imCheckBind()
    {
        $this->checkLogin();
        $bind_data = model('MemberBind')
            ->where(['uid' => $this->userinfo->uid])
            ->where('type', 'weixin')
            ->where('is_subscribe', 1)
            ->find();
        if (null === $bind_data) {
            $this->ajaxReturn(200, '未绑定', 0);
        }
        $this->ajaxReturn(200, '已绑定', 1);
    }

    /*
     * 【新增】职聊关键字屏蔽功能
     * zch 2022.10.18
     * */
    public function keywordReplace()
    {
        $content = input('get.content/s', '', 'trim');
        $string = badword_filter($content);
        $this->ajaxReturn(200, '获取成功', $string);
    }
}
