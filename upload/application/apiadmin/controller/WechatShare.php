<?php
namespace app\apiadmin\controller;
class WechatShare extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $list = model('WechatShare')
            ->where($where)
            ->order('id asc')
            ->select();

        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('WechatShare')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'content' => input('post.content/s', '', 'trim'),
                'img' => input('post.img/s', '', 'trim'),
                'explain' => input('post.explain/s', '', 'trim')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if (
                false ===
                model('WechatShare')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('WechatShare')->getError());
            }
            model('AdminLog')->record(
                '编辑微信分享。页面【' .
                    $input_data['name'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
}
