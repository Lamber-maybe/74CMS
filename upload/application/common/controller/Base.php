<?php
namespace app\common\controller;
use think\Cookie;

class Base extends \think\Controller
{
    protected $subsite=null;
    protected $subsiteCondition=[];
    protected $request;
    protected $module_name;
    protected $controller_name;
    protected $action_name;
    protected $expire_platform = [
        'app' => 604800, //7天有效期
        'mobile' => 604800, //7天有效期
        'wechat' => 604800, //7天有效期
        'web' => 604800 //7天有效期
    ];
    public function _initialize()
    {
        parent::_initialize();
        /**
         * 【ID1000446】
         * 【新增】后台控制登录时效，默认7天
         * yx - 2022.11.29
         * 登录状态有效期，登录状态失效后会自动退出账号，最小10分钟。单位分钟。
         */
        $token_expire = config('global_config.token_expire');
        if (intval($token_expire) >= 10) {
            $token_expire = $token_expire * 60;
            $this->expire_platform = [
                'app' => $token_expire,
                'mobile' => $token_expire,
                'wechat' => $token_expire,
                'miniprogram' => $token_expire,
                'tiktok' => $token_expire,
                'web' => $token_expire
            ];
        }
        $this->request = \think\Request::instance();
        $this->module_name = strtolower($this->request->module());
        $this->controller_name = strtolower($this->request->controller());
        $this->action_name = strtolower($this->request->action());
        $this->filterIp();
        $this->initSubsite();
    }
    /**
     * 初始化分站信息
     */
    protected function initSubsite(){
        if(intval(config('global_config.subsite_open'))==0){
            return;
        }
        $subsiteid = 0;
        do{
            $subsiteid = Cookie::has('subsiteid')?Cookie::get('subsiteid'):0;
            if($subsiteid){
                break;
            }
            $header_info = \think\Request::instance()->header();
            $subsiteid = isset($header_info['subsiteid']) ? $header_info['subsiteid'] : 0;
            if($subsiteid){
                break;
            }
            $subsiteid = input('param.subsiteid/d',0,'intval');
        }while(0);
        if($subsiteid>0){
            $this->subsite = model('Subsite')->where('id',$subsiteid)->find();
            if($this->subsite===null){
                return;
            }
            if($this->subsite->district3>0){
                $this->subsiteCondition = ['district3'=>$this->subsite->district3];
            }else if($this->subsite->district2>0){
                $this->subsiteCondition = ['district2'=>$this->subsite->district2];
            }else{
                $this->subsiteCondition = ['district1'=>$this->subsite->district1];
            }
            $category_district_data = model('CategoryDistrict')->getCache();
            $this->subsite->district_text = isset($category_district_data[$this->subsite->district]) ? $category_district_data[$this->subsite->district] : '';
            $this->subsite->district_text = cut_str($this->subsite->district_text,5);
            $this->subsite->district_level = $this->subsite->district3>0?3:($this->subsite->district2>0?2:1);
        }
        \think\Config::set('subsite', $this->subsite);
        \think\Config::set('subsiteid', $this->subsite===null?0:$this->subsite->id);
        \think\Config::set('subsiteCondition', $this->subsiteCondition);
        if($this->subsite!==null){
            \think\Config::set('global_config.sitename',$this->subsite->sitename);
        }
    }
    public function filterIp(){
        if(!in_array($this->module_name,['apiadmin','apiadminmobile'])){
            $config = config('global_config');
			//dump($config);die;
            if(isset($config['filter_ip']) && $config['filter_ip']!=''){
                $iparr = explode('|',$config['filter_ip']);
                $ip = get_client_ip();
                if(in_array($ip,$iparr)){
                    if(in_array($this->module_name,['v1_0'])){
                        $this->ajaxReturn(60001,'您的IP已经被禁止访问，请联系网站管理员');
                    }else{
                        echo $this->fetch('common@deny/ipfilter');exit;
                    }
                }
            }
        }
    }
    protected function ajaxReturn($code = 200, $message = '', $data = [])
    {
        $return = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        exit(json_encode($return, JSON_UNESCAPED_UNICODE));
    }
    protected function auth($request_token)
    {
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
