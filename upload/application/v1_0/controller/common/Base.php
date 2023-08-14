<?php
namespace app\v1_0\controller\common;

use think\Request;

class Base extends \app\common\controller\Base
{
    protected $userinfo = null;
    protected $platform;
    protected $company_profile;
    protected $resume_info;

    public function _initialize()
    {
        if(Request::instance()->method() == 'OPTIONS'){
            exit;
        }
        parent::_initialize();
        $this->platform = input('param.platform/s','','trim');
        if($this->platform==''){
            $header_info = \think\Request::instance()->header();
            $this->platform = isset($header_info['platform']) ? $header_info['platform'] : '';
        }
        if(!$this->platform){
            $this->platform = 'wechat';
        }
        \think\Config::set('platform', $this->platform);
        if(config('global_config.isclose')==1){
            $this->ajaxReturn(50008, '网站暂时关闭',config('global_config.close_reason'));
        }
        $this->initUserinfo();
    }
    /**
     * 检查是否登录
     */
    public function checkLogin($need_utype = 0)
    {
        if ($need_utype == 0) {
            $code = 50009;
            $tip = '请先登录';
        } else {
            $tip =
                '当前操作需要登录' .
                ($need_utype == 1 ? '企业' : '个人') .
                '会员';
            $code = $need_utype==1?50011:50010;
        }

        if (
            $this->userinfo === null ||
            ($need_utype > 0 && $this->userinfo->utype != $need_utype)
        ) {

            $this->ajaxReturn($code, $tip);
        }
        if($this->userinfo !== null){
            $member = model('Member')
                ->field(true)
                ->where('uid', $this->userinfo->uid)
                ->find();
            if($member===null){
                $this->ajaxReturn(50002, '请先登录');
            }else{
                if($member['last_login_time']==0 || strtotime(date('Y-m-d',$member['last_login_time']))!=strtotime('today')){
                    $this->writeMemberActionLog($member['uid'],'登录成功',true);
                }
            }
        }
    }
    /**
     * 初始化会员登录信息
     */
    public function initUserinfo()
    {
        $user_token = input('param.user-token/s','','trim');
        if($user_token==''){
            $header_info = \think\Request::instance()->header();
            $user_token = isset($header_info['user-token']) ? $header_info['user-token'] : '';
        }
        if ($user_token) {
            try {
                $auth_result = $this->auth($user_token);
                if ($auth_result['code'] == 200) {
                    $this->userinfo = $auth_result['info'];
                }
            } catch (\Exception $e) {
            }
            //判断token是否过期，如果没有过期，更新token有效期
            if($this->userinfo!==null){
                $refresh_result = model('IdentityToken')->refreshToken($user_token);
                if($refresh_result===false){
                    $this->userinfo = null;
                }
            }
        }
    }
    protected function writeMemberActionLog($uid,$content,$isLogin=false)
    {
        $memberinfo = model('Member')
            ->where('uid', $uid)
            ->find();
        if($isLogin===true){
            $memberinfo->last_login_time = time();
            $memberinfo->last_login_ip = get_client_ip();
            $memberinfo->last_login_address = get_client_ipaddress(
                $memberinfo->last_login_ip
            );
            $memberinfo->last_login_ip =
                $memberinfo->last_login_ip . ':' . get_client_port();
            $memberinfo->nologin_notice_counter = 0;
            $memberinfo->save();

            // 自动刷新简历 chenyang 2022年3月10日15:42:14
            model('Resume')->refreshResumeData($memberinfo);
        }
        $action_log_data['utype'] = $memberinfo->utype;
        $action_log_data['uid'] = $uid;
        $action_log_data['content'] = $content;
        $action_log_data['addtime'] = time();
        $action_log_data['ip'] = get_client_ip();
        $action_log_data['ip_addr'] = get_client_ipaddress($action_log_data['ip']);
        $action_log_data['ip'] = $action_log_data['ip'] . ':' . get_client_port();
        $action_log_data['platform'] = config('platform');
        $action_log_data['is_login'] = $isLogin?1:0;
        model('MemberActionLog')->save($action_log_data);
    }
    public function loginExtra($uid, $utype, $mobile)
    {
        $this->writeMemberActionLog($uid,'登录成功',true);
        $JwtAuth = \app\common\lib\JwtAuth::mkToken(
            config('sys.safecode'),
            31212000, //360天有效期
            [
                'info' => [
                    'uid' => $uid,
                    'utype' => $utype,
                    'mobile' => $mobile
                ]
            ]
        );
        $user_token = $JwtAuth->getString();
        //把token存入数据表，并设置有效期
        model('IdentityToken')->makeToken($uid, $user_token,$this->expire_platform[config('platform')]);

        if ($utype == 1) {
            $next_code = $this->interceptCompanyProfile(true, $uid);
            if ($next_code == 200) {
                $next_code = $this->interceptCompanyAuth(true, $uid);
            }
        } else {
            $next_code = $this->interceptPersonalResume(true, $uid);
        }
        $visitor = new \app\common\lib\Visitor;
        $visitor->setLogin([
            'utype'=>$utype,
            'mobile' => $mobile,
            'token'=>$user_token
        ],$this->expire_platform[config('platform')]);
        return [
            'uid' => $uid,
            'token' => $user_token,
            'utype' => $utype,
            'mobile' => $mobile,
            'next_code' => $next_code
        ];
    }
    /**
     * 拦截企业基本资料
     */
    public function interceptCompanyProfile($after_login = false, $uid = null)
    {
        if ($after_login === true) {
            $err_code = 200;
            $this->company_profile = model('Company')
                ->field(true)
                ->where('uid', 'eq', $uid)
                ->find();
            if (
                $this->company_profile === null ||
                $this->company_profile['district'] == 0
            ) {
                $err_code = 50003;
            }
            return $err_code;
        } else {
            if ($this->userinfo->utype == 2) {
                $this->ajaxReturn(500, '当前操作需要登录企业会员');
            }
            $this->company_profile = model('Company')
                ->field(true)
                ->where('uid', 'eq', $this->userinfo->uid)
                ->find();
            if (
                $this->company_profile === null ||
                $this->company_profile['district'] == 0
            ) {
                $this->ajaxReturn(50003, '请先填写企业资料');
            }
        }
    }
    /**
     * 拦截企业认证
     */
    public function interceptCompanyAuth($after_login = false, $uid = null)
    {
        if ($after_login === true) {
            $err_code = 200;
            if (config('global_config.must_com_audit_certificate') == 1) {
                $this->company_profile = model('Company')
                    ->field(true)
                    ->where('uid', 'eq', $uid)
                    ->find();
                if ($this->company_profile['audit'] != 1) {
                    $err_code = 50004;
                }
            }
            return $err_code;
        } else {
            if ($this->userinfo->utype == 2) {
                $this->ajaxReturn(500, '当前操作需要登录企业会员');
            }
            //不强制认证，验证通过
            if (config('global_config.must_com_audit_certificate') == 1) {
                if ($this->company_profile['audit'] != 1) {
                    $this->ajaxReturn(50004, '请先认证企业');
                }
            }
        }
    }
    /**
     * 拦截简历信息
     */
    public function interceptPersonalResume($after_login = false, $uid = null)
    {
        if ($after_login === true) {
            $err_code = 200;
            do {
                $this->resume_info = model('Resume')
                    ->field(true)
                    ->where('uid', 'eq', $uid)
                    ->find();
                if ($this->resume_info === null) {
                    $err_code = 50007;
                    break;
                }
                $intention = model('ResumeIntention')
                    ->field('id')
                    ->where('rid', $this->resume_info['id'])
                    ->find();
                if ($intention === null) {
                    $err_code = 50005;
                    break;
                }
            } while (0);
            return $err_code;
        } else {
            if ($this->userinfo->utype == 1) {
                $this->ajaxReturn(500, '当前操作需要登录个人会员');
            }
            $this->resume_info = model('Resume')
                ->field(true)
                ->where('uid', 'eq', $this->userinfo->uid)
                ->find();
            if ($this->resume_info === null) {
                $this->ajaxReturn(50007, '请先添加一份简历', [
                    'basic' => [],
                    'contact' => []
                ]);
            }
            $intention = model('ResumeIntention')
                ->field('id')
                ->where('rid', $this->resume_info['id'])
                ->find();
            if ($intention === null) {
                $contact = model('ResumeContact')
                    ->where('uid', $this->userinfo->uid)
                    ->find();
                $this->ajaxReturn(50005, '请先完善简历', [
                    'basic' => $this->resume_info,
                    'contact' => $contact
                ]);
            }
        }
    }
    /**
     * 计算入驻时长
     */
    protected function getDuration($addtime){
        $minus = time() - $addtime;
        $month_count = $minus/3600/24/30;
        if($month_count<1){
            return '<1个月';
        }else{
            return round($month_count).'个月';
        }
    }

}
