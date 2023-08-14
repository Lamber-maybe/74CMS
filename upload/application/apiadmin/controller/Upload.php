<?php
/**
 * 上传
 */
namespace app\apiadmin\controller;

class Upload extends \app\common\controller\Backend
{
    public function index()
    {
        $file = input('file.file');
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
    public function wechatMedia()
    {
        $file = input('file.file');
        if (!$file) {
            $this->ajaxReturn(500, '请选择文件');
        }
        $filemanager = new \app\common\lib\FileManager();
        $result = $filemanager->uploadReturnPath($file);
        if (false !== $result) {
            $instance = new \app\common\lib\Wechat;
            $res = $instance->uploadMedia($result['save_path']);
            if($res!==false){
                $this->ajaxReturn(200, '上传成功', $res);
            }else{
                $this->ajaxReturn(500, $instance->getError());
            }
        } else {
            $this->ajaxReturn(500, $filemanager->getError());
        }
    }
}
