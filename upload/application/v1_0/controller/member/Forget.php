<?php
/**
 * 找回密码
 */
namespace app\v1_0\controller\member;

class Forget extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function byMobile()
    {
        $input_data = [
            'utype' => input('post.utype/d', 0, 'intval'),
            'mobile' => input('post.mobile/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'password' => input('post.password/s', '', 'trim')
        ];
        $validate = new \think\Validate([
            'utype' => 'require|in:1,2',
            'mobile' => 'require|checkMobile',
            'code' => 'require|max:4',
            'password' => 'require|max:15|min:6'
        ]);

        $validate->extend('checkMobile', function ($value) {
            if (fieldRegex($value, 'mobile')) {
                return true;
            } else {
                return '请输入正确的手机号码';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $auth_result = cache('smscode_' . $input_data['mobile']);
        if (
            $auth_result === false ||
            $auth_result['code'] != $input_data['code'] ||
            $auth_result['mobile'] != $input_data['mobile'] ||
            (isset($auth_result['utype']) && $auth_result['utype'] != $input_data['utype'])
        ) {
            \think\Cache::inc('smscode_error_num_' . $input_data['mobile']);
            $this->ajaxReturn(500, '验证码错误');
        }
        $error_num = \think\Cache::get(
            'smscode_error_num_' . $input_data['mobile']
        );
        if ($error_num !== false && $error_num >= 5) {
            $this->ajaxReturn(500, '验证码失效，请重新获取');
        }
        $model = model('Member')
            ->where([
                'mobile' => $input_data['mobile'],
                'utype' => $input_data['utype']
            ])
            ->find();
        $uid = $model['uid'];
        $model->password = $model->makePassword(
            $input_data['password'],
            $model->pwd_hash
        );
        $model->save();

        /**
         * 修改会员手机号、密码。及后台修改时，清除所有登录状态，需重新登录
         * yx - 2022.11.09
         */
        model('IdentityToken')->where(['uid' => $uid])->delete(); //修改密码即删除token,

        cache('smscode_' . $input_data['mobile'], null);
        $this->ajaxReturn(200, '重置密码成功');
    }
    public function byEmail()
    {
        $input_data = [
            'utype' => input('post.utype/d', 0, 'intval'),
            'email' => input('post.email/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'password' => input('post.password/s', '', 'trim'),
        ];
        $validate = new \think\Validate([
            'utype' => 'require|in:1,2',
            'email' => 'require|checkEmail',
            'code' => 'require|max:4',
            'password' => 'require|max:15|min:6',
        ]);

        $validate->extend('checkEmail', function ($value) {
            if (fieldRegex($value, 'email')) {
                return true;
            } else {
                return '请输入正确的邮箱';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $auth_result = cache('emailcode_' . $input_data['email']);
        if (
            $auth_result === false ||
            $auth_result['code'] != $input_data['code'] ||
            $auth_result['email'] != $input_data['email'] ||
            (isset($auth_result['utype']) && $auth_result['utype'] != $input_data['utype'])
        ) {
            \think\Cache::inc('emailcode_error_num_' . $input_data['email']);
            $this->ajaxReturn(500, '验证码错误');
        }
        $error_num = \think\Cache::get(
            'emailcode_error_num_' . $input_data['email']
        );
        if ($error_num !== false && $error_num >= 5) {
            $this->ajaxReturn(500, '验证码失效，请重新获取');
        }
        $model = model('Member')
            ->where([
                'email' => $input_data['email'],
                'utype' => $input_data['utype'],
            ])
            ->find();
        $uid = $model['uid'];
        $model->password = $model->makePassword(
            $input_data['password'],
            $model->pwd_hash
        );
        $model->save();

        /**
         * 修改会员手机号、密码。及后台修改时，清除所有登录状态，需重新登录
         * yx - 2022.11.09
         */
        model('IdentityToken')->where(['uid' => $uid])->delete(); //修改密码即删除token,

        cache('emailcode_' . $input_data['email'], null);
        $this->ajaxReturn(200, '重置密码成功');
    }
}
