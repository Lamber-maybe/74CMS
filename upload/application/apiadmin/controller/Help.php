<?php
namespace app\apiadmin\controller;
class Help extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $is_display = input('get.is_display/s', '', 'trim');
        $cid = input('get.cid/d', 0, 'intval');
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
        if ($cid) {
            $where['cid'] = ['eq', $cid];
        }

        $total = model('Help')
            ->where($where)
            ->count();
        $list = model('Help')
            ->where($where)
            ->order('sort_id desc,id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        
        $category_arr = model('HelpCategory')->column('id,name');
        foreach ($list as $key => $value) {
            $value['link'] = url('index/help/show', [
                'id' => $value['id']
            ]);
            
            $value['cname'] = isset($category_arr[$value['cid']])
                ? $category_arr[$value['cid']]
                : '';
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
            'cid' => input('post.cid/d', 0, 'intval'),
            'content' => input('post.content/s', '', 'trim'),
            'is_display' => input('post.is_display/d', 1, 'intval'),
            'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
            'seo_description' => input('post.seo_description/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        if (
            false ===
            model('Help')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('Help')->getError());
        }
        model('AdminLog')->record(
            '添加帮助。帮助ID【' .
                model('Help')->id .
                '】;帮助标题【' .
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
            $info = model('Help')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'cid' => input('post.cid/d', 0, 'intval'),
                'content' => input('post.content/s', '', 'trim'),
                'is_display' => input('post.is_display/d', 1, 'intval'),
                'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
                'seo_description' => input(
                    'post.seo_description/s',
                    '',
                    'trim'
                ),
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if (
                false ===
                model('Help')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('Help')->getError());
            }
            model('AdminLog')->record(
                '编辑帮助。帮助ID【' .
                    $id .
                    '】;帮助标题【' .
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
        $list = model('Help')
            ->where('id', 'in', $id)
            ->column('title');
        model('Help')->destroy($id);
        model('AdminLog')->record(
            '删除帮助。帮助ID【' .
                implode(',', $id) .
                '】;帮助标题【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
