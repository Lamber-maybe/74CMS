<?php
namespace app\common\model;

class Admin extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'addtime'];
    protected $type     = [
        'id'        => 'integer',
        'tid'        => 'integer',
        'is_display' => 'integer',
        'click'      => 'integer',
        'addtime'    => 'integer',
        'sort_id'    => 'integer',
    ];
    protected $insert = ['addtime','last_login_time'=>0,'last_login_ip'=>'','last_login_ipaddress'=>''];
    protected function setAddtimeAttr()
    {
        return time();
    }
    public function makePassword($password, $randstr) {
        return md5(md5($password).$randstr.config('sys.safecode'));
    }
    public function setLogin($admininfo){
        $login_update_info['last_login_time'] = time();
        $login_update_info['last_login_ip'] = get_client_ip();
        $login_update_info['last_login_ipaddress'] = get_client_ipaddress(
            $login_update_info['last_login_ip']
        );
        $login_update_info['last_login_ip'] =
            $login_update_info['last_login_ip'] . ':' . get_client_port();
        $this->where('id', $admininfo['id'])->update($login_update_info);

        $roleinfo = model('AdminRole')->find($admininfo['role_id']);
        $admininfo['access'] = $roleinfo['access'] == 'all' ? $roleinfo['access'] : unserialize($roleinfo['access']);
        $admininfo['access_mobile'] = $roleinfo['access_mobile'] == 'all' ? $roleinfo['access_mobile'] : unserialize($roleinfo['access_mobile']);
        $admininfo['access_export'] = $roleinfo['access'] == 'all' ? 1 : $roleinfo['access_export'];
        $admininfo['access_delete'] = $roleinfo['access'] == 'all' ? 1 : $roleinfo['access_delete'];
        $admininfo['access_set_service'] = $roleinfo['access'] == 'all' ? 1 : $roleinfo['access_set_service'];
        $admininfo['rolename'] = $roleinfo['name'];
        $JwtAuth = \app\common\lib\JwtAuth::mkToken(
            config('sys.safecode'),
            7776000, //90天有效期
            ['info' => $admininfo]
        );
        $admin_token = $JwtAuth->getString();
        return [
            'token'=>$admin_token,
            'access' => $admininfo['access'],
            'access_export' => $admininfo['access_export'],
            'access_delete' => $admininfo['access_delete'],
            'access_set_service' => $admininfo['access_set_service']
        ];
    }
}
