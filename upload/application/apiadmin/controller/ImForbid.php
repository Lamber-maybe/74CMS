<?php
namespace app\apiadmin\controller;

class ImForbid extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];        
        $where['disable_im'] = 1;
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 20, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['username'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    break;
            }
        }
        $field = 'm.utype,m.username,m.status,m.disable_im,f.*';
        $total = model('ImForbid')->alias('f')->join(config('database.prefix').'member m','m.uid=f.uid','LEFT')->where($where)->count();
        $list = model('ImForbid')
            ->alias('f')
            ->join(config('database.prefix').'member m','m.uid=f.uid','LEFT') 
            ->field($field)   
            ->where($where)
            ->order('addtime desc')
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
