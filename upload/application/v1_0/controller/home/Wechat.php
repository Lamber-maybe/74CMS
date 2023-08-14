<?php
namespace app\v1_0\controller\home;

class Wechat extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function getOpenid()
    {
        $code = input('post.code');
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.config('global_config.payment_wechat_appid').'&secret='.config('global_config.payment_wechat_appsecret').'&code='.$code.'&grant_type=authorization_code';
        $http = new \app\common\lib\Http;
        $result = $http->get($url);
        $result = json_decode($result,1);
        if(isset($result['openid'])){
            $this->ajaxReturn(200, '获取数据成功',$result['openid']);
        }else{
            $this->ajaxReturn(200, '获取数据成功','');
        }
    }
}