<?php

namespace app\apiadmin\controller;

use app\common\controller\Backend;

class Custom extends Backend
{
    public function index()
    {
        if (request()->isGet()) {
            $name = input('get.name/s', '', 'trim');
            if (empty($name))
            {
                $this->ajaxReturn(500, '缺少配置名称');
            }
            $view = model('CustomList')
                ->where([
                    'name' => $name,
                    'admin_id' => $this->admininfo->id
                ])
                ->value('value');

            if (empty($view))
            {
                $view = '';
            }
            $this->ajaxReturn(200, '获取数据成功', $view);
        } else {
            $data_input = [
                'name' => input('post.name/s', '', 'trim'),
                'value' => input('post.value/s', '', null),
                'admin_id' => $this->admininfo->id
            ];
            if (empty($data_input['name']))
            {
                $this->ajaxReturn(500, '缺少配置名称');
            }
            if (empty($data_input['value']))
            {
                $this->ajaxReturn(500, '缺少列表参数');
            }
            $custom_list = model('CustomList')
                ->where([
                    'name' => $data_input['name'],
                    'admin_id' => $this->admininfo->id
                ])->find();
            if (empty($custom_list)) {
                $save = model('CustomList')
                    ->allowField(true)
                    ->isUpdate(false)
                    ->save($data_input);
            } else {
                $save = model('CustomList')
                    ->allowField(true)
                    ->isUpdate(true)
                    ->save(['value'=>$data_input['value']], [
                        'name' => $data_input['name'],
                        'admin_id' => $this->admininfo->id
                    ]);
            }

            if (false === $save) {
                $this->ajaxReturn(500, model('CustomList')->getError());
            }
            $this->ajaxReturn(200, '保存成功');
        }
    }
}