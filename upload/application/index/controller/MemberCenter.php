<?php
namespace app\index\controller;

class MemberCenter extends \think\Controller
{
    public function index()
    {
        return $this->fetch('./tpl/member/index.html');
    }
}
