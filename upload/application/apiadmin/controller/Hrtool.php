<?php

namespace app\apiadmin\controller;

use think\Db;

class Hrtool extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $cid = input('get.cid/d', 0, 'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['filename'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['id'] = ['eq', $keyword];
                    break;
                default:
                    break;
            }
        }
        if ($cid) {
            $where['cid'] = ['eq', $cid];
        }

        $total = model('Hrtool')
            ->where($where)
            ->count();
        $list = model('Hrtool')
            ->where($where)
            ->order('sort_id desc,id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();

        $category_arr = model('HrtoolCategory')->getCache();
        foreach ($list as $key => $value) {
            $value['cname'] = isset($category_arr[$value['cid']])
                ? $category_arr[$value['cid']]
                : '';
            $list[$key] = $value;
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
            'filename' => input('post.filename/s', '', 'trim'),
            'cid' => input('post.cid/d', 0, 'intval'),
            'fileurl' => input('post.fileurl/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        $input_data['addtime'] = time();

        Db::startTrans();
        try {
            if (
                false ===
                model('Hrtool')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('Hrtool')->getError());
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                '添加HR工具箱文件，' . $input_data['filename'] . '(ID:' . model('Hrtool')->id . ')',
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
            $info = model('Hrtool')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'filename' => input('post.filename/s', '', 'trim'),
                'cid' => input('post.cid/d', 0, 'intval'),
                'fileurl' => input('post.fileurl/s', '', 'trim'),
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
                    model('Hrtool')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('Hrtool')->getError());
                }

                // 日志
                $log_result = model('AdminLog')->writeLog(
                    '修改HR工具箱文件，' . $input_data['filename'] . '(ID:' . $id . ')',
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
            $list = model('Hrtool')
                ->where('id', 'in', $id)
                ->column('id,filename');

            $log_field = '删除HR工具箱文件，';

            foreach ($list as $tid => $filename) {
                $log_field .= $filename . '(ID:' . $tid . ')；';
            }

            $del_result = model('Hrtool')->destroy($id);
            if (false === $del_result) {
                throw new \Exception(model('Hrtool')->getError());
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                rtrim($log_field, '；'),
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

    public function upload()
    {
        $file = input('file.file');
        if (!$file) {
            $this->ajaxReturn(500, '请选择文件');
        }
        $filemanager = new \app\common\lib\FileManager();
        $result = $filemanager->uploadReturnPath($file);
        if (false !== $result) {
            $this->ajaxReturn(200, '上传成功', $result);
        } else {
            $this->ajaxReturn(500, $filemanager->getError());
        }
    }
}
