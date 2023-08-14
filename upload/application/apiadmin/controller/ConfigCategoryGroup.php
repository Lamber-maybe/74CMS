<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class ConfigCategoryGroup extends Backend
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

        try {
            // 开启事务
            Db::startTrans();

            $result = model('CategoryGroup')
                ->validate(true)
                ->allowField(true)
                ->save($data);
            if (false === $result) {
                throw new \Exception(model('CategoryGroup')->getError());
            }

            // 日志
            $log_field = '系统-分类配置-其他分类组，新增分类组，分类组名称:'
                . $data['name']
                . '；调用名称:'
                . $data['alias'];
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

            try {
                $info = model('CategoryGroup')->find($id);
                if (!$info) {
                    $this->ajaxReturn(500, '要修改的分类组不存在');
                }

                // 开启事务
                Db::startTrans();

                $result = model('CategoryGroup')
                    ->validate(true)
                    ->allowField(true)
                    ->save($data, ['id' => $id]);
                if (false === $result) {
                    throw new \Exception(model('CategoryGroup')->getError());
                }

                // 日志
                $log_field = '系统-分类配置-其他分类组，修改分类组，分类组名称:' . $info['name'];
                if ($data['name'] != $info['name']) {
                    $log_field .= '->' . $data['name'];
                }
                if ($data['alias'] != $info['alias']) {
                    $log_field .= '；调用名称:' . $info['alias'] . '->' . $data['alias'];
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
        $id = input('param.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        try {
            $info = model('CategoryGroup')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '要删除的分类组不存在');
            }

            // 开启事务
            Db::startTrans();

            $cat_del = model('Category')
                ->where('alias', $info['alias'])
                ->delete();
            if (false === $cat_del) {
                throw new \Exception(model('Category')->getError());
            }

            $group_del = model('CategoryGroup')->destroy($id);
            if (false === $group_del) {
                throw new \Exception(model('CategoryGroup')->getError());
            }

            // 日志
            $log_field = '系统-分类配置-其他分类组，删除分类组，分类组名称:'
                . $info['name']
                . '；调用名称:'
                . $info['alias'];
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
