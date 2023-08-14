<?php
namespace app\v1_0\controller\member;

class Bind extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        if(config('global_config.closereg')==1){
            $this->ajaxReturn(500,'网站已关闭会员注册');
        }
    }
    public function qq()
    {
        $input_data = [
            'mobile' => input('post.mobile/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'utype' => input('post.utype/d', 0, 'intval'),
            'type' => 'qq',
            'openid' => input('post.openid/s', '', 'trim'),
            'unionid' => input('post.unionid/s', '', 'trim'),
            'nickname' => input('post.nickname/s', '', 'trim'),
            'avatar' => input('post.avatar/s', '', 'trim'),
            'bindtime' => time()
        ];
        $validate = new \think\Validate([
            'mobile' => 'require|checkMobile',
            'code' => 'require|max:4',
            'utype' => 'require|in:1,2',
            'openid' => 'require',
            'unionid' => 'require',
            'nickname' => 'require',
            'avatar' => 'require'
        ]);
        $validate->extend('checkMobile', function ($value) {
            if (fieldRegex($value, 'mobile')) {
                return true;
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
            (isset($auth_result['utype']) && $auth_result['utype'] != $input_data['utype'])
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
        $member = model('Member')
            ->where([
                'utype' => ['eq', $input_data['utype']],
                'mobile' => ['eq', $input_data['mobile']]
            ])
            ->find();

        if (!$member) {
            //如果未注册过，默认给注册一下
            if ($input_data['utype'] == 1) {
                $member = model('Member')->regCompany($input_data);
            } else {
                $member = model('Member')->regPersonal($input_data);
            }

            if (false === $member) {
                $this->ajaxReturn(500, model('Member')->getError());
            }
        } elseif ($member['status'] == 0) {
            $this->ajaxReturn(500, '账号已被暂停使用');
        }
        $bindinfo = model('MemberBind')->where([
            'uid' => ['eq', $member['uid']],
            'type' => $input_data['type'],
            'openid' => ['eq', $input_data['openid']],
            'unionid' => ['eq', $input_data['unionid']]
        ])->find();
        if ($bindinfo === null) {
            $sqlarr['uid'] = $member['uid'];
            $sqlarr['type'] = $input_data['type'];
            $sqlarr['openid'] = $input_data['openid'];
            $sqlarr['unionid'] = $input_data['unionid'];
            $sqlarr['nickname'] = $input_data['nickname'];
            $sqlarr['avatar'] = $input_data['avatar'];
            $sqlarr['bindtime'] = $input_data['bindtime'];
            model('MemberBind')->save($sqlarr);
            model('Task')->doTask($member['uid'], $member['utype'], 'bind_qq');
        }else{
            $sqlarr['uid'] = $member['uid'];
            $sqlarr['nickname'] = $input_data['nickname'];
            $sqlarr['avatar'] = $input_data['avatar'];
            $sqlarr['bindtime'] = $input_data['bindtime'];
            model('MemberBind')->save($sqlarr,['id'=>$bindinfo['id']]);
        }
        cache('smscode_' . $input_data['mobile'], null);
        $this->writeMemberActionLog($member['uid'],'绑定QQ');

        $this->ajaxReturn(
            200,
            '绑定并登录成功',
            $this->loginExtra(
                $member['uid'],
                $input_data['utype'],
                $member['mobile']
            )
        );
    }
    public function sina()
    {
        $input_data = [
            'mobile' => input('post.mobile/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'utype' => input('post.utype/d', 0, 'intval'),
            'type' => 'sina',
            'openid' => input('post.openid/s', '', 'trim'),
            'bindtime' => time()
        ];
        $validate = new \think\Validate([
            'mobile' => 'require|checkMobile',
            'code' => 'require|max:4',
            'utype' => 'require|in:1,2',
            'openid' => 'require'
        ]);
        $validate->extend('checkMobile', function ($value) {
            if (fieldRegex($value, 'mobile')) {
                return true;
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
            (isset($auth_result['utype']) && $auth_result['utype'] != $input_data['utype'])
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
        $member = model('Member')
            ->where([
                'utype' => ['eq', $input_data['utype']],
                'mobile' => ['eq', $input_data['mobile']]
            ])
            ->find();
        if (!$member) {
            //如果未注册过，默认给注册一下
            if ($input_data['utype'] == 1) {
                $member = model('Member')->regCompany($input_data);
            } else {
                $member = model('Member')->regPersonal($input_data);
            }

            if (false === $member) {
                $this->ajaxReturn(500, model('Member')->getError());
            }
        } elseif ($member['status'] == 0) {
            $this->ajaxReturn(500, '账号已被暂停使用');
        }
        if (
            model('MemberBind')
                ->where([
                    'uid' => ['eq', $member['uid']],
                    'type' => $input_data['type'],
                    'openid' => ['eq', $input_data['openid']]
                ])
                ->find() === null
        ) {
            $sqlarr['uid'] = $member['uid'];
            $sqlarr['type'] = $input_data['type'];
            $sqlarr['openid'] = $input_data['openid'];
            $sqlarr['unionid'] = '';
            $sqlarr['nickname'] = '';
            $sqlarr['avatar'] = '';
            $sqlarr['bindtime'] = $input_data['bindtime'];
            model('MemberBind')->save($sqlarr);
            model('Task')->doTask(
                $member['uid'],
                $member['utype'],
                'bind_sina'
            );
        }
        cache('smscode_' . $input_data['mobile'], null);
        $this->writeMemberActionLog($member['uid'],'绑定新浪微博');
        $this->ajaxReturn(
            200,
            '绑定并登录成功',
            $this->loginExtra(
                $member['uid'],
                $input_data['utype'],
                $member['mobile']
            )
        );
    }
    public function weixin()
    {
        $input_data = [
            'mobile' => input('post.mobile/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'utype' => input('post.utype/d', 0, 'intval'),
            'type' => 'weixin',
            'openid' => input('post.openid/s', '', 'trim'),
            'unionid' => input('post.unionid/s', '', 'trim'),
            'nickname' => input('post.nickname/s', '', 'trim'),
            'avatar' => input('post.avatar/s', '', 'trim'),
            'bindtime' => time()
        ];
        $validate = new \think\Validate([
            'mobile' => 'require|checkMobile',
            'code' => 'require|max:4',
            'utype' => 'require|in:1,2',
            'openid' => 'require',
            'unionid' => 'require',
            'nickname' => 'require',
            'avatar' => 'require'
        ]);
        $validate->extend('checkMobile', function ($value) {
            if (fieldRegex($value, 'mobile')) {
                return true;
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
            (isset($auth_result['utype']) && $auth_result['utype'] != $input_data['utype'])
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
        $member = model('Member')
            ->where([
                'utype' => ['eq', $input_data['utype']],
                'mobile' => ['eq', $input_data['mobile']]
            ])
            ->find();
        if (!$member) {
            //如果未注册过，默认给注册一下
            if ($input_data['utype'] == 1) {
                $member = model('Member')->regCompany($input_data);
            } else {
                $member = model('Member')->regPersonal($input_data);
            }

            if (false === $member) {
                $this->ajaxReturn(500, model('Member')->getError());
            }
        } elseif ($member['status'] == 0) {
            $this->ajaxReturn(500, '账号已被暂停使用');
        }


        $empty_bind = true;
        $bindinfo_where = [
            'type' => $input_data['type']
        ];
        if($input_data['unionid']!=''){
            $bindinfo_where['unionid'] = $input_data['unionid'];
        }else{
            $bindinfo_where['openid'] = $input_data['openid'];
        }
        $bindinfo = model('MemberBind')->where($bindinfo_where)->find();
        //如果该openid或unionid已经绑定过了，查询已绑定的是否是自己的账户；
        //情况一：如果不是，清除掉，继续绑定；
        //情况二：如果是，查询openid一致不一致，不一致说明绑定的是其他端，这次还需要再存一下信息；否则跳过；
        do{
            if($bindinfo!==null){
                $empty_bind = false;
                if($bindinfo['uid']!=$member['uid']){
                    model('MemberBind')->where($bindinfo_where)->delete();
                    $empty_bind = true;
                    break;
                }
                if($bindinfo['is_subscribe']==0){
                    if($bindinfo['openid']!=$input_data['openid']){
                        $empty_bind = true;
                        break;
                    }
                }
            }
        }while(0);

        //检测当前手机号是否绑定过其他账号
        if($empty_bind===true){
            $bindinfo = model('MemberBind')->where('type','weixin')->where('uid',$member['uid'])->find();
            if($bindinfo!==null && ($bindinfo['unionid']!=$input_data['unionid'] || $bindinfo['openid']!=$input_data['openid'])){
                model('MemberBind')->where('type','weixin')->where('uid',$member['uid'])->delete();
            }
        }

        if($empty_bind===true){
            $fansCheck = model('WechatFans')->where('openid',$input_data['openid'])->find();
            if($fansCheck===null){
                $is_subscribe = 0;
            }else{
                $is_subscribe = 1;
            }
            $sqlarr['uid'] = $member['uid'];
            $sqlarr['type'] = $input_data['type'];
            $sqlarr['openid'] = $input_data['openid'];
            $sqlarr['unionid'] = $input_data['unionid'];
            $sqlarr['nickname'] = $input_data['nickname'];
            $sqlarr['avatar'] = $input_data['avatar'];
            $sqlarr['bindtime'] = $input_data['bindtime'];
            $sqlarr['is_subscribe'] = $is_subscribe;
            model('MemberBind')->save($sqlarr);
            if($is_subscribe==1){
                model('Task')->doTask($member['uid'], $member['utype'], 'bind_weixin');
            }
        }

        cache('smscode_' . $input_data['mobile'], null);
        $this->writeMemberActionLog($member['uid'],'绑定微信');
        $this->ajaxReturn(
            200,
            '绑定并登录成功',
            $this->loginExtra(
                $member['uid'],
                $input_data['utype'],
                $member['mobile']
            )
        );
    }
}
