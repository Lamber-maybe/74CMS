<?php
namespace app\apiadminmobile\controller;

class Member extends \app\apiadmin\controller\Member
{
    public function saveMemberUsername()
    {
        $input_data = [
            'username' => input('post.username/s', '', 'trim')
        ];
        $uid = input('post.uid/d', 0, 'intval');
        $info = model('Member')->find($uid);
        if (!$info) {
            $this->ajaxReturn(500, '数据获取失败');
        }
        if(!$input_data['username']){
            $this->ajaxReturn(500, '请输入用户名');
        }
        $check_username = model('Member')->where('username',$input_data['username'])->where('uid','neq',$uid)->find();
        if($check_username!==null){
            $this->ajaxReturn(500, '用户名已被占用');
        }
        $result = model('Member')
            ->allowField(true)
            ->save($input_data, ['uid' => $uid]);
        if (false === $result) {
            $this->ajaxReturn(500, model('Member')->getError());
        }
        model('AdminLog')->record(
            '修改会员用户名。会员UID【' .
                $uid .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function saveMemberPassword()
    {
        $input_data = [
            'password' => input('post.password/s', '', 'trim')
        ];
        $uid = input('post.uid/d', 0, 'intval');
        $info = model('Member')->find($uid);
        if (!$info) {
            $this->ajaxReturn(500, '数据获取失败');
        }
        if(!$input_data['password']){
            $this->ajaxReturn(500, '请输入密码');
        }
        $input_data['password'] = model('Member')->makePassword(
            $input_data['password'],
            $info['pwd_hash']
        );
        $result = model('Member')
            ->allowField(true)
            ->save($input_data, ['uid' => $uid]);
        if (false === $result) {
            $this->ajaxReturn(500, model('Member')->getError());
        }
        model('AdminLog')->record(
            '修改会员密码。会员UID【' .
                $uid .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '修改密码成功');
    }
    public function saveMemberMobile()
    {
        $input_data = [
            'mobile' => input('post.mobile/s', '', 'trim')
        ];
        $uid = input('post.uid/d', 0, 'intval');
        $info = model('Member')->find($uid);
        if (!$info) {
            $this->ajaxReturn(500, '数据获取失败');
        }
        if(!$input_data['mobile']){
            $this->ajaxReturn(500, '请输入手机号');
        }
        $check_mobile = model('Member')->where('mobile',$input_data['mobile'])->where('uid','neq',$uid)->find();
        if($check_mobile!==null){
            $this->ajaxReturn(500, '手机号已被占用');
        }
        $result = model('Member')
            ->allowField(true)
            ->save($input_data, ['uid' => $uid]);
        if (false === $result) {
            $this->ajaxReturn(500, model('Member')->getError());
        }
        model('AdminLog')->record(
            '修改会员手机号。会员UID【' .
                $uid .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
}
