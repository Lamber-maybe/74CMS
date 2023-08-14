<?php
namespace app\apiadmin\controller;
use app\common\model\Category;

class ConfigCategory extends \app\common\controller\Backend
{
    public function index()
    {
        $alias = input('get.alias/s', '', 'trim');
        $list = model('Category')
            ->where('alias', $alias)
            ->order('sort_id desc,id asc')
            ->select();
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
    public function add()
    {
        $input_data = [
            'name' => input('post.name/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
            'alias' => input('post.alias/s', '', 'trim')
        ];
        $result = model('Category')
            ->validate(true)
            ->allowField(true)
            ->save($input_data);
        if (false === $result) {
            $this->ajaxReturn(500, model('Category')->getError());
        }
        model('AdminLog')->record(
            '添加系统分类。分类ID【' .
                model('Category')->id .
                '】;分类名称【' .
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
            $info = model('Category')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', $info);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            $result = model('Category')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id]);
            if (false === $result) {
                $this->ajaxReturn(500, model('Category')->getError());
            }
            model('AdminLog')->record(
                '编辑系统分类。分类ID【' .
                    $id .
                    '】;分类名称【' .
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
        $list = model('Category')
            ->where('id', 'in', $id)
            ->column('name');
        model('Category')->destroy($id);
        model('AdminLog')->record(
            '删除系统分类。分类ID【' .
                implode(',', $id) .
                '】;分类名称【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function tablesave()
    {
        $inputdata = input('post.');
        if (!$inputdata) {
            $this->ajaxReturn(500, '提交数据为空');
        }
        $sqldata = [];
        foreach ($inputdata as $key => $value) {
            if (!$value['id']) {
                continue;
            }
            $arr['id'] = $value['id'];
            $arr['sort_id'] = $value['sort_id'] == '' ? 0 : $value['sort_id'];
            $arr['alias'] = $value['alias'];
            $arr['name'] = $value['name'];
            $sqldata[] = $arr;
        }
        $validate = \think\Loader::validate('Category');
        foreach ($sqldata as $key => $value) {
            if (!$validate->check($value)) {
                $this->ajaxReturn(500, $validate->getError());
            }
        }
        model('Category')
            ->isUpdate()
            ->saveAll($sqldata);
        model('AdminLog')->record('批量保存系统分类', $this->admininfo);
        $this->ajaxReturn(200, '保存成功');
    }
}
