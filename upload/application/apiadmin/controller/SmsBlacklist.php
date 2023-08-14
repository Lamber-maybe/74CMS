<?php

namespace app\apiadmin\controller;

use think\Db;

class SmsBlacklist extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['mobile'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    break;
            }
        }

        $total = model('SmsBlacklist')->where($where)->count();
        $list = model('SmsBlacklist')
            ->where($where)
            ->order('id desc')
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
            'mobile' => input('post.mobile/s', '', 'trim'),
            'note' => input('post.note/s', '', 'trim'),
            'addtime' => time()
        ];
        $validate = new \think\Validate([
            'mobile' => 'require|max:60|checkMobile'
        ]);
        $validate->extend('checkMobile', function ($value) {
            if (fieldRegex($value, 'mobile')) {
                $info = model('SmsBlacklist')
                    ->where([
                        'mobile' => $value
                    ])
                    ->find();
                if (null === $info) {
                    return true;
                } else {
                    return '手机号已存在黑名单中';
                }
            } else {
                return '请输入正确的手机号码';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }

        try {
            Db::startTrans();

            if (
                false ===
                model('SmsBlacklist')->save($input_data)
            ) {
                throw new \Exception(model('SmsBlacklist')->getError());
            }

            // 日志
            $log_field = '系统-基础配置-短信配置，添加短信黑名单，电话:'
                . $input_data['mobile']
                . '，备注:'
                . (empty($input_data['note']) ? '未填写' : $input_data['note']);
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
            $info = model('SmsBlacklist')->find($id);
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
                'mobile' => input('post.mobile/s', '', 'trim'),
                'note' => input('post.note/s', '', 'trim'),
                'addtime' => time()
            ];
            $validate = new \think\Validate([
                'mobile' => 'require|max:60|checkMobile'
            ]);
            $validate->extend('checkMobile', function ($value) use ($input_data) {
                if (fieldRegex($value, 'mobile')) {
                    $info = model('SmsBlacklist')
                        ->where([
                            'mobile' => $value
                        ])
                        ->where('id', 'neq', $input_data['id'])
                        ->find();
                    if (null === $info) {
                        return true;
                    } else {
                        return '手机号已存在黑名单中';
                    }
                } else {
                    return '请输入正确的手机号码';
                }
            });
            if (!$validate->check($input_data)) {
                $this->ajaxReturn(500, $validate->getError());
            }
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            try {
                $info = model('SmsBlacklist')->find($id);
                if (null === $info) {
                    $this->ajaxReturn(500, '要修改的短信黑名单不存在');
                }
                $info = $info->toArray();

                Db::startTrans();

                if (
                    false ===
                    model('SmsBlacklist')
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('SmsBlacklist')->getError());
                }

                // 日志
                $log_field = '系统-基础配置-短信配置，修改短信黑名单，电话:' . $info['mobile'];
                if ($input_data['mobile'] != $info['mobile']) {
                    $log_field .= '->' . $input_data['mobile'];
                }
                if ($input_data['note'] != $info['note']) {
                    $log_field .= '，备注:' . (empty($info['note']) ? '未填写' : $info['note']) . '->' . (empty($input_data['note']) ? '未填写' : $input_data['note']);
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
            $list = model('SmsBlacklist')
                ->where('id', 'in', $id)
                ->column('mobile');
            if (empty($list)) {
                $this->ajaxReturn(500, '没有要删除的短信黑名单');
            }

            Db::startTrans();

            if (
                false ===
                model('SmsBlacklist')->destroy($id)
            ) {
                throw new \Exception(model('SmsBlacklist')->getError());
            }

            // 日志
            $log_field = '系统-基础配置-短信配置，删除短信黑名单，电话:' . implode('；', $list);
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
