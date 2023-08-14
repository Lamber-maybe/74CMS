<?php
namespace app\common\behavior;

class InitConfig
{
    public function run(&$params)
    {
        $qscms_config = model('\app\common\model\Config')->getCacheAll();
        $qscms_config['mobile_domain'] = $qscms_config['mobile_domain']?$qscms_config['mobile_domain']:($qscms_config['sitedomain'].$qscms_config['sitedir'].'m/');
        \think\Config::set('global_config', $qscms_config);
    }
}
