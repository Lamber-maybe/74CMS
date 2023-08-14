<?php
namespace app\apiadmin\controller;

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
        if (
            false ===
            model('CompanyServicePoints')
            ->validate(true)
            ->allowField(true)
            ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('CompanyServicePoints')->getError());
        }

        model('AdminLog')->record(
            '添加' . config('global_config.points_byname') . '套餐。套餐名称【' .
            $input_data['name'] .
            '】；ID【' .
            model('CompanyServicePoints')->id .
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
            'points' => input('post.points/d', 0, 'intval'),
            'expense' => input('post.expense/f', 0, 'floatval'),
            'is_display' => input('post.is_display/d', 0, 'intval'),
            'recommend' => input('post.recommend/d', 0, 'intval'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
        ];
        if (
            false ===
            model('CompanyServicePoints')
            ->validate(true)
            ->allowField(true)
            ->save($input_data, ['id' => $input_data['id']])
        ) {
            $this->ajaxReturn(500, model('CompanyServicePoints')->getError());
        }
        model('AdminLog')->record(
            '添加' . config('global_config.points_byname') . '套餐。套餐名称【' .
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
        model('CompanyServicePoints')
            ->where('id', $id)
            ->delete();
        model('AdminLog')->record(
            '删除' . config('global_config.points_byname') . '套餐。套餐ID【' . $id . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
