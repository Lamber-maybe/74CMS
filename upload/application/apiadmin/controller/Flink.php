<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class Flink extends Backend
{
    public function index()
    {
        $where = [];
        $is_display = input('get.is_display/s', '', 'trim');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['name'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['id'] = ['eq', $keyword];
                    break;
                default:
                    break;
            }
        }
        if ($is_display != '') {
            $where['is_display'] = ['eq', intval($is_display)];
        }

        $total = model('Link')
            ->where($where)
            ->count();
        $list = model('Link')
            ->where($where)
            ->order('sort_id desc,id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $k => $v) {
            $list[$k]['display'] = !!$v['is_display'];
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
            'notes' => input('post.notes/s', '', 'trim'),
            'is_display' => input('post.is_display/d', 1, 'intval'),
            'link_url' => input('post.link_url/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];

        Db::startTrans();
        try {
            if (
                false ===
                model('Link')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('HrtoolCategory')->getError());
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                '添加友情链接，' . $input_data['name'] . '(ID:' . model('Link')->id . ')',
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
            $info = model('Link')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'notes' => input('post.notes/s', '', 'trim'),
                'is_display' => input('post.is_display/d', 1, 'intval'),
                'link_url' => input('post.link_url/s', '', 'trim'),
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
                    model('Link')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('Link')->getError());
                }

                // 日志
                $log_result = model('AdminLog')->writeLog(
                    '修改友情链接，' . $input_data['name'] . '(ID:' . $id . ')',
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
            $list = model('Link')
                ->where('id', 'in', $id)
                ->column('id,name');

            $log_field = '删除友情链接，';
            foreach ($list as $lid => $name) {
                $log_field .= $name . '(ID:' . $lid . ')；';
            }

            $del_result = model('Link')->destroy($id);
            if (false === $del_result) {
                throw new \Exception(model('Link')->getError());
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

    public function flinkModifyState()
    {
        $id = input('post.id/d', 0, 'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择要修改的友情链接');
        }

        $display = input('post.display/d', 0, 'intval');
        if (empty(model('BaseModel')->map_is_display[$display])) {
            $this->ajaxReturn(500, '要设置的显示状态错误');
        }
        $display_text = model('BaseModel')->map_is_display[$display];

        try {
            $link = model('Link')
                ->find($id);
            if (null === $link) {
                throw new \Exception('要修改的友情链接不存在');
            }

            Db::startTrans();

            $modify_result = model('Link')
                ->where('id', $id)
                ->setField('is_display', $display);
            if (false === $modify_result) {
                throw new \Exception(model('Link')->getError());
            }

            /**
             * 日志
             */
            $log_field = '修改友情链接，'
                . $link['name']
                . '(ID:'
                . $link['id']
                . ')'
                . '，显示状态:'
                . $display_text;
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                3
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            Db::commit();
            $this->ajaxReturn(200, '修改成功，显示状态修改为' . $display_text);

        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '修改失败', ['err_msg' => $e->getMessage()]);
        }
    }
}
