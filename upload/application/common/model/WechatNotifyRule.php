<?php

namespace app\common\model;

class WechatNotifyRule extends \app\common\model\BaseModel
{
    protected $template = [
        'touser' => '',
        'template_id' => '',
        'url' => '',
        'data' => []
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
            $data = collection($data)->toArray();
            $list = [];
            foreach ($data as $key => $value) {
                $list[$value['tpl_type']][$value['utype']][$value['alias']] = $value;
            }
            $wechatTplType = config('global_config.wechat_tpl_type');
            $wechatTplType = !empty($wechatTplType) ? $wechatTplType : 1;
            if (isset($list[$wechatTplType]) && !empty($list[$wechatTplType])) {
                $list = $list[$wechatTplType];
            } else {
                $list = [];
            }
            cache('cache_wechat_notify_rule', $list);
        }
        return $list;
    }

    public function notify($uid, $utype, $alias, $data = [], $url = "")
    {
        if (!$uid || !$utype || !$alias) {
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
        if ($url != '') {
            $this->template['url'] = config('global_config.mobile_domain') . $url;
        }
        $this->initTplData($data);

        $openid_arr = $this->initTplUser($uid);
        if ($openid_arr === false) {
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
    protected function initTplData($data)
    {
        $tpl_data = json_decode($this->tplRule['tpl_data'], true);
        /**
         * 排除 first remark
         * 因为微信模板已取消这两个字段
         * cy 2023-10-18
         */
        $remove = ['first', 'remark'];
        $tpl_data = array_values(array_diff($tpl_data, $remove));

        foreach ($tpl_data as $key => $value) {
            if (isset($data[$key]) && !empty($data[$key])) {
                $this->template['data'][$value] = [
                    'value' => $data[$key]
                ];
            }
        }
    }

    /**
     * 给模板中touser赋值
     */
    protected function initTplUser($uid)
    {
        $uidarr = is_array($uid) ? $uid : [$uid];
        $userdata = model('MemberBind')->whereIn('uid', $uidarr)->where('type', 'weixin')->where('is_subscribe', 1)->select();
        $return_openidarr = [];
        foreach ($userdata as $key => $value) {
            $return_openidarr[] = $value['openid'];
        }
        if (empty($return_openidarr)) {
            return false;
        }
        return $return_openidarr;
    }
}
