<?php
namespace app\common\lib\oauth;
class qq
{
    protected $appid;
    protected $appkey;
    protected $error = 0;
    public function __construct()
    {
        $this->appid = config('global_config.account_qqlogin_appid');
        $this->appkey = config('global_config.account_qqlogin_appkey');
    }
    /**
     * 获取openid
     */
    public function getOpenid($access_token)
    {
        $url = 'https://graph.qq.com/oauth2.0/me?access_token=' . $access_token;
        $http = new \app\common\lib\Http();
        $response = $http->get($url);
        if (strpos($response, 'callback') !== false) {
            $lpos = strpos($response, '(');
            $rpos = strrpos($response, ')');
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }
        $user = json_decode($response, true);
        if (isset($user['error'])) {
            $this->error = $user['error_description'];
            return false;
        }
        return $user->openid;
    }
    /**
     * 获取unionid
     */
    public function getUnionid($access_token)
    {
        $url =
            'https://graph.qq.com/oauth2.0/me?access_token=' .
            $access_token .
            '&unionid=1';
        $http = new \app\common\lib\Http();
        $response = $http->get($url);
        if (strpos($response, 'callback') !== false) {
            $lpos = strpos($response, '(');
            $rpos = strrpos($response, ')');
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }
        $user = json_decode($response, true);
        if (isset($user['error'])) {
            $this->error = $user['error_description'];
            return false;
        }
        return $user;
    }
    /**
     * 获取用户信息
     */
    public function getUserinfo($access_token)
    {
        $union_info = $this->getUnionid($access_token);
        if ($union_info === false) {
            return false;
        }
        $openid = $union_info['openid'];
        $unionid = $union_info['unionid'];
        $url =
            'https://graph.qq.com/user/get_user_info?access_token=' .
            $access_token .
            '&oauth_consumer_key=' .
            $this->appid .
            '&openid=' .
            $openid;
        $http = new \app\common\lib\Http();
        $response = $http->get($url);
        if (strpos($response, 'callback') !== false) {
            $lpos = strpos($response, '(');
            $rpos = strrpos($response, ')');
            $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
        }
        $userinfo = json_decode($response, true);
        if (isset($userinfo['error'])) {
            $this->error = $userinfo['error_description'];
            return false;
        }
        return [
            'openid' => $openid,
            'unionid' => $unionid,
            'nickname' => $userinfo['nickname'],
            'avatar' => $userinfo['figureurl_qq_2']?$userinfo['figureurl_qq_2']:$userinfo['figureurl_qq_1']
        ];
    }
    public function getError()
    {
        return $this->error;
    }
}
?>
