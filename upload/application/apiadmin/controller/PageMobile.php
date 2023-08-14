<?php
namespace app\apiadmin\controller;

class PageMobile extends \app\common\controller\Backend
{
    public function index()
    {
        $list = model('PageMobile')
            ->order('id asc')
            ->select();
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('PageMobile')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info = $info->toArray();
            $info['params'] = json_decode($info['params'],1);
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'expire' => input('post.expire/d', 0, 'intval'),
                'seo_title' => input('post.seo_title/s', '', 'trim'),
                'seo_keywords' => input('post.seo_keywords/s', '', 'trim'),
                'seo_description' => input('post.seo_description/s', '', 'trim')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }

            $info = model('PageMobile')->find($id);
            if (null === $info) {
                $this->ajaxReturn(500, '要修改的页面不存在');
            }

            if (
                false ===
                model('PageMobile')
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('PageMobile')->getError());
            }

            $log_field = '触屏端页面管理修改页面信息，页面名称:' . $info['name'];
            model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                3
            );

            $this->ajaxReturn(200, '保存成功');
        }
    }
}
