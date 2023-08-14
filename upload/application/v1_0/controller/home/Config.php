<?php
namespace app\v1_0\controller\home;

class Config extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    /**
     * 获取系统全局配置信息
     */
    public function index()
    {
        $list = model('Config')->getFrontendCache();
        $img_id_arr = [$list['logo'],$list['square_logo'],$list['wechat_qrcode']];
        $img_arr = model('Uploadfile')->getFileUrlBatch($img_id_arr);
        $list['logo'] = isset($img_arr[$list['logo']])?$img_arr[$list['logo']]:make_file_url('resource/logo.png');
        $list['square_logo'] = isset($img_arr[$list['square_logo']])?$img_arr[$list['square_logo']]:make_file_url('resource/square_logo.png');
        $list['wechat_qrcode'] = isset($img_arr[$list['wechat_qrcode']])?$img_arr[$list['wechat_qrcode']]:make_file_url('resource/weixin_img.jpg');
        $list['link_url_web'] = [
            'index'=>url('index/index/index'),
            'joblist'=>url('index/job/index'),
            'famous_joblist'=>url('index/job/index',['famous'=>1]),
            'emergency_joblist'=>url('index/job/index',['listtype'=>'emergency']),
            'companylist'=>url('index/company/index'),
            'resumelist'=>url('index/resume/index'),
            'map'=>url('index/map/index'),
            'articlelist'=>url('index/article/index'),
            'noticelist'=>url('index/notice/index'),
            'hrtoollist'=>url('index/hrtool/index'),
            'helplist'=>url('index/help/show'),
            'resumelist_search_key'=>url('index/resume/index',['keyword'=>'_key_']),
            'joblist_search_key'=>url('index/job/index',['keyword'=>'_key_']),
            'companylist_search_key'=>url('index/company/index',['keyword'=>'_key_']),
            'resumeshow'=>url('index/resume/show',['id'=>'_id_']),
            'companyshow'=>url('index/company/show',['id'=>'_id_']),
            'jobshow'=>url('index/job/show',['id'=>'_id_']),
            'jobfairollist'=>url('index/jobfairol/index')
        ];
        $list['subsite_list'] = [];
        if(config('global_config.subsite_open')==1){
            $subsite_list = model('Subsite')->where('is_display',1)->field('id,sitename,district')->order('sort_id desc,id asc')->select();
            if(!empty($subsite_list)){
                $category_district_data = model('CategoryDistrict')->getCache();
                foreach ($subsite_list as $key => $value) {
                    $arr = [
                        'id'=>$value['id'],
                        'sitename'=>$value['sitename']
                    ];
                    $arr['district_text'] = isset($category_district_data[$value['district']]) ? $category_district_data[$value['district']] : '';
                    $list['subsite_list'][] = $arr;
                }
            }
        }
        if($this->subsite===null){
            $list['subsite_info'] = [
                'id'=>0,
                'sitename'=>'总站',
                'district_text'=>'总站',
                'district1'=>0,
                'district2'=>0,
                'district3'=>0,
                'district'=>0,
                'district_level'=>0,
                'title'=>'',
                'keywords'=>'',
                'description'=>''
            ];
        }else{
            $list['sitename'] = $this->subsite->sitename;
            $list['subsite_info'] = $this->subsite->toArray();
        }

        $config_payment = config('global_config.account_alipay');
        $list['account_alipay_appid'] = $config_payment['appid'];
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
    /**
     * 获取当前用户信息（可用于判断当前用户是否登录）
     */
    public function userinfo()
    {
        $return['login'] = $this->userinfo===null?false:true;
        $return['userinfo'] = $this->userinfo;
        if($this->userinfo===null){
            $return['show_username'] = '';
            $return['preview_id'] = 0;
        }else{
            if($this->userinfo->utype==2){
                $resume = model('Resume')->field('id,fullname')->where('uid',$this->userinfo->uid)->find();
                $return['show_username'] = $resume===null?$this->userinfo->mobile:$resume['fullname'];
                $return['preview_id'] = $resume===null?0:$resume['id'];
            }else{
                $company = model('Company')->field('id,companyname')->where('uid',$this->userinfo->uid)->find();
                $return['show_username'] = $company===null?$this->userinfo->mobile:$company['companyname'];
                $return['preview_id'] = $company===null?0:$company['id'];
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 隐私政策和注册协议
     */
    public function agreementAndPrivacy()
    {
        $list = model('Config')->getCache();
        foreach ($list as $key => $value) {
            if (in_array($key, ['agreement', 'privacy'])) {
                $list[$key] = htmlspecialchars_decode($value,ENT_QUOTES);
                continue;
            }
            unset($list[$key]);
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
    /**
     * 获取音视频配置信息
     */
    public function webrtc()
    {
        $interview_id = input('post.interview_id/d', 0, 'intval');
        if($interview_id>0){
            $this->checkLogin();
            $interview_info = model('CompanyInterviewVideo')->where('id',$interview_id)->find();
            if($interview_info===null){
                $this->ajaxReturn(500,'没有找到面试信息');
            }
            $error = 0;
            if ($interview_info['deadline'] < time()) {
                $room_status = 'overtime';
                $error = 1;
            } else {
                $interview_daytime = strtotime(date('Y-m-d', $interview_info['interview_time']));
                if (time() < $interview_daytime) {
                    $room_status = 'nostart';
                    $error = 1;
                } else {
                    $room_status = 'opened';
                }
            }
            if($error===0){
                $userid = $this->userinfo->uid;
                $userid = 'user_' . $userid . '_splmobile';
                $config = config('global_config');
                $tencent = new \app\common\lib\TLSSigAPIv2($config['account_trtc_appid'], $config['account_trtc_secretkey']);
                $sig = $tencent->genSig($userid);

                $jobinfo = model('Job')->field('minwage,maxwage,negotiable')->where('id',$interview_info['jobid'])->find();
                $resumeinfo = model('Resume')->field('sex,birthday,education,enter_job_time')->where('id',$interview_info['resume_id'])->find();
                $info = [
                    'error'=>$error,
                    'room_status'=>$room_status,
                    'appid' => $config['account_trtc_appid'],
                    'userid' => $userid,
                    'roomid' => $interview_id,
                    'sig' => $sig,
                    'jobname'=>$interview_info['jobname'],
                    'joburl'=>url('index/job/show',['id'=>$interview_info['jobid']]),
                    'wage_text'=>$jobinfo===null?'':model('BaseModel')->handle_wage($jobinfo['minwage'],$jobinfo['maxwage'],$jobinfo['negotiable']),
                    'companyname'=>$interview_info['companyname'],
                    'interview_time'=>date('Y-m-d H:i',$interview_info['interview_time']),
                    'fullname'=>$interview_info['fullname'],
                    'resumeurl'=>url('index/resume/show',['id'=>$interview_info['resume_id']]),
                    'sex_text'=>$resumeinfo['sex']==1?'男':'女',
                    'age_text'=>date('Y') - intval($resumeinfo['birthday']),
                    'education_text'=>isset(model('BaseModel')->map_education[$resumeinfo['education']]) ? model('BaseModel')->map_education[$resumeinfo['education']] : '',
                    'experience_text'=>$resumeinfo['enter_job_time'] == 0? '尚未工作' : format_date($resumeinfo['enter_job_time'])
                ];
            }else{
                $info = [
                    'error'=>$error,
                    'room_status'=>$room_status
                ];
            }
        }else{
            $userid = "test_user_" . substr(md5(time()), 0, 8) . rand(10000, 99999);
            $config = config('global_config');
            $tencent = new \app\common\lib\TLSSigAPIv2($config['account_trtc_appid'], $config['account_trtc_secretkey']);
            $sig = $tencent->genSig($userid);
            $info = [
                'error'=>0,
                'appid' => $config['account_trtc_appid'],
                'userid'=>$userid,
                'sig'=>$sig
            ];
        }

        $this->ajaxReturn(200, '获取数据成功', $info);
    }
    /**
     * 页面信息
     */
    public function pageinfo(){
        $alias = input('post.alias/s','','trim');
        if($alias==''){
            $this->ajaxReturn(200,'获取数据成功',[]);
        }
        $return = model('PageMobile')->getCache($alias);
        if(!$return){
            $this->ajaxReturn(200,'获取数据成功',[]);
        }

        if($this->subsite!==null){
            if($this->subsite->title!=''){
                $return['seo_title'] = $this->subsite->title;
            }
            if($this->subsite->keywords!=''){
                $return['seo_keywords'] = $this->subsite->keywords;
            }
            if($this->subsite->description!=''){
                $return['seo_description'] = $this->subsite->description;
            }
        }
        $return['seo_title'] = str_replace("{sitename}",config('global_config.sitename'),$return['seo_title']);
        $return['seo_keywords'] = str_replace("{sitename}",config('global_config.sitename'),$return['seo_keywords']);
        $return['seo_description'] = str_replace("{sitename}",config('global_config.sitename'),$return['seo_description']);
        $return['og_title'] = config('global_config.sitename');
        $return['og_type'] = '招聘求职网';
        $return['og_site_name'] = config('global_config.sitename');
        $return['og_description'] = '为求职者提供免费注册、求职指导、简历管理等服务，职位真实可靠，上' . config('global_config.sitename') . '，找到满意工作';
        //============处理替换自定义标签start=============
        $query = input('post.data/a',[]);
//        $query = htmlspecialchars_decode($query,ENT_QUOTES);
//        if($query!="{}"){
//            $query = json_decode($query,true);
//        }else{
//            $query = [];
//        }

        $seoData = [];


        if(isset($query['article_cid']) && intval($query['article_cid'])>0){
            $categoryinfo = model('ArticleCategory')->where('id',intval($query['article_cid']))->find();
            if($categoryinfo!==null){
                $seoData['cname'] = $categoryinfo['name'];
                $seoData['seo_keywords'] = $categoryinfo['seo_keywords'];
                $seoData['seo_description'] = $categoryinfo['seo_description'];
            }
        }else{
            $seoData['cname'] = '最新资讯';
        }


        $category_district_data = model('CategoryDistrict')->getCache();
        $category_job_data = model('CategoryJob')->getCache();

        if(isset($query['keyword']) && $query['keyword']!=''){
            $seoData['keyword'] = $query['keyword'];
        }else{
            $seoData['keyword'] = '';
        }

        if(isset($query['district3'])>0 && intval($query['district3'])>0){
            $seoData['citycategory'] = isset($category_district_data[intval($query['district3'])]) ? $category_district_data[intval($query['district3'])] : '';
        }else if(isset($query['district2'])>0 && intval($query['district2'])>0){
            $seoData['citycategory'] = isset($category_district_data[intval($query['district2'])]) ? $category_district_data[intval($query['district2'])] : '';
        }else if(isset($query['district1'])>0 && intval($query['district1'])>0){
            $seoData['citycategory'] = isset($category_district_data[intval($query['district1'])]) ? $category_district_data[intval($query['district1'])] : '';
        }else{
            $seoData['citycategory'] = '';
        }

        if(isset($query['category3'])>0 && intval($query['category3'])>0){
            $seoData['jobcategory'] = isset($category_job_data[intval($query['category3'])]) ? $category_job_data[intval($query['category3'])] : '';
        }else if(isset($query['category2'])>0 && intval($query['category2'])>0){
            $seoData['jobcategory'] = isset($category_job_data[intval($query['category2'])]) ? $category_job_data[intval($query['category2'])] : '';
        }else if(isset($query['category1'])>0 && intval($query['category1'])>0){
            $seoData['jobcategory'] = isset($category_job_data[intval($query['category1'])]) ? $category_job_data[intval($query['category1'])] : '';
        }else{
            $seoData['jobcategory'] = '';
        }

        foreach ($seoData as $key => $value) {
            $return['seo_title'] = str_replace("{".$key."}",$value,$return['seo_title']);
            $return['seo_keywords'] = str_replace("{".$key."}",$value,$return['seo_keywords']);
            $return['seo_description'] = str_replace("{".$key."}",$value,$return['seo_description']);
        }


        $custom_data = input('post.custom_data/a');
        $custom_data = json_encode($custom_data);
        $custom_data = htmlspecialchars_decode($custom_data,ENT_QUOTES);
        if($custom_data!="{}"){
            $custom_data = json_decode($custom_data,true);
        }else{
            $custom_data = [];
        }
        foreach ($custom_data as $key => $value) {
            $return['seo_title'] = str_replace("{".$key."}",$value,$return['seo_title']);
            $return['seo_keywords'] = str_replace("{".$key."}",$value,$return['seo_keywords']);
            $return['seo_description'] = str_replace("{".$key."}",$value,$return['seo_description']);
        }


        //============处理替换自定义标签end=============


        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /*
     * 获取配置信息(单个查询)
     * zch
     * 2022-8-17
     * */
    public function getConfig()
    {
        $name = input('get.name/s','','trim');
        if (empty($name))
        {
            $this->ajaxReturn(500, '缺少查询参数', []);
        }
        $list = model('Config')->getCache($name);
        if (empty($list))
        {
            $this->ajaxReturn(200, '获取数据成功', []);
        }
        if (is_array($list))
        {
            foreach($list as $k=>$v)
            {
                switch ($k)
                {
                    case 'business_license_id':
                        $list['business_license_url'] = model('Uploadfile')->getFileUrl($v);
                        break;
                    case 'business_licenses_id':
                        $list['business_licenses_url'] = model('Uploadfile')->getFileUrl($v);
                        break;
                    case 'service_license_id':
                        $list['service_license_url'] = model('Uploadfile')->getFileUrl($v);
                        break;
                }
            }
        }

        $this->ajaxReturn(200, '获取数据成功', $list);
    }
}
