<?php
/**
 * 签到
 */
namespace app\v1_0\controller\member;

class SignIn extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin();
        if ($this->userinfo->utype == 1) {
            $this->interceptCompanyProfile();
            $this->interceptCompanyAuth();
        } else {
            $this->interceptPersonalResume();
        }
    }
    public function index()
    {
        $map['uid'] = $this->userinfo->uid;
        $map['addtime'] = strtotime('today');
        $map['alias'] = 'sign_in';
        $check_signin = model('TaskRecord')
            ->where($map)
            ->find();
        if ($check_signin !== null) {
            $this->ajaxReturn(500, '你今天已经签到了');
        }
        model('Task')->doTask(
            $this->userinfo->uid,
            $this->userinfo->utype,
            'sign_in'
        );
        $this->ajaxReturn(200, '签到成功',['points'=>model('Member')->getMemberPoints($this->userinfo->uid)]);
    }
}
