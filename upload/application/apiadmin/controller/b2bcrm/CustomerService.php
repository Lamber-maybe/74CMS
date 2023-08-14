<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;
use think\Request;
use think\Validate;

class CustomerService extends Backend
{
    public function edit(Request $request)
    {
        if ($request->method() == 'GET') {
            $customer_service = model('b2bcrm.CrmCustomerService')
                ->where(['admin_id'=>$this->admininfo->id])
                ->find();
            if (empty($customer_service))
            {
                $this->ajaxReturn(200, '获取数据成功', []);
            }
            $customer_service['photo_url'] = model('Uploadfile')->getFileUrl($customer_service['photo']);
            $customer_service['wx_qrcode_url'] = model('Uploadfile')->getFileUrl($customer_service['wx_qrcode']);

            $this->ajaxReturn(200, '获取数据成功', $customer_service);
        } else {
            $input_data = [
                'photo' => input('post.photo/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'mobile' => input('post.mobile/s', '', 'trim'),
                'tel' => input('post.tel/s', '', 'trim'),
                'wx_qrcode' => input('post.wx_qrcode/d', 0, 'intval'),
                'qq' => input('post.qq/s', '', 'trim'),
                'admin_id' => $this->admininfo->id
            ];

            $rule = [
                'photo'  =>  'require|max:10',
                'name' =>  'require|max:6',
                'mobile'=>'number|length:11',
                'tel' => 'max:20',
                'wx_qrcode' => 'require|max:10',
                'qq' => 'max:15',
            ];

            $message = [
                'photo' => '请上传头像',
                'name' => '请输入称呼，6字以内',
                'mobile' => '请输入手机号，11位数字',
                'tel' => '请输入正确的座机',
                'wx_qrcode' => '请上传微信二维码',
                'qq' => '请输入正确的QQ'
            ];
            $validate = new Validate($rule, $message);
            if (!$validate->check($input_data)) {
                $this->ajaxReturn(500, $validate->getError());
            }
            if (empty($input_data['mobile']) && empty($input_data['tel']))
            {
                $this->ajaxReturn(500, '请填写手机号和座机二选一');
            }
            $CrmCustomerService = model('b2bcrm.CrmCustomerService')
                ->where(['admin_id'=>$this->admininfo->id])
                ->find();
            $isUpdate = false;
            if (!empty($CrmCustomerService))
            {
                $isUpdate = true;
                $input_data['id'] = $CrmCustomerService['id'];
            }
            $save_config = model('b2bcrm.CrmCustomerService')
                ->allowField(true)
                ->isUpdate($isUpdate)
                ->save($input_data);

            if (false === $save_config) {
                $this->ajaxReturn(500, model('b2bcrm.CrmCustomerService')->getError());
            }
            model('AdminLog')->record(
                '编辑客服名片。客服姓名【' .
                $input_data['name'] .
                '】，客服手机号【'.$input_data['photo'].'】。',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
}