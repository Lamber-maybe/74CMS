<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class Article extends Backend
{
    public function index()
    {
        $where = [];
        $is_display = input('get.is_display/s', '', 'trim');
        $cid = input('get.cid/d', 0, 'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['title'] = ['like', '%' . $keyword . '%'];
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
        if ($cid) {
            $where['cid'] = ['eq', $cid];
        }

        $total = model('Article')
            ->where($where)
            ->count();
        $list = model('Article')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $image_id_arr = $image_list = [];
        foreach ($list as $key => $value) {
            $value['thumb'] && ($image_id_arr[] = $value['thumb']);
        }
        if (!empty($image_id_arr)) {
            $image_list = model('Uploadfile')->getFileUrlBatch($image_id_arr);
        }
        $category_arr = model('ArticleCategory')->getCache();
        foreach ($list as $key => $value) {
            $value['thumb'] = isset($image_list[$value['thumb']])
                ? $image_list[$value['thumb']]
                : '';
            $value['cname'] = isset($category_arr[$value['cid']])
                ? $category_arr[$value['cid']]
                : '';
            if ($value['link_url'] == '') {
                $value['link'] = url('index/article/show', [
                    'id' => $value['id']
                ]);
            } else {
                $value['link'] = $value['link_url'];
            }
            $value['display'] = $value['is_display'] === 1 ? true : false;
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
            'title' => input('post.title/s', '', 'trim'),
            'cid' => input('post.cid/d', 0, 'intval'),
            'content' => input('post.content/s', '', 'trim'),
            'attach' => input('post.attach/a', []),
            'thumb' => input('post.thumb/d', 0, 'intval'),
            'is_display' => input('post.is_display/d', 1, 'intval'),
            'link_url' => input('post.link_url/s', '', 'trim'),
            'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
            'seo_description' => input('post.seo_description/s', '', 'trim'),
            'addtime' => input('post.addtime/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
            'source' => input('post.source/d', 0, 'intval'),
            'click' => input('post.click/d', 0, 'intval'),
            'source_reprint' => input('post.source_reprint/s', '', 'trim')
        ];
        if ($input_data['addtime']) {
            $input_data['addtime'] = strtotime($input_data['addtime']);
        } else {
            $input_data['addtime'] = time();
        }
        $input_data['attach'] = json_encode($input_data['attach'], JSON_UNESCAPED_UNICODE);

        try {
            Db::startTrans();

            if (
                false ===
                model('Article')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('Article')->getError());
            }

            /**
             * 日志
             */
            $category = model('ArticleCategory')->where('id', $input_data['cid'])->value('name');
            $log_result = model('AdminLog')->writeLog(
                '发布资讯信息，' . $input_data['title'] . '(ID:' . model('Article')->id . ')，分类:' . $category,
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
            $info = model('Article')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info['content'] = htmlspecialchars_decode($info['content'], ENT_QUOTES);
            $info['attach'] = json_decode($info['attach'], true);
            /**
             * 防止第一次编辑时上传的附件保存不成功
             * attach数据库中存的空时要把格式改为数组
             * cy 2023-10-12
             */
            if (empty($info['attach'])) {
                $info['attach'] = [];
            }
            $imageUrl = model('Uploadfile')->getFileUrl($info['thumb']);
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'imageUrl' => $imageUrl
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'cid' => input('post.cid/d', 0, 'intval'),
                'content' => input('post.content/s', '', 'trim'),
                'attach' => input('post.attach/a', []),
                'thumb' => input('post.thumb/d', 0, 'intval'),
                'is_display' => input('post.is_display/d', 1, 'intval'),
                'link_url' => input('post.link_url/s', '', 'trim'),
                'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
                'seo_description' => input(
                    'post.seo_description/s',
                    '',
                    'trim'
                ),
                'addtime' => input('post.addtime/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval'),
                'source' => input('post.source/d', 0, 'intval'),
                'click' => input('post.click/d', 0, 'intval'),
                'source_reprint' => input('post.source_reprint/s', '', 'trim')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if ($input_data['addtime']) {
                $input_data['addtime'] = strtotime($input_data['addtime']);
            } else {
                $input_data['addtime'] = time();
            }
            $input_data['attach'] = json_encode($input_data['attach'], JSON_UNESCAPED_UNICODE);

            try {
                Db::startTrans();

                if (
                    false ===
                    model('Article')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('Article')->getError());
                }

                /**
                 * 日志
                 */
                $category = model('ArticleCategory')->where('id', $input_data['cid'])->value('name');
                $log_result = model('AdminLog')->writeLog(
                    '修改资讯信息，' . $input_data['title'] . '(ID:' . $id . ')，分类:' . $category,
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

            $list = model('Article')
                ->where('id', 'in', $id)
                ->column('id,cid,title');

            if (false === model('Article')->destroy($id)) {
                throw new \Exception(model('Article')->getError());
            }

            /**
             * 日志
             */
            $log_field = '删除资讯信息，';
            foreach ($list as $article) {
                $category = model('ArticleCategory')->where('id', $article['cid'])->value('name');
                $log_field .= $article['title'] . '(ID:' . $article['id'] . ')，分类:' . $category . '；';
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

    public function articleModifyState(){
        $id = input('post.id/a',[],'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择要修改的资讯');
        }

        $display = input('post.display/d', 0, 'intval');
        if (empty(model('BaseModel')->map_is_display[$display])){
            $this->ajaxReturn(500, '要设置的显示状态错误');
        }
        $display_text = model('BaseModel')->map_is_display[$display];

        try {
            $article = model('Article')
                ->whereIn('id', $id)
                ->select();
            if (null === $article) {
                throw new \Exception('要修改的资讯不存在');
            }

            Db::startTrans();

            $modify_result = model('Article')
                ->whereIn('id', $id)
                ->setField('is_display', $display);
            if (false === $modify_result) {
                throw new \Exception(model('Article')->getError());
            }

            /**
             * 日志
             */
            $l_list = [];
            foreach ($article as $one) {
                $l_list[] = $one['title'] . '(ID:' . $one['id'] . ')';
            }
            $log_field = '修改资讯信息，标题:'
                . implode('；', $l_list)
                .'，显示状态:'
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
            $this->ajaxReturn(200,'修改成功，显示状态修改为'.$display_text);

        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, '修改失败', ['err_msg' => $e->getMessage()]);
        }
    }
}
