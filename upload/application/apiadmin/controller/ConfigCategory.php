<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class ConfigCategory extends Backend
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

        try {
            $group_name = model('CategoryGroup')
                ->where('alias', $input_data['alias'])
                ->find();
            if (false === $group_name) {
                $this->ajaxReturn(500, '要添加的分类所属分类组不存在');
            }

            // 开启事务
            Db::startTrans();

            $result = model('Category')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            if (false === $result) {
                throw new \Exception(model('Category')->getError());
            }

            // 日志
            $log_field = '系统-分类配置-其他分类组，'
                . $group_name['name']
                . '，添加分类，分类名称:'
                . $input_data['name']
                . '；排序:'
                . $input_data['sort_id'];
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                2
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

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

            try {
                $info = model('Category')->find($id);
                if (!$info) {
                    $this->ajaxReturn(500, '要修改的分类不存在');
                }

                $group_name = model('CategoryGroup')
                    ->where('alias', $info['alias'])
                    ->find();
                if (false === $group_name) {
                    $this->ajaxReturn(500, '要修改的分类所属分类组不存在');
                }

                // 开启事务
                Db::startTrans();

                $result = model('Category')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
                if (false === $result) {
                    throw new \Exception(model('Category')->getError());
                }

                // 日志
                $log_field = '系统-分类配置-其他分类组，'
                    . $group_name['name']
                    . '，修改分类，分类组名称:'
                    . $info['name'];
                if ($input_data['name'] != $info['name']) {
                    $log_field .= '->' . $input_data['name'];
                }
                if ($input_data['sort_id'] != $info['sort_id']) {
                    $log_field .= '；排序:' . $info['sort_id'] . '->' . $input_data['sort_id'];
                }

                $log_result = model('AdminLog')->writeLog(
                    $log_field,
                    $this->admininfo,
                    0,
                    3
                );
                if (false === $log_result) {
                    throw new \Exception(model('AdminLog')->getError());
                }

                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollBack();
                $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
            }

            $this->ajaxReturn(200, '保存成功');
        }
    }

    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        try {
            $list = model('Category')
                ->whereIn('id', $id)
                ->column('id,name,alias,sort_id');

            $info = model('Category')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '要修改的分类不存在');
            }

            $log_all = [];
            foreach ($list as $cate) {
                $group_name = model('CategoryGroup')
                    ->where('alias', $cate['alias'])
                    ->find();
                if (false === $group_name) {
                    $this->ajaxReturn(500, '要修改的分类所属分类组不存在');
                }
                $log_all[] = $group_name['name'] . '，删除分类，分类名称:' . $cate['name'] . '；排序:' . $cate['sort_id'];
            }

            // 开启事务
            Db::startTrans();

            $result = model('Category')->destroy($id);
            if (false === $result) {
                throw new \Exception(model('Category')->getError());
            }

            // 日志
            $log_field = '系统-分类配置-其他分类组，' . implode($log_all, '；');
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }

    public function tablesave()
    {
        $inputdata = input('post.');
        if (!$inputdata) {
            $this->ajaxReturn(500, '提交数据为空');
        }

        try {
            $sqldata = [];
            $alias = '';
            foreach ($inputdata as $input_value) {
                if (!$input_value['id']) {
                    continue;
                }
                $arr['id'] = $input_value['id'];
                $arr['sort_id'] = $input_value['sort_id'] == '' ? 0 : $input_value['sort_id'];
                $arr['alias'] = $input_value['alias'];
                $arr['name'] = $input_value['name'];
                $sqldata[] = $arr;
                $alias = $arr['alias'];
            }
            $validate = \think\Loader::validate('Category');
            foreach ($sqldata as $sql_value) {
                if (!$validate->check($sql_value)) {
                    $this->ajaxReturn(500, $validate->getError());
                }
            }

            $group_name = model('CategoryGroup')
                ->where('alias', $alias)
                ->find();
            if (false === $group_name) {
                $this->ajaxReturn(500, '要批量保存的分类组不存在');
            }

            // 开启事务
            Db::startTrans();

            $result = model('Category')
                ->isUpdate()
                ->saveAll($sqldata);
            if (false === $result) {
                throw new \Exception(model('Category')->getError());
            }

            // 日志
            $log_field = '系统-分类配置-其他分类组，'
                . $group_name['name']
                . '，批量保存';
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                3
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '保存成功');
    }
}
