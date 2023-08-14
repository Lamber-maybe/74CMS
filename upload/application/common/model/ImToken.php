<?php
namespace app\common\model;

class ImToken extends \app\common\model\BaseModel
{
    public function regToken($uid, $utype = 1)
    {
        if ($utype == 1) {
            $cominfo = model('Company')
                ->field('companyname,short_name,logo')
                ->where('uid', $uid)
                ->find();
            if ($cominfo === null) {
                $userinfo['nickname'] = '';
                $userinfo['avatar'] = model('Uploadfile')->getFileUrl(0);
            }else{
                $userinfo['nickname'] = $cominfo['short_name'] != '' ? $cominfo['short_name'] : $cominfo['companyname'];
                $userinfo['avatar'] = model('Uploadfile')->getFileUrl($cominfo['logo']);
            }
        } else {
            $resumeinfo = model('Resume')
                ->field('fullname,sex,photo_img')
                ->where('uid', $uid)
                ->find();
            if ($resumeinfo === null) {
                $userinfo['nickname'] = '';
                $userinfo['avatar'] = model('Uploadfile')->getFileUrl(0);
            }else{
                if ($resumeinfo['sex'] == 1) {
                    $userinfo['nickname'] = cut_str(
                        $resumeinfo['fullname'],
                        1,
                        0,
                        '先生'
                    );
                } elseif ($resumeinfo['sex'] == 2) {
                    $userinfo['nickname'] = cut_str(
                        $resumeinfo['fullname'],
                        1,
                        0,
                        '女士'
                    );
                } else {
                    $userinfo['nickname'] = cut_str(
                        $resumeinfo['fullname'],
                        1,
                        0,
                        '**'
                    );
                }
                $userinfo['avatar'] = model('Uploadfile')->getFileUrl(
                    $resumeinfo['photo_img']
                );
            }
        }
        $userinfo['userid'] = md5(md5($uid) . config('sys.safecode'));
        $im = new \app\common\lib\Im();
        try {
            $user_token = $im->getUserToken($userinfo);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
        if (null === $this->where('uid', $uid)->find()) {
            $data['uid'] = $uid;
            $data['im_userid'] = $userinfo['userid'];
            $data['token'] = $user_token;
            $this->save($data);
        }

        return true;
    }
    public function getUserImInfo($uid,$utype)
    {
        $info = $this->where('uid', $uid)->find();
        if ($info === null) {
            $this->regToken($uid,$utype);
            $info = $this->where('uid', $uid)->find();
        }
        $return = [
            'userid' => isset($info['im_userid'])?$info['im_userid']:'',
            'user_token' => isset($info['token'])?$info['token']:''
        ];
        return $return;
    }
}
