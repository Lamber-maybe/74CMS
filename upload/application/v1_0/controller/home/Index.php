<?php
namespace app\v1_0\controller\home;

class Index extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    protected function writeShowCache($pageCache,$index_module){
//        if (in_array(config('platform'),['wechat','mobile'])) {
//            $index_module = model('MobileIndexModule')->column(
//                'alias,is_display,plan_id'
//            );
//            $return['module_rule'] = $index_module;
//        }
        //菜单
        if (
            !in_array(config('platform'),['wechat','mobile']) ||
            $index_module['menu']['is_display'] == 1
        ) {
            $data['menu_list'] = $this->getMenu();
        }
        //公告
        if (
            !in_array(config('platform'),['wechat','mobile']) ||
            $index_module['notice']['is_display'] == 1
        ) {
            $data['notice_list'] = $this->getNotice();
        }
        //名企
        if (
            !in_array(config('platform'),['wechat','mobile']) ||
            $index_module['famous']['is_display'] == 1
        ) {
            $data['famous_list'] = $this->getFamous();
        }
        //热门职位
        if (
            !in_array(config('platform'),['wechat','mobile']) ||
            $index_module['hotword']['is_display'] == 1
        ) {
            $data['hotword_list'] = $this->getHotword();
        }
        //新闻资讯
        if (
            !in_array(config('platform'),['wechat','mobile']) ||
            $index_module['article']['is_display'] == 1
        ) {
            $data['article_list'] = $this->getArticle();
        }
        $return['data'] = $data;
        if($pageCache['expire']>0){
            model('PageMobile')->writeCacheByAlias('index',$return,$pageCache['expire']);
        }
        return $return;
    }
    public function index()
    {
        //读取页面缓存配置
        $pageCache = model('PageMobile')->getCache('index');
        //如果缓存有效期为0，则不使用缓存
        if($pageCache['expire']>0){
            $return = model('PageMobile')->getCacheByAlias('index');
        }else{
            $return = false;
        }
        $index_module = model('MobileIndexModule')->column(
            'alias,is_display,plan_id'
        );
        if (!$return) {
            $return = $this->writeShowCache($pageCache,$index_module);
        }
        $return['module_rule'] = $index_module;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 获取首页导航菜单
     */
    protected function getMenu()
    {
        $list = model('MobileIndexMenu')->getCache();
        return $list;
    }
    /**
     * 获取公告
     */
    protected function getNotice()
    {
        $list = model('Notice')
            ->field('id,title,link_url')
            ->where('is_display', 1)
            ->order('sort_id desc,id desc')
            ->select();
        return $list;
    }
    /**
     * 热门职位
     */
    protected function getHotword()
    {
        return model('Hotword')->getList(16);
    }
    /**
     * 名企
     */
    protected function getFamous()
    {
        $famous_enterprises_setmeal = config(
            'global_config.famous_enterprises'
        );
        $famous_enterprises_setmeal =
            $famous_enterprises_setmeal == ''
                ? []
                : explode(',', $famous_enterprises_setmeal);
        if (empty($famous_enterprises_setmeal)) {
            $this->ajaxReturn(200, '获取数据成功', ['items' => []]);
        }
        //触屏个性化首页名企栏设置数量不对应 zch 2022.07.06
        $limit = 9;
        $famous = model('MobileIndexModule')->where(['alias'=>'famous'])->value('plan_id');
        if (intval($famous) == 3)
        {
            $limit = 6;
        }
        $subsiteCondition = get_subsite_condition('a');
        $list = model('Company')
            ->alias('a')
            ->where('a.is_display',1)
            ->join(
                config('database.prefix') . 'job_search_rtime c',
                'a.uid=c.uid',
                'LEFT'
            )
            ->where('c.id','not null')
            ->where('a.setmeal_id', 'in', $famous_enterprises_setmeal)
            ->where($subsiteCondition)
            ->field('distinct a.id,a.logo,a.companyname')
            ->order('a.refreshtime desc')
            ->limit($limit)
            ->select();
        $job_list = $comid_arr = $logo_id_arr = $logo_arr = [];
        foreach ($list as $key => $value) {
            $comid_arr[] = $value['id'];
            $value['logo'] > 0 && ($logo_id_arr[] = $value['logo']);
        }
        if (!empty($logo_id_arr)) {
            $logo_arr = model('Uploadfile')->getFileUrlBatch($logo_id_arr);
        }
        if (!empty($comid_arr)) {
            $job_data = model('Job')
                ->where('company_id', 'in', $comid_arr)
                ->where('is_display', 1)
                ->where('audit', 1)
                ->column('id,company_id,jobname', 'id');
            foreach ($job_data as $key => $value) {
                $job_list[$value['company_id']][] = $value['jobname'];
            }
        }
        $return = [];
        foreach ($list as $key => $value) {
            $arr = $value->toArray();
            $arr['logo'] = isset($logo_arr[$value['logo']])
                ? $logo_arr[$arr['logo']]
                : default_empty('logo');
            $arr['jobnum'] = isset($job_list[$value['id']])
                ? count($job_list[$arr['id']])
                : 0;
            $return[] = $arr;
        }

        return $return;
    }
    /**
     * 新闻资讯
     */
    protected function getArticle()
    {
        $list = model('Article')
            ->field('id,title,thumb,link_url,click,addtime,source')
            ->where('is_display', 1)
            ->limit(5)
            ->order('sort_id desc,id desc')
            ->select();
        $thumb_id_arr = $thumb_arr = [];
        foreach ($list as $key => $value) {
            $value['thumb'] > 0 && ($thumb_id_arr[] = $value['thumb']);
        }
        if (!empty($thumb_id_arr)) {
            $thumb_arr = model('Uploadfile')->getFileUrlBatch(
                $thumb_id_arr
            );
        }
        $return = [];
        foreach ($list as $key => $value) {
            $arr = $value->toArray();
            $arr['thumb'] = isset($thumb_arr[$arr['thumb']])
                ? $thumb_arr[$arr['thumb']]
                : default_empty('thumb');
            $arr['source_text'] = $arr['source'] == 1 ? '转载' : '原创';
            $return[] = $arr;
        }
        return $return;
    }
    public function ajaxSearchLocation(){
        $alias = input('get.alias/s','joblist','trim');
        $input = [
            'keyword'=>input('get.keyword/s',null,'trim,addslashes')
        ];
        $path = 'index/index/index';
        if($alias=='joblist'){
            $path = 'index/job/index';
        }else if($alias=='resumelist'){
            $path = 'index/resume/index';
        }else if($alias=='companylist'){
            $path = 'index/company/index';
        }else if($alias=='articlelist'){
            $path = 'index/article/index';
        }
        $this->ajaxReturn(200,'获取数据成功',url($path,$input));
    }
    public function downloadproxy(){
        $file = SYS_UPLOAD_PATH.'resource/proxy.docx';
        $result = file_get_contents($file);
        ob_start(); 
        echo "$result"; 
        header("Cache-Control: public"); 
        Header("Content-type: application/octet-stream"); 
        Header("Accept-Ranges: bytes"); 
        header('Content-Disposition: attachment; filename=招聘委托书.docx'); 
        header("Pragma:no-cache"); 
        header("Expires:0"); 
        ob_end_flush(); 
    }
    public function scenerecord(){
        $scene_id = input('post.scene_id/s','','trim');
        $scene_uuid = input('post.scene_uuid/s','','trim');
        $sceneQrcodeInfo = model('SceneQrcode')->where('uuid',$scene_uuid)->whereOr('id',$scene_id)->find();
        if($sceneQrcodeInfo!==null){
            model('SceneQrcodeScanLog')->save(['pid'=>$sceneQrcodeInfo['id'],'addtime'=>time()]);
        }
        $this->ajaxReturn(200,'记录成功');
    }
}
