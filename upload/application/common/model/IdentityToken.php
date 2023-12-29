<?php

namespace app\common\model;

class IdentityToken extends \app\common\model\BaseModel
{
    public function makeToken($uid, $token, $expire = 86400)
    {
        $mdtoken = md5($token);
        $identity_token_data = model('IdentityToken')->where('mdtoken', $mdtoken)->find();
        if ($identity_token_data !== null) {
            $identity_token_data->delete();
        }
        model('IdentityToken')->save(['mdtoken' => $mdtoken, 'updatetime' => time(), 'expire' => $expire, 'uid' => $uid]);
    }

    public function refreshToken($token)
    {
        $mdtoken = md5($token);
        $identity_token_data = model('IdentityToken')->where('mdtoken', $mdtoken)->find();
        if ($identity_token_data === null || ($identity_token_data->updatetime + $identity_token_data->expire) < time()) {
            (new \app\common\lib\Visitor)->setLogout();
            $identity_token_data !== null && $identity_token_data->delete();
            return false;
        } else {
            $identity_token_data->updatetime = time();
            $identity_token_data->save();
            (new \app\common\lib\Visitor)->refreshLogin($identity_token_data->expire);
            return true;
        }
    }
}
