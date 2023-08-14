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
            'access' => input('post.access/a'),
            'access_mobile' => input('post.access_mobile/a'),
            'access_export' => input('post.access_export/d',0,'intval'),
            'access_delete' => input('post.access_delete/d',0,'intval'),
            'access_set_service' => input('post.access_set_service/d',0,'intval')
        ];
        $input_data['access'] = serialize($input_data['access']);
        $input_data['access_mobile'] = serialize($input_data['access_mobile']);
        $result = model('AdminRole')
            ->validate(true)
            ->allowField(true)
            ->save($input_data);
        if (false === $result) {
            $this->ajaxReturn(500, model('AdminRole')->getError());
        }

        model('AdminLog')->writeLog(
            '系统-系统管理员-角色权限管理，添加角色，角色名称:' . $input_data['name'],
            $this->admininfo,
            0,
            2
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
            if ($info['access_mobile'] != 'all') {
                $info['access_mobile'] = unserialize($info['access_mobile']);
            }
            $info['access_export'] = $info['access_export']==1?true:false;
            $info['access_delete'] = $info['access_delete']==1?true:false;
            $info['access_set_service'] = $info['access_set_service']==1?true:false;

            $this->ajaxReturn(200, '获取数据成功', $info);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'access' => input('post.access/a'),
                'access_mobile' => input('post.access_mobile/a'),
                'access_export' => input('post.access_export/d',0,'intval'),
                'access_delete' => input('post.access_delete/d',0,'intval'),
                'access_set_service' => input('post.access_set_service/d',0,'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            $info = model('AdminRole')
                ->where('id', $id)
                ->find();
            if (null === $info) {
                $this->ajaxReturn(500, '要编辑的角色信息异常');
            }
            $input_data['access'] = serialize($input_data['access']);
            $input_data['access_mobile'] = serialize($input_data['access_mobile']);
            $result = model('AdminRole')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id]);
            if (false === $result) {
                $this->ajaxReturn(500, model('AdminRole')->getError());
            }

            if ($info['name'] != $input_data['name']) {
                $name_update = $info['name'] . '->' . $input_data['name'];
            } else {
                $name_update = $info['name'];
            }
            model('AdminLog')->writeLog(
                '系统-系统管理员-角色权限管理，修改角色，角色名称:' . $name_update,
                $this->admininfo,
                0,
                3
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

        model('AdminLog')->writeLog(
            '系统-系统管理员-角色权限管理，删除角色，角色名称:' . $info['name'],
            $this->admininfo,
            0,
            4
        );

        $this->ajaxReturn(200, '删除成功');
    }
}
