<?php
namespace app\index\controller;

class Base extends \app\common\controller\Base
{
    protected $visitor;
    protected $pageHeader=[];
    public function _initialize()
    {
        parent::_initialize();
        $global_config = config('global_config');
        $img_id_arr = [$global_config['logo'],$global_config['square_logo'],$global_config['wechat_qrcode']];
        $img_arr = model('Uploadfile')->getFileUrlBatch($img_id_arr);
        $global_config['logo'] = isset($img_arr[$global_config['logo']])?$img_arr[$global_config['logo']]:make_file_url('resource/logo.png');
        $global_config['square_logo'] = isset($img_arr[$global_config['square_logo']])?$img_arr[$global_config['square_logo']]:make_file_url('resource/square_logo.png');
        $global_config['wechat_qrcode'] = isset($img_arr[$global_config['wechat_qrcode']])?$img_arr[$global_config['wechat_qrcode']]:make_file_url('resource/weixin_img.jpg');
        $this->assign('global_config',$global_config);
        $this->initPageHeader($global_config);
        $this->initVisitor();
        if(config('global_config.isclose')==1){
            abort(500,'网站暂时关闭：'.config('global_config.close_reason'));
        }
        $this->assign('navSelTag','null');
    }
    protected function initPageHeader($global_config){
        $og['type'] = '招聘求职网';
        $og['title'] = $global_config['sitename'];
        $og['url'] = $this->getHttpType().input('server.HTTP_HOST').input('server.REQUEST_URI');
        $og['site_name'] = $global_config['sitename'];
        $og['description'] = '为求职者提供免费注册、求职指导、简历管理等服务，职位真实可靠，上'.$global_config['sitename'].'，找到满意工作';
        $this->pageHeader['og'] = $og;
        $this->pageHeader['title'] = $global_config['sitename'];
        $this->pageHeader['keywords'] = $global_config['sitename'];
        $this->pageHeader['description'] = $global_config['sitename'];
    }
    protected function getHttpType()
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        return $http_type;
    }
    protected function initVisitor(){
        $instance = new \app\common\lib\Visitor;
        $this->visitor = $instance->getLoginInfo();
        $this->assign('visitor',$this->visitor);
    }
    protected function initPageSeo($alias,$data=[]){
        $pageinfo = model('Page')->getCache($alias);
        $seo_title = $pageinfo['seo_title'];
        $seo_keywords = $pageinfo['seo_keywords'];
        $seo_description = $pageinfo['seo_description'];
        $seo_title = str_replace("{sitename}",config('global_config.sitename'),$seo_title);
        $seo_keywords = str_replace("{sitename}",config('global_config.sitename'),$seo_keywords);
        $seo_description = str_replace("{sitename}",config('global_config.sitename'),$seo_description);
        foreach ($data as $key => $value) {
            $seo_title = str_replace("{".$key."}",$value,$seo_title);
            $seo_keywords = str_replace("{".$key."}",$value,$seo_keywords);
            $seo_description = str_replace("{".$key."}",$value,$seo_description);
        }
        $this->pageHeader['title'] = $seo_title;
        $this->pageHeader['keywords'] = $seo_keywords;
        $this->pageHeader['description'] = $seo_description;
    }
}
