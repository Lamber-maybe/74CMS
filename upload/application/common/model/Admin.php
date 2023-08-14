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
        $access = $roleinfo['access'] == 'all' ? $roleinfo['access'] : unserialize($roleinfo['access']);
        $access_mobile = $roleinfo['access_mobile'] == 'all' ? $roleinfo['access_mobile'] : unserialize($roleinfo['access_mobile']);
        $access_export = $roleinfo['access'] == 'all' ? 1 : $roleinfo['access_export'];
        $access_delete = $roleinfo['access'] == 'all' ? 1 : $roleinfo['access_delete'];
        $access_set_service = $roleinfo['access'] == 'all' ? 1 : $roleinfo['access_set_service'];
        $JwtAuth = \app\common\lib\JwtAuth::mkToken(
            config('sys.safecode'),
            7776000, //90天有效期
            // ['info' => $admininfo]
            [
                'info' => [
                    'id'      => $admininfo['id'],
                    'role_id' => $admininfo['role_id'],
                ]
            ]
        );
        $admin_token = $JwtAuth->getString();
        model('AdminLog')->record('登录成功',$admininfo,1);

        return [
            'token'=>$admin_token,
            'access' => $access,
            'access_export' => $access_export,
            'access_delete' => $access_delete,
            'access_set_service' => $access_set_service
        ];
    }

    /**
     * @Purpose:
     * 验证MD5加密后的密码
     * @Method checkMd5Password()
     *
     * @param string $md5Password Md5加密后的密码
     * @param string $randstr 密码盐
     *
     * @return string
     *
     * @throws null
     *
     * @link XXXXXXXXXX
     *
     * @author  Mr.yx
     * @version 1.1
     * @since   2022/4/27
     */
    public function makeMd5Password($md5Password, $randstr) {
        return md5($md5Password.$randstr.config('sys.safecode'));
    }
}
