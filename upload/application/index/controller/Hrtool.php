<?php
namespace app\index\controller;

class Hrtool extends \app\index\controller\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('navSelTag','hrtool');
    }
    public function index()
    {
        $list = model('HrtoolCategory')->order('sort_id desc,id asc')->column('id,name,describe','id');
        foreach ($list as $key => $value) {
            $list[$key]['last_num'] = $value['id']%10;
        }
        $this->initPageSeo('hrtoolindex');
        $this->assign('list',$list);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('index');
    }
    public function show()
    {
        $id = request()->route('id/d',0,'intval');
        $list = model('Hrtool')->where('cid', $id)->order('sort_id desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['filetype'] = $this->getFileType($value['fileurl']);
        }
        $info = model('HrtoolCategory')->where('id',$id)->find();
        $this->pageHeader['title'] = $info['name'].' - '.$this->pageHeader['title'];
        $seoData = [
            'cname'=>$info['name'],
            'describe'=>$info['describe']
        ];
        $this->initPageSeo('hrtoolshow',$seoData);
        $this->assign('info',$info);
        $this->assign('list',$list);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('show');
    }
    public function download(){
        $id = request()->route('id/d',0,'intval');
        $info = model('Hrtool')->where('id',$id)->find();
        if($info!==null){
            $file_dir = SYS_UPLOAD_PATH;
            $file_name = $info['fileurl'];
            $file_name = strtolower($file_name);
            if(file_exists($file_dir . $file_name)){
                $ext = substr(strrchr($file_name, '.'), 1);
                $file = fopen ( $file_dir . $file_name, "rb"); 
                Header("Content-type: application/octet-stream"); 
                Header("Accept-Ranges: bytes");
                Header("Accept-Length: " . filesize($file_dir . $file_name));  
                Header("Content-Disposition: attachment; filename=" . $info['filename'].'.'.$ext);    
                echo fread($file,filesize($file_dir . $file_name));    
                fclose($file);    
                exit();
            }else{
                abort(404,'页面不存在');
            }
        }else{
            abort(404,'页面不存在');
        }
    }
    protected function getFileType($filename){
        $filename = strtolower($filename);
        $ext = substr(strrchr($filename, '.'), 1);
        switch($ext){
            case 'doc':
            case 'docx':
                return 'word';
            case 'xls':
            case 'xlsx':
            case 'csv':
                return 'excel';
            case 'pdf':
                return 'pdf';
            case 'ppt':
            case 'pptx':
                return 'pdf';
            default:
                return 'word';
        }
    }
}
