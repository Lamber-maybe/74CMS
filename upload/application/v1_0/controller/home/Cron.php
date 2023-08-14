<?php
namespace app\v1_0\controller\home;

class Cron extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $instance = new \app\common\lib\Cron();
        if (false === ($return_data = $instance->run())) {
            $this->ajaxReturn(200, $instance->getError());
        }
        $this->ajaxReturn(200, '执行成功');
    }
}
