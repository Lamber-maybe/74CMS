<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;

class CrmClueRelease extends Backend
{
    public function index()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $uid = input('get.uid/d', 0, 'intval');

        if ($uid == 0)
        {
            $this->ajaxReturn(500, '请选择客户');
        }
        $where = [
            'a.uid' => $uid,
            'a.operator' => 2,
            'a.utype' => 1
        ];
        $field = 'a.create_time,a.operation_type,b.username';

        $data = model('b2bcrm.CrmClueRelease')
            ->getList($where, ['a.create_time DESC'], $current_page, $pagesize, $field);
        if (empty($data))
        {
            $data = [];
        }
        $this->ajaxReturn(200, '获取数据成功', $data);
    }
}