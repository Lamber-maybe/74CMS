<?php
namespace app\apiadmin\controller;

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
            $list[$key]['bind_setmeal_name'] =
                $setmeal_list[$value['bind_setmeal_id']];
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
        if (
            false ===
            model('Coupon')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('Coupon')->getError());
        }
        model('AdminLog')->record(
            '添加优惠券。优惠券ID【' .
                model('Coupon')->id .
                '】；优惠券名称【' .
                $input_data['name'] .
                '】',
            $this->admininfo
        );
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
            if (
                false ===
                model('Coupon')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('Coupon')->getError());
            }
            model('AdminLog')->record(
                '编辑优惠券。优惠券ID【' .
                    $id .
                    '】；优惠券名称【' .
                    $input_data['name'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $list = model('Coupon')
            ->where('id', 'in', $id)
            ->column('title');
        model('Coupon')->destroy($id);
        model('AdminLog')->record(
            '删除优惠券。优惠券ID【' .
                implode(',', $id) .
                '】;优惠券名称【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
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
        model('AdminLog')->record(
            '发放优惠券。优惠券ID【' .
                implode(',', $input_data['coupon_id']) .
                '】',
            $this->admininfo
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
