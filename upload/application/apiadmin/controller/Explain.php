<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class Explain extends Backend
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
                    $where['id'] = ['eq', $keyword];
                    break;
                default:
                    break;
            }
        }
        if ($is_display != '') {
            $where['is_display'] = ['eq', intval($is_display)];
        }

        $total = model('Explain')
            ->where($where)
            ->count();
        $list = model('Explain')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();

        foreach ($list as $key => $value) {
            if ($value['link_url'] == '') {
                $value['link'] = url('index/explain/show', [
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
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        $input_data['attach'] = json_encode($input_data['attach'], JSON_UNESCAPED_UNICODE);

        Db::startTrans();
        try {
            if (
                false ===
                model('Explain')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                $this->ajaxReturn(500, model('Explain')->getError());
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                '添加说明页文章，' . $input_data['title'] . '(ID:' . model('Explain')->id . ')',
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
            $info = model('Explain')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info['content'] = htmlspecialchars_decode($info['content'], ENT_QUOTES);
            $info['attach'] = json_decode($info['attach'], true);
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
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $input_data['attach'] = json_encode($input_data['attach'], JSON_UNESCAPED_UNICODE);
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            Db::startTrans();
            try {
                if (
                    false ===
                    model('Explain')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('Explain')->getError());
                }

                // 日志
                $log_result = model('AdminLog')->writeLog(
                    '修改说明页文章，' . $input_data['title'] . '(ID:' . $id . ')',
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
            $list = model('Explain')
                ->where('id', 'in', $id)
                ->column('id,title');

            $del_result = model('Explain')->destroy($id);
            if (false === $del_result) {
                throw new \Exception(model('Explain')->getError());
            }

            $log_field = '删除说明页文章，';

            foreach ($list as $eid => $title) {
                $log_field .= $title . '(ID:' . $eid . ')；';
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

    public function explainModifyState()
    {
        $id = input('post.id/a', [], 'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择要修改的说明页文章');
        }

        $display = input('post.display/d', 0, 'intval');
        if (empty(model('BaseModel')->map_is_display[$display])) {
            $this->ajaxReturn(500, '要设置的显示状态错误');
        }
        $display_text = model('BaseModel')->map_is_display[$display];

        try {
            $explain = model('Explain')
                ->whereIn('id', $id)
                ->select();
            if (null === $explain) {
                throw new \Exception('没有要修改的说明页文章');
            }

            Db::startTrans();

            $modify_result = model('Explain')
                ->whereIn('id', $id)
                ->setField('is_display', $display);
            if (false === $modify_result) {
                throw new \Exception(model('Explain')->getError());
            }

            /**
             * 日志
             */
            $l_list = [];
            foreach ($explain as $one) {
                $l_list[] = $one['title'] . '(ID:' . $one['id'] . ')';
            }
            $log_field = '修改说明页文章，标题:'
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
