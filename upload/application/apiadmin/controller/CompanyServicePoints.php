<?php

namespace app\apiadmin\controller;

use think\Db;

class CompanyServicePoints extends \app\common\controller\Backend
{
    public function index()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $total = model('CompanyServicePoints')->count();
        $list = model('CompanyServicePoints')
            ->order('id asc')
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
            'name' => input('post.name/s', '', 'trim'),
            'points' => input('post.points/d', 0, 'intval'),
            'expense' => input('post.expense/f', 0, 'floatval'),
            'is_display' => input('post.is_display/d', 0, 'intval'),
            'recommend' => input('post.recommend/d', 0, 'intval'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
        ];

        try {
            Db::startTrans();

            $add_result = model('CompanyServicePoints')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            if (false === $add_result) {
                throw new \Exception(model('CompanyServicePoints')->getError());
            }

            /**
             * 日志
             */
            $log_field = '系统-企业业务配置-积分套餐，积分套餐配置，添加积分套餐，服务名:' . $input_data['name']
                . '；积分数:' . $input_data['points']
                . '点；服务价格:' . $input_data['expense']
                . '元；排序:' . $input_data['sort_id']
                . '；显示:' . model('CompanyServicePoints')->map_is_display[$input_data['is_display']]
                . '；推荐:' . model('CompanyServicePoints')->map_recommend[$input_data['recommend']];
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
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'name' => input('post.name/s', '', 'trim'),
            'points' => input('post.points/d', 0, 'intval'),
            'expense' => input('post.expense/f', 0, 'floatval'),
            'is_display' => input('post.is_display/d', 0, 'intval'),
            'recommend' => input('post.recommend/d', 0, 'intval'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
        ];

        $id = intval($input_data['id']);
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        try {
            $info = model('CompanyServicePoints')->find($id);
            if (null === $info) {
                $this->ajaxReturn(500, '要修改的积分套餐不存在');
            }

            Db::startTrans();

            $update_result = model('CompanyServicePoints')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $input_data['id']]);
            if (false === $update_result) {
                throw new \Exception(model('CompanyServicePoints')->getError());
            }

            /**
             * 日志
             */
            $log_field = '系统-企业业务配置-增值服务，积分套餐配置，修改积分套餐，服务名:' . $info['name'];
            if ($input_data['name'] != $info['name']) {
                $log_field .= '->' . $input_data['name'];
            }
            if ($input_data['points'] != $info['points']) {
                $log_field .= '；积分数:' . $info['points'] . '->' . $input_data['points'] . '点';
            }
            if ($input_data['expense'] != $info['expense']) {
                $log_field .= '；服务价格:' . $info['expense'] . '->' . $input_data['expense'] . '元';
            }
            if ($input_data['sort_id'] != $info['sort_id']) {
                $log_field .= '；排序:' . $info['sort_id'] . '->' . $input_data['sort_id'];
            }
            if ($input_data['is_display'] != $info['is_display']) {
                $log_field .= '；显示:'
                    . model('CompanyServicePoints')->map_is_display[$info['is_display']]
                    . '->'
                    . model('CompanyServicePoints')->map_is_display[$input_data['is_display']];
            }
            if ($input_data['recommend'] != $info['recommend']) {
                $log_field .= '；推荐:'
                    . model('CompanyServicePoints')->map_recommend[$info['recommend']]
                    . '->'
                    . model('CompanyServicePoints')->map_recommend[$input_data['recommend']];
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

    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }


        try {
            $info = model('CompanyServicePoints')->find($id);
            if (null === $info) {
                $this->ajaxReturn(500, '要删除的积分套餐不存在');
            }

            Db::startTrans();

            $del_result = model('CompanyServicePoints')
                ->where('id', $id)
                ->delete();
            if (false === $del_result) {
                throw new \Exception(model('CompanyServicePoints')->getError());
            }

            /**
             * 日志
             */
            $log_field = '系统-企业业务配置-增值服务，积分套餐配置，删除积分套餐，服务名:' . $info['name']
                . '；积分数:' . $info['points']
                . '点；服务价格:' . $info['expense'] . '元';
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
}
