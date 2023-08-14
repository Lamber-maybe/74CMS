<?php
namespace app\common\model;

class AdminLog extends \app\common\model\BaseModel
{
    public function record($content, $admin_info, $is_login = 0)
    {
        $data['admin_id'] = $admin_info->id;
        $data['admin_name'] = $admin_info->username;
        $data['content'] = $content;
        $data['is_login'] = $is_login;
        $data['addtime'] = time();
        $data['ip'] = get_client_ip();
        $data['ip_addr'] = get_client_ipaddress($data['ip']);
        $data['ip'] = $data['ip'] . ':' . get_client_port();
        $this->save($data);
    }
}
