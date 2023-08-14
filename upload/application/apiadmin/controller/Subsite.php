<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class Subsite extends Backend
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
            $where['a.deadline'] = [
                ['neq', 0],
                ['elt', strtotime('+' . $settr . ' day')],
                ['gt', time()]
            ];
        }

        $total = model('Subsite')->where($where)->count();
        $list = model('Subsite')->where($where)->order('sort_id desc,id asc')->page($current_page . ',' . $pagesize)->select();

        $category_district_data = model('CategoryDistrict')->getCache();
        $tpl_list = model('Tpl')->where('type', 'index')->column('alias,title');
        foreach ($list as $key => $value) {
            $list[$key]['district_text'] = isset($category_district_data[$value['district1']]) ? $category_district_data[$value['district1']] : '';
            if ($list[$key]['district_text'] != '' && $value['district2'] > 0) {
                $list[$key]['district_text'] .= isset(
                    $category_district_data[$value['district2']]
                )
                    ? ' / ' . $category_district_data[$value['district2']]
                    : '';
            }
            if ($list[$key]['district_text'] != '' && $value['district3'] > 0) {
                $list[$key]['district_text'] .= isset(
                    $category_district_data[$value['district3']]
                )
                    ? ' / ' . $category_district_data[$value['district3']]
                    : '';
            }
            $list[$key]['tpl'] = isset($tpl_list[$value['tpl']]) ? $tpl_list[$value['tpl']] : '';
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
            'sitename' => input('post.sitename/s', '', 'trim'),
            'district1' => input('post.district1/d', 0, 'intval'),
            'district2' => input('post.district2/d', 0, 'intval'),
            'district3' => input('post.district3/d', 0, 'intval'),
            'title' => input('post.title/s', '', 'trim'),
            'keywords' => input('post.keywords/s', '', 'trim'),
            'description' => input('post.description/s', '', 'trim'),
            'tpl' => input('post.tpl/s', '', 'trim'),
            'is_display' => input('post.is_display/d', 1, 'intval'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        $input_data['district'] = $input_data['district3'] > 0 ? $input_data['district3'] : ($input_data['district2'] > 0 ? $input_data['district2'] : $input_data['district1']);

        try {
            Db::startTrans();

            if (
                false ===
                model('Subsite')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('Subsite')->getError());
            }

            $category_district_data = model('CategoryDistrict')->getCache();

            if ($input_data['district1']) {
                $district_fulltext = isset($category_district_data[$input_data['district1']])
                    ? $category_district_data[$input_data['district1']]
                    : '';
            } else {
                $district_fulltext = '';
            }
            if ($district_fulltext != '' && $input_data['district2'] > 0) {
                $district_fulltext .= isset($category_district_data[$input_data['district2']])
                    ? '/' . $category_district_data[$input_data['district2']]
                    : '';
            }
            if ($district_fulltext != '' && $input_data['district3'] > 0) {
                $district_fulltext .= isset($category_district_data[$input_data['district3']])
                    ? '/' . $category_district_data[$input_data['district3']]
                    : '';
            }

            $tpl_title = model('Tpl')
                ->where('type', 'index')
                ->where('alias', $input_data['tpl'])
                ->value('title');

            $log_field = '地区分站-分站管理，添加分站，分站名称:' . $input_data['sitename']
                . '；绑定地区:' . $district_fulltext . '；'
                . '使用模板:' . $tpl_title . '；'
                . '排序:' . $input_data['sort_id'] . '；'
                . '显示状态:' . model('Subsite')->map_is_display[$input_data['is_display']];

            $log_result = model('AdminLog')->writeLog(
                $log_field,
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
            $info = model('Subsite')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info = $info->toArray();
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'sitename' => input('post.sitename/s', '', 'trim'),
                'district1' => input('post.district1/d', 0, 'intval'),
                'district2' => input('post.district2/d', 0, 'intval'),
                'district3' => input('post.district3/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'keywords' => input('post.keywords/s', '', 'trim'),
                'description' => input('post.description/s', '', 'trim'),
                'tpl' => input('post.tpl/s', '', 'trim'),
                'is_display' => input('post.is_display/d', 1, 'intval'),
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $input_data['district'] = $input_data['district3'] > 0 ? $input_data['district3'] : ($input_data['district2'] > 0 ? $input_data['district2'] : $input_data['district1']);
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            try {
                $info = model('Subsite')
                    ->alias('s')
                    ->join('Tpl t', 't.alias = s.tpl')
                    ->field('s.*,t.title as tpl_title')
                    ->find($id);
                if (null === $info) {
                    $this->ajaxReturn(500, '要修改的地区分站不存在');
                }
                $info = $info->toArray();

                Db::startTrans();

                if (
                    false ===
                    model('Subsite')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('Subsite')->getError());
                }

                $log_field = '地区分站-分站管理，修改分站，';

                if ($input_data['sitename'] != $info['sitename']) {
                    $log_field .= '分站名称:' . $info['sitename'] . '->' . $input_data['sitename'] . '；';
                } else {
                    $log_field .= '分站名称:' . $info['sitename'] . '；';
                }
                if ($input_data['district'] != $info['district']) {
                    $category_district_data = model('CategoryDistrict')->getCache();

                    if ($input_data['district1']) {
                        $district_fulltext = isset($category_district_data[$input_data['district1']])
                            ? $category_district_data[$input_data['district1']]
                            : '';
                    } else {
                        $district_fulltext = '';
                    }
                    if ($district_fulltext != '' && $input_data['district2'] > 0) {
                        $district_fulltext .= isset($category_district_data[$input_data['district2']])
                            ? '/' . $category_district_data[$input_data['district2']]
                            : '';
                    }
                    if ($district_fulltext != '' && $input_data['district3'] > 0) {
                        $district_fulltext .= isset($category_district_data[$input_data['district3']])
                            ? '/' . $category_district_data[$input_data['district3']]
                            : '';
                    }

                    if ($info['district1']) {
                        $district_fulltext_old = isset($category_district_data[$info['district1']])
                            ? $category_district_data[$info['district1']]
                            : '';
                    } else {
                        $district_fulltext_old = '';
                    }
                    if ($district_fulltext_old != '' && $info['district2'] > 0) {
                        $district_fulltext_old .= isset($category_district_data[$info['district2']])
                            ? '/' . $category_district_data[$info['district2']]
                            : '';
                    }
                    if ($district_fulltext_old != '' && $info['district3'] > 0) {
                        $district_fulltext_old .= isset($category_district_data[$info['district3']])
                            ? '/' . $category_district_data[$info['district3']]
                            : '';
                    }

                    $log_field .= '绑定地区:' . $district_fulltext_old . '->' . $district_fulltext;
                }
                if ($input_data['tpl'] != $info['tpl']) {
                    $tpl_title = model('Tpl')
                        ->where('type', 'index')
                        ->where('alias', $input_data['tpl'])
                        ->value('title');
                    $log_field .= '使用模板:' . $info['tpl_title'] . '->' . $tpl_title . '；';
                }
                if ($input_data['sort_id'] != $info['sort_id']) {
                    $log_field .= '排序:' . $info['sort_id'] . '->' . $input_data['sort_id'] . '；';
                }
                if ($input_data['is_display'] != $info['is_display']) {
                    $log_field .= '显示状态:' . model('Subsite')->map_is_display[$info['is_display']] . '->' . model('Subsite')->map_is_display[$input_data['is_display']] . '；';
                }

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

        try {
            $list = model('Subsite')
                ->alias('s')
                ->join('Tpl t', 't.alias = s.tpl')
                ->whereIn('s.id', $id)
                ->column('s.sitename,s.district1,s.district2,s.district3,t.title as tpl_title');
            if (null === $list) {
                $this->ajaxReturn(500, '没有要删除的地区分站');
            }


            Db::startTrans();

            $del_result = model('Subsite')->destroy($id);
            if (false === $del_result) {
                throw new \Exception(model('Subsite')->getError());
            }

            $log_field = '地区分站-分站管理，删除分站，';

            $category_district_data = model('CategoryDistrict')->getCache();

            foreach ($list as $site) {
                if ($site['district1']) {
                    $district_fulltext = isset($category_district_data[$site['district1']])
                        ? $category_district_data[$site['district1']]
                        : '';
                } else {
                    $district_fulltext = '';
                }
                if ($district_fulltext != '' && $site['district2'] > 0) {
                    $district_fulltext .= isset($category_district_data[$site['district2']])
                        ? '/' . $category_district_data[$site['district2']]
                        : '';
                }
                if ($district_fulltext != '' && $site['district3'] > 0) {
                    $district_fulltext .= isset($category_district_data[$site['district3']])
                        ? '/' . $category_district_data[$site['district3']]
                        : '';
                }

                $log_field .= '分站名称:' . $site['sitename'] . ';'
                    . '绑定地区:' . $district_fulltext . ';'
                    . '使用模板:' . $site['tpl_title'] . '；';
            }

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
            $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }
}
