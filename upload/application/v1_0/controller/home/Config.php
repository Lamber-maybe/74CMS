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
            'index'=>config('global_config.sitedomain').url('index/index/index'),
            'joblist'=>config('global_config.sitedomain').url('index/job/index'),
            'famous_joblist'=>config('global_config.sitedomain').url('index/job/index',['famous'=>1]),
            'emergency_joblist'=>config('global_config.sitedomain').url('index/job/index',['listtype'=>'emergency']),
            'companylist'=>config('global_config.sitedomain').url('index/company/index'),
            'resumelist'=>config('global_config.sitedomain').url('index/resume/index'),
            'map'=>config('global_config.sitedomain').url('index/map/index'),
            'articlelist'=>config('global_config.sitedomain').url('index/article/index'),
            'noticelist'=>config('global_config.sitedomain').url('index/notice/index'),
            'hrtoollist'=>config('global_config.sitedomain').url('index/hrtool/index'),
            'helplist'=>config('global_config.sitedomain').url('index/help/show'),
            'resumelist_search_key'=>config('global_config.sitedomain').url('index/resume/index',['keyword'=>'_key_']),
            'joblist_search_key'=>config('global_config.sitedomain').url('index/job/index',['keyword'=>'_key_']),
            'companylist_search_key'=>config('global_config.sitedomain').url('index/company/index',['keyword'=>'_key_']),
            'resumeshow'=>config('global_config.sitedomain').url('index/resume/show',['id'=>'_id_']),
            'companyshow'=>config('global_config.sitedomain').url('index/company/show',['id'=>'_id_'])
        ];
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
                $list[$key] = htmlspecialchars_decode($value);
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
                    'joburl'=>config('global_config.sitedomain').url('index/job/show',['id'=>$interview_info['jobid']]),
                    'wage_text'=>$jobinfo===null?'':model('BaseModel')->handle_wage($jobinfo['minwage'],$jobinfo['maxwage'],$jobinfo['negotiable']),
                    'companyname'=>$interview_info['companyname'],
                    'interview_time'=>date('Y-m-d H:i',$interview_info['interview_time']),
                    'fullname'=>$interview_info['fullname'],
                    'resumeurl'=>config('global_config.sitedomain').url('index/resume/show',['id'=>$interview_info['resume_id']]),
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
}
