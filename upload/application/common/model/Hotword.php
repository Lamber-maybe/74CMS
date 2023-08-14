<?php
namespace app\common\model;

class Hotword extends \app\common\model\BaseModel
{
    public function getList($num=10)
    {
        $list = model('Hotword')
            ->order('hot desc')
            ->limit($num)
            ->column('id,word,hot');
        return $list;
    }
}
