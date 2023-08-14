<?php
namespace app\index\controller;

class Screen extends \app\index\controller\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $screen_token = input('get.token/s','','trim');
        if(config('global_config.screen_token')=='' || $screen_token!=config('global_config.screen_token')){
            abort(500,'token无效，请联系网站管理员');
        }
        return $this->fetch('index');
    }
}
