<?php
namespace app\v1_0\controller\member;

class Sendsms extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    protected function _verify($post_data)
    {
        if (config('global_config.captcha_open') == 1) {
            $engine = '';
            $captcha = new \app\common\lib\Captcha($engine);
            try {
                $result = $captcha->verify($post_data);
            } catch (\Exception $e) {
                $this->ajaxReturn(500, $e->getMessage());
            }
            if (false === $result) {
                $this->ajaxReturn(500, $captcha->getError());
            }
        }
    }
    /**
     * 注册发送验证码
     */
    public function reg()
    {
        $mobile = input('post.mobile/s', '', 'trim');
        if (!fieldRegex($mobile, 'mobile')) {
            $this->ajaxReturn(500, '手机号格式错误');
        }
        $utype = input('post.utype/d', 0, 'intval');
        if (!$utype) {
            $this->ajaxReturn(500, '参数错误');
        }

        $this->_verify(input('post.'));
        if(1===cache('sendsms_time_limit_'.$mobile)){
            $this->ajaxReturn(500,'请60秒后再重新获取');
        }

        //检测手机号是否存在
        $is_exist = $this->checkMobileExist($mobile, $utype);
        if ($is_exist) {
            $this->ajaxReturn(500, '手机号已被占用');
        }
        $code = mt_rand(1000, 9999) . '';
        $templateCode = 'SMS_1';
        $params = [
            'code' => $code,
            'sitename' => config('global_config.sitename')
        ];
        $class = new \app\common\lib\Sms();
        if (false === $class->send($mobile, $templateCode, $params)) {
            $this->ajaxReturn(500, $class->getError());
        }
        cache(
            'smscode_' . $mobile,
            [
                'code' => $code,
                'mobile' => $mobile,
                'utype' => $utype
            ],
            180
        );
        cache('sendsms_time_limit_'.$mobile,1,60);
        \think\Cache::set('smscode_error_num_' . $mobile, 0, 180);
        $this->ajaxReturn(200, '发送验证码成功');
    }
    /**
     * 手机号验证码登录发送验证码
     */
    public function login()
    {
        $mobile = input('post.mobile/s', '', 'trim');
        if (!fieldRegex($mobile, 'mobile')) {
            $this->ajaxReturn(500, '手机号格式错误');
        }
        $utype = input('post.utype/d', 0, 'intval');
        if (!$utype) {
            $this->ajaxReturn(500, '参数错误');
        }
        $this->_verify(input('post.'));
        if(1===cache('sendsms_time_limit_'.$mobile)){
            $this->ajaxReturn(500,'请60秒后再重新获取');
        }
        $code = mt_rand(1000, 9999) . '';
        $templateCode = 'SMS_2';
        $params = [
            'code' => $code,
            'sitename' => config('global_config.sitename')
        ];
        $class = new \app\common\lib\Sms();
        if (false === $class->send($mobile, $templateCode, $params)) {
            $this->ajaxReturn(500, $class->getError());
        }
        cache(
            'smscode_' . $mobile,
            [
                'code' => $code,
                'mobile' => $mobile,
                'utype' => $utype
            ],
            180
        );
        cache('sendsms_time_limit_'.$mobile,1,60);
        \think\Cache::set('smscode_error_num_' . $mobile, 0, 180);
        $this->ajaxReturn(200, '发送验证码成功');
    }
    /**
     * 找回密码时发送短信验证码
     */
    public function forget()
    {
        $mobile = input('post.mobile/s', '', 'trim');
        if (!fieldRegex($mobile, 'mobile')) {
            $this->ajaxReturn(500, '手机号格式错误');
        }
        $utype = input('post.utype/d', 0, 'intval');
        if (!$utype) {
            $this->ajaxReturn(500, '参数错误');
        }
        $this->_verify(input('post.'));
        if(1===cache('sendsms_time_limit_'.$mobile)){
            $this->ajaxReturn(500,'请60秒后再重新获取');
        }
        //检测手机号是否存在
        $is_exist = $this->checkMobileExist($mobile, $utype);
        if (!$is_exist) {
            $this->ajaxReturn(500, '手机号未注册');
        }
        $code = mt_rand(1000, 9999) . '';
        $templateCode = 'SMS_3';
        $params = [
            'code' => $code,
            'sitename' => config('global_config.sitename')
        ];
        $class = new \app\common\lib\Sms();
        if (false === $class->send($mobile, $templateCode, $params)) {
            $this->ajaxReturn(500, $class->getError());
        }
        cache(
            'smscode_' . $mobile,
            [
                'code' => $code,
                'mobile' => $mobile,
                'utype' => $utype
            ],
            180
        );
        cache('sendsms_time_limit_'.$mobile,1,60);
        \think\Cache::set('smscode_error_num_' . $mobile, 0, 180);
        $this->ajaxReturn(200, '发送验证码成功');
    }
    /**
     * 验证手机号，无须检查是否唯一，如修改联系手机等场景
     */
    public function authMobileNoCheck()
    {
        $mobile = input('post.mobile/s', '', 'trim');
        if (!fieldRegex($mobile, 'mobile')) {
            $this->ajaxReturn(500, '手机号格式错误');
        }
        $this->_verify(input('post.'));
        if(1===cache('sendsms_time_limit_'.$mobile)){
            $this->ajaxReturn(500,'请60秒后再重新获取');
        }
        $code = mt_rand(1000, 9999) . '';
        $templateCode = 'SMS_5';
        $params = [
            'code' => $code,
            'sitename' => config('global_config.sitename')
        ];
        $class = new \app\common\lib\Sms();
        if (false === $class->send($mobile, $templateCode, $params)) {
            $this->ajaxReturn(500, $class->getError());
        }
        cache(
            'smscode_' . $mobile,
            [
                'code' => $code,
                'mobile' => $mobile
            ],
            180
        );
        cache('sendsms_time_limit_'.$mobile,1,60);
        \think\Cache::set('smscode_error_num_' . $mobile, 0, 180);
        $this->ajaxReturn(200, '发送验证码成功');
    }
    /**
     * 验证手机号，如修改联系手机、未登录状态下的验证手机号真实性等场景
     */
    public function authMobile()
    {
        $mobile = input('post.mobile/s', '', 'trim');
        if (!fieldRegex($mobile, 'mobile')) {
            $this->ajaxReturn(500, '手机号格式错误');
        }
        $utype = input('post.utype/d', 0, 'intval');
        if (!$utype) {
            $this->ajaxReturn(500, '参数错误');
        }
        $this->_verify(input('post.'));
        if(1===cache('sendsms_time_limit_'.$mobile)){
            $this->ajaxReturn(500,'请60秒后再重新获取');
        }
        //检测手机号是否存在
        $is_exist = $this->checkMobileExist($mobile, $utype);
        if ($is_exist) {
            $this->ajaxReturn(500, '手机号已被占用');
        }
        $code = mt_rand(1000, 9999) . '';
        $templateCode = 'SMS_5';
        $params = [
            'code' => $code,
            'sitename' => config('global_config.sitename')
        ];
        $class = new \app\common\lib\Sms();
        if (false === $class->send($mobile, $templateCode, $params)) {
            $this->ajaxReturn(500, $class->getError());
        }
        cache(
            'smscode_' . $mobile,
            [
                'code' => $code,
                'mobile' => $mobile,
                'utype'=>$utype
            ],
            180
        );
        cache('sendsms_time_limit_'.$mobile,1,60);
        \think\Cache::set('smscode_error_num_' . $mobile, 0, 180);
        $this->ajaxReturn(200, '发送验证码成功');
    }
    /**
     * 快速注册简历发送验证码
     */
    public function regResumeQuick()
    {
        $utype = 2;
        $mobile = input('post.mobile/s', '', 'trim');
        if (!fieldRegex($mobile, 'mobile')) {
            $this->ajaxReturn(500, '手机号格式错误');
        }

        $this->_verify(input('post.'));
        if(1===cache('sendsms_time_limit_'.$mobile)){
            $this->ajaxReturn(500,'请60秒后再重新获取');
        }

        //检测手机号是否存在
        $is_exist = $this->checkMobileExist($mobile, $utype);
        if ($is_exist) {
            $this->ajaxReturn(200, '您的手机号已注册过简历，是否立即登录',['notice'=>1]);
        }
        $code = mt_rand(1000, 9999) . '';
        $templateCode = 'SMS_1';
        $params = [
            'code' => $code,
            'sitename' => config('global_config.sitename')
        ];
        $class = new \app\common\lib\Sms();
        if (false === $class->send($mobile, $templateCode, $params)) {
            $this->ajaxReturn(500, $class->getError());
        }
        cache(
            'smscode_' . $mobile,
            [
                'code' => $code,
                'mobile' => $mobile,
                'utype' => $utype
            ],
            180
        );
        cache('sendsms_time_limit_'.$mobile,1,60);
        \think\Cache::set('smscode_error_num_' . $mobile, 0, 180);
        $this->ajaxReturn(200, '发送验证码成功',['notice'=>0]);
    }
    private function checkMobileExist($mobile, $utype)
    {
        $info = model('Member')
            ->where([
                'mobile' => $mobile,
                'utype' => $utype
            ])
            ->find();
        if (null === $info) {
            return false;
        } else {
            return true;
        }
    }
}
