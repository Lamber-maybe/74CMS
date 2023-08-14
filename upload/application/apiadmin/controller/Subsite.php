<?php
namespace app\apiadmin\controller;

class Subsite extends \app\common\controller\Backend
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
            $where['a.deadline'] = [
                ['neq', 0],
                ['elt', strtotime('+' . $settr . ' day')],
                ['gt', time()]
            ];
        }

        $total = model('Subsite')->where($where)->count();
        $list = model('Subsite')->where($where)->order('sort_id desc,id asc')->page($current_page . ',' . $pagesize)->select();
        
        $category_district_data = model('CategoryDistrict')->getCache();
        $tpl_list = model('Tpl')->where('type','index')->column('alias,title');
        foreach ($list as $key => $value) {
            $list[$key]['district_text'] = isset($category_district_data[$value['district1']]) ? $category_district_data[$value['district1']] : '';
            if($list[$key]['district_text']!='' && $value['district2']>0){
                $list[$key]['district_text'] .= isset(
                    $category_district_data[$value['district2']]
                )
                    ? ' / '.$category_district_data[$value['district2']]
                    : '';
            }
            if($list[$key]['district_text']!='' && $value['district3']>0){
                $list[$key]['district_text'] .= isset(
                    $category_district_data[$value['district3']]
                )
                    ? ' / '.$category_district_data[$value['district3']]
                    : '';
            }
            $list[$key]['tpl'] = isset($tpl_list[$value['tpl']])?$tpl_list[$value['tpl']]:'';
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
            'sitename' => input('post.sitename/s', '', 'trim'),
            'district1' => input('post.district1/d', 0, 'intval'),
            'district2' => input('post.district2/d', 0, 'intval'),
            'district3' => input('post.district3/d', 0, 'intval'),
            'title' => input('post.title/s', '', 'trim'),
            'keywords' => input('post.keywords/s', '', 'trim'),
            'description' => input('post.description/s', '', 'trim'),
            'tpl' => input('post.tpl/s', '', 'trim'),
            'is_display' => input('post.is_display/d', 1, 'intval'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        $input_data['district'] = $input_data['district3'] > 0 ? $input_data['district3'] : ($input_data['district2'] > 0 ? $input_data['district2'] : $input_data['district1']);
        if (
            false ===
            model('Subsite')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('Subsite')->getError());
        }
        model('AdminLog')->record(
            '添加分站。分站ID【' .
                model('Subsite')->id .
                '】;分站名称【' .
                $input_data['sitename'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('Subsite')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info = $info->toArray();
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'sitename' => input('post.sitename/s', '', 'trim'),
                'district1' => input('post.district1/d', 0, 'intval'),
                'district2' => input('post.district2/d', 0, 'intval'),
                'district3' => input('post.district3/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'keywords' => input('post.keywords/s', '', 'trim'),
                'description' => input('post.description/s', '', 'trim'),
                'tpl' => input('post.tpl/s', '', 'trim'),
                'is_display' => input('post.is_display/d', 1, 'intval'),
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $input_data['district'] = $input_data['district3'] > 0 ? $input_data['district3'] : ($input_data['district2'] > 0 ? $input_data['district2'] : $input_data['district1']);
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if (
                false ===
                model('Subsite')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('Subsite')->getError());
            }
            model('AdminLog')->record(
                '编辑分站。分站ID【' .
                    $id .
                    '】;分站名称【' .
                    $input_data['sitename'] .
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
        $list = model('Subsite')
            ->where('id', 'in', $id)
            ->column('sitename');
        model('Subsite')->destroy($id);
        model('AdminLog')->record(
            '删除分站。分站ID【' .
                implode(',', $id) .
                '】;分站标题【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
