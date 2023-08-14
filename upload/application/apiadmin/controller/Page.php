<?php

namespace app\apiadmin\controller;

use think\Db;

class Page extends \app\common\controller\Backend
{
    public function index()
    {
        $list = model('Page')
            ->order('id asc')
            ->select();
        $this->ajaxReturn(200, '获取数据成功', $list);
    }

    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('Page')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info = $info->toArray();
            $info['params'] = json_decode($info['params'], 1);
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'expire' => input('post.expire/d', 0, 'intval'),
                'seo_title' => input('post.seo_title/s', '', 'trim'),
                'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
                'seo_description' => input('post.seo_description/s', '', 'trim')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            try {
                $page_info = model('Page')->find($id);
                if (null === $page_info) {
                    $this->ajaxReturn(500, '要修改的页面不存在');
                }

                Db::startTrans();

                if (
                    false ===
                    model('Page')
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('Page')->getError());
                }

                // 日志
                $log_field = '修改系统-基础配置-页面管理，页面名称:' . $page_info['name'];
                if ($input_data['expire'] != $page_info['expire']) {
                    $log_field .= '；缓存时长:' . $page_info['expire'] . '->' . $input_data['expire'];
                }
                if ($input_data['seo_title'] != $page_info['seo_title']) {
                    $log_field .= '；SEO标题:' . $page_info['seo_title'] . '->' . $input_data['seo_title'];
                }
                if ($input_data['seo_keywords'] != $page_info['seo_keywords']) {
                    $log_field .= '；SEO关键词:' . $page_info['seo_keywords'] . '->' . $input_data['seo_keywords'];
                }
                if ($input_data['seo_description'] != $page_info['seo_description']) {
                    $log_field .= '；SEO描述:' . $page_info['seo_description'] . '->' . $input_data['seo_description'];
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
}
