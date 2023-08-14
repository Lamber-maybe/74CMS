<?php

namespace app\apiadmin\controller;

use think\Db;

class WechatKeyword extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $total = model('WechatKeyword')
            ->where($where)
            ->count();
        $list = model('WechatKeyword')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();

        /**
         *【ID1000595】
         * 【优化】公众号关键词回复链接
         * yx - 2023.03.20
         * [新增]:
         */
        foreach ($list as &$keyword) {
            $keyword['return_link'] = htmlspecialchars_decode($keyword['return_link']);
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
            'word' => input('post.word/s', '', 'trim'),
            'return_text' => input('post.return_text/s', '', 'trim'),
            'return_img' => input('post.return_img/s', '', 'trim'),
            'return_img_mediaid' => input('post.return_img_mediaid/s', '', 'trim'),
            'return_link' => input('post.return_link/s', '', 'trim')
        ];

        try {
            Db::startTrans();

            if (
                false ===
                model('WechatKeyword')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('WechatKeyword')->getError());
            }

            // 日志
            $log_field = '系统-微信平台配置，自定义关键词，添加关键词，关键词:' . $input_data['word']
                . '；返回文字:' . (!empty($input_data['return_text']) ? $input_data['return_text'] : '未填写')
                . '；返回链接:' . (!empty($input_data['return_link']) ? $input_data['return_link'] : '未填写');
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
            $info = model('WechatKeyword')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $returnImgUrl = model('Uploadfile')->getFileUrl($info['return_img']);
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'returnImgUrl' => $returnImgUrl
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'word' => input('post.word/s', '', 'trim'),
                'return_text' => input('post.return_text/s', '', 'trim'),
                'return_img' => input('post.return_img/s', '', 'trim'),
                'return_img_mediaid' => input('post.return_img_mediaid/s', '', 'trim'),
                'return_link' => input('post.return_link/s', '', 'trim')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            try {
                $info = model('WechatKeyword')->find($id);
                if (null === $info) {
                    $this->ajaxReturn(500, '要修改的自定义关键词不存在');
                }
                $info = $info->toArray();

                Db::startTrans();

                if (
                    false ===
                    model('WechatKeyword')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('WechatKeyword')->getError());
                }

                // 日志
                $log_field = '系统-微信平台配置，自定义关键词，修改关键词，关键词:' . $info['word'];
                if ($input_data['word'] != $info['word']) {
                    $log_field .= '->' . $input_data['word'];
                }
                if ($input_data['return_text'] != $info['return_text']) {
                    $log_field .= '；返回文字:'
                        . (!empty($info['return_text']) ? $info['return_text'] : '未填写')
                        . '->'
                        . (!empty($input_data['return_text']) ? $input_data['return_text'] : '未填写');
                }
                if ($input_data['return_link'] != $info['return_link']) {
                    $log_field .= '；返回链接:'
                        . (!empty($info['return_link']) ? $info['return_link'] : '未填写')
                        . '->'
                        . (!empty($input_data['return_link']) ? $input_data['return_link'] : '未填写');
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
    }

    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        try {
            $list = model('WechatKeyword')
                ->whereIn('id', $id)
                ->column('word');
            if (empty($list)) {
                $this->ajaxReturn(500, '没有要删除的自定义关键词');
            }

            Db::startTrans();

            if (
                false ===
                model('WechatKeyword')->destroy($id)
            ) {
                throw new \Exception(model('WechatKeyword')->getError());
            }

            // 日志
            $log_field = '系统-微信平台配置，自定义关键词，删除关键词，关键词:' . implode('；', $list);
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
