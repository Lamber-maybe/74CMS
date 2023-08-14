<?php

namespace app\apiadmin\controller;

use think\Db;

class CompanyServiceImPackage extends \app\common\controller\Backend
{
    public function index()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $total = model('CompanyServiceIm')->count();
        $list = model('CompanyServiceIm')
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
            'times' => input(
                'post.times/d',
                0,
                'intval'
            ),
            'expense' => input('post.expense/f', 0, 'floatval'),
            'enable_points_deduct' => input(
                'post.enable_points_deduct/d',
                1,
                'intval'
            ),
            'deduct_max' => input('post.deduct_max/f', 0, 'floatval'),
            'is_display' => input('post.is_display/d', 0, 'intval'),
            'recommend' => input('post.recommend/d', 0, 'intval'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];

        try {
            Db::startTrans();

            $add_result = model('CompanyServiceIm')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            if (false === $add_result) {
                throw new \Exception(model('CompanyServiceIm')->getError());
            }

            /**
             * 日志
             */
            $log_field = '系统-企业业务配置-增值服务，职聊增值包管理，添加职聊增值包套餐，服务名:' . $input_data['name']
                . '；职聊次数:' . $input_data['times']
                . '次；服务价格:' . $input_data['expense']
                . '元；积分抵扣:' . model('CompanyServiceIm')->map_enable_points_deduct[$input_data['enable_points_deduct']]
                . '；排序:' . $input_data['sort_id']
                . '；显示:' . model('CompanyServiceIm')->map_is_display[$input_data['is_display']]
                . '；推荐:' . model('CompanyServiceIm')->map_recommend[$input_data['recommend']];
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
            'times' => input(
                'post.times/d',
                0,
                'intval'
            ),
            'expense' => input('post.expense/f', 0, 'floatval'),
            'enable_points_deduct' => input(
                'post.enable_points_deduct/d',
                1,
                'intval'
            ),
            'deduct_max' => input('post.deduct_max/f', 0, 'floatval'),
            'is_display' => input('post.is_display/d', 0, 'intval'),
            'recommend' => input('post.recommend/d', 0, 'intval'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];

        $id = intval($input_data['id']);
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        try {
            $info = model('CompanyServiceIm')->find($id);
            if (null === $info) {
                $this->ajaxReturn(500, '要修改的职聊增值包套餐不存在');
            }

            Db::startTrans();

            $update_result = model('CompanyServiceIm')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $input_data['id']]);
            if (false === $update_result) {
                throw new \Exception(model('CompanyServiceIm')->getError());
            }

            /**
             * 日志
             */
            $log_field = '系统-企业业务配置-增值服务，职聊增值包管理，修改职聊增值包套餐，服务名:' . $info['name'];
            if ($input_data['name'] != $info['name']) {
                $log_field .= '->' . $input_data['name'];
            }
            if ($input_data['times'] != $info['times']) {
                $log_field .= '；职聊次数:' . $info['times'] . '->' . $input_data['times'] . '次';
            }
            if ($input_data['expense'] != $info['expense']) {
                $log_field .= '；服务价格:' . $info['expense'] . '->' . $input_data['expense'] . '元';
            }
            if ($input_data['enable_points_deduct'] != $info['enable_points_deduct']) {
                $log_field .= '；积分抵扣:'
                    . model('CompanyServiceIm')->map_enable_points_deduct[$info['enable_points_deduct']]
                    . '->'
                    . model('CompanyServiceIm')->map_enable_points_deduct[$input_data['enable_points_deduct']];
            }
            if ($input_data['sort_id'] != $info['sort_id']) {
                $log_field .= '；排序:' . $info['sort_id'] . '->' . $input_data['sort_id'];
            }
            if ($input_data['is_display'] != $info['is_display']) {
                $log_field .= '；显示:'
                    . model('CompanyServiceIm')->map_is_display[$info['is_display']]
                    . '->'
                    . model('CompanyServiceIm')->map_is_display[$input_data['is_display']];
            }
            if ($input_data['recommend'] != $info['recommend']) {
                $log_field .= '；推荐:'
                    . model('CompanyServiceIm')->map_recommend[$info['recommend']]
                    . '->'
                    . model('CompanyServiceIm')->map_recommend[$input_data['recommend']];
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
            $info = model('CompanyServiceIm')->find($id);
            if (null === $info) {
                $this->ajaxReturn(500, '要删除的职聊增值包套餐不存在');
            }

            Db::startTrans();

            $del_result = model('CompanyServiceIm')
                ->where('id', $id)
                ->delete();
            if (false === $del_result) {
                throw new \Exception(model('CompanyServiceIm')->getError());
            }

            /**
             * 日志
             */
            $log_field = '系统-企业业务配置-增值服务，职聊增值包管理，删除职聊增值包套餐，服务名:' . $info['name']
                . '；职聊次数:' . $info['times']
                . '次；服务价格:' . $info['expense'] . '元';
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
