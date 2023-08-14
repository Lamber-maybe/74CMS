<?php
namespace app\common\lib\cron;

class Baiduxml
{
    protected $error = '';
    public function execute()
    {
        $instance = new \app\common\lib\Baiduxml;
        $result = $instance->update();
        if($result===false){
            $this->error = $instance->getError();
            return false;
        }else{
            return true;
        }
    }
}
