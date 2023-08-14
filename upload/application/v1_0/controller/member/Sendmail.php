<?php
namespace app\v1_0\controller\member;

class Sendmail extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    /**
     * 找回密码时发送邮件
     */
    public function forget()
    {
        $email = input('post.email/s', '', 'trim');
        if (!fieldRegex($email, 'email')) {
            $this->ajaxReturn(500, '邮箱格式错误');
        }
        $utype = input('post.utype/d', 0, 'intval');
        if (!$utype) {
            $this->ajaxReturn(500, '参数错误');
        }
        $is_exist = $this->checkEmailExist($email, $utype);
        if (!$is_exist) {
            $this->ajaxReturn(500, '邮箱未注册');
        }
        $alias = 'set_forget';
        $code = mt_rand(1000, 9999) . '';
        $replac = [
            'code' => $code,
            'sitedomain' => config('global_config.sitename'),
        ];
        $class = new \app\common\lib\Mail();
        if (false === $class->sendTpl($email, $alias, $replac)) {
            $this->ajaxReturn(500, $class->getError());
        }
        cache(
            'emailcode_' . $email,
            [
                'code' => $code,
                'email' => $email,
                'utype' => $utype,
            ],
            180
        );
        \think\Cache::set('emailcode_error_num_' . $email, 0, 180);
        $this->ajaxReturn(200, '发送邮件成功');
    }
    /**
     * 绑定邮箱时发送邮件
     */
    public function bind()
    {
        $this->checkLogin();
        $email = input('post.email/s', '', 'trim');
        if (!fieldRegex($email, 'email')) {
            $this->ajaxReturn(500, '邮箱格式错误');
        }
        //检测邮箱是否存在
        $member = $this->checkEmailExist($email, $this->userinfo->utype);
        if (false !== $member && $member->uid != $this->userinfo->uid) {
            $this->ajaxReturn(500, '邮箱已被占用');
        }
        $alias = 'set_auth_email';
        $code = mt_rand(1000, 9999) . '';
        $replac = [
            'code' => $code,
            'sitedomain' => config('global_config.sitename'),
        ];
        $class = new \app\common\lib\Mail();
        if (false === $class->sendTpl($email, $alias, $replac)) {
            $this->ajaxReturn(500, $class->getError());
        }
        cache(
            'emailcode_' . $email,
            [
                'code' => $code,
                'email' => $email,
                'utype' => $this->userinfo->utype,
            ],
            180
        );
        \think\Cache::set('emailcode_error_num_' . $email, 0, 180);
        $this->ajaxReturn(200, '发送邮件成功');
    }
    private function checkEmailExist($email, $utype)
    {
        $info = model('Member')
            ->where([
                'email' => $email,
                'utype' => $utype,
            ])
            ->find();
        if (null === $info) {
            return false;
        } else {
            return $info;
        }
    }
}
