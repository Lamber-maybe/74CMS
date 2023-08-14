<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/6/23
 * Time: 9:13
 */

namespace app\v1_0\controller\home;


use app\common\lib\FileManager;
use app\common\lib\Http;
use app\common\lib\Qiniu;
use app\common\model\shortvideo\SvAd;
use app\common\model\shortvideo\SvAdCategory;
use app\common\model\shortvideo\SvCompanyVideo;
use app\common\model\shortvideo\SvCollect;
use app\common\model\shortvideo\SvPersonalVideo;
use app\common\model\shortvideo\Video;
use app\common\model\Uploadfile;
use app\v1_0\controller\member\Upload;
use think\File;

class ShortVideo extends \app\v1_0\controller\common\Base
{
    protected $collect;
    protected $company;
    protected $personal;

    public function _initialize()
    {
        parent::_initialize();
        $this->collect = new SvCollect();
        $this->company = new SvCompanyVideo();
        $this->personal = new SvPersonalVideo();
    }

    public function adlist(){
        $type = input('get.type/d', 0, 'intval');
        $AdCategoryModel = new  SvAdCategory();
        $AdModel = new SvAd();
        $where_ad_category['alias']= $type == 0 ? 'QS_shortvideo_jobing_top': 'QS_shortvideo_finding_top';
        $ad_category = $AdCategoryModel
            ->where($where_ad_category)->find();
        $timestamp = time();
        $ad_list = $AdModel
            ->where('is_display', 1)
            ->where('cid', $ad_category['id'])
            ->where('starttime', '<=', $timestamp)
            ->where(function ($query) use ($timestamp) {
                $query->where('deadline', '>=', $timestamp)->whereOr('deadline', 0);
            })
            ->limit($ad_category['ad_num'])
            ->order('sort_id desc,id desc')
            ->select();

        $AdModel->process($ad_list);
        $this->ajaxReturn(200, 'ok', $ad_list);
    }

    public function douyin(){
        $this->checkLogin();
        set_time_limit(0); // nginx 设置 keepalive_timeout
        $url = input('get.url/s', '', 'trim');
        $http = new Http();
        try {
            if (intval(config('global_config.shortvideo_enable')) == 0) {
                $this->ajaxReturn(500, '视频招聘功能已关闭');
            }
            if (empty($url)) exception('参数不正确');

            /**
             * 【修改】
             * 【bug】短视频招聘，抖音导入报错
             * yx - 2023.03.09
             */
            $headers = get_headers($url, 1);
            $redirect_url = $headers['Location'];
            if ($redirect_url) {
                $url = $redirect_url;
            }

            preg_match('/(\d+)/', $url, $matches);
            $num = $matches[1];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://tiktok.iculture.cc/X-Bogus',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
            "url":"https://www.douyin.com/aweme/v1/web/aweme/detail/?aweme_id=' . $num . '&aid=1128&version_name=23.5.0&device_platform=android&os_version=2333",
            "user_agent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36"
        }',
                CURLOPT_HTTPHEADER => array(
                    'User-Agent: FancyPig',
                    'Content-Type: application/json',
                    'Accept: */*',
                    'Host: tiktok.iculture.cc',
                    'Connection: keep-alive'
                ),
            ));

            $json_array = json_decode(curl_exec($curl));
            curl_close($curl);
            $new_url = $json_array->param;
            $msToken = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 107);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $new_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36',
                    'Referer: https://www.douyin.com/',
                    'Cookie: msToken=' . $msToken . ';odin_tt=324fb4ea4a89c0c05827e18a1ed9cf9bf8a17f7705fcc793fec935b637867e2a5a9b8168c885554d029919117a18ba69; ttwid=1%7CWBuxH_bhbuTENNtACXoesI5QHV2Dt9-vkMGVHSRRbgY%7C1677118712%7C1d87ba1ea2cdf05d80204aea2e1036451dae638e7765b8a4d59d87fa05dd39ff; bd_ticket_guard_client_data=eyJiZC10aWNrZXQtZ3VhcmQtdmVyc2lvbiI6MiwiYmQtdGlja2V0LWd1YXJkLWNsaWVudC1jc3IiOiItLS0tLUJFR0lOIENFUlRJRklDQVRFIFJFUVVFU1QtLS0tLVxyXG5NSUlCRFRDQnRRSUJBREFuTVFzd0NRWURWUVFHRXdKRFRqRVlNQllHQTFVRUF3d1BZbVJmZEdsamEyVjBYMmQxXHJcbllYSmtNRmt3RXdZSEtvWkl6ajBDQVFZSUtvWkl6ajBEQVFjRFFnQUVKUDZzbjNLRlFBNUROSEcyK2F4bXAwNG5cclxud1hBSTZDU1IyZW1sVUE5QTZ4aGQzbVlPUlI4NVRLZ2tXd1FJSmp3Nyszdnc0Z2NNRG5iOTRoS3MvSjFJc3FBc1xyXG5NQ29HQ1NxR1NJYjNEUUVKRGpFZE1Cc3dHUVlEVlIwUkJCSXdFSUlPZDNkM0xtUnZkWGxwYmk1amIyMHdDZ1lJXHJcbktvWkl6ajBFQXdJRFJ3QXdSQUlnVmJkWTI0c0RYS0c0S2h3WlBmOHpxVDRBU0ROamNUb2FFRi9MQnd2QS8xSUNcclxuSURiVmZCUk1PQVB5cWJkcytld1QwSDZqdDg1czZZTVNVZEo5Z2dmOWlmeTBcclxuLS0tLS1FTkQgQ0VSVElGSUNBVEUgUkVRVUVTVC0tLS0tXHJcbiJ9',
                    'Accept: */*',
                    'Host: www.douyin.com',
                    'Connection: keep-alive'
                ),
            ));
            $arr = json_decode(curl_exec($curl), true);
            curl_close($curl);

            if (isset($arr['status_code']) && $arr['status_code'] == 0) {
                $VideoSrc = $arr['aweme_detail']['video']['play_addr']['url_list'][0];
                $VideoTitle = $arr['aweme_detail']['desc'];
            } else {
                exception('抖音视频解析失败，请稍后重新尝试');
            }

