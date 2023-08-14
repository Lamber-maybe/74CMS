<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class Hotword extends Backend
{
    public function index()
    {
        $where = [];
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 20, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['word'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    break;
            }
        }
        $total = model('Hotword')->where($where)->count();
        $list = model('Hotword')
            ->where($where)
            ->order('hot desc')
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
        $data = input('param.');
        if (!$data) {
            $this->ajaxReturn(500, '提交数据为空');
        }

        try {
            Db::startTrans();

            $result = model('Hotword')
                ->validate(true)
                ->allowField(true)
                ->save($data);
            if (false === $result) {
                throw new \Exception(model('Hotword')->getError());
            }

            // 日志
            $log_field = '系统-基础配置-自定义数据配置，添加热门关键词，关键词:'
                . $data['word']
                . '；排序:' . $data['hot'];
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
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('Hotword')->find($id);
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
                $info = model('Hotword')->find($id);
                if (null === $info) {
                    $this->ajaxReturn(500, '要修改的热门关键词不存在');
                }

                Db::startTrans();

                $result = model('Hotword')
                    ->validate(true)
                    ->allowField(true)
                    ->save($data, ['id' => $id]);
                if (false === $result) {
                    throw new \Exception(model('Hotword')->getError());
                }

                // 日志
                $log_field = '系统-基础配置-自定义数据配置，修改热门关键词，关键词:' . $info['word'];

                if ($data['word'] != $info['word']) {
                    $log_field .= '->' . $data['word'];
                }

                if ($data['hot'] != $info['hot']) {
                    $log_field .= '；排序:' . $info['hot'] . '->' . $data['hot'];
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
                $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
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
            $info = model('Hotword')->find($id);
            if (null === $info) {
                $this->ajaxReturn(500, '要删除的热门关键词不存在');
            }

            Db::startTrans();

            $result = model('Hotword')->destroy($id);
            if (false === $result) {
                throw new \Exception(model('Hotword')->getError());
            }

            // 日志
            $log_field = '系统-基础配置-自定义数据配置，删除热门关键词，关键词:'
                . $info['word']
                . '；排序:' . $info['hot'];
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

    public function saveAll()
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
            $arr['hot'] = $value['hot'] == '' ? 0 : $value['hot'];
            $arr['word'] = $value['word'];
            $sqldata[] = $arr;
        }
        $validate = \think\Loader::validate('Hotword');
        foreach ($sqldata as $key => $value) {
            if (!$validate->check($value)) {
                $this->ajaxReturn(500, $validate->getError());
            }
        }

        try {
            Db::startTrans();

            $result = model('Hotword')
                ->isUpdate()
                ->saveAll($sqldata);
            if (false === $result) {
                throw new \Exception(model('Hotword')->getError());
            }

            // 日志
            $log_field = '系统-基础配置-自定义数据配置，批量保存热门关键词';
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
}
