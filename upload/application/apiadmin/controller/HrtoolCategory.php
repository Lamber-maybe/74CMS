<?php

namespace app\apiadmin\controller;

use think\Db;

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

        Db::startTrans();
        try {
            if (
                false ===
                model('HrtoolCategory')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('HrtoolCategory')->getError());
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                '添加HR工具箱分类，' . $input_data['name'] . '(ID:' . model('HrtoolCategory')->id . ')',
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

            Db::startTrans();
            try {
                if (
                    false ===
                    model('HrtoolCategory')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('HrtoolCategory')->getError());
                }

                // 日志
                $log_result = model('AdminLog')->writeLog(
                    '修改HR工具箱分类，' . $input_data['name'] . '(ID:' . $id . ')',
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

        Db::startTrans();
        try {
            $list = model('HrtoolCategory')
                ->where('id', 'in', $id)
                ->column('id,name');

            $log_field = '删除HR工具箱分类，';
            foreach ($list as $cid => $name) {
                $log_field .= $name . '(ID:' . $cid . ')；';
            }

            $del_result = model('HrtoolCategory')->destroy($id);
            if (false === $del_result) {
                throw new \Exception(model('HrtoolCategory')->getError());
            }

            // 日志
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
}
