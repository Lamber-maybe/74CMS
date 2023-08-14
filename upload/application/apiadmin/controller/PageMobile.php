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
            if (
                false ===
                model('PageMobile')
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('PageMobile')->getError());
            }
            model('AdminLog')->record('修改触屏页面管理。页面ID【' .$id .'】',$this->admininfo);
            $this->ajaxReturn(200, '保存成功');
        }
    }
}
