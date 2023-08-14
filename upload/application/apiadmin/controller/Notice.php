<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class Notice extends Backend
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
                    $where['title'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['id'] = ['eq', intval($keyword)];
                    break;
                default:
                    break;
            }
        }
        if ($is_display != '') {
            $where['is_display'] = ['eq', intval($is_display)];
        }

        $total = model('Notice')
            ->where($where)
            ->count();
        $list = model('Notice')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            if ($value['link_url'] == '') {
                $value['link'] = url('index/notice/show', [
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
            'content' => input('post.content/s', '', 'trim'),
            'attach' => input('post.attach/a', []),
            'is_display' => input('post.is_display/d', 1, 'intval'),
            'link_url' => input('post.link_url/s', '', 'trim'),
            'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
            'seo_description' => input('post.seo_description/s', '', 'trim'),
            'addtime' => input('post.addtime/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
            'click' => input('post.click/d', 0, 'intval'),
            'source' => input('post.source/d', 0, 'intval'),
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
                model('Notice')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('Notice')->getError());
            }

            /**
             * 日志
             */
            $log_result = model('AdminLog')->writeLog(
                '添加公告，标题:' . $input_data['title'] . '(ID:' . model('Notice')->id . ')',
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
            $info = model('Notice')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info['content'] = htmlspecialchars_decode($info['content'], ENT_QUOTES);
            $info['attach'] = json_decode($info['attach'], true);
            // $info = $info->toArray();
            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'content' => input('post.content/s', '', 'trim'),
                'attach' => input('post.attach/a', []),
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
                'click' => input('post.click/d', 0, 'intval'),
                'source' => input('post.source/d', 0, 'intval'),
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
                    model('Notice')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('Notice')->getError());
                }

                /**
                 * 日志
                 */
                $log_result = model('AdminLog')->writeLog(
                    '编辑公告，标题:' . $input_data['title'] . '(ID:' . $id . ')',
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

            $list = model('Notice')
                ->whereIn('id', $id)
                ->column('id,title,is_display');

            if (false === model('Notice')->destroy($id)) {
                throw new \Exception(model('Notice')->getError());
            }

            /**
             * 日志
             */
            $log_field = '删除公告，';
            foreach ($list as $notice) {
                $log_field .= $notice['title'] . '(ID:' . $notice['id'] . ')；';
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

    public function noticeModifyState()
    {
        $id = input('post.id/a', [], 'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择要修改的公告');
        }

        $display = input('post.display/d', 0, 'intval');
        if (empty(model('BaseModel')->map_is_display[$display])) {
            $this->ajaxReturn(500, '要设置的显示状态错误');
        }
        $display_text = model('BaseModel')->map_is_display[$display];

        try {
            $notice = model('Notice')
                ->whereIn('id', $id)
                ->select();
            if (null === $notice) {
                throw new \Exception('没有要修改的公告');
            }

            Db::startTrans();

            $modify_result = model('Notice')
                ->whereIn('id', $id)
                ->setField('is_display', $display);
            if (false === $modify_result) {
                throw new \Exception(model('Notice')->getError());
            }

            /**
             * 日志
             */
            $l_list = [];
            foreach ($notice as $one) {
                $l_list[] = $one['title'] . '(ID:' . $one['id'] . ')';
            }
            $log_field = '编辑公告，标题:'
                . implode('；', $l_list)
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
