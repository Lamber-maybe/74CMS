<?php
namespace app\v1_0\controller\home;

class Qrcode extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $type = input('get.type/s','','trim');
        if($type==''){
            $type = config('global_config.qrcode_type');
        }
        if($type=='normal'){
            $this->makeNormalQrcode();
        }
        if($type=='wechat'){
            $this->makeWechatQrcode();
        }
    }
    /**
     * 生成普通二维码（跳转到指定链接）
     */
    protected function makeNormalQrcode(){
        if($url = input('get.url/s','','trim')){
            ob_clean();
            $url = htmlspecialchars_decode($url,ENT_QUOTES);
            vendor('phpqrcode.phpqrcode');   
            $qrcode = new \QRcode();
            ob_clean();
            $qrcode::png($url,false, 'H', 8, 2);
        }
    }
    /**
     * 生成微信带参数二维码
     */
    protected function makeWechatQrcode(){
        if($alias = input('get.alias/s','mapQrcode','trim')){
            $params = input('get.');
            $class = new \app\common\lib\Wechat;
            $qrcode = $class->makeQrcode($params);
            if($qrcode){
                $this->showImg($qrcode);
            }
		}
    }
    protected function showImg($img){
        $size = getimagesize($img);
        $fp = fopen($img, "rb");
        if ($size && $fp) {
            header("Content-type: {$size['mime']}");
            fpassthru($fp);
            exit;
        }
    }
}