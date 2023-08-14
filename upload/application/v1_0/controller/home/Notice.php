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

        /**
         * 【优化】公告 发布时间按显示时间出现
         * zch 2022.9.20
         * 【新增】
         * $where['addtime'] = ['lt',time()];
         */
        $where['addtime'] = ['lt',time()];
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

        /**
         * 【ID1000453】
         * 【优化】公告触屏端增加简历附件的显示(同资讯)
         * yx - 2022.12.16
         */
        if (isset($info['attach']) && !empty($info['attach'])) {
            $info['attach_info'] = json_decode($info['attach'], true);
        } else {
            $info['attach_info'] = [];
        }

        $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);

        /**
         * 【ID1000421】
         * 【bug】公告设置隐藏，触屏端详情下一篇还有入口
         * yx - 2022.11.11
         * [新增]:
         * ->where('is_display', '1')
         */
        $prev = model('Notice')
            ->where('id', '>', $info['id'])
            ->where('is_display', '1')
            ->order('id asc')
            ->field('id,title')
            ->find();
        $next = model('Notice')
            ->where('id', '<', $info['id'])
            ->where('is_display', '1')
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
