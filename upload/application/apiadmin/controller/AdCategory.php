<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class AdCategory extends Backend
{
    public function index()
    {
        $where = [];
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $total = model('AdCategory')
            ->where($where)
            ->count();
        $list = model('AdCategory')
            ->where($where)
            ->order('id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['platform'] = model('BaseModel')->map_ad_platform[$value['platform']];
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
            'alias' => input('post.alias/s', '', 'trim'),
            'ad_num' => input('post.ad_num/d', 0, 'intval'),
            'platform' => input('post.platform/s', '', 'trim'),
            'height' => input('post.height/d', 0, 'intval'),
            'width' => input('post.width/d', 0, 'intval'),
        ];

        Db::startTrans();
        try {
            if (
                false ===
                model('AdCategory')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('AdCategory')->getError());
            }

            $log_field = '添加广告位分类，所属平台:'
                . model('BaseModel')->map_ad_platform[$input_data['platform']]
                . '；广告位名称:'
                . $input_data['name']
                . '；调用名称:'
                . $input_data['alias']
                . '；数量:'
                . $input_data['ad_num']
                . '；宽度:'
                . $input_data['width']
                . '；高度:'
                . $input_data['height'];
            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                1
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
            $info = model('AdCategory')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'alias' => input('post.alias/s', '', 'trim'),
                'ad_num' => input('post.ad_num/d', 0, 'intval'),
                'platform' => input('post.platform/s', '', 'trim'),
                'height' => input('post.height/d', 0, 'intval'),
                'width' => input('post.width/d', 0, 'intval'),
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            $info = model('AdCategory')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '要修改的广告位不存在');
            }

            Db::startTrans();
            try {
                if (
                    false ===
                    model('AdCategory')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('AdCategory')->getError());
                }

                $log_field = '修改广告位分类，广告位名称:{' . $input_data['name'] . '}；';

                if ($input_data['name'] != $info['name']) {
                    $log_field .= '广告位名称:' . $info['name'] . '->' . $input_data['name'] . '；';
                }

                if ($input_data['platform'] != $info['platform']) {
                    $log_field .= '所属平台:' . model('BaseModel')->map_ad_platform[$info['platform']] . '->' . model('BaseModel')->map_ad_platform[$input_data['platform']] . '；';
                }

                if ($input_data['alias'] != $info['alias']) {
                    $log_field .= '调用名称:' . $info['alias'] . '->' . $input_data['alias'] . '；';
                }

                if ($input_data['ad_num'] != $info['ad_num']) {
                    $log_field .= '数量:' . $info['ad_num'] . '->' . $input_data['ad_num'] . '；';
                }

                if ($input_data['height'] != $info['height']) {
                    $log_field .= '高度:' . $info['height'] . '->' . $input_data['height'] . '；';
                }

                if ($input_data['width'] != $info['width']) {
                    $log_field .= '宽度:' . $info['width'] . '->' . $input_data['width'] . '；';
                }

                // 日志
                $log_result = model('AdminLog')->writeLog(
                    rtrim($log_field, '；'),
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
            $list = model('AdCategory')
                ->where('id', 'in', $id)
                ->column('id,name,platform');

            $log_field = '删除广告位分类，';

            foreach ($list as $ad) {
                $log_field .= '所属平台:' . model('BaseModel')->map_ad_platform[$ad['platform']] . '；广告位名称:' . $ad['name'] . '；';
            }

            $destory_result = model('AdCategory')->destroy($id);
            if (false === $destory_result) {
                throw new \Exception(model('AdCategory')->getError());
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
}
