<?php
namespace app\common\model;

class SmsBlacklist extends \app\common\model\BaseModel
{
    public function isExist($mobile){
        $info = $this->where('mobile',$mobile)->find();
        if($info===null){
            return false;
        }
        return true;
    }
}
