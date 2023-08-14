<?php
/**
 * 账号申诉
 */
namespace app\v1_0\controller\member;

class Appeal extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 提交数据
     */
    public function index()
    {
        $input_data = [
            'realname' => input('post.realname/s', '', 'trim'),
            'mobile' => input('post.mobile/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'description' => input('post.description/s', '', 'trim')
        ];
        $validate = new \think\Validate([
            'realname' => 'require|max:30',
            'mobile' => 'require|checkMobile',
            'code' => 'require|max:4',
            'description' => 'require|max:100'
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
        $input_data['addtime'] = time();
        $input_data['status'] = 0;
        model('MemberAppeal')
            ->allowField(true)
            ->save($input_data);
        cache('smscode_' . $input_data['mobile'], null);
        $this->ajaxReturn(200, '提交成功');
    }
}
