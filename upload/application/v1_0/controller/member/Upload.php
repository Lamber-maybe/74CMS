<?php
namespace app\v1_0\controller\member;

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
}
