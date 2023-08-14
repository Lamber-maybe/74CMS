<?php
namespace app\index\controller;

class Article extends \app\index\controller\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('navSelTag','article');
    }
    public function index()
    {
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'newslist',302);
            exit;
        }

        // 增加关键词搜索 chenyang 2022年3月14日17:27:27
        $keyword = request()->route('keyword/s', '', 'trim');
        $where = [];
        if (!empty($keyword)) {
            $where['a.title'] = ['like', '%'.$keyword.'%'];
        }

        $list = model('Article')
            ->alias('a')
            ->join(config('dababase.prefix') . 'article_category b','a.cid=b.id','LEFT')
            ->where('b.id','not null')
            ->where($where)
            ->order('a.sort_id desc,a.id desc')
            ->field('a.*');
        $current_page = request()->get('page/d',1,'intval');
        $pagesize = 10;
        $cid = request()->route('cid/d',0,'intval');
        $seoData = [
            'cname'=>'最新资讯',
            'seo_keywords'=>'最新资讯',
            'seo_description'=>'最新资讯'
        ];
        if ($cid > 0) {
            $categoryinfo = model('ArticleCategory')->where('id',$cid)->find();
            if($categoryinfo!==null){
                $seoData = [
                    'cname'=>$categoryinfo['name'],
                    'seo_keywords'=>$categoryinfo['seo_keywords'],
                    'seo_description'=>$categoryinfo['seo_description']
                ];
            }
            $list = $list->where('a.cid','eq',$cid);
        }
        $list = $list->where('a.is_display',1)->paginate(['list_rows'=>$pagesize,'page'=>$current_page,'type'=>'\\app\\common\\lib\\Pager']);
        $pagerHtml = $list->render();
        $thumb_id_arr = $thumb_arr = [];
        foreach ($list as $key => $value) {
            $value['thumb'] > 0 && ($thumb_id_arr[] = $value['thumb']);
        }
        if (!empty($thumb_id_arr)) {
            $thumb_arr = model('Uploadfile')->getFileUrlBatch($thumb_id_arr);
        }
        foreach ($list as $key => $value) {
            $list[$key]['content'] = strip_tags(htmlspecialchars_decode($value['content'],ENT_QUOTES));
            $list[$key]['content'] = cut_str($list[$key]['content'],200,0,'...');
            $list[$key]['thumb_src'] = isset($thumb_arr[$value['thumb']]) ? $thumb_arr[$value['thumb']] : default_empty('thumb');
            $list[$key]['link_url'] = $value['link_url']==''?url('index/article/show',['id'=>$value['id']]):$value['link_url'];
        }
        $options = model('ArticleCategory')->getCache();
        $options1 = $options2 = [];
        $counter = 1;
        foreach ($options as $key => $value) {
            if($counter>=10){
                $options2[$key] = $value;
            }else{
                $options1[$key] = $value;
            }
            $counter++;
        }
        $this->initPageSeo('articlelist',$seoData);
        $this->assign('list',$list);
        $this->assign('pagerHtml',$pagerHtml);
        $this->assign('options1',$options1);
        $this->assign('options2',$options2);
        $this->assign('options',$options);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('index');
    }
    public function show()
    {
        $id = request()->route('id/d',0,'intval');
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'news/'.$id,302);
            exit;
        }
        //读取页面缓存配置
        $pageCache = model('Page')->getCache('articleshow');
        //如果缓存有效期为0，则不使用缓存
        if($pageCache['expire']>0){
            $info = model('Page')->getCacheByAlias('articleshow',$id);
        }else{
            $info = false;
        }
        if (!$info) {
            $info = $this->writeShowCache($id,$pageCache);
            if($info===false){
                abort(404,'页面不存在');
            }
        }
        $prev = model('Article')
            ->where('id', '>', $info['id'])
            ->order('id asc')
            ->field('id,title,link_url')
            ->find();
        if($prev!==null){
            $prev['link_url'] = $prev['link_url']==''?url('index/article/show',['id'=>$prev['id']]):$prev['link_url'];
        }
        
        $next = model('Article')
            ->where('id', '<', $info['id'])
            ->order('id desc')
            ->field('id,title,link_url')
            ->find();
        if($next!==null){
            $next['link_url'] = $next['link_url']==''?url('index/article/show',['id'=>$next['id']]):$next['link_url'];
        }
        $options = model('ArticleCategory')->getCache();
        $info['category_text'] = isset($options[$info['cid']])?$options[$info['cid']]:'最新资讯';
        $info['share_url'] = config('global_config.mobile_domain').'news/'.$info['id'];
        $hotArticleList = $this->getHotArticleList($id);
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
        $this->initPageSeo('articleshow',$seoData);
        $this->assign('hotArticleList',$hotArticleList);
        $this->assign('options',$options);
        $this->assign('info',$info);
        $this->assign('prev',$prev);
        $this->assign('next',$next);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('show');
    }
    protected function writeShowCache($id,$pageCache){
        $info = model('Article')
            ->field('is_display,link_url', true)
            ->where('id', $id)
            ->find();
        if ($info === null) {
            return false;
        }
        $info = $info->toArray();
        $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);
        $info['thumb'] =
            $info['thumb'] > 0
                ? model('Uploadfile')->getFileUrl($info['thumb'])
                : default_empty('thumb');
        $info['source_text'] = $info['source'] == 1 ? '转载' : '原创';
        if($pageCache['expire']>0){
            model('Page')->writeCacheByAlias('articleshow',$info,$pageCache['expire'],$id);
        }
        return $info;
    }
    protected function getHotArticleList($id){
        $list = model('Article')->where('id','neq',$id)->limit(10)->order('click desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['link_url'] = $value['link_url']==''?url('index/article/show',['id'=>$value['id']]):$value['link_url'];
        }
        return $list;
    }
}
