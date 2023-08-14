<?php

namespace app\apiadmin\controller;

class Login extends \app\common\controller\Backend
{
    public function captcha()
    {
        $captcha = new \think\captcha\Captcha(['useZh' => false]);
        $result = $captcha->entryWithJwt();
        $this->ajaxReturn(200, '获取验证码成功', $result);
    }
    public function index()
    {
        $data['username'] = input('post.username/s', '', 'trim');
        $data['password'] = input('post.password/s', '', 'trim');
        $data['code'] = input('post.code/s', '', 'trim');
        $data['secret_str'] = input('post.secret_str/s', '', 'trim');
        $validate = validate('Login');
        if (!$validate->check($data)) {
            $this->ajaxReturn(0, $validate->getError());
        } else {
            $admininfo = model('Admin')
                ->where([
                    'username' => ['eq', $data['username']]
                ])
                ->find();
            if (!$admininfo) {
                $this->ajaxReturn(0, '没有找到用户信息');
            }
            $loginReturn = model('Admin')->setLogin($admininfo);
            $this->ajaxReturn(200, '登录成功', $loginReturn);
        }
    }
    public function logout()
    {
        $this->ajaxReturn(200, '退出成功');
    }
    public function userinfo()
    {
        $this->ajaxReturn(200, '获取数据成功', $this->admininfo);
    }
    public function config()
    {
        $this->ajaxReturn(200, '获取数据成功', model('Config')->getCache());
    }
    public function scan()
    {
        $scan_token = input('post.scan_token/s', '', 'trim');
        if($scan_token){
            $certinfo = model('AdminScanCert')->where('token',$scan_token)->find();
            if($certinfo!==null && $certinfo->info!=''){
                $info = json_decode($certinfo->info);
                $certinfo->delete();
                $this->ajaxReturn(200, '登录成功', ['pass'=>1,'info'=>$info]);
            }
        }
        $this->ajaxReturn(200, '等待扫码', ['pass'=>0,'info'=>[]]);
    }
}
