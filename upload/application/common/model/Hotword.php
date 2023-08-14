<?php
namespace app\common\model;

class Hotword extends \app\common\model\BaseModel
{
    public function getList($num=10)
    {
        $list = model('Hotword');
        if(config('global_config.hotword_display_method')==1){
            $list = $list->orderRaw('rand()');
        }else{
            $list = $list->order('hot desc');
        }
        $list = $list->limit($num)->field('word,hot')->select();
        return $list;
    }
}
