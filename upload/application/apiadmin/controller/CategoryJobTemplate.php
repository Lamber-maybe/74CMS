<?php
namespace app\apiadmin\controller;

class CategoryJobTemplate extends \app\common\controller\Backend
{
    public function index()
    {
        $pid = input('get.pid/d', 0, 'intval');
        $list = model('CategoryJobTemplate')
            ->where('pid', $pid)
            ->order('id asc')
            ->select();
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
    public function add()
    {
        $input_data = [
            'pid' => input('post.pid/d',0,'intval'),
            'title' => input('post.title/s', '', 'trim'),
            'content' => input('post.content/s', '', 'trim')
        ];
        $result = model('CategoryJobTemplate')
            ->validate(true)
            ->allowField(true)
            ->save($input_data);
        if (false === $result) {
            $this->ajaxReturn(500, model('CategoryJobTemplate')->getError());
        }
        model('AdminLog')->record(
            '添加职位分类模板。模板ID【' .
                model('CategoryJobTemplate')->id .
                '】;模板名称【' .
                $input_data['title'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'pid' => input('post.pid/d',0,'intval'),
            'title' => input('post.title/s', '', 'trim'),
            'content' => input('post.content/s', '', 'trim')
        ];
        $id = intval($input_data['id']);
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $result = model('CategoryJobTemplate')
            ->validate(true)
            ->allowField(true)
            ->save($input_data, ['id' => $id]);
        if (false === $result) {
            $this->ajaxReturn(500, model('CategoryJobTemplate')->getError());
        }
        model('AdminLog')->record(
            '编辑职位分类模板。模板ID【' .
                $id .
                '】;模板名称【' .
                $input_data['title'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('CategoryJobTemplate')->destroy($id);
        model('AdminLog')->record(
            '删除职位分类模板。模板ID【' . implode(',', $id) . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
