<?php

namespace app\apiadmin\controller;

use think\Db;

class Coupon extends \app\common\controller\Backend
{
    public function index()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $total = model('Coupon')->count();
        $list = model('Coupon')
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $setmeal_list = model('Setmeal')->column('id,name');
        foreach ($list as $key => $value) {
            $list[$key]['bind_setmeal_name'] = !empty($setmeal_list[$value['bind_setmeal_id']]) ? $setmeal_list[$value['bind_setmeal_id']] : '';
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
            'name' => input('post.name/s', '', 'trim'),
            'face_value' => input('post.face_value/f', 0, 'doubleval'),
            'bind_setmeal_id' => input('post.bind_setmeal_id/d', 0, 'intval'),
            'days' => input('post.days/d', 1, 'intval')
        ];
        $input_data['addtime'] = time();

        Db::startTrans();
        try {
            if (
                false ===
                model('Coupon')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data)
            ) {
                throw new \Exception(model('Coupon')->getError());
            }

            $setmeal_name = model('Setmeal')->where('id', $input_data['bind_setmeal_id'])->value('name');

            // 日志
            $log_result = model('AdminLog')->writeLog(
                '添加系统优惠券，名称:' . $input_data['name'] . '；抵扣金额:' . $input_data['face_value'] . '；绑定套餐:' . $setmeal_name . '；有效期:' . $input_data['days'] . '天',
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
            $info = model('Coupon')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'face_value' => input('post.face_value/f', 0, 'doubleval'),
                'bind_setmeal_id' => input(
                    'post.bind_setmeal_id/d',
                    0,
                    'intval'
                ),
                'days' => input('post.days/d', 1, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            Db::startTrans();
            try {
                $info = model('Coupon')->find($id);
                if (!$info) {
                    $this->ajaxReturn(500, '要修改的优惠券不存在');
                }

                if (
                    false ===
                    model('Coupon')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data, ['id' => $id])
                ) {
                    throw new \Exception(model('Coupon')->getError());
                }

                $log_field = '修改系统优惠券，';

                if ($input_data['name'] != $info['name']) {
                    $log_field .= '名称:' . $info['name'] . '->' . $input_data['name'] . '；';
                } else {
                    $log_field .= '名称:' . $info['name'] . '；';
                }

                if ($input_data['face_value'] != $info['face_value']) {
                    $log_field .= '抵扣金额:' . $info['face_value'] . '->' . $input_data['face_value'] . '；';
                }

                if ($input_data['bind_setmeal_id'] != $info['bind_setmeal_id']) {
                    $setmeal_old = model('Setmeal')->where('id', $info['bind_setmeal_id'])->value('name');
                    $setmeal_new = model('Setmeal')->where('id', $input_data['bind_setmeal_id'])->value('name');
                    $log_field .= '绑定套餐:' . $setmeal_old . '->' . $setmeal_new . '；';
                }

                if ($input_data['days'] != $info['days']) {
                    $log_field .= '有效期:' . $info['days'] . '天->' . $input_data['days'] . '天；';
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

    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        Db::startTrans();
        try {
            $list = model('Coupon')
                ->where('id', 'in', $id)
                ->column('id,name,face_value,bind_setmeal_id,days');

            $del_result = model('Coupon')->destroy($id);
            if (false === $del_result) {
                throw new \Exception(model('Coupon')->getError());
            }

            $log_field = '删除系统优惠券，';

            foreach ($list as $coupon) {
                $setmeal_name = model('Setmeal')->where('id', $coupon['bind_setmeal_id'])->value('name');
                $log_field .= '名称:' . $coupon['name'] . '；抵扣金额:' . $coupon['face_value'] . '；绑定套餐:' . $setmeal_name . '；有效期:' . $coupon['days'] . '天，';
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
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
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }

    public function send()
    {
        $input_data = [
            'coupon_id' => input('post.coupon_id/a'),
            'setmeal_id' => input('post.setmeal_id/d', 0, 'intval'),
            'uid' => input('post.uid/a')
        ];
        if (false === model('Coupon')->send($input_data, $this->admininfo)) {
            $this->ajaxReturn(500, model('Coupon')->getError());
        }

        $coupons = model('Coupon')->where('id', 'in', $input_data['coupon_id'])->column('name');
        $coupons_name = implode('，', $coupons);

        switch ($input_data['setmeal_id']) {
            case '0':
                $check_company = '全部企业';
                break;
            case '-1':
                $check_company = '自定义企业类型';
                break;
            default:
                $check_company = model('Setmeal')->where('id', $input_data['setmeal_id'])->value('name');
                break;
        }

        model('AdminLog')->writeLog(
            '发放优惠券，优惠券名称:' . $coupons_name . '；企业:' . $check_company,
            $this->admininfo,
            0,
            1
        );

        $this->ajaxReturn(200, '发放成功');
    }

    public function log()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $count_list = model('CouponRecord')
            ->group('log_id')
            ->column('log_id,count(*)');
        $total = model('CouponLog')->count();
        $list = model('CouponLog')
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['count_num'] = isset($count_list[$value['id']])
                ? $count_list[$value['id']]
                : 0;
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function record()
    {
        $log_id = input('get.log_id/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $where['log_id'] = ['eq', $log_id];
        $total = model('CouponRecord')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company b',
                'b.uid=a.uid',
                'LEFT'
            )
            ->where($where)
            ->count();
        $list = model('CouponRecord')
            ->alias('a')
            ->field('a.*,b.companyname')
            ->join(
                config('database.prefix') . 'company b',
                'b.uid=a.uid',
                'LEFT'
            )
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
}
