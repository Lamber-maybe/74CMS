<?php

namespace app\common\controller;

use think\Controller;
use think\Cookie;

class Base extends Controller
{
    protected $subsite = null;
    protected $subsiteCondition = [];
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

    public $sub_site_domain = '';
    public $sub_site_domain_m_path = '';
    public $sub_site_domain_m = '';


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
    protected function initSubsite()
    {
        if (intval(config('global_config.subsite_open')) == 0) {
            $this->sub_site_domain = config('global_config.sitedomain');
            $this->sub_site_domain_m = config('global_config.mobile_domain');
            return;
        }
        $subsiteId = 0;
        do {
            // 分账URL类型:2|首页目录
//            $routerInfo = $this->request->routeInfo();
//            $directory = isset($routerInfo['rule']['0']) ? $routerInfo['rule']['0'] : '';
//            $subsiteId = model('Subsite')
//                ->where('is_display', 1)
//                ->where('url_type', 2)
//                ->where('directory', $directory)
//                ->value('id');
//            if ($subsiteId) {
//                break;
//            }

            // 分站URL类型:1|绑定域名
            $server = $this->request->server();

            $module = strtolower($this->request->module());

            if ($module === 'index') {
                $http_host = $server['HTTP_HOST'];
                $request_scheme = $server['REQUEST_SCHEME'];
                $second_domain = $request_scheme . '://' . $http_host;
                if ($second_domain == config('global_config.sitedomain')) {
                    $subsiteId = 0;
                    break;
                }
                $subsiteId = model('Subsite')
                    ->where('is_display', 1)
                    ->where('url_type', 1)
                    ->where('second_domain', $second_domain)
                    ->value('id');
                if ($subsiteId) {
                    cookie('subsiteid', $subsiteId);
                    break;
                }
            }

            if ($module === 'v1_0') {
                if (isset($server['HTTP_ORIGIN']) && !empty($server['HTTP_ORIGIN'])) {
                    $second_domain = $server['HTTP_ORIGIN'];
                    if ($second_domain == config('global_config.sitedomain')) {
                        $subsiteId = 0;
                        break;
                    }
                    $subsiteId = model('Subsite')
                        ->where('is_display', 1)
                        ->where('url_type', 1)
                        ->where('second_domain', $second_domain)
                        ->value('id');
                    if ($subsiteId) {
                        cookie('subsiteid', $subsiteId);
                        break;
                    }
                }
            }

//            $subsiteId = Cookie::has('subsiteid') ? Cookie::get('subsiteid') : 0;
//            if ($subsiteId) {
//                break;
//            }
//            $header_info = \think\Request::instance()->header();
//            $subsiteId = isset($header_info['subsiteid']) ? $header_info['subsiteid'] : 0;
//            if ($subsiteId) {
//                break;
//            }
//            $subsiteId = input('param.subsiteid/d', 0, 'intval');
//            if ($subsiteId) {
//                break;
//            }

        } while (0);

        if ($subsiteId > 0) {
            $this->subsite = model('Subsite')->where('id', $subsiteId)->find();
            if ($this->subsite === null) {
                return;
            }
            if ($this->subsite->district3 > 0) {
                $this->subsiteCondition = ['district3' => $this->subsite->district3];
            } else if ($this->subsite->district2 > 0) {
                $this->subsiteCondition = ['district2' => $this->subsite->district2];
            } else {
                $this->subsiteCondition = ['district1' => $this->subsite->district1];
            }
            $category_district_data = model('CategoryDistrict')->getCache();
            $this->subsite->district_text = isset($category_district_data[$this->subsite->district]) ? $category_district_data[$this->subsite->district] : '';
            $this->subsite->district_text = cut_str($this->subsite->district_text, 5);
            $this->subsite->district_level = $this->subsite->district3 > 0 ? 3 : ($this->subsite->district2 > 0 ? 2 : 1);
            $this->sub_site_domain = $this->subsite['second_domain'];
            $this->sub_site_domain_m_path = str_replace(config('global_config.sitedomain'), '', config('global_config.mobile_domain'));
            $this->sub_site_domain_m = $this->subsite['second_domain'] . $this->sub_site_domain_m_path;
        } else {
            $this->sub_site_domain = config('global_config.sitedomain');
            $this->sub_site_domain_m = config('global_config.mobile_domain');
        }
        \think\Config::set('sub_site_domain', $this->sub_site_domain);
        \think\Config::set('sub_site_domain_m', $this->sub_site_domain_m);
        \think\Config::set('subsite', $this->subsite);
        \think\Config::set('subsiteid', $this->subsite === null ? 0 : $this->subsite->id);
        \think\Config::set('subsiteCondition', $this->subsiteCondition);
        if ($this->subsite !== null) {
            \think\Config::set('global_config.sitename', $this->subsite->sitename);
        }
    }

    public function filterIp()
    {
        if (!in_array($this->module_name, ['apiadmin', 'apiadminmobile'])) {
            $config = config('global_config');
            if (isset($config['filter_ip']) && $config['filter_ip'] != '') {
                $iparr = explode('|', $config['filter_ip']);
                $ip = get_client_ip();
                if (in_array($ip, $iparr)) {
                    if (in_array($this->module_name, ['v1_0'])) {
                        $this->ajaxReturn(60001, '您的IP已经被禁止访问，请联系网站管理员');
                    } else {
                        echo $this->fetch('common@deny/ipfilter');
                        exit;
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
