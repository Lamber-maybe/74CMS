<?php
namespace app\apiadmin\controller;
class Qrcode extends \app\common\controller\Base{
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        if($url = input('get.url/s','','trim')){
            $url = htmlspecialchars_decode($url,ENT_QUOTES);
            $download = input('get.download/d',0,'intval');
            $px = input('get.px/d',0,'intval');
            $size = round($px/44.95, 2);
            Vendor('phpqrcode.phpqrcode');   
            $qrcode = new \QRcode();
            ob_end_clean();
            if($download==1){
                header("Content-type:application/x-png");
                header("Content-Disposition:attachment;filename=二维码.png");
                echo $qrcode::png($url,false, 'H', $size, 2);
            }else{
                $qrcode::png($url,false, 'H', $size, 2);
            }
		}
    }
    public function normal(){
        if($url = input('get.url/s','','trim')){
            $url = htmlspecialchars_decode($url,ENT_QUOTES);
            vendor('phpqrcode.phpqrcode');   
            $qrcode = new \QRcode();
            ob_end_clean();
            $qrcode::png($url,false, 'H', 8, 2);
		}
    }
    /**
     * 生成微信带参数二维码
     */
    public function login(){
        $params = [
            'alias'=>'admin_login',
            'token'=>input('get.scan_token/s','','trim')
        ];
        $class = new \app\common\lib\Wechat;
        $qrcode = $class->makeQrcode($params);
        if($qrcode){
            model('AdminScanCert')->save(['token'=>$params['token'],'info'=>'','addtime'=>time()]);
            //删除今天之前的所有记录（废弃的过期的）
            model('AdminScanCert')->where('addtime','lt',strtotime('today'))->delete();
            $this->ajaxReturn(200, '', $qrcode);
        }else{
            $this->ajaxReturn(200, 'server error', false);
        }
    }
}