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
            $login_update_info['last_login_time'] = time();
            $login_update_info['last_login_ip'] = get_client_ip();
            $login_update_info['last_login_ipaddress'] = get_client_ipaddress(
                $login_update_info['last_login_ip']
            );
            $login_update_info['last_login_ip'] =
                $login_update_info['last_login_ip'] . ':' . get_client_port();
            model('Admin')
                ->where('id', $admininfo['id'])
                ->update($login_update_info);
            $roleinfo = model('AdminRole')->find($admininfo['role_id']);
            $admininfo['access'] =
                $roleinfo['access'] == 'all'
                    ? $roleinfo['access']
                    : unserialize($roleinfo['access']);
            $admininfo['rolename'] = $roleinfo['name'];
            $JwtAuth = \app\common\lib\JwtAuth::mkToken(
                config('sys.safecode'),
                7776000, //90天有效期
                ['info' => $admininfo]
            );
            $admin_token = $JwtAuth->getString();
            model('AdminLog')->record('登录成功', $admininfo, 1);
            $this->ajaxReturn(200, '登录成功', [
                'token' => $admin_token,
                'access' => $admininfo['access']
            ]);
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
}
