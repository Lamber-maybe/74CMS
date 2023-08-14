<?php
namespace app\index\controller;

class Captcha extends \app\index\controller\Base
{
    public function index()
    {
        if(config('global_config.captcha_open')==1){
            $type = config('global_config.captcha_type');
            if($type=='vaptcha'){
                return $this->fetch('vaptcha');
            }
            if($type=='tencent'){
                return $this->fetch('tencent');
            }
        }
    }
}
