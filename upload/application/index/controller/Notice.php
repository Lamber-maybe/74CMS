<?php
namespace app\index\controller;

class Notice extends \app\index\controller\Base
{
    public function index()
    {
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'noticelist',302);
            exit;
        }
        $current_page = request()->get('page/d',1,'intval');
        $pagesize = 10;
        /**
         * 【优化】公告 发布时间按显示时间出现
         * zch 2022.9.20
         * 【新增】
         * $where['addtime'] = ['lt',time()];
         * ->where($where)
         */
        $where['addtime'] = ['lt',time()];
        $list = model('Notice')
            ->order('sort_id desc,id desc')
            ->where('is_display',1)
            ->where($where)
            ->paginate(['list_rows'=>$pagesize,'page'=>$current_page,'type'=>'\\app\\common\\lib\\Pager']);
        
        $pagerHtml = $list->render();
        
        foreach ($list as $key => $value) {
            $list[$key]['content'] = strip_tags(htmlspecialchars_decode($value['content'],ENT_QUOTES));
            $list[$key]['content'] = cut_str($list[$key]['content'],200,0,'...');
            $list[$key]['link_url'] = $value['link_url']==''?url('index/notice/show',['id'=>$value['id']]):$value['link_url'];
        }
        $this->initPageSeo('noticelist');
        $this->assign('list',$list);
        $this->assign('pagerHtml',$pagerHtml);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('index');
    }
    public function show()
    {
        $id = request()->route('id/d',0,'intval');
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'notice/'.$id,302);
            exit;
        }
        //读取页面缓存配置
        $pageCache = model('Page')->getCache('noticeshow');
        //如果缓存有效期为0，则不使用缓存
        if($pageCache['expire']>0){
            $info = model('Page')->getCacheByAlias('noticeshow',$id);
        }else{
            $info = false;
        }
        if (!$info) {
            $info = $this->writeShowCache($id,$pageCache);
            if($info===false){
                abort(404,'页面不存在');
            }
        }
        
        $prev = model('Notice')
            ->where('id', '>', $info['id'])
            ->order('id asc')
            ->field('id,title,link_url')
            ->find();
        if($prev!==null){
            $prev['link_url'] = $prev['link_url']==''?url('index/notice/show',['id'=>$prev['id']]):$prev['link_url'];
        }
        $next = model('Notice')
            ->where('id', '<', $info['id'])
            ->order('id desc')
            ->field('id,title,link_url')
            ->find();
        if($next!==null){
            $next['link_url'] = $next['link_url']==''?url('index/notice/show',['id'=>$next['id']]):$next['link_url'];
        }
        $info['share_url'] = config('global_config.mobile_domain').'notice/'.$info['id'];
        $newNoticeList = $this->getNewNoticeList($id);
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
        $info['attach'] = $info['attach']?json_decode($info['attach'],true):[];
        $this->initPageSeo('noticeshow',$seoData);
        $this->assign('newNoticeList',$newNoticeList);
        $this->assign('info',$info);
        $this->assign('prev',$prev);
        $this->assign('next',$next);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('show');
    }
    protected function writeShowCache($id,$pageCache){
        $info = model('Notice')
            ->field('is_display,link_url', true)
            ->where('id', $id)
            ->find();
        if ($info === null) {
            return false;
        }
        $info = $info->toArray();
        $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);
        if($pageCache['expire']>0){
            model('Page')->writeCacheByAlias('noticeshow',$info,$pageCache['expire'],$id);
        }
        return $info;
    }
    protected function getNewNoticeList($id){
        $list = model('Notice')->where('id','neq',$id)->limit(10)->order('id desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['link_url'] = $value['link_url']==''?url('index/notice/show',['id'=>$value['id']]):$value['link_url'];
        }
        return $list;
    }
}
