<?php
/**
 * 验证手机号等
 */
namespace app\v1_0\controller\member;

class Auth extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin();
    }
    /**
     * 修改联系方式手机号时验证
     */
    public function mobile()
    {
        $input_data = [
            'mobile' => input('post.mobile/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim')
        ];
        $validate = new \think\Validate([
            'mobile' => 'require|checkMobile',
            'code' => 'require|max:4'
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
            $auth_result['mobile'] != $input_data['mobile']
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
        cache('smscode_' . $input_data['mobile'], null);
        $this->ajaxReturn(200, '验证通过');
    }
}
