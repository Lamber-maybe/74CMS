<?php

namespace app\apiadmin\controller;

class Poster extends \app\common\controller\Backend
{
    public function lists()
    {
        $list = model('Poster')->getList(input('get.type/d', 1, 'intval'));
        $this->ajaxReturn(200, '获取数据成功', $list);
    }

    public function add()
    {
        $input_data = [
            'type' => input('post.type/d', 1, 'intval'),
            'name' => input('post.name/s', '', 'trim'),
            'img' => input('post.img/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
            'is_display' => input('post.is_display/d', 1, 'intval')
        ];
        if (
            false === model('Poster')->addOne($input_data)
        ) {
            $this->ajaxReturn(500, model('Poster')->getError());
        }
        model('AdminLog')->record(
            '添加海报。海报ID【' .
            model('Poster')->id .
            '】;海报名称【' .
            $input_data['name'] .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }

    public function edit()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'type' => input('post.type/d', 1, 'intval'),
            'name' => input('post.name/s', '', 'trim'),
            'img' => input('post.img/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval'),
            'is_display' => input('post.is_display/d', 1, 'intval')
        ];
        if (
            false === model('Poster')->editOne($input_data)
        ) {
            $this->ajaxReturn(500, model('Poster')->getError());
        }
        model('AdminLog')->record(
            '编辑海报。海报ID【' .
            $input_data['id'] .
            '】;海报名称【' .
            $input_data['name'] .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }

    public function setDisplay()
    {
        $id = input('post.id/d', 0, 'intval');
        $info = model('Poster')->find($id);
        if ($info['is_display'] == 1) {
            model('Poster')->save(['is_display' => 0], ['id' => $id]);
        } else {
            model('Poster')->save(['is_display' => 1], ['id' => $id]);
        }
        model('AdminLog')->record(
            '编辑海报显示状态。海报ID【' .
            $id .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '设置成功');
    }

    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        model('Poster')->deleteOne($id);
        model('AdminLog')->record(
            '删除海报。海报ID【' . $id .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }

    public function index()
    {
        $type = input('get.type/s', 'job', 'trim');
        $id = input('get.id/d', 0, 'intval');
        $index = input('get.index/d', 0, 'intval');
        $result = false;
        $poster = new \app\common\lib\Poster;
        if ($type == 'job') {
            $result = $poster->makeJobPoster($index, $id);
        }
        if ($type == 'resume') {
            $result = $poster->makeResumePoster($index, $id);
        }
        if ($type == 'company') {
            $result = $poster->makeCompanyPoster($index, $id);
        }
        if ($result === false) {
            $this->ajaxReturn(500, $poster->getError());
        }
        $this->ajaxReturn(200, '生成海报成功', $result);
    }

    public function download()
    {
        $type = input('get.type/s', 'job', 'trim');
        $id = input('get.id/d', 0, 'intval');
        $index = input('get.index/d', 0, 'intval');
        $result = false;
        switch ($type) {
            case 'job':
                $info = model('Job')->where('id', $id)->find();
                break;
            case 'company':
                $info = model('Company')->where('id', $id)->find();
                break;
            case 'resume':
                $info = model('Resume')->where('id', $id)->find();
                break;
        }
        $filename = $id . '_' . $info['updatetime'] . '_' . $index . '.jpg';
        $show_path = SYS_UPLOAD_PATH . 'poster/' . $type . '/' . ($id % 10) . '/' . $filename;
        $save_name = '';
        switch ($type) {
            case 'job':
                $save_name = '职位-' . $id . '-' . $index . '.jpg';
                break;
            case 'company':
                $save_name = '企业-' . $id . '-' . $index . '.jpg';
                break;
            case 'resume':
                $save_name = '简历-' . $id . '-' . $index . '.jpg';
                break;
        }
        header('Content-Disposition:attachment;filename=' . $save_name);
        header('Content-Length:' . filesize($show_path));
        readfile($show_path);
    }

    public function getTplindexList()
    {
        $type = input('get.type/d', 1, 'intval');
        $list = model('Poster')
            ->where('type', $type)
            ->where('is_display', 1)
            ->order('sort_id DESC, indexid ASC')
            ->column('indexid');
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
}
