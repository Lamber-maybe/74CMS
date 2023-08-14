<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class Ad extends Backend
{
    public function index()
    {
        $where = [];
        $platform = input('get.platform/s', '', 'trim');
        $settr = input('get.settr/s', '', 'trim');
        $is_display = input('get.is_display/s', '', 'trim');
        $cid = input('get.cid/d', 0, 'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['a.title'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['a.id'] = ['eq', intval($keyword)];
                    break;
                default:
                    break;
            }
        }
        if ($is_display != '') {
            $where['a.is_display'] = ['eq', intval($is_display)];
        }
        if ($platform != '') {
            $where['b.platform'] = ['eq', $platform];
        }
        if ($cid > 0) {
            $where['a.cid'] = ['eq', $cid];
        }
        if ($settr == '0') {
            $where['a.deadline'] = [['neq', 0], ['lt', time()]];
        } elseif ($settr > 0) {
            //后台广告位到期筛选，逻辑混乱，数据不对 tapd:201
            if ($settr == 1) {
                $where['a.deadline'] = [['neq', 0], ['lt', time()]];
            } else {
                $where['a.deadline'] = [
                    ['neq', 0],
                    ['elt', strtotime('+' . $settr . ' day')],
                    ['gt', time()]
                ];
            }
        }

        $total = model('Ad')->alias('a')->join(config('database.prefix') . 'ad_category b', 'a.cid=b.id', 'LEFT')
            ->where($where)
            ->count();
        $list = model('Ad')->alias('a')->field('a.*')->join(config('database.prefix') . 'ad_category b', 'a.cid=b.id', 'LEFT')
            ->where($where)
            ->order('a.id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $image_id_arr = $image_list = [];
        foreach ($list as $key => $value) {
            $value['imageid'] && ($image_id_arr[] = $value['imageid']);
        }
        if (!empty($image_id_arr)) {
            $image_list = model('Uploadfile')->getFileUrlBatch($image_id_arr);
        }
        $category_arr = model('AdCategory')->getCache();
        foreach ($list as $key => $value) {
            $value['imageurl'] = isset($image_list[$value['imageid']])
                ? $image_list[$value['imageid']]
                : $value['imageurl'];
            $value['cname'] = isset($category_arr[$value['cid']]['name'])
                ? $category_arr[$value['cid']]['name']
                : '';
            $value['platform'] =
                isset($category_arr[$value['cid']]['platform']) &&
                isset(
                    model('BaseModel')->map_ad_platform[$category_arr[$value['cid']]['platform']]
                )
                    ? model('BaseModel')->map_ad_platform[$category_arr[$value['cid']]['platform']]
                    : '';
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
            'cid' => input('post.cid/a', []),
            'imageid' => input('post.imageid/d', 0, 'intval'),
            'imageurl' => input('post.imageurl/s', '', 'trim'),
            'explain' => input('post.explain/s', '', 'trim'),
            'starttime' => input('post.starttime/s', '', 'trim'),
            'deadline' => input('post.deadline/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
            'target' => input('post.target/d', 0, 'intval'),
            'link_url' => input('post.link_url/s', '', 'trim'),
            'inner_link' => input('post.inner_link/s', '', 'trim'),
            'inner_link_params' => input(
                'post.inner_link_params/d',
                0,
                'intval'
            ),
            'company_id' => input('post.company_id/d', 0, 'intval'),
            'is_display' => input('post.is_display/d', 1, 'intval')
        ];
        if ($input_data['starttime']) {
            $input_data['starttime'] = strtotime($input_data['starttime']);
        }
        if ($input_data['deadline']) {
            $input_data['deadline'] = strtotime($input_data['deadline']);
        } else {
            $input_data['deadline'] = 0;
        }
        if ($input_data['target'] == 0) {
            $input_data['inner_link'] = '';
            $input_data['inner_link_params'] = 0;
            $input_data['company_id'] = 0;
        } elseif ($input_data['target'] == 1) {
            $input_data['link_url'] = '';
            $input_data['company_id'] = 0;
        } elseif ($input_data['target'] == 2) {
            $input_data['link_url'] = '';
            $input_data['inner_link'] = '';
            $input_data['inner_link_params'] = 0;
        }
        $cid_arr = $input_data['cid'];
        $input_data['cid'] = $cid_arr[1];

        try {
            Db::startTrans();

            if (
                false ===
                model('Ad')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('Ad')->getError());
            }

            /**
             * 日志
             */
            $category_name = model('AdCategory')
                ->where('id', $input_data['cid'])
                ->value('name');
            $log_field = '添加广告位，广告标题:'
                . $input_data['title']
                . '；广告位:' . model('BaseModel')->map_ad_platform[$cid_arr['0']] . '/' . $category_name
                . '；展示时间:' . date('Y-m-d H:i', $input_data['starttime']) . ' ~ ' . date('Y-m-d H:i', $input_data['deadline'])
                . '；显示状态:' . model('Ad')->map_is_display[$input_data['is_display']];

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
            $info = model('Ad')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info = $info->toArray();
            $ad_category = model('AdCategory')
                ->where('id', $info['cid'])
                ->find();
            $info['cid'] = [$ad_category['platform'], $info['cid']];
            $imageSrc = model('Uploadfile')->getFileUrl($info['imageid']);
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'imageSrc' => $imageSrc
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'cid' => input('post.cid/a', []),
                'imageid' => input('post.imageid/d', 0, 'intval'),
                'imageurl' => input('post.imageurl/s', '', 'trim'),
                'explain' => input('post.explain/s', '', 'trim'),
                'starttime' => input('post.starttime/s', '', 'trim'),
                'deadline' => input('post.deadline/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval'),
                'target' => input('post.target/d', 0, 'intval'),
                'link_url' => input('post.link_url/s', '', 'trim'),
                'inner_link' => input('post.inner_link/s', '', 'trim'),
                'inner_link_params' => input(
                    'post.inner_link_params/d',
                    0,
                    'intval'
                ),
                'company_id' => input('post.company_id/d', 0, 'intval'),
                'is_display' => input('post.is_display/d', 1, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if ($input_data['starttime']) {
                $input_data['starttime'] = strtotime($input_data['starttime']);
            }
            if ($input_data['deadline']) {
                $input_data['deadline'] = strtotime($input_data['deadline']);
            } else {
                $input_data['deadline'] = 0;
            }
            if ($input_data['target'] == 0) {
                $input_data['inner_link'] = '';
                $input_data['inner_link_params'] = 0;
                $input_data['company_id'] = 0;
            } elseif ($input_data['target'] == 1) {
                $input_data['link_url'] = '';
                $input_data['company_id'] = 0;
            } elseif ($input_data['target'] == 2) {
                $input_data['link_url'] = '';
                $input_data['inner_link'] = '';
                $input_data['inner_link_params'] = 0;
            }
            $cid_arr = $input_data['cid'];
            $input_data['cid'] = $cid_arr[1];

            try {
                Db::startTrans();

                $info = model('Ad')->find($id);
                if (!$info) {
                    $this->ajaxReturn(500, '要修改的广告不存在');
                }
                $info = $info->toArray();

                if (
                    false ===
                    model('Ad')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('Ad')->getError());
                }

                /**
                 * 日志
                 */
                $log_field = '编辑广告位，广告标题:' . $info['title'];
                if ($input_data['title'] != $info['title']) {
                    $log_field .= '->' . $input_data['title'];
                }

                $old_category = model('AdCategory')
                    ->field('id,platform,name')
                    ->where('id', $info['cid'])
                    ->find();
                $log_field .= '；广告位:'
                    . model('BaseModel')->map_ad_platform[$old_category['platform']]
                    . '/'
                    . $old_category['name'];
                if ($input_data['cid'] != $info['cid']) {
                    $new_category = model('AdCategory')
                        ->field('id,platform,name')
                        ->where('id', $input_data['cid'])
                        ->find();
                    $log_field .= '->'
                        . model('BaseModel')->map_ad_platform[$new_category['platform']]
                        . '/'
                        . $new_category['name'];
                }

                if ($input_data['starttime'] != $info['starttime'] || $input_data['deadline'] != $info['deadline']) {
                    $old_starttime = $info['starttime'] > 0 ? date('Y-m-d H:i', $info['starttime']) : '无';
                    $old_deadline = $info['deadline'] > 0 ? date('Y-m-d H:i', $info['deadline']) : '无';
                    $new_starttime = $input_data['starttime'] > 0 ? date('Y-m-d H:i', $input_data['starttime']) : '无';
                    $new_deadline = $input_data['deadline'] > 0 ? date('Y-m-d H:i', $input_data['deadline']) : '无';
                    $log_field .= '；展示时间:'
                        . $old_starttime . ' ~ ' . $old_deadline
                        . '->'
                        . $new_starttime . ' ~ ' . $new_deadline;
                }
                if ($input_data['is_display'] != $info['is_display']) {
                    $log_field .= '；显示状态:'
                        . model('Ad')->map_is_display[$info['is_display']]
                        . '->'
                        . model('Ad')->map_is_display[$input_data['is_display']];
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

            $list = model('Ad')
                ->where('id', 'in', $id)
                ->column('id,cid,title');

            if (false === model('Ad')->destroy($id)) {
                throw new \Exception(model('Ad')->getError());
            }

            /**
             * 日志
             */
            $log_field = '删除广告位，';
            foreach ($list as $ad) {
                $category = model('AdCategory')
                    ->field('id,platform,name')
                    ->where('id', $ad['cid'])
                    ->find();
                $log_field .= '广告标题:' . $ad['title'] .
                    ',广告位:' . model('BaseModel')->map_ad_platform[$category['platform']] . '/' . $category['name'] . '；';
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
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }

    public function innerLinkOptions()
    {
        $list = model('Ad')->innerLinks;
        $this->ajaxReturn(200, '获取数据成功', $list);
    }

    public function adModifyState()
    {
        $id = input('post.id/d', 0, 'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择要修改的广告');
        }

        $display = input('post.display/d', 0, 'intval');
        if (empty(model('BaseModel')->map_is_display[$display])) {
            $this->ajaxReturn(500, '要设置的显示状态错误');
        }
        $display_text = model('BaseModel')->map_is_display[$display];

        try {
            $ad = model('Ad')
                ->find($id);
            if (null === $ad) {
                throw new \Exception('要修改的广告不存在');
            }

            $category = model('AdCategory')
                ->field('id,platform,name')
                ->where('id', $ad['cid'])
                ->find();

            Db::startTrans();

            $modify_result = model('Ad')
                ->where('id', $id)
                ->setField('is_display', $display);
            if (false === $modify_result) {
                throw new \Exception(model('Ad')->getError());
            }

            /**
             * 日志
             */
            $log_field = '编辑广告位，广告标题:'
                . $ad['title']
                . ',广告位:'
                . model('BaseModel')->map_ad_platform[$category['platform']]
                . '/'
                . $category['name']
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
