<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;
use think\Validate;

class CompanyContact extends Backend
{
    protected $rule =   [
        'comid'  => 'require|gt:0|max:10',
        'uid'   => 'require|gt:0|max:10',
        'contact' => 'require|max:6',
        'mobile'=>'require|number|max:11',
        'weixin'=>'max:15',
        'telephone'=>'max:20',
        'qq'=>'max:15',
        'email'=>'max:30'
    ];

    protected $message = [
        'comid' =>  '企业id必填，且最多10位数字',
        'uid' => 'uid必填，且最多10位数字',
        'contact' => '联系人必填，且最多6个字',
        'mobile' => '手机号必填，且11位数字',
        'weixin' => '微信最多15位',
        'telephone' => '座机最多20位',
        'qq' => 'QQ最多15位',
        'email' => '邮箱最多30位'
    ];
    /*
     * 联系人列表
     * */
    public function index()
    {
        $uid = input('get.uid/d',0,'intval');
        if ($uid == 0)
        {
            $this->ajaxReturn(500, '缺少uid');
        }
        $company = model('CompanyContact')->where(['uid'=>$uid])->find();
        if (!empty($company))
        {
            $company['type'] = 'company';
            $data[] = $company;
        }
        $phone = model('Member')->where(['uid'=>$uid])->value('mobile');
        $data[] = [
            'id' => $uid,
            'comid' => isset($company['id']) ? $company['id'] : 0,
            'uid' => $uid,
            'contact' => '会员账号',
            'mobile' => $phone,
            'weixin' => '',
            'telephone' => '',
            'qq' => '',
            'email' => '',
            'type' => 'member'
        ];
        $crm_contact = model('b2bcrm.CrmCompanyContact')
                ->where(['uid'=>$uid])
            ->field("id,comid,uid,contact,mobile,weixin,telephone,qq,email,sex,'crm_contact' as type")
            ->select();
        if (!empty($crm_contact))
        {
            foreach ($crm_contact as $v)
            {
                $data[] = $v;
            }
        }
        $this->ajaxReturn(200, '获取数据成功',$data);
    }

    /*
     * 联系人添加
     * */
    public function addContact()
    {
        $data_input = [
            'comid' => input('post.comid/d',0,'intval'),
            'uid' => input('post.uid/d',0,'intval'),
            'contact' => input('post.contact/s','','trim'),
            'mobile' => input('post.mobile/d',0,'intval'),
            'weixin' => input('post.weixin/s','','trim'),
            'telephone' => input('post.telephone/s','','trim'),
            'qq' => input('post.telephone/s','','trim'),
            'email' => input('post.email/s','','trim'),
            'sex' => input('post.sex/d',0,'intval')
        ];

        if ($data_input['comid'] == 0 || $data_input['uid'] == 0)
        {
            $this->ajaxReturn(500, '企业id或uid错误');
        }
        try {
            $validate = new Validate($this->rule, $this->message);
            $result = $validate->check($data_input);
            if (false === $result)
            {
                $this->ajaxReturn(500, $validate->getError());
            }
            if (!empty($data_input['mobile']) && !fieldRegex($data_input['mobile'], 'mobile'))
            {
                throw new \Exception('手机号格式错误');
            }
            if (!empty($data_input['email']) && !fieldRegex($data_input['email'], 'email'))
            {
                throw new \Exception('邮箱格式错误');
            }
            $count = model('b2bcrm.CrmCompanyContact')->where(['comid'=>$data_input['comid'],'uid'=>$data_input['uid']])->count();
            if ($count >= 6)
            {
                $this->ajaxReturn(500, '联系人最多8个！');
            }
            $add = model('b2bcrm.CrmCompanyContact')
                ->isUpdate(false)
                ->save($data_input);
            if (false === $add)
            {
                $this->ajaxReturn(500, model('b2bcrm.CrmCompanyContact')->getError());
            }
            // 日志
            $log_result = model('AdminLog')->record(
                '添加企业联系人【ID:' . model('b2bcrm.CrmCompanyContact')->id . '】,名称【'.$data_input['contact'].'】',
                $this->admininfo
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }
            $this->ajaxReturn(200, '添加成功', []);
        }catch (\Exception $e)
        {
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

    /*
     * 联系人编辑
     * */
    public function editContact()
    {
        $data_input = [
            'id' => input('post.id/d',0,'intval'),
            'comid' => input('post.comid/d',0,'intval'),
            'uid' => input('post.uid/d',0,'intval'),
            'contact' => input('post.contact/s','','trim'),
            'mobile' => input('post.mobile/s','','trim'),
            'weixin' => input('post.weixin/s','','trim'),
            'telephone' => input('post.telephone/s','','trim'),
            'qq' => input('post.telephone/s','','trim'),
            'email' => input('post.email/s','','trim'),
            'sex' => input('post.sex/d',0,'intval')
        ];

        if ($data_input['id'] == 0)
        {
            $this->ajaxReturn(500, '联系人id错误');
        }
        try {
            $validate = new Validate($this->rule, $this->message);
            $result = $validate->check($data_input);
            if (false === $result)
            {
                $this->ajaxReturn(500, $validate->getError());
            }

            if (!empty($data_input['mobile']))
            {
                $preg_phone = '/^1[345789]\d{9}$/ims';
                if (!preg_match($preg_phone, $data_input['mobile'])) {
                    throw new \Exception('手机号格式错误');
                }
            }
            $edit = model('b2bcrm.CrmCompanyContact')
                ->isUpdate(true)
                ->save($data_input,['id'=>$data_input['id']]);
            if (false === $edit)
            {
                $this->ajaxReturn(500, model('b2bcrm.CrmCompanyContact')->getError());
            }
            // 日志
            $log_result = model('AdminLog')->record(
                '编辑企业联系人【ID:' . model('b2bcrm.CrmCompanyContact')->id . '】,名称【'.$data_input['contact'].'】',
                $this->admininfo
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }
            $this->ajaxReturn(200, '编辑成功', []);
        }catch (\Exception $e)
        {
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

    /*
     * 联系人删除
     * */
    public function delContact(){
        $id = input('get.id/d',0,'intval');
        $CrmCompanyContact = model('b2bcrm.CrmCompanyContact')->where(['id'=>$id])->find();
        if (empty($CrmCompanyContact))
        {
            $this->ajaxReturn(500, '联系人id错误');
        }
        $del = model('b2bcrm.CrmCompanyContact')->where(['id'=>$id])->delete();
        if ($del === false)
        {
            $this->ajaxReturn(500, model('b2bcrm.CrmCompanyContact')->getError());
        }
        $log_result = model('AdminLog')->record(
            '删除企业联系人【ID:' . $id . '】,名称【'.$CrmCompanyContact['contact'].'】',
            $this->admininfo
        );
        if (false === $log_result) {
            throw new \Exception(model('AdminLog')->getError());
        }
        $this->ajaxReturn(200, '删除成功', []);
    }
}