<?php
namespace app\common\model;

class WechatNotifyRule extends \app\common\model\BaseModel
{
    protected $template = [
        'touser'=>'',
        'template_id'=>'',
        'url'=>'',
        'topcolor'=>"#7B68EE",
        'data'=>[]
    ];
    protected $tplRule;
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_wechat_notify_rule', null);
        });
    }
    
    public function getCache()
    {
        if (false === ($list = cache('cache_wechat_notify_rule'))) {
            $data = $this->field(true)->select();
            $list = [];
            foreach ($data as $key => $value) {
                $list[$value['utype']][$value['alias']] = $value;
            }
            cache('cache_wechat_notify_rule', $list);
        }
        return $list;
    }
    public function notify($uid,$utype,$alias,$data=[],$url="") {
        if(!$uid || !$utype || !$alias){
            return;
        }
        $ruleAll = $this->getCache();
        if (!isset($ruleAll[$utype])) {
            return;
        }
        $ruleAll = $ruleAll[$utype];
        if (!isset($ruleAll[$alias])) {
            return;
        }
        $this->tplRule = $ruleAll[$alias];
        if ($this->tplRule['is_open'] != 1 || $this->tplRule['tpl_id'] == '' || config('global_config.wechat_open')!=1) {
            return;
        }
        $this->template['template_id'] = $this->tplRule['tpl_id'];
        if($url!=''){
            $this->template['url'] = config('global_config.mobile_domain').$url;
        }
        $this->initTplData($data);

        $openid_arr = $this->initTplUser($uid);
        if($openid_arr===false){
            return;
        }
        foreach ($openid_arr as $key => $value) {
            $this->template['touser'] = $value;
            $instance = new \app\common\lib\Wechat;
            $instance->buildTplMsg($this->template);
        }
    }
    /**
     * 给模板中data赋值
     */
    protected function initTplData($data){
        $tpl_data = json_decode($this->tplRule['tpl_data'],true);
        foreach ($tpl_data as $key => $value) {
            $this->template['data'][$value] = [
                'value'=>$data[$key],
                'color'=>'#743A3A'
            ];
        }
    }
    /**
     * 给模板中touser赋值
     */
    protected function initTplUser($uid){
        $uidarr = is_array($uid)?$uid:[$uid];
        $userdata = model('MemberBind')->whereIn('uid',$uidarr)->where('type','weixin')->where('is_subscribe',1)->select();
        $return_openidarr = [];
        foreach ($userdata as $key => $value) {
            $return_openidarr[] = $value['openid'];
        }
        if(empty($return_openidarr)){
            return false;
        }
        return $return_openidarr;
    }
}
