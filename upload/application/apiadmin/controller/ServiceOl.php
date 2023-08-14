<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class ServiceOl extends Backend
{
    public function index()
    {
        $order = 'sort desc';
        $list = model('ServiceOl')
            ->order($order)
            ->select();
        $image_id_arr = $image_list = [];
        foreach ($list as $key => $value) {
            $value['weixin'] && ($image_id_arr[] = $value['weixin']);
        }
        if (!empty($image_id_arr)) {
            $image_list = model('Uploadfile')->getFileUrlBatch($image_id_arr);
        }
        foreach ($list as $key => $value) {
            $value['weixin_url'] = isset($image_list[$value['weixin']])
                ? $image_list[$value['weixin']]
                : '';
        }
        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function add()
    {
        $input_data = [
            'name' => input('post.name/s', '', 'trim'),
            'mobile' => input('post.mobile/s', '', 'trim'),
            'weixin' => input('post.weixin/d', '', 'intval'),
            'qq' => input('post.qq/s', '', 'trim'),
            'sort' => input('post.sort/d', 0, 'intval'),
            'display' => input('post.display/d', 1, 'intval')
        ];


        try {
            Db::startTrans();

            $result = model('ServiceOl')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            if ($result === false) {
                throw new \Exception(model('ServiceOl')->getError());
            }

            // 日志
            $log_field = '系统-基础配置-在线客服，新增客服，姓名:' . $input_data['name']
                . '；电话:' . $input_data['mobile']
                . '；QQ:' . $input_data['qq']
                . '；显示状态:' . model('ServiceOl')->map_display[$input_data['display']];
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
            $info = model('ServiceOl')->find($id);
            if (null === $info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info = $info->toArray();
            $imageSrc = model('Uploadfile')->getFileUrl($info['weixin']);
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'imageSrc' => $imageSrc
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'mobile' => input('post.mobile/s', '', 'trim'),
                'weixin' => input('post.weixin/d', '', 'intval'),
                'qq' => input('post.qq/s', '', 'trim'),
                'sort' => input('post.sort/d', 0, 'intval'),
                'display' => input('post.display/d', 1, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            try {
                $ol_info = model('ServiceOl')->find($id);
                if (null === $ol_info) {
                    $this->ajaxReturn(500, '要修改的在线客服不存在');
                }
                $ol_info = $ol_info->toArray();

                Db::startTrans();

                $result = model('ServiceOl')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
                if (false === $result) {
                    throw new \Exception(model('ServiceOl')->getError());
                }

                // 日志
                $log_field = '系统-基础配置-在线客服，修改客服，姓名:' . $ol_info['name'];
                if ($input_data['name'] != $ol_info['name']) {
                    $log_field .= '->' . $input_data['name'];
                }
                if ($input_data['mobile'] != $ol_info['mobile']) {
                    $log_field .= '；电话:'
                        . $ol_info['mobile']
                        . '->'
                        . $input_data['mobile'];
                }
                if ($input_data['qq'] != $ol_info['qq']) {
                    $log_field .= '；QQ:'
                        . $ol_info['qq']
                        . '->'
                        . $input_data['qq'];
                }
                if ($input_data['display'] != $ol_info['display']) {
                    $log_field .= '；显示状态:'
                        . model('ServiceOl')->map_display[$ol_info['display']]
                        . '->'
                        . model('ServiceOl')->map_display[$input_data['display']];
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
            $list = model('ServiceOl')
                ->whereIn('id', $id)
                ->column('id,name,mobile');
            if (null === $list) {
                $this->ajaxReturn(500, '没有要删除的在线客服');
            }

            Db::startTrans();

            if (
                false ===
                model('ServiceOl')->destroy($id)
            ) {
                throw new \Exception(model('ServiceOl')->getError());
            }

            // 日志
            $log_field = '系统-基础配置-在线客服，删除客服，';
            foreach ($list as $one) {
                $log_field .= '姓名:' . $one['name'] . '；电话:' . $one['mobile'] . '；';
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
}
