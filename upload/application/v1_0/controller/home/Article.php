<?php
namespace app\v1_0\controller\home;

class Article extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $where = ['is_display' => 1];
        $cid = input('get.cid/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($cid > 0) {
            $where['cid'] = ['eq', $cid];
        }
        $list = model('Article')
            ->field('id,title,thumb,link_url,click,addtime,source')
            ->where($where)
            ->page($current_page, $pagesize)
            ->order('sort_id desc,id desc')
            ->select();
        $thumb_id_arr = $thumb_arr = [];
        foreach ($list as $key => $value) {
            $value['thumb'] > 0 && ($thumb_id_arr[] = $value['thumb']);
        }
        if (!empty($thumb_id_arr)) {
            $thumb_arr = model('Uploadfile')->getFileUrlBatch($thumb_id_arr);
        }
        $return['items'] = [];
        foreach ($list as $key => $value) {
            $arr = $value->toArray();
            $arr['thumb'] = isset($thumb_arr[$arr['thumb']])
                ? $thumb_arr[$arr['thumb']]
                : default_empty('thumb');
            $arr['source_text'] = $arr['source'] == 1 ? '转载' : '原创';
            $return['items'][] = $arr;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function category()
    {
        $list = model('ArticleCategory')
            ->field('sort_id,is_sys', true)
            ->order('sort_id desc,id asc')
            ->select();
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }
    public function show()
    {
        $id = input('get.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择');
        }
        $info = model('Article')
            ->field('is_display,link_url', true)
            ->where('id', $id)
            ->find();
        if ($info === null) {
            $this->ajaxReturn(500, '没有找到资讯');
        }
        $info->click++;
        $info->save();
        $info = $info->toArray();
        $info['thumb'] =
            $info['thumb'] > 0
                ? model('Uploadfile')->getFileUrl($info['thumb'])
                : default_empty('thumb');
        $info['source_text'] = $info['source'] == 1 ? '转载' : '原创';
        $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);
        $prev = model('Article')
            ->where('id', '>', $info['id'])
            ->where('is_display',1)
            ->order('id asc')
            ->field('id,title')
            ->find();
        $next = model('Article')
            ->where('id', '<', $info['id'])
            ->where('is_display',1)
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
        $info = model('Article')
            ->where('id', 'eq', $id)
            ->field('id,click')
            ->find();
        if ($info !== null) {

            //新增资讯随机阅读量添加
            $rand_click_article = empty(config('global_config.rand_click_article')) ? 0 :intval(config('global_config.rand_click_article'));
            $num = $rand_click_article>0 ? rand(1,$rand_click_article) : 1;

            $info->click = $info->click+$num;
            $info->save();
            $click = $info['click'];
        }else{
            $click = 0;
        }
        $this->ajaxReturn(200, '数据添加成功',$click);
    }
}
