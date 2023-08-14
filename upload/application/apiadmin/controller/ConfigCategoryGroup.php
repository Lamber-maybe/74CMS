<?php
namespace app\apiadmin\controller;
use app\common\model\CategoryGroup;

class ConfigCategoryGroup extends \app\common\controller\Backend
{
    public function index()
    {
        $list = model('CategoryGroup')
            ->order('id asc')
            ->select();
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
    public function add()
    {
        $data = input('param.');
        if (!$data) {
            $this->ajaxReturn(500, '提交数据为空');
        }
        $result = model('CategoryGroup')
            ->validate(true)
            ->allowField(true)
            ->save($data);
        if (false === $result) {
            $this->ajaxReturn(500, model('CategoryGroup')->getError());
        }
        model('AdminLog')->record(
            '添加系统分类组。分类组ID【' .
                model('CategoryGroup')->id .
                '】;分类组名称【' .
                $data['name'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('CategoryGroup')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', $info);
        } else {
            $data = input('param.');
            if (!$data) {
                $this->ajaxReturn(500, '提交数据为空');
            }
            $id = intval($data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            $result = model('CategoryGroup')
                ->validate(true)
                ->allowField(true)
                ->save($data, ['id' => $id]);
            if (false === $result) {
                $this->ajaxReturn(500, model('CategoryGroup')->getError());
            }
            model('AdminLog')->record(
                '编辑系统分类组。分类组ID【' .
                    $id .
                    '】;分类组名称【' .
                    $data['name'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function delete()
    {
        $id = input('param.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('CategoryGroup')->find($id);
        model('Category')
            ->where('alias', $info['alias'])
            ->delete();
        model('CategoryGroup')->destroy($id);
        model('AdminLog')->record(
            '删除系统分类组。分类组ID【' .
                $id .
                '】;分类组名称【' .
                $info['name'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
