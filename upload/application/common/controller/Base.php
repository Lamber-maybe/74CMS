<?php
namespace app\common\controller;

class Base extends \think\Controller
{
    protected $request;
    protected $module_name;
    protected $controller_name;
    protected $action_name;
    protected $expire_platform = [
        'mobile' => 604800, //7天有效期
        'wechat' => 604800, //7天有效期
        'web' => 604800 //7天有效期
    ];
    public function _initialize()
    {
        parent::_initialize();
        $this->request = \think\Request::instance();
        $this->module_name = strtolower($this->request->module());
        $this->controller_name = strtolower($this->request->controller());
        $this->action_name = strtolower($this->request->action());
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
