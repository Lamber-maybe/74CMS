<?php
namespace app\index\controller;

class Map extends \app\index\controller\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('navSelTag','map');
    }
    public function index()
    {
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'jobmap',302);
            exit;
        }
        $this->pageHeader['title'] = '地图找工作 - '.$this->pageHeader['title'];
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('index');
    }
}
