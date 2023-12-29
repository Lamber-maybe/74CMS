<?php

namespace app\apiadmin\controller;

class Login extends \app\common\controller\Backend
{
    public function captcha()
    {
        $captcha = new \think\captcha\Captcha(config('global_config.captcha_picture_rule'));
        $result = $captcha->entryWithJwt();
        $this->ajaxReturn(200, '获取验证码成功', $result);
    }
    public function index()
    {
        $data['username'] = input('post.username/s', '', 'trim');
        $data['password'] = input('post.password/s', '', 'trim');
        /**
         * 明文传输漏洞
         * 旧代码:
         *  $validate = validate('Login');
         * @since    2022/4/27
         */
        $validate = validate('LoginMd5');
        /**
         * 【ID1000706】
         * 【bug/新增】系统内置验证码加纯数字
         * cy 2023-6-30
         * 验证码开启后再进行验证码的校验
         */
        $rule = [];
        if (config('global_config.captcha_open') == 1) {
            $data['code'] = input('post.code/s', '', 'trim');
            $data['secret_str'] = input('post.secret_str/s', '', 'trim');
            $rule = [
                'code' => 'require|checkCaptcha',
                'secret_str' => 'require',
            ];
        }
        if (!$validate->rule($rule)->check($data)) {
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
            if (isset($admininfo['status']) && $admininfo['status'] != 1) {
                $this->ajaxReturn(0, '当前管理员账号已被锁定，请联系超级管理员');
            }
            $loginReturn = model('Admin')->setLogin($admininfo);
            $this->ajaxReturn(200, '登录成功', $loginReturn);
        }
    }
    public function logout()
    {
        // 清除token信息
        model('AdminIdentityToken')->where(['admin_id' => $this->admininfo['id']])->delete();
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
