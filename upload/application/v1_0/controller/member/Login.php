<?php
namespace app\v1_0\controller\member;

class Login extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    protected function _verify($post_data)
    {
        if (config('global_config.captcha_open') == 1) {
            $captcha = new \app\common\lib\Captcha();
            try {
                $result = $captcha->verify($post_data);
            } catch (\Exception $e) {
                $this->ajaxReturn(500, $e->getMessage());
            }
            if (false === $result) {
                $this->ajaxReturn(500, $captcha->getError());
            }
        }
    }
    public function password()
    {
        if(request()->isGet()){
            $error_mark = input('get.username/s', '', 'trim');
        }else{
            $error_mark = input('post.username/s', '', 'trim');
        }
        $error_mark = 'login_pwd_error_num_'.$error_mark;
        $error_num = \think\Cache::get($error_mark)?\think\Cache::get($error_mark):0;
        if(request()->isGet()){
            $show = 0;
            if(config('global_config.captcha_open')==1){
                if($error_num>=config('global_config.captcha_show_by_pwd_error')){
                    $show = 1;
                }
            }
            $this->ajaxReturn(200,'获取数据成功',$show);
        }
        $input_data = [
            'username' => input('post.username/s', '', 'trim'),
            'password' => input('post.password/s', '', 'trim'),
            'utype' => input('post.utype/d', 0, 'intval')
        ];
        $validate = new \think\Validate([
            'username' => 'require|max:30',
            'password' => 'require|max:15',
            'utype' => 'require|in:1,2'
        ]);
        if (!$validate->check($input_data)) {
            \think\Cache::inc($error_mark);
            $this->ajaxReturn(500, $validate->getError());
        }
        if (fieldRegex($input_data['username'], 'mobile')) {
            $field = 'mobile';
        } elseif (fieldRegex($input_data['username'], 'email')) {
            $field = 'email';
        } else {
            $field = 'username';
        }
        if($error_num>=config('global_config.captcha_show_by_pwd_error')){
            $this->_verify(input('post.'));
        }
        
        $member = model('Member')
            ->where([
                'utype' => ['eq', $input_data['utype']],
                $field => ['eq', $input_data['username']]
            ])
            ->find();
        if (!$member) {
            \think\Cache::inc($error_mark);
            $this->ajaxReturn(500, '账号未注册');
        }
        if (
            $member['password'] !=
            model('Member')->makePassword(
                $input_data['password'],
                $member['pwd_hash']
            )
        ) {
            \think\Cache::inc($error_mark);
            $this->ajaxReturn(500, '账号或密码错误');
        }
        if ($member['status'] == 0) {
            \think\Cache::inc($error_mark);
            $this->ajaxReturn(500, '账号已被暂停使用');
        }
        //通知完整度
        if ($input_data['utype'] == 2) {
            // 刷新简历信息 chenyang 2022年3月15日10:10:51
            model('Resume')->refreshResumeData($member);

            $notify_alias = '';
            $compelte_percent = model('Resume')->countCompletePercent(
                0,
                $member['uid']
            );
            if ($compelte_percent <= 55) {
                $notify_alias = 'resume_complete_too_low';
            } elseif ($compelte_percent <= 75) {
                $notify_alias = 'resume_complete_lower';
            }
            if ($notify_alias != '') {
                model('NotifyRule')->notify($member['uid'], 2, $notify_alias);
            }
        }
        \think\Cache::rm($error_mark);
        $this->ajaxReturn(
            200,
            '登录成功',
            $this->loginExtra(
                $member['uid'],
                $member['utype'],
                $member['mobile']
            )
        );
    }
    public function code()
    {
        if(request()->isGet()){
            $error_mark = input('get.mobile/s', '', 'trim');
        }else{
            $error_mark = input('post.mobile/s', '', 'trim');
        }
        $error_mark = 'login_code_error_num_'.$error_mark;
        $error_num = \think\Cache::get($error_mark)?\think\Cache::get($error_mark):0;
        if(request()->isGet()){
            $show = 0;
            if(config('global_config.captcha_open')==1){
                if($error_num>=config('global_config.captcha_show_by_code_error')){
                    $show = 1;
                }
            }
            $this->ajaxReturn(200,'获取数据成功',$show);
        }
        $input_data = [
            'mobile' => input('post.mobile/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'utype' => input('post.utype/d', 0, 'intval')
        ];
        $validate = new \think\Validate([
            'mobile' => 'require|checkMobile',
            'code' => 'require|max:4',
            'utype' => 'require|in:1,2'
        ]);
        $validate->extend('checkMobile', function ($value) {
            if (fieldRegex($value, 'mobile')) {
                return true;
            } else {
                \think\Cache::inc($error_mark);
                return '请输入正确的手机号码';
            }
        });
        if (!$validate->check($input_data)) {
            \think\Cache::inc($error_mark);
            $this->ajaxReturn(500, $validate->getError());
        }
        $auth_result = cache('smscode_' . $input_data['mobile']);
        if (
            $auth_result === false ||
            $auth_result['code'] != $input_data['code'] ||
            $auth_result['mobile'] != $input_data['mobile'] ||
            (isset($auth_result['utype']) && $auth_result['utype'] != $input_data['utype'])
        ) {
            \think\Cache::inc('smscode_error_num_' . $input_data['mobile']);
            \think\Cache::inc($error_mark);
            $this->ajaxReturn(500, '验证码错误');
        }
        $smscode_error_num = \think\Cache::get(
            'smscode_error_num_' . $input_data['mobile']
        );
        if ($smscode_error_num !== false && $smscode_error_num >= 5) {
            \think\Cache::inc($error_mark);
            $this->ajaxReturn(500, '验证码失效，请重新获取');
        }
        if($error_num>=config('global_config.captcha_show_by_code_error')){
            $this->_verify(input('post.'));
        }
        $member = model('Member')
            ->where([
                'utype' => ['eq', $input_data['utype']],
                'mobile' => ['eq', $input_data['mobile']]
            ])
            ->find();
        $is_reg = 0;
        if (!$member) {
            $is_reg = 1;
            //如果未注册过，默认给注册一下
            if ($input_data['utype'] == 1) {
                $member = model('Member')->regCompany($input_data);
            } else {
                $member = model('Member')->regPersonal($input_data);
            }

            if (false === $member) {
                \think\Cache::inc($error_mark);
                $this->ajaxReturn(500, model('Member')->getError());
            }
        } elseif ($member['status'] == 0) {
            \think\Cache::inc($error_mark);
            $this->ajaxReturn(500, '账号已被暂停使用');
        }
        cache('smscode_' . $input_data['mobile'], null);

        //通知完整度
        if ($input_data['utype'] == 2 && $is_reg==0) {
            // 刷新简历信息 chenyang 2022年3月15日10:10:51
            model('Resume')->refreshResumeData($member);

            $notify_alias = '';
            $compelte_percent = model('Resume')->countCompletePercent(
                0,
                $member['uid']
            );
            if ($compelte_percent <= 55) {
                $notify_alias = 'resume_complete_too_low';
            } elseif ($compelte_percent <= 75) {
                $notify_alias = 'resume_complete_lower';
            }
            if ($notify_alias != '') {
                model('NotifyRule')->notify($member['uid'], 2, $notify_alias);
            }
        }

        \think\Cache::rm($error_mark);
        \think\Cache::rm('smscode_error_num_' . $input_data['mobile']);
        $this->ajaxReturn(
            200,
            '登录成功',
            $this->loginExtra(
                $member['uid'],
                $input_data['utype'],
                $member['mobile']
            )
        );
    }
    /**
     * qq登录
     */
    public function qq()
    {
        $mod = 'qq';
        $openid = input('post.openid/s', '', 'trim');
        $unionid = input('post.unionid/s', '', 'trim');
        $nickname = input('post.nickname/s', '', 'trim');
        $avatar = input('post.avatar/s', '', 'trim');
        $where['type'] = $mod;
        $where['unionid'] = $unionid;
        $bind_info = model('MemberBind')
            ->where($where)
            ->find();
        if ($bind_info === null) {
            $this->ajaxReturn(50006, '未绑定',['openid'=>$openid,'unionid'=>$unionid,'nickname'=>$nickname,'avatar'=>$avatar,'bindType'=>$mod]);
        }
        $member = model('Member')
            ->where([
                'uid' => ['eq', $bind_info['uid']]
            ])
            ->find();
        if ($member === null) {
            $this->ajaxReturn(500, '未找到会员信息');
        }
        if ($member['status'] == 0) {
            $this->ajaxReturn(500, '账号已被暂停使用');
        }
        $bind_info_other = model('MemberBind')
            ->where(['type' => $mod, 'openid' => $openid])
            ->find();
        if ($bind_info_other === null) {
            $sqlarr['uid'] = $bind_info['uid'];
            $sqlarr['type'] = $bind_info['type'];
            $sqlarr['openid'] = $openid;
            $sqlarr['unionid'] = $unionid;
            $sqlarr['nickname'] = $bind_info['nickname'];
            $sqlarr['avatar'] = $bind_info['avatar'];
            $sqlarr['bindtime'] = $bind_info['bindtime'];
            model('MemberBind')->save($sqlarr);
            model('Task')->doTask($member['uid'], $member['utype'], 'bind_qq');
        }
        //通知完整度
        if ($member['utype'] == 2) {
            // 刷新简历信息 chenyang 2022年3月15日10:10:51
            model('Resume')->refreshResumeData($member);

            $notify_alias = '';
            $compelte_percent = model('Resume')->countCompletePercent(
                0,
                $member['uid']
            );
            if ($compelte_percent <= 55) {
                $notify_alias = 'resume_complete_too_low';
            } elseif ($compelte_percent <= 75) {
                $notify_alias = 'resume_complete_lower';
            }
            if ($notify_alias != '') {
                model('NotifyRule')->notify($member['uid'], 2, $notify_alias);
            }
        }
        $this->ajaxReturn(
            200,
            '登录成功',
            $this->loginExtra(
                $member['uid'],
                $member['utype'],
                $member['mobile']
            )
        );
    }
    /**
     * sina登录
     */
    public function sina()
    {
        $mod = 'sina';
        $openid = input('post.openid/s', '', 'trim');
        $where['type'] = $mod;
        $where['openid'] = $openid;
        $bind_info = model('MemberBind')
            ->where($where)
            ->find();
        if ($bind_info === null) {
            $this->ajaxReturn(50006, '未绑定');
        }
        $member = model('Member')
            ->where([
                'uid' => ['eq', $bind_info['uid']]
            ])
            ->find();
        if ($member === null) {
            $this->ajaxReturn(500, '未找到会员信息');
        }
        if ($member['status'] == 0) {
            $this->ajaxReturn(500, '账号已被暂停使用');
        }
        //通知完整度
        if ($member['utype'] == 2) {
            // 刷新简历信息 chenyang 2022年3月15日10:10:51
            model('Resume')->refreshResumeData($member);

            $notify_alias = '';
            $compelte_percent = model('Resume')->countCompletePercent(
                0,
                $member['uid']
            );
            if ($compelte_percent <= 55) {
                $notify_alias = 'resume_complete_too_low';
            } elseif ($compelte_percent <= 75) {
                $notify_alias = 'resume_complete_lower';
            }
            if ($notify_alias != '') {
                model('NotifyRule')->notify($member['uid'], 2, $notify_alias);
            }
        }
        $this->ajaxReturn(
            200,
            '登录成功',
            $this->loginExtra(
                $member['uid'],
                $member['utype'],
                $member['mobile']
            )
        );
    }
    /**
     * weixin登录
     */
    public function weixin()
    {
        $mod = 'weixin';
        $openid = input('post.openid/s', '', 'trim');
        $unionid = input('post.unionid/s', '', 'trim');
        $nickname = input('post.nickname/s', '', 'trim');
        $avatar = input('post.avatar/s', '', 'trim');
        $where['type'] = $mod;
        if($unionid!=''){
            $where['unionid'] = $unionid;
        }else{
            $where['openid'] = $openid;
        }
        $bind_info = model('MemberBind')
            ->where($where)
            ->find();
        if ($bind_info === null) {
            $this->ajaxReturn(50006, '未绑定',['openid'=>$openid,'unionid'=>$unionid,'nickname'=>$nickname,'avatar'=>$avatar,'bindType'=>$mod]);
        }
        $member = model('Member')
            ->where([
                'uid' => ['eq', $bind_info['uid']]
            ])
            ->find();
        if ($member === null) {
            $this->ajaxReturn(500, '未找到会员信息');
        }
        if ($member['status'] == 0) {
            $this->ajaxReturn(500, '账号已被暂停使用');
        }
        
        //通知完整度
        if ($member['utype'] == 2) {
            // 刷新简历信息 chenyang 2022年3月15日10:10:51
            model('Resume')->refreshResumeData($member);

            $notify_alias = '';
            $compelte_percent = model('Resume')->countCompletePercent(
                0,
                $member['uid']
            );
            if ($compelte_percent <= 55) {
                $notify_alias = 'resume_complete_too_low';
            } elseif ($compelte_percent <= 75) {
                $notify_alias = 'resume_complete_lower';
            }
            if ($notify_alias != '') {
                model('NotifyRule')->notify($member['uid'], 2, $notify_alias);
            }
        }
        $this->ajaxReturn(
            200,
            '登录成功',
            $this->loginExtra(
                $member['uid'],
                $member['utype'],
                $member['mobile']
            )
        );
    }
    
    public function logout(){
        $visitor = new \app\common\lib\Visitor;
        $visitor->setLogout();
        $this->ajaxReturn(200,'退出成功');
    }
}
