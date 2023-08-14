<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class CategoryJobTemplate extends Backend
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
            'pid' => input('post.pid/d', 0, 'intval'),
            'title' => input('post.title/s', '', 'trim'),
            'content' => input('post.content/s', '', 'trim')
        ];

        try {
            $catName = model('CategoryJob')->where('id', $input_data['pid'])->value('name');
            if (empty($catName)) {
                $this->ajaxReturn(200, '模板对应职位分类不存在');
            }

            Db::startTrans();

            $result = model('CategoryJobTemplate')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            if (false === $result) {
                throw new \Exception(model('CategoryJobTemplate')->getError());
            }

            // 日志
            $log_field = '系统-分类配置-职位分类，分类名称:' . $catName
                . '，新增模板，模板名称:' . $input_data['title']
                . '；模板内容:' . $input_data['content'];
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                2
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '保存成功');
    }

    public function edit()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'pid' => input('post.pid/d', 0, 'intval'),
            'title' => input('post.title/s', '', 'trim'),
            'content' => input('post.content/s', '', 'trim')
        ];
        $id = intval($input_data['id']);
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        try {
            $info = model('CategoryJobTemplate')->find($id);
            if (null === $info) {
                $this->ajaxReturn(200, '要修改的职位分类模板不存在');
            }

            $catName = model('CategoryJob')->where('id', $input_data['pid'])->value('name');
            if (empty($catName)) {
                $this->ajaxReturn(200, '模板对应职位分类不存在');
            }

            Db::startTrans();

            $result = model('CategoryJobTemplate')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id]);
            if (false === $result) {
                throw new \Exception(model('CategoryJobTemplate')->getError());
            }

            // 日志
            $log_field = '系统-分类配置-职位分类，分类名称:' . $catName
                . '，修改模板，模板名称:' . $info['title'];
            if ($input_data['title'] != $info['title']) {
                $log_field .= '->' . $input_data['title'];
            }
            if ($input_data['content'] != $info['content']) {
                $log_field .= '；模板内容:' . $info['content'] . '->' . $input_data['content'];
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

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '保存成功');
    }

    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        try {
            $info = model('CategoryJobTemplate')->find($id);
            if (null === $info) {
                $this->ajaxReturn(200, '要删除的职位分类模板不存在');
            }

            $catName = model('CategoryJob')->where('id', $info['pid'])->value('name');
            if (empty($catName)) {
                $this->ajaxReturn(200, '模板对应职位分类不存在');
            }

            Db::startTrans();

            $result = model('CategoryJobTemplate')->destroy($id);
            if (false === $result) {
                throw new \Exception(model('CategoryJobTemplate')->getError());
            }

            // 日志
            $log_field = '系统-分类配置-职位分类，分类名称:' . $catName
                . '，删除模板，模板名称:' . $info['title']
                . '；模板内容:' . $info['content'];
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }
}
