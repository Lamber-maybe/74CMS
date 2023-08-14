<?php
namespace app\apiadmin\controller;

class Task extends \app\common\controller\Backend
{
    public function index()
    {
        $utype = input('get.utype/d', 1, 'intval');
        $list = model('Task')
            ->field('alias,utype', true)
            ->where('utype', 'eq', $utype)
            ->order('id asc')
            ->select();
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }
    public function saveall()
    {
        $post_data = input('post.');
        $sql_arr = [];
        foreach ($post_data as $key => $value) {
            $arr['id'] = $value['id'];
            $arr['points'] = $value['points'];
            $arr['max_perday'] = $value['max_perday'];
            $sql_arr[] = $arr;
        }
        if (!empty($sql_arr)) {
            model('Task')->saveAll($sql_arr);
        }
        model('AdminLog')->record('批量保存任务配置', $this->admininfo);
        $this->ajaxReturn(200, '保存成功');
    }
}
