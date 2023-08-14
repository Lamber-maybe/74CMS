<?php
namespace app\apiadmin\controller;

class ServiceOl extends \app\common\controller\Backend
{
    public function index()
    {
        $order = 'sort desc';
        $list = model('ServiceOl')
            ->order($order)
            ->select();
        $image_id_arr = $image_list = [];
        foreach ($list as $key => $value) {
            $value['weixin'] && ($image_id_arr[] = $value['weixin']);
        }
        if (!empty($image_id_arr)) {
            $image_list = model('Uploadfile')->getFileUrlBatch($image_id_arr);
        }
        foreach ($list as $key => $value) {
            $value['weixin_url'] = isset($image_list[$value['weixin']])
                ? $image_list[$value['weixin']]
                : '';
        }
        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function add()
    {
        $input_data = [
            'name' => input('post.name/s', '', 'trim'),
            'mobile' => input('post.mobile/s', '', 'trim'),
            'weixin' => input('post.weixin/d', '', 'intval'),
            'qq' => input('post.qq/s', '', 'trim'),
            'sort' => input('post.sort/d', 0, 'intval'),
            'display' => input('post.display/d', 1, 'intval')
        ];
        $result = model('ServiceOl')
            ->validate(true)
            ->allowField(true)
            ->save($input_data);
        if ($result === false) {
            $this->ajaxReturn(500, model('ServiceOl')->getError());
        }
        model('AdminLog')->record(
            '添加在线客服。客服ID【' .
                model('ServiceOl')->id .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }

    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('ServiceOl')->find($id);
            if (null===$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info = $info->toArray();
            $imageSrc = model('Uploadfile')->getFileUrl($info['weixin']);
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'imageSrc' => $imageSrc
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'mobile' => input('post.mobile/s', '', 'trim'),
                'weixin' => input('post.weixin/d', '', 'intval'),
                'qq' => input('post.qq/s', '', 'trim'),
                'sort' => input('post.sort/d', 0, 'intval'),
                'display' => input('post.display/d', 1, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            $result = model('ServiceOl')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, ['id' => $id]);
            if (false === $result) {
                $this->ajaxReturn(500, model('ServiceOl')->getError());
            }
            model('AdminLog')->record(
                '编辑在线客服。客服ID【' .
                $id .
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
        model('ServiceOl')->destroy($id);
        model('AdminLog')->record(
            '删除在线客服。客服ID【' .
            implode(',', $id) .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
