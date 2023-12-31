<?php

namespace app\common\model;

class AdminLog extends BaseModel
{
    /**
     * 操作类型
     * @var array
     */
    public $map_type = [
        1 => '常规',
        2 => '新增',
        3 => '修改',
        4 => '删除',
        5 => '业务',
        6 => '审核',
        7 => '锁定',
        8 => '导出',
        99 => '特殊'
    ];

    /**
     * @param $content string 日志内容
     * @param $admin_info object admin信息
     * @param $is_login integer 是否登录0|否；1|是
     * @param $type integer 操作类型[0:-;1:常规;2:新增;3:修改;4:删除;5:业务;6:审核;7:锁定;8:导出]
     * @return bool|float|int|string
     */
    public function record($content, $admin_info, $is_login = 0, $type = 1)
    {
        $data['admin_id'] = $admin_info->id;
        $data['admin_name'] = $admin_info->username;
        $data['content'] = $content;
        $data['is_login'] = $is_login;
        $data['addtime'] = time();
        $data['ip'] = get_client_ip();
        $data['ip_addr'] = get_client_ipaddress($data['ip']);
        $data['ip'] = $data['ip'] . ':' . get_client_port();
        $data['type'] = $type;
        return $this->save($data);
    }

    /**
     * @param $content string|array 日志内容
     * @param $admin_info object admin信息
     * @param $is_login integer 是否登录0|否；1|是
     * @param $type integer 操作类型[0:-;1:常规;2:新增;3:修改;4:删除;5:业务;6:审核;7:锁定;]
     * @return bool|float|int|string
     */
    public function writeLog($content, $admin_info, $is_login = 0, $type = 1)
    {
        $ip = get_client_ip();
        $ip_addr = get_client_ipaddress($ip);
        $remote_ip = $ip . ':' . get_client_port();

        if (is_array($content)) {
            $log_all = [];
            foreach ($content as $one) {
                $one = trim($one);
                $one = $this->mb_rtrim($one, '；');
                $one = $this->mb_rtrim($one, '，');
                $log_all[] = [
                    'admin_id' => $admin_info->id,
                    'admin_name' => $admin_info->username,
                    'content' => $one,
                    'is_login' => $is_login,
                    'addtime' => time(),
                    'ip' => $remote_ip,
                    'ip_addr' => $ip_addr,
                    'type' => $type,
                ];
            }
            return $this->saveAll($log_all);

        } else {
            $content = trim($content);
            $content = $this->mb_rtrim($content, '；');
            $content = $this->mb_rtrim($content, '，');
            $data['admin_id'] = $admin_info->id;
            $data['admin_name'] = $admin_info->username;
            $data['content'] = $content;
            $data['is_login'] = $is_login;
            $data['addtime'] = time();
            $data['ip'] = $remote_ip;
            $data['ip_addr'] = $ip_addr;
            $data['type'] = $type;
            return $this->save($data);
        }
    }


    /**
     * @param $content string 日志内容
     * @param $admin_info object admin信息
     * @param $is_login integer 是否登录0|否；1|是
     * @param $type integer 操作类型[0:-;1:常规;2:新增;3:修改;4:删除;5:业务;6:审核;7:锁定;8:导出]
     * @return bool|float|int|string
     */
    public function checkLog($content, $admin_info, $is_login = 0, $type = 99)
    {
        $data['admin_id'] = $admin_info->id;
        $data['admin_name'] = $admin_info->username;
        $data['content'] = $content;
        $data['is_login'] = $is_login;
        $data['addtime'] = time();
        $data['ip'] = get_client_ip();
        $data['ip_addr'] = get_client_ipaddress($data['ip']);
        $data['ip'] = $data['ip'] . ':' . get_client_port();
        $data['type'] = $type;
        return $this->save($data);
    }

    private function mb_ltrim($str, $char)
    {
        if (empty($str)) return '';
        while (mb_substr($str, 0, 1) == $char) {
            $str = mb_substr($str, 1);
        }
        return $str;
    }

    private function mb_rtrim($str, $char)
    {
        if (empty($str)) return '';
        while (mb_substr($str, -1, 1) == $char) {
            $str = mb_substr($str, 0, -1);
        }
        return $str;
    }

    private function mb_trim($str, $char)
    {
        return mb_rtrim(mb_ltrim($str, $char), $char);
    }

}
