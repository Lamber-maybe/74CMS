<?php
namespace app\apiadmin\controller;

class Hotword extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 20, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['word'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    break;
            }
        }
        $total = model('Hotword')->where($where)->count();
        $list = model('Hotword')
            ->where($where)
            ->order('hot desc')
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
        $data = input('param.');
        if (!$data) {
            $this->ajaxReturn(500, '提交数据为空');
        }
        $result = model('Hotword')
            ->validate(true)
            ->allowField(true)
            ->save($data);
        if (false === $result) {
            $this->ajaxReturn(500, model('Hotword')->getError());
        }
        model('AdminLog')->record(
            '添加热门关键词。关键词ID【' .
                model('Hotword')->id .
                '】;关键词【' .
                $data['word'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('Hotword')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', $info);
        } else {
            $data = input('param.');
            if (!$data) {
                $this->ajaxReturn(500, '提交数据为空');
            }
            $id = intval($data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            $result = model('Hotword')
                ->validate(true)
                ->allowField(true)
                ->save($data, ['id' => $id]);
            if (false === $result) {
                $this->ajaxReturn(500, model('Hotword')->getError());
            }
            model('AdminLog')->record(
                '编辑热门关键词。关键词ID【' .
                    $id .
                    '】;关键词【' .
                    $data['word'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function delete()
    {
        $id = input('param.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('Hotword')->find($id);
        model('Hotword')->destroy($id);
        model('AdminLog')->record(
            '删除热门关键词。关键词ID【' .
                $id .
                '】;关键词【' .
                $info['word'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function saveAll()
    {
        $inputdata = input('post.');
        if (!$inputdata) {
            $this->ajaxReturn(500, '提交数据为空');
        }
        $sqldata = [];
        foreach ($inputdata as $key => $value) {
            if (!$value['id']) {
                continue;
            }
            $arr['id'] = $value['id'];
            $arr['hot'] = $value['hot'] == '' ? 0 : $value['hot'];
            $arr['word'] = $value['word'];
            $sqldata[] = $arr;
        }
        $validate = \think\Loader::validate('Hotword');
        foreach ($sqldata as $key => $value) {
            if (!$validate->check($value)) {
                $this->ajaxReturn(500, $validate->getError());
            }
        }
        model('Hotword')
            ->isUpdate()
            ->saveAll($sqldata);
        model('AdminLog')->record('批量保存热门关键词', $this->admininfo);
        $this->ajaxReturn(200, '保存成功');
    }
}
