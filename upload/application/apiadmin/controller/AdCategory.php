<?php
namespace app\apiadmin\controller;

class AdCategory extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $total = model('AdCategory')
            ->where($where)
            ->count();
        $list = model('AdCategory')
            ->where($where)
            ->order('id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['platform'] = model('BaseModel')->map_ad_platform[
                $value['platform']
            ];
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
            'alias' => input('post.alias/s', '', 'trim'),
            'ad_num' => input('post.ad_num/d', 0, 'intval'),
            'platform' => input('post.platform/s', '', 'trim'),
            'height' => input('post.height/d', 0, 'intval'),
            'width' => input('post.width/d', 0, 'intval'),
        ];
        if (
            false ===
            model('AdCategory')
            ->validate(true)
            ->allowField(true)
            ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('AdCategory')->getError());
        }
        model('AdminLog')->record(
            '添加广告位。广告位ID【' .
            model('AdCategory')->id .
            '】;广告位名称【' .
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
            $info = model('AdCategory')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'alias' => input('post.alias/s', '', 'trim'),
                'ad_num' => input('post.ad_num/d', 0, 'intval'),
                'platform' => input('post.platform/s', '', 'trim'),
                'height' => input('post.height/d', 0, 'intval'),
                'width' => input('post.width/d', 0, 'intval'),
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if (
                false ===
                model('AdCategory')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('AdCategory')->getError());
            }
            model('AdminLog')->record(
                '编辑广告位。广告位ID【' .
                $id .
                '】;广告位名称【' .
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
        $list = model('AdCategory')
            ->where('id', 'in', $id)
            ->column('name');
        model('AdCategory')->destroy($id);
        model('AdminLog')->record(
            '删除广告位。广告位ID【' .
            implode(',', $id) .
            '】;广告位名称【' .
            implode(',', $list) .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
