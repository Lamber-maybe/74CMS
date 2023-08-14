<?php
namespace app\v1_0\controller\company;

class Auth extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
    }
    /**
     * 获取企业认证信息
     */
    public function index()
    {
        $audit_text = [
            0 => '未提交审核',
            1 => '审核通过',
            2 => '审核不通过',
            3 => '已提交资料但待审核'
        ];
        $return = [
            'audit' => 0,
            'audit_text' => '',
            'audit_reason' => ''
        ];
        if ($this->company_profile['audit'] == 0) {
            //待审核
            $auth = model('CompanyAuth')
                ->where('uid', $this->userinfo->uid)
                ->find();
            if ($auth === null) {
                //未提交审核
                $return['audit'] = 0;
            } else {
                //已提交认证资料但待审核
                $return['audit'] = 3;
            }
        } elseif ($this->company_profile['audit'] == 1) {
            $return['audit'] = 1;
        } else {
            $return['audit'] = 2;
            $job_audit_reason = model('CompanyAuthLog')
                ->field('reason')
                ->where(['uid' => ['eq', $this->userinfo->uid], 'audit' => 2])
                ->order('id desc')
                ->find();
            $return['audit_reason'] =
                $job_audit_reason === null ? '' : $job_audit_reason['reason'];
        }
        $return['audit_text'] = $audit_text[$return['audit']];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 负责人认证
     */
    public function legal_personal()
    {
        $input_data = [
            'legal_person_name' => input(
                'post.legal_person_name/s',
                '',
                'trim'
            ),
            'legal_person_idcard_number' => input(
                'post.legal_person_idcard_number/s',
                '',
                'trim'
            ),
            'legal_person_idcard_front' => input(
                'post.legal_person_idcard_front/d',
                0,
                'intval'
            ),
            'legal_person_idcard_back' => input(
                'post.legal_person_idcard_back/d',
                0,
                'intval'
            )
        ];
        $validate = new \think\Validate([
            'legal_person_name' => 'require|max:10',
            'legal_person_idcard_number' => 'require|checkIdcard',
            'legal_person_idcard_front' => 'require|number|gt:0',
            'legal_person_idcard_back' => 'require|number|gt:0'
        ]);
        $validate->extend('checkIdcard', function ($value) {
            if ($value == '') {
                return '请输入正确的身份证号码';
            }
            if (is_idcard($value)) {
                return true;
            } else {
                return '请输入正确的身份证号码';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $auth = model('CompanyAuth')
            ->where('uid', $this->userinfo->uid)
            ->find();
        if ($auth === null) {
            $input_data['comid'] = $this->company_profile['id'];
            $input_data['uid'] = $this->company_profile['uid'];
            $input_data['license'] = 0;
            model('CompanyAuth')->save($input_data);
        } else {
            model('CompanyAuth')->save($input_data, ['id' => $auth['id']]);
        }
        model('Company')
            ->where('uid', $this->userinfo->uid)
            ->setField('audit', 0);
        $this->ajaxReturn(200, '提交成功');
    }

    /**
     * 营业执照认证
     */
    public function license()
    {
        $input_data = [
            'license' => input('post.license/d', 0, 'intval')
        ];
        $validate = new \think\Validate([
            'license' => 'require|number|gt:0'
        ]);
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $auth = model('CompanyAuth')
            ->where('uid', $this->userinfo->uid)
            ->find();
        if ($auth === null) {
            $input_data['comid'] = $this->company_profile['id'];
            $input_data['uid'] = $this->company_profile['uid'];
            $input_data['legal_person_name'] = '';
            $input_data['legal_person_idcard_number'] = '';
            $input_data['legal_person_idcard_front'] = 0;
            $input_data['legal_person_idcard_back'] = 0;
            model('CompanyAuth')->save($input_data);
        } else {
            model('CompanyAuth')->save($input_data, ['id' => $auth['id']]);
        }
        model('Company')
            ->where('uid', $this->userinfo->uid)
            ->setField('audit', 0);
        $this->ajaxReturn(200, '提交成功');
    }
}
