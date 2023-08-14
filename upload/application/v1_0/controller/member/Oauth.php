<?php
namespace app\v1_0\controller\member;

class Oauth extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function qq()
    {
        $accessToken = input('post.accessToken/s');
        $oauth = new \app\common\lib\oauth\qq;
        $user = $oauth->getUserinfo($accessToken);
        if($user===false){
            $this->ajaxReturn(500,$oauth->getError());
        }
        $this->ajaxReturn(200, '获取数据成功', $user);
    }
    public function weixin()
    {
        $code = input('post.code/s');
        $oauth = new \app\common\lib\oauth\weixin;
        $user = $oauth->getUserinfo($code);
        if($user===false){
            $this->ajaxReturn(500,$oauth->getError());
        }
        $this->ajaxReturn(200, '获取数据成功', $user);
    }
    public function weixinOffiaccount()
    {
        $code = input('post.code/s');
        $oauth = new \app\common\lib\oauth\offiaccount;
        $user = $oauth->getUserinfo($code);
        if($user===false){
            $this->ajaxReturn(500,$oauth->getError());
        }
        $this->ajaxReturn(200, '获取数据成功', $user);
    }
}
