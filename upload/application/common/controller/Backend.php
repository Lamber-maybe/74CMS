<?php
namespace app\common\controller;

class Backend extends \app\common\controller\Base
{
    protected $admininfo;

    public function _initialize()
    {
        parent::_initialize();
        $header_info = \think\Request::instance()->header();
        $white_list = ['login-index', 'login-config', 'login-captcha','login-weixin','login-scan'];
        if (
            !in_array(
                $this->controller_name . '-' . $this->action_name,
                $white_list
            )
        ) {
            $admin_token = isset($header_info['admintoken'])
                ? $header_info['admintoken']
                : (input('param.admintoken/s')
                    ? input('param.admintoken/s')
                    : '');
            if (!$admin_token) {
                $this->ajaxReturn(50001, 'token为空');
            }
            $auth_result = $this->auth($admin_token);
            if ($auth_result['code'] != 200) {
                $this->ajaxReturn($auth_result['code'], $auth_result['info']);
            }

            // 获取当前用户信息 chenyang 2022年3月21日17:14:07
            $adminInfo = model('Admin')->where(['id' => $auth_result['info']->id])->find();
            if (!$adminInfo) {
                $this->ajaxReturn(50001, '没有找到用户信息');
            }
            // 获取当前角色下的所有权限
            $roleinfo = model('AdminRole')->find($adminInfo['role_id']);

            $adminInfo['access'] = $roleinfo['access'] == 'all' ? $roleinfo['access'] : unserialize($roleinfo['access']);
            $adminInfo['access_mobile'] = $roleinfo['access_mobile'] == 'all' ? $roleinfo['access_mobile'] : unserialize($roleinfo['access_mobile']);
            $adminInfo['access_export'] = $roleinfo['access'] == 'all' ? 1 : $roleinfo['access_export'];
            $adminInfo['access_delete'] = $roleinfo['access'] == 'all' ? 1 : $roleinfo['access_delete'];
            $adminInfo['access_set_service'] = $roleinfo['access'] == 'all' ? 1 : $roleinfo['access_set_service'];
            $adminInfo['rolename'] = $roleinfo['name'];

            $this->admininfo = $adminInfo;
        }
        \think\Config::set('platform', 'system');
        $this->checkDeleteAccess();
    }
    protected function checkDeleteAccess(){
        if(in_array($this->action_name,['del','delete']) && $this->admininfo->access_delete==0){
            $this->ajaxReturn(500, '当前管理员没有删除数据权限');
        }
    }
    protected function checkExportAccess(){
        if($this->admininfo->access_export==0){
            $this->ajaxReturn(500, '当前管理员没有导出数据权限');
        }
    }
    protected function checkSetServiceAccess(){
        if($this->admininfo->access_set_service==0){
            $this->ajaxReturn(500, '当前管理员没有分配客服权限');
        }
    }
}
