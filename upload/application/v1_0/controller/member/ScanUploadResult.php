<?php
/**
 * 扫描扫码上传结果
 */
namespace app\v1_0\controller\member;

class ScanUploadResult extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin();
    }
    public function index()
    {
        $type = input('post.type/s','','trim');
        $result = cache('scan_upload_result_'.$type.'_'.$this->userinfo->uid);
        if($result){
            cache('scan_upload_result_'.$type.'_'.$this->userinfo->uid,null);
            $this->ajaxReturn(200,'数据已更新',json_decode($result,1));
        }
        $this->ajaxReturn(200,'数据没有更新',0);
    }
}
