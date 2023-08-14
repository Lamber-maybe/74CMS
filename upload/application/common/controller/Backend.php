<?php

namespace app\common\controller;

class Backend extends Base
{
    protected $admininfo;

    public function _initialize()
    {
        parent::_initialize();
        $header_info = \think\Request::instance()->header();
        $white_list = ['login-index',
            'login-config',
            'login-captcha',
            'login-weixin',
            'login-scan',
            // UEditor
            'ueditor.controller-index',
            // im
            'im.unreadremind-timingpush' // 未读消息提醒，推送接收
        ];
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

            # 50003后台锁定管理员
            if (isset($adminInfo['status']) && $adminInfo['status'] != 1) {
                $this->ajaxReturn(50003, '当前管理员账号已被锁定，请联系超级管理员');
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

    protected function checkDeleteAccess()
    {
        if (in_array($this->action_name, ['del', 'delete']) && $this->admininfo->access_delete == 0) {
            $this->ajaxReturn(500, '当前管理员没有删除数据权限');
        }
    }

    protected function checkExportAccess()
    {
        if ($this->admininfo->access_export == 0) {
            $this->ajaxReturn(500, '当前管理员没有导出数据权限');
        }
    }

    protected function checkSetServiceAccess()
    {
        if ($this->admininfo->access_set_service == 0) {
            $this->ajaxReturn(500, '当前管理员没有分配客服权限');
        }
    }

    protected function checkIsAdministrator()
    {
        if ($this->admininfo->role_id != 1) {
            $this->ajaxReturn(500, '当前管理员非超级管理员');
        }
    }
}
