<?php
namespace app\index\controller;

class Index extends \app\index\controller\Base
{
    public function _initialize(){
        parent::_initialize();
    }
    public function index()
    {
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain'),302);
            exit;
        }
        $index_tpl = config('global_config.index_tpl');
        $index_tpl = $index_tpl?$index_tpl:'def';
        $instance = new \app\common\lib\Tpl($this->visitor);
        $return = $instance->index($index_tpl);
        foreach ($return as $key => $value) {
            $this->assign($key,$value);
        }
        $this->initPageSeo('index');
        $this->assign('pageHeader',$this->pageHeader);
        $this->assign('navSelTag','index');
        return $this->fetch('index/'.$index_tpl.'/index');
    }
}
