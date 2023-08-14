<?php
namespace app\apiadmin\controller;

class Explain extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $is_display = input('get.is_display/s', '', 'trim');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['title'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['id'] = ['eq', $keyword];
                    break;
                default:
                    break;
            }
        }
        if ($is_display != '') {
            $where['is_display'] = ['eq', intval($is_display)];
        }

        $total = model('Explain')
            ->where($where)
            ->count();
        $list = model('Explain')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();

        foreach ($list as $key => $value) {
            if ($value['link_url'] == '') {
                $value['link'] = url('index/explain/show', [
                    'id' => $value['id']
                ]);
            } else {
                $value['link'] = $value['link_url'];
            }
            $list[$key] = $value;
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
            'title' => input('post.title/s', '', 'trim'),
            'content' => input('post.content/s', '', 'trim'),
            'attach' => input('post.attach/a', []),
            'is_display' => input('post.is_display/d', 1, 'intval'),
            'link_url' => input('post.link_url/s', '', 'trim'),
            'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
            'seo_description' => input('post.seo_description/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        $input_data['attach'] = json_encode($input_data['attach'],JSON_UNESCAPED_UNICODE);
        if (
            false ===
            model('Explain')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('Explain')->getError());
        }
        model('AdminLog')->record(
            '添加说明页。说明页ID【' .
                model('Explain')->id .
                '】;说明页标题【' .
                $input_data['title'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('Explain')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);
            $info['attach'] = json_decode($info['attach'],true);
            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'content' => input('post.content/s', '', 'trim'),
                'attach' => input('post.attach/a', []),
                'is_display' => input('post.is_display/d', 1, 'intval'),
                'link_url' => input('post.link_url/s', '', 'trim'),
                'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
                'seo_description' => input(
                    'post.seo_description/s',
                    '',
                    'trim'
                ),
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $input_data['attach'] = json_encode($input_data['attach'],JSON_UNESCAPED_UNICODE);
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if (
                false ===
                model('Explain')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('Explain')->getError());
            }
            model('AdminLog')->record(
                '编辑说明页。说明页ID【' .
                    $id .
                    '】;说明页标题【' .
                    $input_data['title'] .
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
        $list = model('Explain')
            ->where('id', 'in', $id)
            ->column('title');
        model('Explain')->destroy($id);
        model('AdminLog')->record(
            '删除说明页。说明页ID【' .
                implode(',', $id) .
                '】;说明页标题【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
