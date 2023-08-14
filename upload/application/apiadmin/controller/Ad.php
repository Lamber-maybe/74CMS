<?php
namespace app\apiadmin\controller;

class Ad extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $platform = input('get.platform/s', '', 'trim');
        $settr = input('get.settr/s', '', 'trim');
        $is_display = input('get.is_display/s', '', 'trim');
        $cid = input('get.cid/d', 0, 'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['a.title'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['a.id'] = ['eq', intval($keyword)];
                    break;
                default:
                    break;
            }
        }
        if ($is_display != '') {
            $where['a.is_display'] = ['eq', intval($is_display)];
        }
        if ($platform!='') {
            $where['b.platform'] = ['eq', $platform];
        }
        if ($cid>0) {
            $where['a.cid'] = ['eq', $cid];
        }
        if ($settr == '0') {
            $where['a.deadline'] = [['neq', 0], ['lt', time()]];
        } elseif ($settr > 0) {
            //后台广告位到期筛选，逻辑混乱，数据不对 tapd:201
            if ($settr == 1)
            {
                $where['a.deadline'] = [['neq', 0], ['lt', time()]];
            }else
            {
                $where['a.deadline'] = [
                    ['neq', 0],
                    ['elt', strtotime('+' . $settr . ' day')],
                    ['gt', time()]
                ];
            }
        }

        $total = model('Ad')->alias('a')->join(config('database.prefix').'ad_category b','a.cid=b.id','LEFT')
            ->where($where)
            ->count();
        $list = model('Ad')->alias('a')->field('a.*')->join(config('database.prefix').'ad_category b','a.cid=b.id','LEFT')
            ->where($where)
            ->order('a.id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $image_id_arr = $image_list = [];
        foreach ($list as $key => $value) {
            $value['imageid'] && ($image_id_arr[] = $value['imageid']);
        }
        if (!empty($image_id_arr)) {
            $image_list = model('Uploadfile')->getFileUrlBatch($image_id_arr);
        }
        $category_arr = model('AdCategory')->getCache();
        foreach ($list as $key => $value) {
            $value['imageurl'] = isset($image_list[$value['imageid']])
                ? $image_list[$value['imageid']]
                : $value['imageurl'];
            $value['cname'] = isset($category_arr[$value['cid']]['name'])
                ? $category_arr[$value['cid']]['name']
                : '';
            $value['platform'] =
                isset($category_arr[$value['cid']]['platform']) &&
                isset(
                    model('BaseModel')->map_ad_platform[
                        $category_arr[$value['cid']]['platform']
                    ]
                )
                    ? model('BaseModel')->map_ad_platform[
                        $category_arr[$value['cid']]['platform']
                    ]
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
            'cid' => input('post.cid/a', []),
            'imageid' => input('post.imageid/d', 0, 'intval'),
            'imageurl' => input('post.imageurl/s', '', 'trim'),
            'explain' => input('post.explain/s', '', 'trim'),
            'starttime' => input('post.starttime/s', '', 'trim'),
            'deadline' => input('post.deadline/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
            'target' => input('post.target/d', 0, 'intval'),
            'link_url' => input('post.link_url/s', '', 'trim'),
            'inner_link' => input('post.inner_link/s', '', 'trim'),
            'inner_link_params' => input(
                'post.inner_link_params/d',
                0,
                'intval'
            ),
            'company_id' => input('post.company_id/d', 0, 'intval'),
            'is_display' => input('post.is_display/d', 1, 'intval')
        ];
        if ($input_data['starttime']) {
            $input_data['starttime'] = strtotime($input_data['starttime']);
        }
        if ($input_data['deadline']) {
            $input_data['deadline'] = strtotime($input_data['deadline']);
        } else {
            $input_data['deadline'] = 0;
        }
        if ($input_data['target'] == 0) {
            $input_data['inner_link'] = '';
            $input_data['inner_link_params'] = 0;
            $input_data['company_id'] = 0;
        } elseif ($input_data['target'] == 1) {
            $input_data['link_url'] = '';
            $input_data['company_id'] = 0;
        } elseif ($input_data['target'] == 2) {
            $input_data['link_url'] = '';
            $input_data['inner_link'] = '';
            $input_data['inner_link_params'] = 0;
        }
        $cid_arr = $input_data['cid'];
        $input_data['cid'] = $cid_arr[1];
        if (
            false ===
            model('Ad')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('Ad')->getError());
        }
        model('AdminLog')->record(
            '添加广告。广告ID【' .
                model('Ad')->id .
                '】;广告标题【' .
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
            $info = model('Ad')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info = $info->toArray();
            $ad_category = model('AdCategory')
                ->where('id', $info['cid'])
                ->find();
            $info['cid'] = [$ad_category['platform'], $info['cid']];
            $imageSrc = model('Uploadfile')->getFileUrl($info['imageid']);
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'imageSrc' => $imageSrc
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'cid' => input('post.cid/a', []),
                'imageid' => input('post.imageid/d', 0, 'intval'),
                'imageurl' => input('post.imageurl/s', '', 'trim'),
                'explain' => input('post.explain/s', '', 'trim'),
                'starttime' => input('post.starttime/s', '', 'trim'),
                'deadline' => input('post.deadline/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval'),
                'target' => input('post.target/d', 0, 'intval'),
                'link_url' => input('post.link_url/s', '', 'trim'),
                'inner_link' => input('post.inner_link/s', '', 'trim'),
                'inner_link_params' => input(
                    'post.inner_link_params/d',
                    0,
                    'intval'
                ),
                'company_id' => input('post.company_id/d', 0, 'intval'),
                'is_display' => input('post.is_display/d', 1, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if ($input_data['starttime']) {
                $input_data['starttime'] = strtotime($input_data['starttime']);
            }
            if ($input_data['deadline']) {
                $input_data['deadline'] = strtotime($input_data['deadline']);
            } else {
                $input_data['deadline'] = 0;
            }
            if ($input_data['target'] == 0) {
                $input_data['inner_link'] = '';
                $input_data['inner_link_params'] = 0;
                $input_data['company_id'] = 0;
            } elseif ($input_data['target'] == 1) {
                $input_data['link_url'] = '';
                $input_data['company_id'] = 0;
            } elseif ($input_data['target'] == 2) {
                $input_data['link_url'] = '';
                $input_data['inner_link'] = '';
                $input_data['inner_link_params'] = 0;
            }
            $cid_arr = $input_data['cid'];
            $input_data['cid'] = $cid_arr[1];
            if (
                false ===
                model('Ad')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('Ad')->getError());
            }
            model('AdminLog')->record(
                '编辑广告。广告ID【' .
                    $id .
                    '】;广告标题【' .
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
        $list = model('Ad')
            ->where('id', 'in', $id)
            ->column('title');
        model('Ad')->destroy($id);
        model('AdminLog')->record(
            '删除广告。广告ID【' .
                implode(',', $id) .
                '】;广告标题【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function innerLinkOptions()
    {
        $list = model('Ad')->innerLinks;
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
}