//            if(preg_match('/http[A-Za-z0-9\/\:\.]+/', $url, $match)){
//                $url = $match[0];
//            }else{
//                exception('参数不正确');
//            }
//            $WebData = $http->request($url, '', 'GET', '', '');
//
//
//            if (is_array($WebData['headers']['Location'])){
//                $VideoInfo = explode("/", $WebData['headers']['Location'][0]);
//
//            }elseif (is_string($WebData['headers']['Location'])){
//                $VideoInfo =explode("/", $WebData['headers']['Location']);
//            }else{
//                exception('抖音分享链接错误');
//            }
//
//            if (isset($VideoInfo[5]) && !empty($VideoInfo['5'])) {
//                $VideoId = $VideoInfo[5];
//            } else {
//                exception('抖音分享视频信息异常');
//            }
//
//            /**
//             * 旧接口无法使用
//             * 'api地址'："https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids=$VideoId"
//             * 不返回标题，可下载
//             * http://47.99.158.118/video-crack/v2/parse?content=
//             */
//            $VideoData =  $http->get3("https://www.iesdouyin.com/aweme/v1/web/aweme/detail/?aweme_id=$VideoId");
//
//            /**
//             * 防止返回‘blocked’
//             */
//            if (is_json($VideoData)){
//                $VideoData = json_decode($VideoData, true);
//            }else{
//                exception('抖音视频解析失败，请稍后重新尝试');
//            }
//
//            /**
//             * 旧接口处理数据：
//             * $VideoSrc = str_replace("playwm", "play", $VideoData['item_list'][0]['video']['play_addr']['url_list'][0]); //视频去水印未跳转地址
//             * $VideoTitle = $VideoData['item_list'][0]['desc']; //视频标题
//             * $VideoImg = $VideoData['item_list'][0]['video']['origin_cover']['url_list'][0]; //视频封面
//             */
//            $VideoSrc = $VideoData['aweme_detail']['video']['bit_rate'][0]['play_addr']['url_list'][0];
//            $VideoTitle = $VideoData['aweme_detail']['desc'];

            $up = new FileManager();
            $ReturnArr = array(
                'title' => $VideoTitle,
            );
            $qiniu = new Qiniu();
            $res = $qiniu->fetchOther($VideoSrc);
            if(isset($res['key'])){
                $file_id = $up->recordToDb($res['key'], 'qiniu');
                $ReturnArr['file_url'] = make_file_url($res['key'], 'qiniu');
                $ReturnArr['file_id'] = $file_id;
                $ReturnArr['file_size'] = $res['fsize'];
            }
            $this->ajaxReturn(200, '上传成功', $ReturnArr);
        }catch (\Exception $e){
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

    public function can_publish(){
        $this->checkLogin();
        if($this->userinfo->utype == 1){
            $m = new SvCompanyVideo();
            try{
                $m->checkCanPublish($this->userinfo->uid);
            }catch (\Exception $e){
                $this->ajaxReturn(500, $e->getMessage(), false);
            }
        }
        $this->ajaxReturn(200, '', true);
    }

    public function save(){
        $this->checkLogin();
        $id = input('post.id/d', 1, 'intval');
        $fid = input('post.fid/d', 0, 'intval');
        $title = input('post.title/s', '', 'trim,htmlspecialchars');
        $lat = input('post.lat/s', '', 'trim,htmlspecialchars');
        $lon = input('post.lon/s', '', 'trim,htmlspecialchars');
        $address = input('post.address/s', '', 'trim,htmlspecialchars');
        $filesize =  input('post.filesize/d', 0, 'intval');
        $type = $this->userinfo->utype;

        if(intval(config('global_config.shortvideo_enable'))==0){
            $this->ajaxReturn(500, '视频招聘功能已关闭');
        }
        $coordinate = model('Config')->wgs84ToBd09($lon,$lat);
        $lon = $coordinate['lng'];
        $lat = $coordinate['lat'];
        try{
            $m = new SvCompanyVideo();
            if($type == 2){
                $m = new SvPersonalVideo();
            }else{
                $m->checkCanPublish($this->userinfo->uid);
            }
            $m->saveVideo($id, $fid,$filesize,$title,$lat,$lon,$address,$this->userinfo->uid);
            $this->ajaxReturn(200, '发布成功');
        }catch (\Exception $e){
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

    public function del(){
        $this->checkLogin();
        $id = input('post.id/d', 1, 'intval');
        $type = $this->userinfo->utype;
        $m = new SvCompanyVideo();
        if($type == 2){
            $m = new SvPersonalVideo();
        }
        try{
            $m->del($id, $this->userinfo->uid);
            $this->ajaxReturn(200, '删除成功');
        }catch (\Exception $e){
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

    public function info(){
        $this->checkLogin();
        $id = input('get.id/d', 1, 'intval');
        $type = $this->userinfo->utype;
        $m = new SvCompanyVideo();
        if($type == 2){
            $m = new SvPersonalVideo();
        }
        $row = $m->find($id);
        if($row['uid'] != $this->userinfo->uid){
            $this->ajaxReturn(500, '非法参数');
        }
        $up = new Uploadfile();
        $row['video_src'] = $up->getFileUrl($row['fid']);
        $this->ajaxReturn(200, '', $row);
    }

    public function lists(){
        $title = input('get.title/s', '', 'trim,htmlspecialchars');
        $page = input('get.page/d', 1, 'intval');
        $pageSize = input('get.pagesize/d', 10, 'intval');
        $type = input('get.type/d', 1, 'intval');
        $comid = input('get.comid/d', 0, 'intval');
        $rid = input('get.rid/d', 0, 'intval');
        $order = input('get.order/d', 1, 'intval');//1最新,2热门,3附近
        $start = input('get.start/d', 0, 'intval');
        $down = input('get.down/d', 1, 'intval');
        $lat = input('get.lat/s', '', 'trim');
        $lon = input('get.lon/s', '', 'trim');
        $m = $this->company;
        if($type == 2){
            $m = $this->personal;
        }
        $uid = 0;
        if($comid){
            $uid = (new \app\common\model\Company())->where(['id'=>$comid])->value('uid');
        }else if($rid){
            $uid = (new \app\common\model\Resume())->where(['id'=>$rid])->value('uid');
        }
        $this->ajaxReturn(200, '', $m->getList($start, $down, $lat, $lon, $title, $uid,  $order,Video::AUDIT_YES, $page, $pageSize, $this->userinfo));
    }

    public function total(){
        $comid = input('get.comid/d', 0, 'intval');
        $rid = input('get.rid/d', 0, 'intval');
        $uid = 0;
        if($comid){
            $m = $this->company;
            $uid = (new \app\common\model\Company())->where(['id'=>$comid])->value('uid');
        }else if($rid){
            $uid = (new \app\common\model\Resume())->where(['id'=>$rid])->value('uid');
            $m = $this->personal;
        }
        $this->ajaxReturn(200, '', $m->getValidTotal($uid));
    }

    public function mine(){
        try{
            $this->checkLogin();
            $page = input('get.page/d', 1, 'intval');
            $pageSize = input('get.pagesize/d', 10, 'intval');
            $start = input('get.start/d', 0, 'intval');
            $down = input('get.down/d', 1, 'intval');
            $type = $this->userinfo->utype;
            $m = $this->company;
            if($type == 2){
                $m = $this->personal;
            }
            $this->ajaxReturn(200, '', $m->getList($start, $down, 0, 0, '', $this->userinfo->uid,  Video::TYPE_LATEST,Video::AUDIT_ALL, $page, $pageSize));
        }catch (\Exception $e){
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

    public function set_public(){
        $this->checkLogin();
        $id = input('post.id/d', 1, 'intval');
        $public = input('post.public/d', 1, 'intval');
        $type = $this->userinfo->utype;
        $m = $this->company;
        if($type == 2){
            $m = $this->personal;
        }
        try{
            $m->setPublic($this->userinfo->uid, $id, $public);
            $this->ajaxReturn(200, $public ? '开启成功' : '关闭成功');
        }catch (\Exception $e){
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

    public function collect(){
        $type = input('post.type/d', 1, 'intval');
        $id = input('post.id/d', 1, 'intval');
        $action = input('post.action/d', 0, 'intval');//0取消,1收藏
        try{
            $this->checkLogin();
            $this->collect->collect($this->userinfo, $id, $type, $action);
            $this->ajaxReturn(200, $action == 1 ? '收藏成功': '取消收藏成功');
        }catch (\Exception $e){
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

    public function detail(){
        $type = input('get.type/d', 1, 'intval');
        $id = input('get.id/d', 1, 'intval');
        $m = $this->company;
        if($type == 2){
            $m = $this->personal;
        }
        try{
            $r = $m->detail($id, $this->userinfo);
            $this->ajaxReturn(200, '', $r);
        }catch (\Exception $e){
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

    public function collects(){
        $page = input('get.page/d', 1, 'intval');
        $pageSize = input('get.pagesize/d', 10, 'intval');
        $start = input('get.start/d', 0, 'intval');
        $down = input('get.down/d', 1, 'intval');
        $this->checkLogin();
        $list = $this->collect->getList($start, $down, $this->userinfo, $page, $pageSize);
        $this->ajaxReturn(200, 'ok', $list);
    }

    public function play(){
        $id = input('get.id/d', 0, 'intval');
        $type = input('get.type/d', 1, 'intval');
        $m = $this->company;
        if($type == 2){
            $m = $this->personal;
        }
        try {
            $m->addPlayRecord($id);
            $this->ajaxReturn(200, 'ok');
        }catch (\Exception $e){
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

    /**
     * 【优化】视频招聘进入传完才提示套餐不支持
     *  zch 2022.9.21
     * 【新增方法】
     */
    public function isAllowPublishing()
    {
        $setMealId = model('Company')->where(['uid'=>$this->userinfo->uid])->value('setmeal_id');
        $setMeals = config('global_config.shortvideo_enable_setmeal');
        $setMeals = array_map('intval', $setMeals);
        if(empty($setMeals)){
            $this->ajaxReturn(500, '当前禁止所有企业上传视频');
        }
        if(!in_array(intval($setMealId), $setMeals)){
            $this->ajaxReturn(500, '您的套餐暂不支持使用视频招聘功能，建议您立即升级套餐');
        }
        $this->ajaxReturn(200, 'ok');
    }


}
