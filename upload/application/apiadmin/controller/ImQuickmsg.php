<?php
namespace app\apiadmin\controller;

class ImQuickmsg extends \app\common\controller\Backend
{
    public function index()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $total = model('ImQuickmsg')->count();
        $list = model('ImQuickmsg')
            ->order('sort_id desc')
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
            'content' => input('post.content/s', '', 'trim'),
            'utype' => input('post.utype/d', 1, 'intval'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        if (
            false ===
            model('ImQuickmsg')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('ImQuickmsg')->getError());
        }
        model('AdminLog')->record(
            '添加即时通讯快捷语。快捷语ID【' .
                model('ImQuickmsg')->id .
                '】;快捷语内容【' .
                $input_data['content'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'content' => input('post.content/s', '', 'trim'),
            'utype' => input('post.utype/d', 1, 'intval'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        $id = intval($input_data['id']);
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        if (
            false ===
            model('ImQuickmsg')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id])
        ) {
            $this->ajaxReturn(500, model('ImQuickmsg')->getError());
        }
        model('AdminLog')->record(
            '编辑即时通讯快捷语。快捷语ID【' .
                $id .
                '】;快捷语内容【' .
                $input_data['content'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id == 0) {
            $this->ajaxReturn(500, '请选择数据');
        }

        $info = model('ImQuickmsg')
            ->where('id', $id)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info->delete();
        model('AdminLog')->record(
            '删除即时通讯快捷语。快捷语ID【' .
                $id .
                '】;快捷语内容【' .
                $info['content'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
