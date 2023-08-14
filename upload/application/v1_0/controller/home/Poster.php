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
        if ($index === 0)
        {
            switch ($type){
                case 'job':
                    $types = 1;
                    break;
                case 'resume':
                    $types = 2;
                    break;
                case 'company':
                    $types = 3;
                    break;
                default:
                    $types = 0;
                    break;
            }
            $index = model('poster')->where('type',$types)->field('id,indexid,name,sort_id,is_display')->order('is_display desc,sort_id desc,id asc')->value('indexid');
            $index = empty($index)?0:$index;
        }
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
        if($type == 'shortvideo'){
            $vtype = input('get.vtype/d',1,'intval');
            $result = $poster->makeVideoPoster(1, $id, $vtype);
        }

        if($result===false){
            $this->ajaxReturn(500,$poster->getError());
        }
        $this->ajaxReturn(200,'生成海报成功',$result);
    }
    public function download(){
        $type = input('get.type/s','job','trim');
        $id = input('get.id/d',0,'intval');
        $index = input('get.index/d',0,'intval');
        $result = false;
        switch($type){
            case 'job':
                $info = model('Job')->where('id',$id)->find();
                break;
            case 'company':
                $info = model('Company')->where('id',$id)->find();
                break;
            case 'resume':
                $info = model('Resume')->where('id',$id)->find();
                break;
        }
        $filename = $id.'_'.$info['updatetime'].'_'.$index.'.jpg';
        $show_path = SYS_UPLOAD_PATH.'poster/'.$type.'/'.($id%10).'/'.$filename;
        $save_name = '';
        switch($type){
            case 'job':
                $save_name = '职位-'.$id.'-'.$index.'.jpg';   
                break;
            case 'company':
                $save_name = '企业-'.$id.'-'.$index.'.jpg';   
                break;
            case 'resume':
                $save_name = '简历-'.$id.'-'.$index.'.jpg';   
                break;
        }
        header('Content-Disposition:attachment;filename=' . $save_name);
        header('Content-Length:' . filesize($show_path));
        readfile($show_path);
    }
    
    public function getTplindexList()
    {
        $type = input('get.type/d',1,'intval');
        $list = model('Poster')->where('type',$type)->where('is_display',1)->column('indexid');
        $this->ajaxReturn(200,'获取数据成功',$list);
    }
}
