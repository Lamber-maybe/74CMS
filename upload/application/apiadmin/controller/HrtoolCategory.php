<?php
namespace app\apiadmin\controller;

class HrtoolCategory extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $total = model('HrtoolCategory')
            ->where($where)
            ->count();
        $list = model('HrtoolCategory')
            ->where($where)
            ->order('id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
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
            'describe' => input('post.describe/s', '', 'trim'),
            'is_sys' => 0,
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        if (
            false ===
            model('HrtoolCategory')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('HrtoolCategory')->getError());
        }
        model('AdminLog')->record(
            '添加hr工具箱分类。hr工具箱分类ID【' .
                model('HrtoolCategory')->id .
                '】;hr工具箱分类名称【' .
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
            $info = model('HrtoolCategory')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            // $info = $info->toArray();

            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'describe' => input('post.describe/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if (
                false ===
                model('HrtoolCategory')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('HrtoolCategory')->getError());
            }
            model('AdminLog')->record(
                '编辑hr工具箱分类。hr工具箱分类ID【' .
                    $id .
                    '】;hr工具箱分类名称【' .
                    $input_data['name'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $list = model('HrtoolCategory')
            ->where('id', 'in', $id)
            ->column('name');
        model('HrtoolCategory')->destroy($id);
        model('AdminLog')->record(
            '删除hr工具箱分类。hr工具箱分类ID【' .
                implode(',', $id) .
                '】;hr工具箱分类名称【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
