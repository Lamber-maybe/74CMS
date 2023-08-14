<?php
namespace app\apiadmin\controller;

class Badword extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword) {
            $where['name'] = ['like', '%' . $keyword . '%'];
        }

        $total = model('Badword')
            ->where($where)
            ->count();
        $list = model('Badword')
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
    public function add()
    {
        $input_data = [
            'name' => input('post.name/s', '', 'trim'),
            'replace_text' => input('post.replace_text/s', '', 'trim')
        ];
        $check = model('Badword')->where('name',$input_data['name'])->find();
        if($check!==null){
            $this->ajaxReturn(500, '敏感词已存在');
        }
        if (
            false ===
            model('Badword')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('Badword')->getError());
        }
        model('AdminLog')->record(
            '添加敏感词。敏感词ID【' .
                model('Badword')->id .
                '】;敏感词【' .
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
            $info = model('Badword')->find($id);
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
                'name' => input('post.name/s', '', 'trim'),
                'replace_text' => input('post.replace_text/s', '', 'trim')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            $check = model('Badword')->where('name',$input_data['name'])->where('id','<>',$id)->find();
            if($check!==null){
                $this->ajaxReturn(500, '敏感词已存在');
            }
            unset($input_data['id']);
            if (
                false ===
                model('Badword')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('Badword')->getError());
            }
            model('AdminLog')->record(
                '编辑敏感词。敏感词ID【' .
                    $id .
                    '】;敏感词【' .
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
        $list = model('Badword')
            ->where('id', 'in', $id)
            ->column('name');
        model('Badword')->destroy($id);
        model('AdminLog')->record(
            '删除敏感词。敏感词ID【' .
                implode(',', $id) .
                '】;敏感词【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function import()
    {
        $input_data = input('post.');
        if(empty($input_data)){
            $this->ajaxReturn(500, '请上传txt文档');
        }
        $name_arr = [];
        foreach ($input_data as $key => $value) {
            if(isset($value['name'])){
                $name_arr[] = $value['name'];
            }
            
            $input_data[$key]['name'] = isset($value['name'])?cut_str($value['name'],15):'';
            $input_data[$key]['replace_text'] = isset($value['replace_text'])?cut_str($value['replace_text'],15):'';
        }
        if(!empty($name_arr)){
            model('Badword')->where('name','in',$name_arr)->delete();
        }
        if (
            false ===
            model('Badword')->saveAll($input_data)
        ) {
            $this->ajaxReturn(500, model('Badword')->getError());
        }
        model('AdminLog')->record(
            '导入敏感词'.count($input_data).'条',
            $this->admininfo
        );
        $this->ajaxReturn(200, '导入成功');
    }
}
