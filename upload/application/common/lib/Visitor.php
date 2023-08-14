<?php
/**
 * visitor管理
 *
 * @author
 */

namespace app\common\lib;

class Visitor
{
    public function setLogin($visitor,$expire)
    {
        cookie('visitor', json_encode($visitor), $expire);
    }
    public function setLogout()
    {
        cookie('visitor',null);
    }
    public function getLoginInfo()
    {
        $visitor = cookie('visitor');
        if($visitor){
            $visitor = json_decode($visitor,true);
            try {
                $user_token = isset($visitor['token'])?$visitor['token']:'';
                $auth_result = $this->auth($user_token);
                if ($auth_result['code'] == 200) {
                    $auth_info = $auth_result['info'];
                    $visitor['uid'] = $auth_info->uid;
                }
            } catch (\Exception $e) {
                return null;
            }
        }
        return $visitor; 
    }
    protected function auth($request_token){
        $token = \app\common\lib\JwtAuth::getToken($request_token);
        if ($token->isExpired()) {
            return ['code' => 50002, 'info' => 'token失效'];
        }
        if (!$token->verify(config('sys.safecode'))) {
            return ['code' => 50001, 'info' => '非法token'];
        }
        return ['code' => 200, 'info' => $token->getData('info')];
    }
}
