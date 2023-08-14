<?php
namespace app\apiadmin\controller;

class CustomerService extends \app\common\controller\Backend
{
    public function index()
    {
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        $total = model('CustomerService')->count();
        $list = model('CustomerService')
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $service_id_arr = $company_total = [];
        foreach ($list as $key => $value) {
            $service_id_arr[] = $value['id'];
        }
        if (!empty($service_id_arr)) {
            $company_total = model('Company')
                ->where('cs_id', 'in', $service_id_arr)
                ->group('cs_id')
                ->column('cs_id,count(*)', 'cs_id');
        }
        foreach ($list as $key => $value) {
            $value['company_num'] = isset($company_total[$value['id']])
                ? $company_total[$value['id']]
                : 0;
            $value['photoUrl'] = model('Uploadfile')->getFileUrl(
                $value['photo']
            );
            $value['qrcodeUrl'] = model('Uploadfile')->getFileUrl(
                $value['wx_qrcode']
            );
            $list[$key] = $value;
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function add()
    {
        $input_data = [
            'name' => input('post.name/s', '', 'trim'),
            'photo' => input('post.photo/d', 0, 'intval'),
            'mobile' => input('post.mobile/s', '', 'trim'),
            'tel' => input('post.tel/s', '', 'trim'),
            'weixin' => input('post.weixin/s', '', 'trim'),
            'wx_qrcode' => input('post.wx_qrcode/d', 0, 'intval'),
            'qq' => input('post.qq/s', '', 'trim'),
            'status' => input('post.status/d', 1, 'intval')
        ];
        if (
            false ===
            model('CustomerService')
                ->validate(true)
                ->allowField(true)
                ->save($input_data)
        ) {
            $this->ajaxReturn(500, model('CustomerService')->getError());
        }

        model('AdminLog')->checkLog(
            '添加客服。客服ID【' .
                model('CustomerService')->id .
                '】；客服姓名【' .
                $input_data['name'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');
    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('CustomerService')->find($id);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'photo' => input('post.photo/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'mobile' => input('post.mobile/s', '', 'trim'),
                'tel' => input('post.tel/s', '', 'trim'),
                'weixin' => input('post.weixin/s', '', 'trim'),
                'wx_qrcode' => input('post.wx_qrcode/d', 0, 'intval'),
                'qq' => input('post.qq/s', '', 'trim'),
                'status' => input('post.status/d', 1, 'intval')
            ];
            $id = intval($input_data['id']);
            if (!$id) {
                $this->ajaxReturn(500, '请选择数据');
            }
            if (
                false ===
                model('CustomerService')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('CustomerService')->getError());
            }
            model('AdminLog')->checkLog(
                '编辑客服。客服ID【' .
                    $id .
                    '】；客服姓名【' .
                    $input_data['name'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id == 0) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $info = model('CustomerService')
            ->where('id', $id)
            ->find();
        if (null === $info) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('Company')
            ->where('cs_id', 'eq', $id)
            ->setField('cs_id', 0);
        $info->delete();
        model('AdminLog')->checkLog(
            '删除客服。客服ID【' . $id . '】;客服姓名【' . $info['name'] . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
