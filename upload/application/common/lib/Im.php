<?php

/**
 * 即时通讯
 *
 * @author
 */

namespace app\common\lib;

class Im
{
    protected $baseUrl = '';
    protected $config = [
        'app_key' => '',
        'app_secret' => ''
    ];
    public function __construct()
    {
        $this->config = config('global_config.account_im');
        $this->baseUrl = config('global_config.im_server');
    }
    public function getAccessToken()
    {
        $access_token = cache('im_access_token');
        if (!$access_token) {
            $url =
                $this->baseUrl .
                '/access_token?app_key=' .
                $this->config['app_key'] .
                '&app_secret=' .
                $this->config['app_secret'];
            $http = new \app\common\lib\Http();
            $result = $http->get($url);
            if ($result === false) {
                throw new \Exception($http->getError());
            }
            $result = json_decode($result, true);
            if ($result['code'] != 200) {
                throw new \Exception($result['msg']);
            }
            cache('im_access_token', $result['result']['access_token'], 7200);
            $access_token = $result['result']['access_token'];
        }
        return $access_token;
    }
    public function getUserToken($userinfo)
    {
        $access_token = $this->getAccessToken();
        $url =
            $this->baseUrl .
            '/user_token?access_token=' .
            $access_token .
            '&uid=' .
            $userinfo['userid'] .
            '&nickname=' .
            $userinfo['nickname'] .
            '&avatar=' .
            $userinfo['avatar'];
        $http = new \app\common\lib\Http();
        $result = $http->get($url);
        if ($result === false) {
            throw new \Exception($http->getError());
        }
        $result = json_decode($result, true);
        if ($result['code'] != 200) {
            throw new \Exception($result['msg']);
        }
        return $result['result']['token'];
    }
}
