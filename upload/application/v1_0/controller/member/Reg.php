<?php
namespace app\v1_0\controller\member;

class Reg extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        if(config('global_config.closereg')==1){
            $this->ajaxReturn(500,'网站已关闭会员注册');
        }
    }
    /**
     * 企业注册
     */
    public function company()
    {
        $input_data = [
            'companyname' => input('post.companyname/s', '', 'trim,badword_filter'),
            'contact' => input('post.contact/s', '', 'trim,badword_filter'),
            'mobile' => input('post.mobile/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'password' => input('post.password/s', '', 'trim'),
            'scene_uuid' => input('post.scene_uuid/s', '', 'trim'),
            'scene_id' => input('post.scene_id/s', '', 'trim')
        ];
        $validate = new \think\Validate([
            'companyname' => 'require|max:60|uniqueCompanyname',
            'contact' => 'require|max:30',
            'mobile' => 'require|checkMobile',
            'code' => 'require|max:4',
            'password' => 'require|max:15|min:6'
        ]);
        $validate->extend('uniqueCompanyname', function ($value) {
            if (config('global_config.company_repeat') == 1) {
                return true;
            } else {
                $info = model('Company')
                    ->where('companyname', $value)
                    ->find();
                if ($info === null) {
                    return true;
                } else {
                    return '企业名称已被占用';
                }
            }
        });
        $validate->extend('checkMobile', function ($value) {
            if (fieldRegex($value, 'mobile')) {
                $info = model('Member')
                    ->where([
                        'mobile' => $value,
                        'utype' => 1
                    ])
                    ->find();
                if (null === $info) {
                    return true;
                } else {
                    return '手机号已被占用';
                }
            } else {
                return '请输入正确的手机号码';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $auth_result = cache('smscode_' . $input_data['mobile']);
        if (
            $auth_result === false ||
            $auth_result['code'] != $input_data['code'] ||
            $auth_result['mobile'] != $input_data['mobile'] ||
            $auth_result['utype'] != 1
        ) {
            \think\Cache::inc('smscode_error_num_' . $input_data['mobile']);
            $this->ajaxReturn(500, '验证码错误');
        }
        $error_num = \think\Cache::get(
            'smscode_error_num_' . $input_data['mobile']
        );
        if ($error_num !== false && $error_num >= 5) {
            $this->ajaxReturn(500, '验证码失效，请重新获取');
        }
        //开始注册
        $reg_userinfo = model('Member')->regCompany($input_data);
        if (false === $reg_userinfo) {
            $this->ajaxReturn(500, model('Member')->getError());
        }
        if($input_data['scene_uuid'] || $input_data['scene_id']){
            $scene_qrcode_info = model('SceneQrcode')->where('id',$input_data['scene_id'])->whereOr('uuid',$input_data['scene_uuid'])->find();
            if($scene_qrcode_info!==null){
                model('SceneQrcodeRegLog')->save(['uid'=>$reg_userinfo['uid'],'pid'=>$scene_qrcode_info['id'],'addtime'=>time()]);
            }
        }
        cache('smscode_' . $input_data['mobile'], null);
        $this->ajaxReturn(
            200,
            '注册成功',
            $this->loginExtra($reg_userinfo['uid'], 1, $input_data['mobile'])
        );
    }
    /**
     * 求职者注册
     */
    public function personal()
    {
        $input_data = [
            'mobile' => input('post.mobile/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'password' => input('post.password/s', '', 'trim'),
            'scene_uuid' => input('post.scene_uuid/s', '', 'trim'),
            'scene_id' => input('post.scene_id/s', '', 'trim')
        ];
        $validate = new \think\Validate([
            'mobile' => 'require|checkMobile',
            'code' => 'require|max:4',
            'password' => 'require|max:15|min:6'
        ]);
        $validate->extend('checkMobile', function ($value) {
            if (fieldRegex($value, 'mobile')) {
                $info = model('Member')
                    ->where([
                        'mobile' => $value,
                        'utype' => 2
                    ])
                    ->find();
                if (null === $info) {
                    return true;
                } else {
                    return '手机号已被占用';
                }
            } else {
                return '请输入正确的手机号码';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $auth_result = cache('smscode_' . $input_data['mobile']);
        if (
            $auth_result === false ||
            $auth_result['code'] != $input_data['code'] ||
            $auth_result['mobile'] != $input_data['mobile'] ||
            $auth_result['utype'] != 2
        ) {
            \think\Cache::inc('smscode_error_num_' . $input_data['mobile']);
            $this->ajaxReturn(500, '验证码错误');
        }
        $error_num = \think\Cache::get(
            'smscode_error_num_' . $input_data['mobile']
        );
        if ($error_num !== false && $error_num >= 5) {
            $this->ajaxReturn(500, '验证码失效，请重新获取');
        }
        //开始注册
        $reg_userinfo = model('Member')->regPersonal($input_data);
        if (false === $reg_userinfo) {
            $this->ajaxReturn(500, model('Member')->getError());
        }
        if($input_data['scene_uuid'] || $input_data['scene_id']){
            $scene_qrcode_info = model('SceneQrcode')->where('id',$input_data['scene_id'])->whereOr('uuid',$input_data['scene_uuid'])->find();
            if($scene_qrcode_info!==null){
                model('SceneQrcodeRegLog')->save(['uid'=>$reg_userinfo['uid'],'pid'=>$scene_qrcode_info['id'],'addtime'=>time()]);
            }
        }
        cache('smscode_' . $input_data['mobile'], null);
        $this->ajaxReturn(
            200,
            '注册成功',
            $this->loginExtra($reg_userinfo['uid'], 2, $input_data['mobile'])
        );
    }
    /**
     * 快速注册简历
     */
    public function personalQuick()
    {
        $input_data = [
            'jobid'=>input('post.jobid/d', 0, 'intval'),
            'fullname' => input('post.fullname/s', '', 'trim,badword_filter'),
            'sex' => input('post.sex/d', 0, 'intval'),
            'birthday' => input('post.birthday/s', '', 'trim'),
            'education' => input('post.education/d', 0, 'intval'),
            'enter_job_time' => input(
                'post.enter_job_time/s',
                '',
                'trim'
            ),
            'category1' => input('post.category1/d', 0, 'intval'),
            'category2' => input('post.category2/d', 0, 'intval'),
            'category3' => input('post.category3/d', 0, 'intval'),
            'district1' => input('post.district1/d', 0, 'intval'),
            'district2' => input('post.district2/d', 0, 'intval'),
            'district3' => input('post.district3/d', 0, 'intval'),
            'minwage' => input('post.minwage/d', 0, 'intval'),
            'maxwage' => input('post.maxwage/d', 0, 'intval'),
            'current' => input('post.current/d', 0, 'intval'),
            'mobile' => input('post.mobile/s', '', 'trim,badword_filter'),
            'code' => input('post.code/s', '', 'trim'),
            'password' => input('post.password/s', '', 'trim'),
            'scene_uuid' => input('post.scene_uuid/s', '', 'trim'),
            'scene_id' => input('post.scene_id/s', '', 'trim'),
        ];
        $validate = new \think\Validate([
            'jobid' => 'require|number|gt:0',
            'fullname' => 'require|max:15',
            'sex' => 'require|in:1,2',
            'birthday' => 'require|max:15',
            'education' => 'require|number|gt:0',
            // 'enter_job_time' => 'require',
            'category1' => 'require|number|gt:0',
            'category2' => 'require|number|egt:0',
            'category3' => 'require|number|egt:0',
            'district1' => 'require|number|gt:0',
            'district2' => 'require|number|egt:0',
            'district3' => 'require|number|egt:0',
            'minwage' => 'require|number|gt:0',
            'maxwage' => 'require|number|gt:0',
            'current' => 'number|gt:0',
            'mobile' => 'require|checkMobile',
            'code' => 'require|max:4',
            'password' => 'require|max:15|min:6'
        ]);
        $validate->extend('checkMobile', function ($value) {
            if (fieldRegex($value, 'mobile')) {
                $info = model('Member')
                    ->where([
                        'mobile' => $value,
                        'utype' => 2
                    ])
                    ->find();
                if (null === $info) {
                    return true;
                } else {
                    return '手机号已被占用';
                }
            } else {
                return '请输入正确的手机号码';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $auth_result = cache('smscode_' . $input_data['mobile']);
        if (
            $auth_result === false ||
            $auth_result['code'] != $input_data['code'] ||
            $auth_result['mobile'] != $input_data['mobile'] ||
            $auth_result['utype'] != 2
        ) {
            \think\Cache::inc('smscode_error_num_' . $input_data['mobile']);
            $this->ajaxReturn(500, '验证码错误');
        }
        $error_num = \think\Cache::get(
            'smscode_error_num_' . $input_data['mobile']
        );
        if ($error_num !== false && $error_num >= 5) {
            $this->ajaxReturn(500, '验证码失效，请重新获取');
        }
        //开始注册
        \think\Db::startTrans();
        try {
            $reg_personal_data = [
                'mobile' => $input_data['mobile'],
                'code' => $input_data['code'],
                'password' => $input_data['password']
            ];
            $reg_userinfo = model('Member')->regPersonal($reg_personal_data);
            if (false === $reg_userinfo) {
                throw new \Exception(model('Member')->getError());
            }
            $add_resume_data = [
                'uid'=>$reg_userinfo['uid'],
                'fullname' => $input_data['fullname'],
                'sex' => $input_data['sex'],
                'birthday' => $input_data['birthday'],
                'education' => $input_data['education'],
                'enter_job_time' => $input_data['enter_job_time'],
                'current' => $input_data['current'],
                'major1' => 0,
                'major2' => 0,
                'major' => 0
            ];
            $add_resume_data['enter_job_time'] = !$add_resume_data['enter_job_time'] ? 0 : strtotime($add_resume_data['enter_job_time']);
            $add_resume_data['platform'] = config('platform');
            $result = model('Resume')
                ->validate('Resume.reg_from_app_by_form')
                ->allowField(true)
                ->save($add_resume_data);
            if (false === $result) {
                throw new \Exception(model('Resume')->getError());
            }
            $resume_id = model('Resume')->id;

            $ad_contact_data = [
                'rid'=>$resume_id,
                'uid'=>$reg_userinfo['uid'],
                'mobile'=>$input_data['mobile'],
                'email'=>'',
                'qq'=>'',
                'weixin'=>''
            ];
            $result = model('ResumeContact')
                    ->validate(false)
                    ->allowField(true)
                    ->save($ad_contact_data);
            if (false === $result) {
                throw new \Exception(model('ResumeContact')->getError());
            }

            $add_intention_data = [
                'rid'=>$resume_id,
                'uid'=>$reg_userinfo['uid'],
                'category1' => $input_data['category1'],
                'category2' => $input_data['category2'],
                'category3' => $input_data['category3'],
                'district1' => $input_data['district1'],
                'district2' => $input_data['district2'],
                'district3' => $input_data['district3'],
                'minwage' => $input_data['minwage'],
                'maxwage' => $input_data['maxwage'],
                'current' => $input_data['current']
            ];
            $add_intention_data['category'] =
                $add_intention_data['category3'] > 0
                    ? $add_intention_data['category3']
                    : ($add_intention_data['category2'] > 0
                    ? $add_intention_data['category2']
                    : $add_intention_data['category1']);
            $add_intention_data['district'] =
                $add_intention_data['district3'] > 0
                    ? $add_intention_data['district3']
                    : ($add_intention_data['district2'] > 0
                    ? $add_intention_data['district2']
                    : $add_intention_data['district1']);
            $result = model('ResumeIntention')
                ->validate('ResumeIntention.reg_from_app_by_form')
                ->allowField(true)
                ->save($add_intention_data);
            if (false === $result) {
                throw new \Exception(model('ResumeIntention')->getError());
            }
            //更新完整度
            model('Resume')->updateComplete(
                [
                    'basic' => 1,
                    'intention' => 1
                ],
                $resume_id,
                $reg_userinfo['uid']
            );
            model('ImToken')->regToken(
                $reg_userinfo['uid'],
                2
            );
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        model('Resume')->refreshSearch($resume_id);
        $this->writeMemberActionLog($reg_userinfo['uid'],'注册 - 保存简历基本信息');
        if($input_data['scene_uuid'] || $input_data['scene_id']){
            $scene_qrcode_info = model('SceneQrcode')->where('id',$input_data['scene_id'])->whereOr('uuid',$input_data['scene_uuid'])->find();
            if($scene_qrcode_info!==null){
                model('SceneQrcodeRegLog')->save(['uid'=>$reg_userinfo['uid'],'pid'=>$scene_qrcode_info['id'],'addtime'=>time()]);
            }
        }
        cache('smscode_' . $input_data['mobile'], null);
        $login_return = $this->loginExtra($reg_userinfo['uid'], 2, $input_data['mobile']);
        $global_config = config('global_config');

        $current_complete = model('Resume')->countCompletePercent($resume_id);
        $login_return['require_complete'] = $global_config['apply_job_min_percent'];
        $login_return['current_complete'] = $current_complete;

        if(config('global_config.audit_add_resume')==0){
            $login_return['next_code'] = 50005;
            $this->ajaxReturn(
                200,
                '您的简历还在审核中，暂不能投递简历哦！完整度高的简历更容易求职成功，建议您立即完善！',
                $login_return
            );
        }

        if ($current_complete < $global_config['apply_job_min_percent']) {
            $login_return['next_code'] = 50005;
            $this->ajaxReturn(
                200,
                '此公司要求简历完整度要达到' . $global_config['apply_job_min_percent'] . '%才能投递！您的简历完整度仅30%，严重影响求职成功率！',
                $login_return
            );
        }
        $job_apply_data = [
            'jobid'=>$input_data['jobid'],
            'note'=>''
        ];
        if (
            false ===
            model('JobApply')->jobApplyAdd($job_apply_data, $reg_userinfo['uid'])
        ) {
            $this->ajaxReturn(500, model('JobApply')->getError());
        }
        $this->writeMemberActionLog($reg_userinfo['uid'],'投递简历【职位ID：'.$job_apply_data['jobid'].'】');
        $this->ajaxReturn(
            200,
            '投递成功',
            $login_return
        );
    }
}
