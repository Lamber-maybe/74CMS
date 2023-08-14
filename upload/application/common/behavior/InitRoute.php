<?php
namespace app\common\behavior;

class InitRoute
{
    public function run(&$params)
    {
        $mobile_domain = trim(config('global_config.mobile_domain'),"http://");
        $mobile_domain = trim($mobile_domain,"https://");
        $mobile_domain = trim($mobile_domain,"/");
        \think\Route::domain($mobile_domain,'index/Mobile');
    }
}
