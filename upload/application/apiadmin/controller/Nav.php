<?php

namespace app\apiadmin\controller;

use think\Db;

class Nav extends \app\common\controller\Backend
{
    public function index()
    {
        $list = model('Navigation')->order('sort_id desc,id asc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['page_cn'] = $value['page'] != '' ? model('Navigation')->map_page[$value['page']] : '';
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }

    public function add()
    {
        $input_data = [
            'title' => input('post.title/s', '', 'trim'),
            'link_type' => input('post.link_type/d', 1, 'intval'),
            'is_display' => input('post.is_display/d', 0, 'intval'),
            'page' => input('post.page/s', '', 'trim'),
            'url' => input('post.url/s', '', 'trim'),
            'target' => input('post.target/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];

        switch ($input_data['link_type']) {
            case 1:
                $input_data['url'] = '';
                break;

            case 2:
                $input_data['page'] = '';
                break;

            default:
                $this->ajaxReturn(500, '错误的导航类型');
                break;
        }

        try {
            Db::startTrans();

            if (
                false ===
                model('Navigation')
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('Navigation')->getError());
            }

            // 日志
            $log_field = '系统-基础配置-导航设置，导航管理，添加导航，导航名称:' . $input_data['title']
                . '；导航类型:' . model('Navigation')->map_link_type[$input_data['link_type']];
            if ($input_data['link_type'] === 1) {
                $log_field .= '；跳转页面:' . model('Navigation')->map_page[$input_data['page']];
            } else {
                $log_field .= '；跳转链接:' . $input_data['url'];
            }
            $log_field .= '；打开方式:' . model('Navigation')->map_target[$input_data['target']]
                . '；排序:' . $input_data['sort_id']
                . '；显示状态:' . model('Navigation')->map_is_display[$input_data['is_display']];
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
            $info = model('Navigation')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'link_type' => input('post.link_type/d', 1, 'intval'),
                'is_display' => input('post.is_display/d', 0, 'intval'),
                'page' => input('post.page/s', '', 'trim'),
                'url' => input('post.url/s', '', 'trim'),
                'target' => input('post.target/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            try {
                $nav_info = model('Navigation')->find($id);
                if (null === $nav_info) {
                    $this->ajaxReturn(500, '要修改的导航不存在');
                }
                $nav_info = $nav_info->toArray();

                Db::startTrans();

                /**
                 * 【ID1000480】
                 * 【bug】系统导航设置站外链接后，无法切换回系统页面
                 * yx - 2022.12.27
                 * [新增]:
                 * 导航类型为系统页面，将跳转链接置空
                 */
                switch ($input_data['link_type']) {
                    case 1:
                        $input_data['url'] = '';
                        break;

                    case 2:
                        $input_data['page'] = '';
                        break;

                    default:
                        $this->ajaxReturn(500, '错误的导航类型');
                        break;
                }

                if (
                    false ===
                    model('Navigation')
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('Navigation')->getError());
                }

                // 日志
                $log_field = '系统-基础配置-导航设置，导航管理，修改导航，导航名称:' . $nav_info['title'];
                if ($input_data['title'] != $nav_info['title']) {
                    $log_field .= '->' . $input_data['title'];
                }

                if ($input_data['link_type'] != $nav_info['link_type']) {
                    $log_field .= '；导航类型:'
                        . model('Navigation')->map_link_type[$nav_info['link_type']]
                        . '->'
                        . model('Navigation')->map_link_type[$input_data['link_type']];
                    if ($input_data['link_type'] === 1) {
                        $log_field .= '；跳转页面:' . $nav_info['url'] . '->' . model('Navigation')->map_page[$input_data['page']];
                    } else {
                        $log_field .= '；跳转链接:' . model('Navigation')->map_page[$nav_info['page']] . '->' . $input_data['url'];
                    }
                } else {
                    $log_field .= '；导航类型:' . model('Navigation')->map_link_type[$nav_info['link_type']];
                    if ($input_data['page'] != $nav_info['page']) {
                        $log_field .= '；跳转页面:'
                            . model('Navigation')->map_page[$nav_info['page']]
                            . '->'
                            . model('Navigation')->map_page[$input_data['page']];
                    }
                    if ($input_data['url'] != $nav_info['url']) {
                        $log_field .= '；跳转链接:'
                            . $nav_info['url']
                            . '->'
                            . $input_data['url'];
                    }
                }

                if ($input_data['target'] != $nav_info['target']) {
                    $log_field .= '；打开方式:'
                        . model('Navigation')->map_target[$nav_info['target']]
                        . '->'
                        . model('Navigation')->map_target[$input_data['target']];
                }
                if ($input_data['is_display'] != $nav_info['is_display']) {
                    $log_field .= '；显示状态:'
                        . model('Navigation')->map_is_display[$nav_info['is_display']]
                        . '->'
                        . model('Navigation')->map_is_display[$input_data['is_display']];
                }
                if ($input_data['sort_id'] != $nav_info['sort_id']) {
                    $log_field .= '；排序:'
                        . $nav_info['sort_id']
                        . '->'
                        . $input_data['sort_id'];
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
            $list = model('Navigation')
                ->where('id', 'in', $id)
                ->column('title');
            if (empty($list)) {
                $this->ajaxReturn(500, '没有要删除的导航');
            }

            Db::startTrans();

            if (
                false ===
                model('Navigation')->destroy($id)
            ) {
                throw new \Exception(model('Navigation')->getError());
            }

            // 日志
            $log_field = '系统-基础配置-导航设置，导航管理，删除导航，导航名称:' . implode('；', $list);
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
