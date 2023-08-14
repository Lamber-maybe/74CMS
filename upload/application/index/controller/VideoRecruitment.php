<?php
namespace app\index\controller;
use app\common\lib\Pager;
class VideoRecruitment extends \app\index\controller\Base{
    public function _initialize(){
        parent::_initialize();
        $this->assign('navSelTag','VideoRecruitment');
    }
    /**
     * [index 视频招聘页]
     */
    public function index()
    {
        if(is_mobile_request()===true){
            $this->redirect($this->sub_site_domain_m.'shortvideo/companylist',302);
            exit;
        }
        $input_data['uuid'] = uuid();
        $mobile_page = 'shortvideo/companylist';
        $locationUrl = $this->sub_site_domain_m.$mobile_page.'?scene_uuid='.$input_data['uuid'];
        $locationUrl = urlencode($locationUrl);
        $qrcodeSrc = config('global_config.sitedomain').config('global_config.sitedir').'v1_0/home/qrcode/index?type=normal&url='.$locationUrl;
        $this->assign('qrcodeSrc',$qrcodeSrc);
        $global_config = config('global_config');
        $logoUrl = model('Uploadfile')->getFileUrl($global_config['logo']);
        $global_config['logoUrl'] = $logoUrl?$logoUrl:make_file_url('resource/logo.png');

        $this->assign('global_config',$global_config);
        return $this->fetch('index');
    }
}
