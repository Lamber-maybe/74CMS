<?php
namespace app\apiadmin\controller;
class Article extends \app\common\controller\Backend
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

        $total = model('Article')
            ->where($where)
            ->count();
        $list = model('Article')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $image_id_arr = $image_list = [];
        foreach ($list as $key => $value) {
            $value['thumb'] && ($image_id_arr[] = $value['thumb']);
        }
        if (!empty($image_id_arr)) {
            $image_list = model('Uploadfile')->getFileUrlBatch($image_id_arr);
        }
        $category_arr = model('ArticleCategory')->getCache();
        foreach ($list as $key => $value) {
            $value['thumb'] = isset($image_list[$value['thumb']])
                ? $image_list[$value['thumb']]
                : '';
            $value['cname'] = isset($category_arr[$value['cid']])
                ? $category_arr[$value['cid']]
                : '';
            if ($value['link_url'] == '') {
                $value['link'] = url('index/article/show', [
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
            'cid' => input('post.cid/d', 0, 'intval'),
            'content' => input('post.content/s', '', 'trim'),
            'attach' => input('post.attach/a', []),
            'thumb' => input('post.thumb/d', 0, 'intval'),
            'is_display' => input('post.is_display/d', 1, 'intval'),
            'link_url' => input('post.link_url/s', '', 'trim'),
            'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
            'seo_description' => input('post.seo_description/s', '', 'trim'),
            'addtime' => input('post.addtime/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
            'source' => input('post.source/d', 0, 'intval')
        ];
        if ($input_data['addtime']) {
            $input_data['addtime'] = strtotime($input_data['addtime']);
        } else {
            $input_data['addtime'] = time();
        }
        $input_data['attach'] = json_encode($input_data['attach'],JSON_UNESCAPED_UNICODE);
        if (
            false ===
            model('Article')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('Article')->getError());
        }
        model('AdminLog')->record(
            '添加资讯。资讯ID【' .
                model('Article')->id .
                '】;资讯标题【' .
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
            $info = model('Article')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);
            $info['attach'] = json_decode($info['attach'],true);
            $imageUrl = model('Uploadfile')->getFileUrl($info['thumb']);
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'imageUrl' => $imageUrl
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'cid' => input('post.cid/d', 0, 'intval'),
                'content' => input('post.content/s', '', 'trim'),
                'attach' => input('post.attach/a', []),
                'thumb' => input('post.thumb/d', 0, 'intval'),
                'is_display' => input('post.is_display/d', 1, 'intval'),
                'link_url' => input('post.link_url/s', '', 'trim'),
                'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
                'seo_description' => input(
                    'post.seo_description/s',
                    '',
                    'trim'
                ),
                'addtime' => input('post.addtime/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval'),
                'source' => input('post.source/d', 0, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if ($input_data['addtime']) {
                $input_data['addtime'] = strtotime($input_data['addtime']);
            } else {
                $input_data['addtime'] = time();
            }
            $input_data['attach'] = json_encode($input_data['attach'],JSON_UNESCAPED_UNICODE);
            if (
                false ===
                model('Article')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('Article')->getError());
            }
            model('AdminLog')->record(
                '编辑资讯。资讯ID【' .
                    $id .
                    '】;资讯标题【' .
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
        $list = model('Article')
            ->where('id', 'in', $id)
            ->column('title');
        model('Article')->destroy($id);
        model('AdminLog')->record(
            '删除资讯。资讯ID【' .
                implode(',', $id) .
                '】;资讯标题【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
