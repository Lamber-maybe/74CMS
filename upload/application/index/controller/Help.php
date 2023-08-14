<?php
namespace app\index\controller;

class Help extends \app\index\controller\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('navSelTag','help');
    }
    public function show()
    {
        $id = request()->route('id/d',0,'intval');
        //读取页面缓存配置
        $pageCache = model('Page')->getCache('helpshow');
        //如果缓存有效期为0，则不使用缓存
        if($pageCache['expire']>0){
            $info = model('Page')->getCacheByAlias('helpshow',$id);
        }else{
            $info = false;
        }
        if (!$info) {
            $info = $this->writeShowCache($id,$pageCache);
            if($info===false){
                abort(404,'页面不存在');
            }
        }
        $nav = model('Help')->getCache();
        
        $seoData['title'] = $info['title'];
        if($info['seo_keywords']!=''){
            $seoData['seo_keywords'] = $info['seo_keywords'];
        }else{
            $seoData['seo_keywords'] = $info['title'];
        }
        if($info['seo_description']!=''){
            $seoData['seo_description'] = $info['seo_description'];
        }else{
            $seoData['seo_description'] = cut_str(strip_tags($info['content']),100);
        }
        $this->initPageSeo('helpshow',$seoData);
        $this->assign('info',$info);
        $this->assign('nav',$nav);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('show');
    }
    protected function writeShowCache($id,$pageCache){
        if($id==0){
            $info = model('Help')->order('id asc')->find();
        }else{
            $info = model('Help')
                ->where('id', $id)
                ->find();
        }
        if ($info === null) {
            return false;
        }
        $info = $info->toArray();
        $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);
        if($pageCache['expire']>0){
            model('Page')->writeCacheByAlias('helpshow',$info,$pageCache['expire'],$id);
        }
        return $info;
    }
}
