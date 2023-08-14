<?php
namespace app\v1_0\controller\home;

class Notice extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $where = ['is_display' => 1];
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $list = model('Notice')
            ->field('id,title,link_url,click,addtime')
            ->where($where)
            ->page($current_page, $pagesize)
            ->order('sort_id desc,id desc')
            ->select();
        $return['items'] = $list;

        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function show()
    {
        $id = input('get.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择');
        }
        $info = model('Notice')
            ->field('is_display,link_url', true)
            ->where('id', $id)
            ->find();
        if ($info === null) {
            $this->ajaxReturn(500, '没有找到公告');
        }
        $info->click++;
        $info->save();
        $info = $info->toArray();
        $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);
        $prev = model('Notice')
            ->where('id', '>', $info['id'])
            ->order('id asc')
            ->field('id,title')
            ->find();
        $next = model('Notice')
            ->where('id', '<', $info['id'])
            ->order('id desc')
            ->field('id,title')
            ->find();

        $this->ajaxReturn(200, '获取数据成功', [
            'info' => $info,
            'prev' => $prev,
            'next' => $next
        ]);
    }
    public function click(){
        $id = input('post.id/d',0,'intval');
        $info = model('Notice')
            ->where('id', 'eq', $id)
            ->field('id,click')
            ->find();
        if ($info !== null) {
            $info->click = $info->click+1;
            $info->save();
            $click = $info['click'];
        }else{
            $click = 0;
        }
        $this->ajaxReturn(200, '数据添加成功',$click);
    }
}
