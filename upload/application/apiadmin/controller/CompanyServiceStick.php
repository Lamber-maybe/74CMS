<?php
namespace app\apiadmin\controller;

class CompanyServiceStick extends \app\common\controller\Backend
{
    public function index()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $total = model('CompanyServiceStick')->count();
        $list = model('CompanyServiceStick')
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
            'days' => input('post.days/d', 0, 'intval'),
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
        if (
            false ===
            model('CompanyServiceStick')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('CompanyServiceStick')->getError());
        }

        model('AdminLog')->record(
            '添加增值服务【职位置顶】。服务名称【' .
                $input_data['name'] .
                '】；ID【' .
                model('CompanyServiceStick')->id .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'name' => input('post.name/s', '', 'trim'),
            'days' => input('post.days/d', 0, 'intval'),
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
        if (
            false ===
            model('CompanyServiceStick')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $input_data['id']])
        ) {
            $this->ajaxReturn(500, model('CompanyServiceStick')->getError());
        }
        model('AdminLog')->record(
            '编辑增值服务【职位置顶】。服务名称【' .
                $input_data['name'] .
                '】；ID【' .
                $input_data['id'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('CompanyServiceStick')
            ->where('id', $id)
            ->delete();
        model('AdminLog')->record(
            '删除增值服务【职位置顶】。服务ID【' . $id . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
