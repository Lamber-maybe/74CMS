<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use think\Db;

class Task extends Backend
{
    public function index()
    {
        $utype = input('get.utype/d', 1, 'intval');
        $list = model('Task')
            ->field('alias,utype', true)
            ->where('utype', 'eq', $utype)
            ->order('id asc')
            ->select();
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }

    public function saveall()
    {
        $post_data = input('post.');

        try {
            $conIds = array_column($post_data, 'id');

            $configList = model('Task')->whereIn('id', $conIds)
                ->column('*');
            if (null === $configList) {
                $this->ajaxReturn(500, '没有要修改的任务配置项');
            }

            $uType = model('Task')->whereIn('id', $conIds)
                ->value('utype');
            if ($uType === 1) {
                $log_field = '系统-企业业务配置-任务配置，修改企业任务，';
            } else {
                $log_field = '系统-个人业务配置-任务配置，修改个人任务，';
            }

            $sql_arr = [];
            foreach ($post_data as $key => $value) {
                if (!isset($configList[$value['id']]) || empty($configList[$value['id']])) {
                    continue;
                }
                $config = $configList[$value['id']];
                if ($value['points'] != $config['points'] || $value['max_perday'] != $config['max_perday']) {
                    $log_field .= $config['name'] . ':积分奖励'
                        . $config['points'] . '->' . $value['points']
                        . ',每天奖励上限:'
                        . $config['max_perday'] . '->' . $value['max_perday'] . '；';
                    $arr['id'] = $value['id'];
                    $arr['points'] = $value['points'];
                    $arr['max_perday'] = $value['max_perday'];
                    $sql_arr[] = $arr;
                }
            }

            // 开启事务
            Db::startTrans();

            if (!empty($sql_arr)) {
                if (
                    false ===
                    model('Task')->saveAll($sql_arr)
                ) {
                    throw new \Exception(model('ServiceOl')->getError());
                }
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
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
