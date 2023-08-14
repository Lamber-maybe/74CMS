<?php
namespace app\apiadmin\controller;

class Nav extends \app\common\controller\Backend
{
    public function index()
    {
        $list = model('Navigation')->order('sort_id desc,id asc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['page_cn'] = $value['page']!=''?model('Navigation')->map_page[$value['page']]:'';
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
    public function add()
    {
        $input_data = [
            'title' => input('post.title/s', '', 'trim'),
            'link_type' => input('post.link_type/d', 1, 'intval'),
            'is_display' => input('post.is_display/d', 0, 'intval'),
            'page' => input('post.page/s', '', 'trim'),
            'url' => input('post.url/s', '', 'trim'),
            'target' => input('post.target/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        if (
            false ===
            model('Navigation')
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('Navigation')->getError());
        }
        model('AdminLog')->record(
            '添加导航。导航名称【' .
                $input_data['title'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('Navigation')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'link_type' => input('post.link_type/d', 1, 'intval'),
                'is_display' => input('post.is_display/d', 0, 'intval'),
                'page' => input('post.page/s', '', 'trim'),
                'url' => input('post.url/s', '', 'trim'),
                'target' => input('post.target/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if (
                false ===
                model('Navigation')
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('Navigation')->getError());
            }
            model('AdminLog')->record(
                '编辑导航。导航ID【' .
                    $id .
                    '】;导航名称【' .
                    $input_data['title'] .
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
        $list = model('Navigation')
            ->where('id', 'in', $id)
            ->column('title');
        model('Navigation')->destroy($id);
        model('AdminLog')->record(
            '删除导航。导航ID【' .
                implode(',', $id) .
                '】;导航名称【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
