<?php
namespace app\apiadmin\controller;

class WechatMenu extends \app\common\controller\Backend
{
    public function index()
    {
        $pid = input('get.pid/d', 0, 'intval');
        $list = model('WechatMenu')
            ->where('pid', $pid)
            ->order('sort_id desc,id asc')
            ->select();
        foreach ($list as $key => $value) {
            $children = model('WechatMenu')->where('pid',$value['id'])->find();
            $list[$key]['hasChildren'] = $children ? true : false;
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
    public function add()
    {
        $input_data = [
            'pid' => input('post.pid/d', 0, 'intval'),
            'title' => input('post.title/s', '', 'trim'),
            'key' => input('post.key/s', '', 'trim'),
            'type' => input('post.type/s', '', 'trim'),
            'url' => input('post.url/s', '', 'trim'),
            'pagepath' => input('post.pagepath/s', '', 'trim'),
            'sort_id' => input('post.sort_id/d', 0, 'intval')
        ];
        $result = model('WechatMenu')
            ->validate(true)
            ->allowField(true)
            ->save($input_data);
        if (false === $result) {
            $this->ajaxReturn(500, model('WechatMenu')->getError());
        }
        model('AdminLog')->record(
            '添加微信菜单。菜单ID【' .
                model('WechatMenu')->id .
                '】;菜单名称【' .
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
            $info = model('WechatMenu')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'pid' => input('post.pid/d', 0, 'intval'),
                'title' => input('post.title/s', '', 'trim'),
                'key' => input('post.key/s', '', 'trim'),
                'type' => input('post.type/s', '', 'trim'),
                'url' => input('post.url/s', '', 'trim'),
                'pagepath' => input('post.pagepath/s', '', 'trim'),
                'sort_id' => input('post.sort_id/d', 0, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            $result = model('WechatMenu')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id]);
            if (false === $result) {
                $this->ajaxReturn(500, model('WechatMenu')->getError());
            }
            model('AdminLog')->record(
                '编辑微信菜单。菜单ID【' .
                    $id .
                    '】;菜单名称【' .
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
        model('WechatMenu')
            ->where('pid', 'in', $id)
            ->delete();
        model('WechatMenu')->destroy($id);
        model('AdminLog')->record(
            '删除微信菜单。菜单ID【' . implode(',', $id) . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    public function sync()
    {
        $instance = new \app\common\lib\Wechat;
        $syncResult = $instance->menuSync();
        if($syncResult===true){
            model('AdminLog')->record(
                '同步微信菜单',
                $this->admininfo
            );
            $this->ajaxReturn(200, '同步菜单成功');
        }else{
            $this->ajaxReturn(500, $instance->getError());
        }
    }
}
