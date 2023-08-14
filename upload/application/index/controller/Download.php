<?php
namespace app\index\controller;

class Download extends \app\index\controller\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $file_dir = SYS_UPLOAD_PATH;
        $url = request()->get('url/s','','trim');
        $ourput_filename = request()->get('name/s','','trim');
        if(file_exists($file_dir . $url)){
            $file = fopen ( $file_dir . $url, "rb"); 
            Header("Content-type: application/octet-stream"); 
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . filesize($file_dir . $url));  
            Header("Content-Disposition: attachment; filename=" . $ourput_filename);    
            echo fread($file,filesize($file_dir . $url));    
            fclose($file);    
            exit();
        }else{
            abort(404,'页面不存在');
        }
        
    }
}
