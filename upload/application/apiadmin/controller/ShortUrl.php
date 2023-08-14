<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/7/22
 * Time: 10:33
 */

namespace app\apiadmin\controller;


class ShortUrl extends \app\common\controller\Backend
{
    public function lists(){
        $page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $m = new \app\common\model\ShortUrl();
        $res = $m->getList($page, $pagesize);
        $this->ajaxReturn(200, '', $res);
    }

    public function save(){
        $id = input('post.id/d', 0, 'intval');
        $url = input('post.url/s', '', 'trim,htmlspecialchars');
        $remark = input('post.remark/s', '', 'trim,htmlspecialchars');
        $endtime = input('post.endtime/s', '', 'trim,htmlspecialchars');

        $m = new \app\common\model\ShortUrl();
        $res = $m->saveOrAdd($id, $url, $remark, $endtime, $this->admininfo);
        $this->ajaxReturn(200, $id>0? '编辑成功': '添加成功', $res);
    }

    public function del(){
        $id = input('post.id/a', []);
        $id = array_map('intval', $id);
        $m = new \app\common\model\ShortUrl();
        if(!empty($id)){
            $m->where(['id'=>['in', $id]])->delete();
            model('AdminLog')->record(sprintf('删除短链。短链ID【%s】', implode(',', $id) ), $this->admininfo);
        }
        $this->ajaxReturn(200, '删除成功');
    }
}
