<?php
namespace app\v1_0\controller\member;

use app\common\lib\Qiniu;
use Think\Exception;

class Upload extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin();
    }
    public function index()
    {
        $extra = input('post.extra/s','','trim');
        $file = input('file.file');
        if (!$file) {
            $this->ajaxReturn(500, '请选择文件');
        }
        $filemanager = new \app\common\lib\FileManager();
        $result = $filemanager->upload($file);
        if (false !== $result) {
            if($extra=='company_logo'){
                cache('scan_upload_result_company_logo_'.$this->userinfo->uid,json_encode($result));
            }
            $this->ajaxReturn(200, '上传成功', $result);
        } else {
            $this->ajaxReturn(500, $filemanager->getError());
        }
    }

    protected function video(){
        $file = input('file.file');
        ini_set('max_execution_time', 0);
        ini_set('post_max_size ', '100M');
        ini_set('upload_max_filesize', '100M');
        config('global_config.fileupload_ext', 'avi,mov,mp4');
        config('global_config.fileupload_size', 100*1024*1024);
        if (!$file) {
            $this->ajaxReturn(500, '请选择文件');
        }
        $filemanager = new \app\common\lib\FileManager();
        $result = $filemanager->upload($file);
        if (false !== $result) {
            $this->ajaxReturn(200, '上传成功', $result);
        } else {
            $this->ajaxReturn(500, $filemanager->getError());
        }
    }

    public function save_qiniu_file(){
        $file_path = input('post.file_path/s', '', 'trim,htmlspecialchars');
        try{
            if(empty($file_path))exception('参数无效');
            $filemanager = new \app\common\lib\FileManager();
            $result = $filemanager->save_path($file_path, 'qiniu');
            $this->ajaxReturn(200, '上传成功', $result);
        }catch (Exception $e){
            $this->ajaxReturn(500, $e->getCode(), $e->getMessage());
        }
    }

    public function qiniu_token(){
        try{
            $qiniu = new Qiniu();
            $res = $qiniu->getToken();
            $this->ajaxReturn(200, '', $res);
        }catch (Exception $e){
            $this->ajaxReturn(500, $e->getCode());
        }
    }
}
