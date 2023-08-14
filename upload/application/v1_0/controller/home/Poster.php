<?php
namespace app\v1_0\controller\home;

class Poster extends \app\v1_0\controller\common\Base
{
    public function index()
    {
        $type = input('get.type/s','job','trim');
        $id = input('get.id/d',0,'intval');
        $index = input('get.index/d',0,'intval');
        $result = false;
        $poster = new \app\common\lib\Poster;
        if($type=='job'){
            $result = $poster->makeJobPoster($index,$id);
        }
        if($type=='resume'){
            $result = $poster->makeResumePoster($index,$id);
        }
        if($type=='company'){
            $result = $poster->makeCompanyPoster($index,$id);
        }
        if($result===false){
            $this->ajaxReturn(500,$poster->getError());
        }
        $this->ajaxReturn(200,'生成海报成功',$result);
    }
    
}
