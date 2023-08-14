<?php
namespace app\common\controller;

class Backend extends \app\common\controller\Base
{
    protected $admininfo;

    public function _initialize()
    {
        parent::_initialize();
        $header_info = \think\Request::instance()->header();
        $white_list = ['login-index', 'login-config', 'login-captcha','login-weixin'];
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
            $this->admininfo = $auth_result['info'];
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
}
