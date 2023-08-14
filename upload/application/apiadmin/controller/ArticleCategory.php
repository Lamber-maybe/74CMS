<?php

namespace app\apiadmin\controller;

use think\Db;

class ArticleCategory extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $total = model('ArticleCategory')
            ->where($where)
            ->count();
        $list = model('ArticleCategory')
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
            'seo_title' => input('post.seo_title/s', '', 'trim'),
            'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
            'seo_description' => input('post.seo_description/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];

        try {
            Db::startTrans();

            if (
                false ===
                model('ArticleCategory')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('ArticleCategory')->getError());
            }


            $log_field = '添加资讯分类，分类名称:'
                . $input_data['name'] . '(ID:' . model('ArticleCategory')->id . ')'
                . '；排序' . $input_data['sort_id']
                . '；seo标题' . $input_data['seo_title']
                . '；seo关键词' . $input_data['seo_keywords']
                . '；seo描述' . $input_data['seo_description'];
            /**
             * 日志
             */
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                2
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '保存成功');
    }

    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('ArticleCategory')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            // $info = $info->toArray();

            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'seo_title' => input('post.seo_title/s', '', 'trim'),
                'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
                'seo_description' => input('post.seo_description/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            try {
                Db::startTrans();

                $info = model('ArticleCategory')->find($id);
                if (!$info) {
                    $this->ajaxReturn(500, '要修改的资讯分类不存在');
                }

                if (
                    false ===
                    model('ArticleCategory')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('ArticleCategory')->getError());
                }

                $log_field = '修改资讯分类，';
                if ($input_data['name'] != $info['name']) {
                    $log_field .= '分类名称:' . $info['name'] . '->' . $input_data['name'] . '(ID:' . model('ArticleCategory')->id . ')；';
                } else {
                    $log_field .= '分类名称:' . $info['name'] . '(ID:' . model('ArticleCategory')->id . ')；';
                }
                if ($input_data['sort_id'] != $info['sort_id']) {
                    $log_field .= '排序:' . $info['sort_id'] . '->' . $input_data['sort_id'] . '；';
                }
                if ($input_data['seo_title'] != $info['seo_title']) {
                    $log_field .= 'seo标题:' . $info['seo_title'] . '->' . $input_data['seo_title'] . '；';
                }
                if ($input_data['seo_keywords'] != $info['seo_keywords']) {
                    $log_field .= 'seo关键词:' . $info['seo_keywords'] . '->' . $input_data['seo_keywords'] . '；';
                }
                if ($input_data['seo_description'] != $info['seo_description']) {
                    $log_field .= 'seo描述:' . $info['seo_description'] . '->' . $input_data['seo_description'] . '；';
                }
                /**
                 * 日志
                 */
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
            } catch (\Exception $e) {
                Db::rollback();
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
            Db::startTrans();

            $list = model('ArticleCategory')
                ->where('id', 'in', $id)
                ->column('id,name');


            if (false === model('ArticleCategory')->destroy($id)) {
                throw new \Exception(model('ArticleCategory')->getError());
            }

            /**
             * 日志
             */
            $log_field = '删除资讯分类，';
            foreach ($list as $c_id => $c_name) {
                $log_field .= '分类名称:' . $c_name . '(ID:' . $c_id . ')；';
            }
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }
}
