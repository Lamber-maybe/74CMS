<?php

namespace app\apiadmin\controller;

use think\Db;

class WechatShare extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $list = model('WechatShare')
            ->where($where)
            ->order('id asc')
            ->select();

        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('WechatShare')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'content' => input('post.content/s', '', 'trim'),
                'img' => input('post.img/s', '', 'trim'),
                'explain' => input('post.explain/s', '', 'trim')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            try {
                $info = model('WechatShare')->find($id);
                if (null === $info) {
                    $this->ajaxReturn(500, '要修改的微信分享配置项不存在');
                }

                Db::startTrans();

                if (
                    false ===
                    model('WechatShare')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('WechatShare')->getError());
                }

                // 日志
                $log_field = '系统-微信平台配置，修改微信分享配置，';
                if ($input_data['name'] != $info['name']) {
                    $log_field .= '页面:' . $info['name'] . '->' . $input_data['name'] . '；';
                } else {
                    $log_field .= '页面:' . $info['name'] . '；';
                }
                if ($input_data['content'] != $info['content']) {
                    $log_field .= '分享语句:' . $info['content'] . '->' . $input_data['content'] . '；';
                }
                if ($input_data['img'] != $info['img']) {
                    if ($input_data['img'] == 'logo') {
                        $log_field .= '显示图片:' . $info['img_self_cn'] . '->网站Logo；';
                    } else {
                        $log_field .= '显示图片:网站Logo->' . $info['img_self_cn'] . '；';
                    }
                }
                if ($input_data['explain'] != $info['explain']) {
                    $log_field .= '说明文字:' . $info['explain'] . '->' . $input_data['explain'] . '；';
                }

                $log_result = model('AdminLog')->writeLog(
                    $log_field,
                    $this->admininfo,
                    0,
                    1
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
}
