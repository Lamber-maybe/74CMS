<?php
namespace app\apiadmin\controller;

class AdminRole extends \app\common\controller\Backend
{
    public function index()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $total = model('AdminRole')->count();
        $list = model('AdminRole')
            ->order('id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['user_total'] = model('Admin')
                ->where('role_id', $value['id'])
                ->count();
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function add()
    {
        $input_data = [
            'name' => input('post.name/s', '', 'trim'),
            'access' => input('post.access/a')
        ];
        $input_data['access'] = serialize($input_data['access']);
        $result = model('AdminRole')
            ->validate(true)
            ->allowField(true)
            ->save($input_data);
        if (false === $result) {
            $this->ajaxReturn(500, model('AdminRole')->getError());
        }
        model('AdminLog')->record(
            '添加管理员角色。角色ID【' .
                model('AdminRole')->id .
                '】;角色名称【' .
                $input_data['name'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('AdminRole')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            if ($info['access'] != 'all') {
                $info['access'] = unserialize($info['access']);
            }

            $this->ajaxReturn(200, '获取数据成功', $info);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'access' => input('post.access/a')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            $input_data['access'] = serialize($input_data['access']);
            $result = model('AdminRole')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id]);
            if (false === $result) {
                $this->ajaxReturn(500, model('AdminRole')->getError());
            }
            model('AdminLog')->record(
                '编辑管理员角色。角色ID【' .
                    $id .
                    '】;角色名称【' .
                    $input_data['name'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('AdminRole')
            ->where('id', $id)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info->delete();
        model('AdminLog')->record(
            '删除管理员角色。角色ID【' .
                $id .
                '】;角色名称【' .
                $info['name'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
