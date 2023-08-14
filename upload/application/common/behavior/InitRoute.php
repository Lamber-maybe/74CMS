<?php
namespace app\common\behavior;

class InitRoute
{
    public function run(&$params)
    {
        $instance = new \app\common\lib\Route();
        $rule = $instance->getRule(config('global_config.route_rule'));
        if (!empty($rule)) {
            \think\Route::rule($rule['rule'], '', 'GET', [
                'ext' => $rule['ext']
            ]);
        }
    }
}
