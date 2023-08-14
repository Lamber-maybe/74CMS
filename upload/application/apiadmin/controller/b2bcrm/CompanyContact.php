<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;
use think\Db;
use think\Validate;

class CompanyContact extends Backend
{
    protected $rule = [
        'comid' => 'require|gt:0|max:10',
        'uid' => 'require|gt:0|max:10',
        'contact' => 'require|max:6',
        'weixin' => 'max:15',
        'telephone' => 'max:20',
        'qq' => 'max:15',
        'email' => 'max:30'
    ];

    protected $message = [
        'comid' => '企业id必填，且最多10位数字',
        'uid' => 'uid必填，且最多10位数字',
        'contact' => '联系人必填，且最多6个字',
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
        $uid = input('get.uid/d', 0, 'intval');
        if ($uid == 0) {
            $this->ajaxReturn(500, '缺少uid');
        }

        $company_info = model('Company')
            ->where('uid', $uid)
            ->find();
        if (empty($company_info)) {
            $this->ajaxReturn(500, '该客户企业信息异常');
        }

        /**
         *【ID1000503】
         * 【bug】线索跟进时选择联系人ID为1导致选择重复
         * yx - 2023.01.12
         * [新增]:
         * `auto_id`自动，重新排序，避免ID重复
         */

        // 1.企业联系方式
        $company_contact = model('CompanyContact')->where(['uid' => $uid])->find();
        $contact_list = [];
        if (!empty($company_contact)) {
            $company_contact['type'] = 'company';
            $company_contact['auto_id'] = 1;
            $contact_list[] = $company_contact;
        }

        // 2.会员联系方式
        $memberContact = model('Member')->where(['uid' => $uid])->value('mobile');
        $contact_list[] = [
            'id' => $uid,
            'comid' => isset($company_info['id']) ? $company_info['id'] : 0,
            'uid' => $uid,
            'contact' => '会员账号',
            'mobile' => $memberContact,
            'weixin' => '',
            'telephone' => '',
            'qq' => '',
            'email' => '',
            'type' => 'member',
            'auto_id' => 2
        ];

        // 3.客户联系人
        $crm_contact = model('b2bcrm.CrmCompanyContact')
            ->where(['uid' => $uid])
            ->field("id,comid,uid,contact,mobile,weixin,telephone,qq,email,sex,'crm_contact' as type")
            ->select();
        if (!empty($crm_contact)) {
            $auto_id = 3;
            foreach ($crm_contact as &$v) {
                $v['auto_id'] = $auto_id;
                $auto_id++;
                $contact_list[] = $v;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $contact_list);
    }

    /*
     * 联系人添加
     * */
    public function addContact()
    {
        $data_input = [
            'comid' => input('post.comid/d', 0, 'intval'),
            'uid' => input('post.uid/d', 0, 'intval'),
            'contact' => input('post.contact/s', '', 'trim'),
            'mobile' => input('post.mobile/s', '', 'trim'),
            'weixin' => input('post.weixin/s', '', 'trim'),
            'telephone' => input('post.telephone/s', '', 'trim'),
            'qq' => input('post.qq/s', '', 'trim'), // 【bug】后台企业管理中添加联系人设置固话保存后 固话自动会显示在QQ那一栏中 zch 2022.12.12
            'email' => input('post.email/s', '', 'trim'),
            'sex' => input('post.sex/d', 0, 'intval')
        ];

        if ($data_input['comid'] == 0 || $data_input['uid'] == 0) {
            $this->ajaxReturn(500, '企业id或uid错误');
        }
        try {
            $validate = new Validate($this->rule, $this->message);
            $result = $validate->check($data_input);
            if (false === $result) {
                $this->ajaxReturn(500, $validate->getError());
            }
            if (!empty($data_input['mobile']) && !fieldRegex($data_input['mobile'], 'mobile')) {
                throw new \Exception('手机号格式不正确');
            }
            if (!empty($data_input['email']) && !fieldRegex($data_input['email'], 'email')) {
                throw new \Exception('邮箱格式错误');
            }
            $count = model('b2bcrm.CrmCompanyContact')->where(['comid' => $data_input['comid'], 'uid' => $data_input['uid']])->count();
            if ($count >= 6) {
                $this->ajaxReturn(500, '联系人最多8个！');
            }

            // 开启事务
            Db::startTrans();

            $add = model('b2bcrm.CrmCompanyContact')
                ->isUpdate(false)
                ->save($data_input);
            if (false === $add) {
                $this->ajaxReturn(500, model('b2bcrm.CrmCompanyContact')->getError());
            }

            // 日志
            $company_name = model('Company')->where('id', $data_input['comid'])->value('companyname');
            $log_field = '为客户{'
                . $company_name
                . '}(企业ID:' . $data_input['comid']
                . ')添加联系人，'
                . $data_input['contact'];
            if (!empty($data_input['sex'])) {
                $log_field .= '；性别:' . model('b2bcrm.CrmCompanyContact')->map_sex[$data_input['sex']];
            }
            if (!empty($data_input['mobile'])) {
                $log_field .= '；手机号:' . $data_input['mobile'];
            }
            if (!empty($data_input['telephone'])) {
                $log_field .= '；公司座机:' . $data_input['telephone'];
            }
            if (!empty($data_input['email'])) {
                $log_field .= '；邮箱:' . $data_input['email'];
            }
            if (!empty($data_input['qq'])) {
                $log_field .= '；QQ:' . $data_input['qq'];
            }
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                2
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 开启事务
            Db::commit();

            $this->ajaxReturn(200, '添加成功', []);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();

            $this->ajaxReturn(500, '添加失败', ['err_msg' => $e->getMessage()]);
        }
    }

    /*
     * 联系人编辑
     * */
    public function editContact()
    {
        $data_input = [
            'id' => input('post.id/d', 0, 'intval'),
            'comid' => input('post.comid/d', 0, 'intval'),
            'uid' => input('post.uid/d', 0, 'intval'),
            'contact' => input('post.contact/s', '', 'trim'),
            'mobile' => input('post.mobile/s', '', 'trim'),
            'weixin' => input('post.weixin/s', '', 'trim'),
            'telephone' => input('post.telephone/s', '', 'trim'),
            'qq' => input('post.qq/s', '', 'trim'), // 【bug】后台企业管理中添加联系人设置固话保存后 固话自动会显示在QQ那一栏中 zch 2022.12.12
            'email' => input('post.email/s', '', 'trim'),
            'sex' => input('post.sex/d', 0, 'intval')
        ];

        if ($data_input['id'] == 0) {
            $this->ajaxReturn(500, '联系人id错误');
        }

        try {
            $validate = new Validate($this->rule, $this->message);
            $result = $validate->check($data_input);
            if (false === $result) {
                $this->ajaxReturn(500, $validate->getError());
            }

            if (!empty($data_input['mobile']) && !fieldRegex($data_input['mobile'], 'mobile')) {
                throw new \Exception('手机号格式不正确');
            }
            if (!empty($data_input['email']) && !fieldRegex($data_input['email'], 'email')) {
                throw new \Exception('邮箱格式错误');
            }

            $contact_info = model('b2bcrm.CrmCompanyContact')->find($data_input['id']);
            if (null === $contact_info) {
                $this->ajaxReturn(500, '要修改的企业联系人不存在');
            }

            // 开启事务
            Db::startTrans();

            $edit = model('b2bcrm.CrmCompanyContact')
                ->isUpdate(true)
                ->save($data_input, ['id' => $data_input['id']]);
            if (false === $edit) {
                throw new \Exception(model('b2bcrm.CrmCompanyContact')->getError());
            }

            // 日志
            $company_name = model('Company')->where('id', $data_input['comid'])->value('companyname');
            $log_field = '为客户{'
                . $company_name
                . '}(企业ID:' . $data_input['comid']
                . ')修改联系人，'
                . $contact_info['contact'];
            if ($data_input['contact'] != $contact_info['contact']) {
                $log_field .= '->' . $data_input['contact'];
            }
            if ($data_input['sex'] != $contact_info['sex']) {
                $log_field .= '；性别:'
                    . model('b2bcrm.CrmCompanyContact')->map_sex[$contact_info['sex']]
                    . '->'
                    . model('b2bcrm.CrmCompanyContact')->map_sex[$data_input['sex']];
            }
            if ($data_input['mobile'] != $contact_info['mobile']) {
                $log_field .= '；手机号:'
                    . $contact_info['mobile']
                    . '->'
                    . $data_input['mobile'];
            }
            if ($data_input['telephone'] != $contact_info['telephone']) {
                $log_field .= '；公司座机:'
                    . $contact_info['telephone']
                    . '->'
                    . $data_input['telephone'];
            }
            if ($data_input['email'] != $contact_info['email']) {
                $log_field .= '；邮箱:'
                    . $contact_info['email']
                    . '->'
                    . $data_input['email'];
            }
            if ($data_input['qq'] != $contact_info['qq']) {
                $log_field .= '；QQ:'
                    . $contact_info['qq']
                    . '->'
                    . $data_input['qq'];
            }
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                3
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();

            $this->ajaxReturn(200, '编辑成功', []);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->ajaxReturn(500, '编辑失败', ['err_msg' => $e->getMessage()]);
        }
    }

    /*
     * 联系人删除
     * */
    public function delContact()
    {
        $id = input('get.id/d', 0, 'intval');
        $CrmCompanyContact = model('b2bcrm.CrmCompanyContact')->where(['id' => $id])->find();
        if (empty($CrmCompanyContact)) {
            $this->ajaxReturn(500, '联系人id错误');
        }

        try {
            // 开启事务
            Db::startTrans();

            $del = model('b2bcrm.CrmCompanyContact')->where(['id' => $id])->delete();
            if ($del === false) {
                throw new \Exception(model('b2bcrm.CrmCompanyContact')->getError());
            }

            // 日志
            $company_name = model('Company')->where('id', $CrmCompanyContact['comid'])->value('companyname');
            $log_field = '为客户{'
                . $company_name
                . '}(企业ID:' . $CrmCompanyContact['comid']
                . ')删除联系人，'
                . $CrmCompanyContact['contact'];
            if (!empty($CrmCompanyContact['sex'])) {
                $log_field .= '；性别:' . model('b2bcrm.CrmCompanyContact')->map_sex[$CrmCompanyContact['sex']];
            }
            if (!empty($CrmCompanyContact['mobile'])) {
                $log_field .= '；手机号:' . $CrmCompanyContact['mobile'];
            }
            if (!empty($CrmCompanyContact['telephone'])) {
                $log_field .= '；公司座机:' . $CrmCompanyContact['telephone'];
            }
            if (!empty($CrmCompanyContact['email'])) {
                $log_field .= '；邮箱:' . $CrmCompanyContact['email'];
            }
            if (!empty($CrmCompanyContact['qq'])) {
                $log_field .= '；QQ:' . $CrmCompanyContact['qq'];
            }
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功', []);
    }
}
