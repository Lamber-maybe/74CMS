<?php
/**
 * 上传
 */
namespace app\apiadmin\controller;

use app\common\lib\Qiniu;

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

    public function checkQiniu(){
        try{
            $qiniu = new Qiniu();
            $domains = $qiniu->getDomains();
            $realDomains = $domains[0];
            if(empty($realDomains))exception('七牛配置有误');
            $config = config('global_config.account_qiniu');
            if(empty($config['domain']))exception('七牛配置有误.');
            $find = false;
            foreach($realDomains as $v){
                if($v == $config['domain']){
                    $find = true;
                }
            }
            if(!$find)exception('七牛域名配置有误');
            $this->ajaxReturn(200, '', $domains);
        }catch (\Exception $e){
            $this->ajaxReturn(400, $e->getMessage());
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
    public function editor()
    {
        $returnJson = [
            'errno'=>500,
            'data'=>[]
        ];
        $file = input('file.');
        do{
            if (!$file) {
                break;
            }
            $file = array_values($file);
            $file = $file[0];
            $filemanager = new \app\common\lib\FileManager();
            $result = $filemanager->upload($file);
            if (false !== $result) {
                $returnJson = [
                    'errno'=>0,
                    'data'=>[
                        [
                            "url"=> $result['file_url'],
                            "alt"=> "",
                            "href"=> ""
                        ]
                    ]
                ];
                break;
            } else {
                break;
            }
        }while(0);
        exit(JSON_ENCODE($returnJson));
    }
    
    public function poster()
    {
        $file = input('file.file');
        if (!$file) {
            $this->ajaxReturn(500, '请选择文件');
        }
        $filemanager = new \app\common\lib\FileManager(['fileupload_type'=>'default']);
        $result = $filemanager->uploadReturnPath($file);
        if (false !== $result) {
            $this->ajaxReturn(200, '上传成功', $result['save_path']);
        } else {
            $this->ajaxReturn(500, $filemanager->getError());
        }
    }
}
