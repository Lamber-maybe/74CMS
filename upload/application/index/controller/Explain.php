<?php
namespace app\index\controller;

class Explain extends \app\index\controller\Base
{
    public function show()
    {
        $id = request()->route('id/d',0,'intval');
        //读取页面缓存配置
        $pageCache = model('Page')->getCache('explainshow');
        //如果缓存有效期为0，则不使用缓存
        if($pageCache['expire']>0){
            $info = model('Page')->getCacheByAlias('explainshow',$id);
        }else{
            $info = false;
        }
        if (!$info) {
            $info = $this->writeShowCache($id,$pageCache);
            if($info===false){
                abort(404,'页面不存在');
            }
        }
        $nav = model('Explain')->getCache();
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
        $info['attach'] = json_decode($info['attach'],true);
        $this->initPageSeo('explainshow',$seoData);
        $this->assign('info',$info);
        $this->assign('nav',$nav);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('show');
    }
    protected function writeShowCache($id,$pageCache){
        $info = model('Explain')
            ->where('id', $id)
            ->find();
        if ($info === null) {
            return false;
        }
        $info = $info->toArray();
        if($pageCache['expire']>0){
            model('Page')->writeCacheByAlias('explainshow',$info,$pageCache['expire'],$id);
        }
        return $info;
    }
}
