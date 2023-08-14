<?php
namespace app\v1_0\controller\home;

class Share extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function wechat()
    {
        $url = input('get.url/s','','trim');
        $alias = input('get.alias/s','','trim');
        $wechat = new \app\common\lib\Wechat;
        $access_token = $wechat->getAccessToken();
        $jssdk = new \app\common\lib\Jssdk(config('global_config.wechat_appid'), config('global_config.wechat_appsecret'),$access_token);
        $signPackage = $jssdk->GetSignPackage($url);
        $data = model('WechatShare')->field('content,img,alias,explain,params')->where('alias',$alias)->find();
        $this->ajaxReturn(200, '获取数据成功',['config'=>$signPackage,'data'=>$data]);
    }
}