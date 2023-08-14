<?php
namespace app\v1_0\controller\home;

class Captcha extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    /**
     * 获取图片验证码
     */
    public function picture()
    {
        $captcha = new \app\common\lib\Captcha('picture');
        $data = $captcha->getData();
        $this->ajaxReturn(200, '获取验证码成功', $data);
    }
}
