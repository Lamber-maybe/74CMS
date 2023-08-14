<?php
namespace app\apiadmin\controller;
class Hrtool extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $cid = input('get.cid/d', 0, 'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['filename'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['id'] = ['eq', $keyword];
                    break;
                default:
                    break;
            }
        }
        if ($cid) {
            $where['cid'] = ['eq', $cid];
        }

        $total = model('Hrtool')
            ->where($where)
            ->count();
        $list = model('Hrtool')
            ->where($where)
            ->order('sort_id desc,id asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        
        $category_arr = model('HrtoolCategory')->getCache();
        foreach ($list as $key => $value) {
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
            'filename' => input('post.filename/s', '', 'trim'),
            'cid' => input('post.cid/d', 0, 'intval'),
            'fileurl' => input('post.fileurl/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        $input_data['addtime'] = time();
        if (
            false ===
            model('Hrtool')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('Hrtool')->getError());
        }
        model('AdminLog')->record(
            '添加hr工具箱。hr工具箱ID【' .
                model('Hrtool')->id .
                '】;hr工具箱文件名【' .
                $input_data['filename'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('Hrtool')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'filename' => input('post.filename/s', '', 'trim'),
                'cid' => input('post.cid/d', 0, 'intval'),
                'fileurl' => input('post.fileurl/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if (
                false ===
                model('Hrtool')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('Hrtool')->getError());
            }
            model('AdminLog')->record(
                '编辑hr工具箱。hr工具箱ID【' .
                    $id .
                    '】;hr工具箱文件名【' .
                    $input_data['filename'] .
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
        $list = model('Hrtool')
            ->where('id', 'in', $id)
            ->column('filename');
        model('Hrtool')->destroy($id);
        model('AdminLog')->record(
            '删除hr工具箱。hr工具箱ID【' .
                implode(',', $id) .
                '】;hr工具箱标题【' .
                implode(',', $list) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function upload()
    {
        $file = input('file.file');
        if (!$file) {
            $this->ajaxReturn(500, '请选择文件');
        }
        $filemanager = new \app\common\lib\FileManager();
        $result = $filemanager->uploadReturnPath($file);
        if (false !== $result) {
            $this->ajaxReturn(200, '上传成功', $result);
        } else {
            $this->ajaxReturn(500, $filemanager->getError());
        }
    }
}
