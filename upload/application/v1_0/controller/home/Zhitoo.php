<?php

namespace app\v1_0\controller\home;

use app\common\lib\zhitoo\ResumeAnalysis;

class Zhitoo extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function documentCheck()
    {
        $resumeAnalysis = new ResumeAnalysis(false);
        $check = $resumeAnalysis->check();

        if (false === $check) {
            $return = [
                'is_check' => 0,
                'total' => 0,
                'list' => []
            ];
            $this->ajaxReturn(200, $resumeAnalysis->getError(), $return);
        } else {
            if (config('global_config.account_zhitoo_resume')['is_open'] == 1 && $check['is_check'] == 1) {
                $check['can_use'] = 1;
            } else {
                $check['can_use'] = 0;
            }
            $this->ajaxReturn(200, 'SUCCESS', $check);
        }
    }
}