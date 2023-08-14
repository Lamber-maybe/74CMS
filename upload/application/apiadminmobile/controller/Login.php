<?php
namespace app\apiadminmobile\controller;

class Login extends \app\apiadmin\controller\Login
{
    public function config()
    {
        $nameArr = ['sitename','wechat_appid'];
        $info = model('Config')->whereIn('name',$nameArr)->column('name,value');
        $this->ajaxReturn(200, '获取数据成功', $info);
    }
    public function weixin(){
        $code = input('post.code');
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.config('global_config.wechat_appid').'&secret='.config('global_config.wechat_appsecret').'&code='.$code.'&grant_type=authorization_code';
        $http = new \app\common\lib\Http;
        $result = $http->get($url);
        $result = json_decode($result,1);
        if(isset($result['openid'])){
            $admininfo = model('Admin')->where('openid',$result['openid'])->find();
            if($admininfo===null){
                $this->ajaxReturn(200, '当前微信未绑定管理员');
            }
            $login_update_info['last_login_time'] = time();
            $login_update_info['last_login_ip'] = get_client_ip();
            $login_update_info['last_login_ipaddress'] = get_client_ipaddress(
                $login_update_info['last_login_ip']
            );
            $login_update_info['last_login_ip'] =
                $login_update_info['last_login_ip'] . ':' . get_client_port();
            model('Admin')->where('id', $admininfo['id'])->update($login_update_info);
            $roleinfo = model('AdminRole')->find($admininfo['role_id']);
            $admininfo['access'] = $roleinfo['access'] == 'all' ? $roleinfo['access'] : unserialize($roleinfo['access']);
            $admininfo['access_mobile'] = $roleinfo['access_mobile'] == 'all' ? $roleinfo['access_mobile'] : unserialize($roleinfo['access_mobile']);
            $admininfo['access_export'] = $roleinfo['access'] == 'all' ? 1 : $roleinfo['access_export'];
            $admininfo['access_delete'] = $roleinfo['access'] == 'all' ? 1 : $roleinfo['access_delete'];
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
                'access' => $admininfo['access_mobile'],
                'access_export' => $admininfo['access_export'],
                'access_delete' => $admininfo['access_delete']
            ]);
        }else{
            $this->ajaxReturn(200, '获取openid失败');
        }
    }
}
