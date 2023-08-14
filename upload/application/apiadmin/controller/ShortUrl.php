<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/7/22
 * Time: 10:33
 */

namespace app\apiadmin\controller;


use app\common\controller\Backend;
use think\Db;

class ShortUrl extends Backend
{
    public function lists()
    {
        $page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $m = new \app\common\model\ShortUrl();
        $res = $m->getList($page, $pagesize);
        $this->ajaxReturn(200, '', $res);
    }

    public function save()
    {
        $id = input('post.id/d', 0, 'intval');
        $url = input('post.url/s', '', 'trim,htmlspecialchars');
        $remark = input('post.remark/s', '', 'trim,htmlspecialchars');
        $endtime = input('post.endtime/s', '', 'trim,htmlspecialchars');

        $m = new \app\common\model\ShortUrl();
        $res = $m->saveOrAdd($id, $url, $remark, $endtime, $this->admininfo);

        # 日志
        if (!empty($id) && !id > 0) {
            model('AdminLog')->writeLog(
                '添加短链接，源地址:' . $url . '；备注:' . (!empty($remark) ? $remark : '无') . '；过期时间:' . (!empty($endtime) ? $endtime : '永久'),
                $this->admininfo,
                0,
                2
            );
        }

        $this->ajaxReturn(200, $id > 0 ? '编辑成功' : '添加成功', $res);
    }

    public function del()
    {
        $id = input('post.id/a', []);
        $id = array_map('intval', $id);
        if (!isset($id) || empty($id)) {
            $this->ajaxReturn(500, '请选择要删除的短链接');
        }

        Db::startTrans();
        try {
            $code_arr = model('ShortUrl')->whereIn('id', $id)->column('code');
            $code_str = implode(';', $code_arr);

            // DB1：删除营销模板
            $del_result = model('ShortUrl')->whereIn('id', $id)->delete();
            if (false === $del_result) {
                throw new Exception(model('ShortUrl')->getError());
            }

            // DB2：写入日志
            $log_result = model('AdminLog')->writeLog(
                '删除短链接，短码:' . $code_str,
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();

            $this->ajaxReturn(200, '删除成功');

        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }
    }
}
