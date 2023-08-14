<?php
namespace app\common\lib\oauth;
class weixin
{
    protected $openid;
    protected $appid;
    protected $appsecret;
    protected $error = 0;
    public function __construct()
    {
        $this->appid = config('global_config.wechat_open_appid');
        $this->appsecret = config('global_config.wechat_open_appsecret');
    }

    /**
     * 获取access_token
     */
    public function getAccessToken($code)
    {
        $url =
            'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' .
            $this->appid .
            '&secret=' .
            $this->appsecret .
            '&code=' .
            $code .
            '&grant_type=authorization_code';
        $http = new \app\common\lib\Http();
        $response = $http->get($url);
        $msg = json_decode($response, true);
        if (isset($msg['errcode'])) {
            $this->error = $msg['errmsg'];
            return false;
        }
        $this->openid = $msg['openid'];
        return $msg['access_token'];
    }
    /**
     * 获取用户信息
     */
    public function getUserinfo($code)
    {
        $access_token = $this->getAccessToken($code);
        if($access_token===false){
            return false;
        }
        $url =
            'https://api.weixin.qq.com/sns/userinfo?access_token=' .
            $access_token .
            '&openid=' .
            $this->openid;
        $http = new \app\common\lib\Http();
        $response = $http->get($url);
        $userinfo = json_decode($response, true);
        if (isset($userinfo['errcode'])) {
            $this->error = $userinfo['errmsg'];
            return false;
        }
        return [
            'openid' => $userinfo['openid'],
            'unionid' => isset($userinfo['unionid'])?$userinfo['unionid']:'',
            'nickname' => $userinfo['nickname'],
            'avatar' => $userinfo['headimgurl']
        ];
    }
    public function getError()
    {
        return $this->error;
    }
}
?>
