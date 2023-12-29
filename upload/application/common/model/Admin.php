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
        /**
         * 【ID1000728】
         * 【优化】修改密码后清空状态需重新登录
         * cy 2023-10-20
         */
        model('AdminIdentityToken')->makeToken($admininfo['id'], $admin_token);

        model('AdminLog')->writeLog(
            '登录成功',
            $admininfo,
            1,
            1
        );

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


    /**
     * @Purpose: 设置管理员销售客户总数上限
     * @Method setCustomerExceed()
     *
     * @param integer $adminId 管理员ID
     *
     * @return void
     *
     * @throws \think\Exception
     *
     * @author  Mr.yx
     * @version 1.1
     * @since   2023年4月6日10:56:30
     */
    public function setCustomerExceed($adminId) {
        $customer_total_now = model('Company')
            ->where([
                'admin_id' => $adminId
            ])
            ->count();
        $customer_total_limit = model('b2bcrm.CrmSysConfig')->getConfigByKey('customer_total_limit');
        if ($customer_total_now >= $customer_total_limit) {
            $this->save(
                ['customer_exceed' => 1],
                ['id' => $adminId]
            );
        } else {
            $this->save(
                ['customer_exceed' => 0],
                ['id' => $adminId]
            );
        }
    }
}
