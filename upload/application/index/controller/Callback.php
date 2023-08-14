<?php
namespace app\index\controller;

class Callback extends \app\index\controller\Base
{
    /**
     * 支付宝异步回调
     */
    public function alipayNotify()
    {
        $pay = new \app\common\lib\Pay('', 'alipay');
        $verify_result = $pay->alipayNotify($_POST);
        if ($verify_result) {
            exit('success');
        } else {
            exit('fail');
        }
    }
    /**
     * 支付宝同步回调
     */
    // public function alipayReturn()
    // {
    //     $pay = new \app\common\lib\Pay('', 'alipay');
    //     $verify_result = $pay->alipayNotifyReturn($_GET);
    //     $this->redirect(
    //         config('global_config.sitedomain') .
    //         config('global_config.sitedir') .
    //         'm/personal/order/detail?oid=' .
    //         $verify_result,
    //         302
    //     );
    // }
    /**
     * 微信回调
     */
    public function wxpayNotify()
    {
        $pay = new \app\common\lib\Pay('', 'wxpay');
        echo $verify_result = $pay->wxpayNotify();
    }
    /**
     * oauth第三方登录回调
     * 暂时废弃
     */
    // public function oauth()
    // {
    //     $state = input('get.state/s', '', 'trim');
    //     $code = input('get.code/s', '', 'trim');
    //     $mod = input('get.mod/s', 'qq', 'trim');
    //     $oauth = new \app\common\lib\oauth($mod);
    //     $userinfo = $oauth->callback($code, $state);
    //     if (false === $userinfo) {
    //         exit($oauth->getError());
    //     }
    //     $userinfo['mod'] = $mod;

    //     //获取到了openid之后在系统中查询用户绑定情况
    //     //1.查询是否有绑定信息
    //     if ($mod == 'sina') {
    //         $where['type'] = 'sina';
    //         $where['openid'] = $userinfo['openid'];
    //     } else {
    //         $where['type'] = $mod;
    //         $where['unionid'] = $userinfo['unionid'];
    //     }
    //     $bind_info = model('MemberBind')
    //         ->where($where)
    //         ->find();
    //     if ($bind_info === null) {
    //         //没有绑定信息，跳转到绑定页面
    //         $param_str = http_build_query($userinfo);
    //         $this->redirect(
    //             'https://127.0.0.1/74cmsx/public/mobile/bind?' . $param_str
    //         );
    //     } elseif ($mod != 'sina') {
    //         //有绑定信息，进一步确认openid是否已绑定，如果没绑定默认给绑定(新浪除外)
    //         $bind_info_other = model('MemberBind')
    //             ->where(['type' => $mod, 'openid' => $userinfo['openid']])
    //             ->find();
    //         if ($bind_info_other === null) {
    //             $sqlarr['uid'] = $bind_info['uid'];
    //             $sqlarr['type'] = $bind_info['type'];
    //             $sqlarr['openid'] = $userinfo['openid'];
    //             $sqlarr['unionid'] = $userinfo['unionid'];
    //             $sqlarr['nickname'] = $userinfo['nickname'];
    //             $sqlarr['avatar'] = $userinfo['avatar'];
    //             $sqlarr['bindtime'] = $userinfo['bindtime'];
    //             model('MemberBind')->save($sqlarr);
    //         }
    //         //登录操作
    //     }
    // }
}
