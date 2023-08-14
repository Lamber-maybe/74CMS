<?php

namespace app\apiadmin\controller;

use think\Db;

class WechatMenu extends \app\common\controller\Backend
{
    public function index()
    {
        $pid = input('get.pid/d', 0, 'intval');
        $list = model('WechatMenu')
            ->where('pid', $pid)
            ->order('sort_id desc,id asc')
            ->select();
        foreach ($list as $key => $value) {
            $children = model('WechatMenu')->where('pid', $value['id'])->find();
            $list[$key]['hasChildren'] = $children ? true : false;
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }

    public function add()
    {
        $input_data = [
            'pid' => input('post.pid/d', 0, 'intval'),
            'title' => input('post.title/s', '', 'trim'),
            'key' => input('post.key/s', '', 'trim'),
            'type' => input('post.type/s', '', 'trim'),
            'url' => input('post.url/s', '', 'trim'),
            'pagepath' => input('post.pagepath/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];

        try {
            $pid = intval($input_data['pid']);
            $p_menu = '顶级菜单';
            if ($pid > 0) {
                $p_menu = model('WechatMenu')->where('id', $pid)->value('title');
                if (null === $p_menu) {
                    $this->ajaxReturn(500, '所属菜单不存在');
                }
            }

            // 开启事务
            Db::startTrans();
            $result = model('WechatMenu')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            if (false === $result) {
                throw new \Exception(model('WechatMenu')->getError());
            }

            // 日志
            $log_field = '系统-微信平台配置，公众号自定义菜单，添加菜单，所属菜单:' . $p_menu
                . '；名称:' . $input_data['title']
                . '；菜单类型:' . model('WechatMenu')->map_type[$input_data['type']];
            switch ($input_data['type']) {
                case 'click':
                    // 点击事件
                    $log_field .= '；菜单KEY:' . $input_data['key'];
                    break;

                case 'view':
                    // 链接事件
                    $log_field .= '；跳转链接:' . $input_data['url'];
                    break;

                case 'miniprogram':
                    $log_field .= '；跳转路径:' . $input_data['pagepath'];
                    // 小程序
                    break;

                default:
                    $this->ajaxReturn(500, '错误的菜单类型');
                    break;
            }
            $log_field .= '；排序:' . $input_data['sort_id'];
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
            $info = model('WechatMenu')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'pid' => input('post.pid/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'key' => input('post.key/s', '', 'trim'),
                'type' => input('post.type/s', '', 'trim'),
                'url' => input('post.url/s', '', 'trim'),
                'pagepath' => input('post.pagepath/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            try {
                $menu_info = model('WechatMenu')->find($id);
                if (null === $menu_info) {
                    $this->ajaxReturn(500, '要修改的菜单不存在');
                }
                $menu_info = $menu_info->toArray();

                $allMenu = model('WechatMenu')
                    ->column('id,title');

                // 开启事务
                Db::startTrans();

                $result = model('WechatMenu')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
                if (false === $result) {
                    throw new \Exception(model('WechatMenu')->getError());
                }

                // 日志
                $p_menu_old = !empty($allMenu[$menu_info['pid']]) ? $allMenu[$menu_info['pid']] : '顶级菜单';
                $type_old = model('WechatMenu')->map_type[$menu_info['type']];
                switch ($menu_info['type']) {
                    case 'click':
                        // 点击事件
                        $link_old = $menu_info['key'];
                        break;

                    case 'view':
                        // 链接事件
                        $link_old = $menu_info['url'];
                        break;

                    case 'miniprogram':
                        $link_old = $menu_info['pagepath'];
                        // 小程序
                        break;

                    default:
                        $this->ajaxReturn(500, '错误的原菜单跳转链接');
                        break;
                }
                $log_field = '系统-微信平台配置，公众号自定义菜单，修改菜单，所属菜单:' . $p_menu_old;

                if ($input_data['pid'] != $menu_info['pid']) {
                    $log_field .= '->' . (!empty($allMenu[$input_data['pid']]) ? $allMenu[$input_data['pid']] : '顶级菜单');
                }
                $log_field .= '；名称:' . $menu_info['title'];
                if ($input_data['title'] != $menu_info['title']) {
                    $log_field .= '->' . $input_data['title'];
                }

                if ($input_data['type'] != $menu_info['type']) {
                    $log_field .= '；菜单类型:'
                        . $type_old
                        . '->'
                        . model('WechatMenu')->map_type[$input_data['type']];

                    switch ($input_data['type']) {
                        case 'click':
                            // 点击事件
                            $log_field .= '；菜单KEY:' . $link_old . '->' . $input_data['key'];
                            break;

                        case 'view':
                            // 链接事件
                            $log_field .= '；跳转链接:' . $link_old . '->' . $input_data['url'];
                            break;

                        case 'miniprogram':
                            $log_field .= '；跳转路径:' . $link_old . '->' . $input_data['pagepath'];
                            // 小程序
                            break;

                        default:
                            $this->ajaxReturn(500, '错误的菜单类型');
                            break;
                    }
                } else {
                    $log_field .= '；菜单类型:' . $type_old;
                    switch ($input_data['type']) {
                        case 'click':
                            // 点击事件
                            $log_field .= '；菜单KEY:' . $link_old . '->' . $input_data['key'];
                            break;

                        case 'view':
                            // 链接事件
                            $log_field .= '；跳转链接:' . $link_old . '->' . $input_data['url'];
                            break;

                        case 'miniprogram':
                            $log_field .= '；跳转路径:' . $link_old . '->' . $input_data['pagepath'];
                            // 小程序
                            break;

                        default:
                            $this->ajaxReturn(500, '错误的菜单类型');
                            break;
                    }
                }

                if ($input_data['sort_id'] != $menu_info['sort_id']) {
                    $log_field .= '；排序:'
                        . $menu_info['sort_id']
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
            $list = model('WechatMenu')
                ->where('pid', 'in', $id)
                ->whereOr('id', 'in', $id)
                ->column('id,pid,title');
            if (empty($list)) {
                $this->ajaxReturn(500, '没有要删除的菜单');
            }

            $allMenu = model('WechatMenu')
                ->column('id,title');

            Db::startTrans();

            if (
                false ===
                model('WechatMenu')
                    ->where('pid', 'in', $id)
                    ->whereOr('id', 'in', $id)
                    ->delete()
            ) {
                throw new \Exception(model('WechatMenu')->getError());
            }

            // 日志
            $log_field = '系统-微信平台配置，公众号自定义菜单，删除菜单，';
            foreach ($list as $menu) {
                $p_menu = !empty($allMenu[$menu['pid']]) ? $allMenu[$menu['pid']] : '顶级菜单';
                $log_field .= '所属菜单:' . $p_menu . '，名称:' . $menu['title'] . '；';
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

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }

    public function sync()
    {
        $instance = new \app\common\lib\Wechat;
        $syncResult = $instance->menuSync();
        if ($syncResult === true) {
            model('AdminLog')->writeLog(
                '系统-微信平台配置，公众号自定义菜单，同步菜单',
                $this->admininfo,
                0,
                1
            );
            $this->ajaxReturn(200, '同步菜单成功');
        } else {
            $this->ajaxReturn(500, $instance->getError());
        }
    }
}
