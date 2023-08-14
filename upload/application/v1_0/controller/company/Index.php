<?php
/**
 * 企业会员中心首页
 */
namespace app\v1_0\controller\company;

class Index extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(1);
    }
    public function index()
    {
        $companyinfo = model('Company')
            ->field('id,logo,companyname,scale,nature,trade,audit,district1')
            ->where('uid', $this->userinfo->uid)
            ->find();
        $auth = model('CompanyAuth')->where('uid', $this->userinfo->uid)->find();
        $return_companyinfo = [];
        if ($companyinfo !== null) {
            $return_companyinfo['id'] = $companyinfo['id'];
            $return_companyinfo['companyname'] = $companyinfo['companyname'];
            $return_companyinfo['district1'] = $companyinfo['district1'];
            $return_companyinfo['company_audit'] = $companyinfo['audit'];
            if ($companyinfo['audit'] == 0) {
                //待审核
                if ($auth === null) {
                    //未提交审核
                    $return_companyinfo['company_audit'] = 0;
                } else {
                    //已提交认证资料但待审核
                    $return_companyinfo['company_audit'] = 3;
                }
            } elseif ($companyinfo['audit'] == 1) {
                $return_companyinfo['company_audit'] = 1;
            } else {
                $return_companyinfo['company_audit'] = 0;
            }
            if($companyinfo['audit']==0){
                $return_companyinfo['need_audit'] = 0;
            }else if(config('global_config.must_com_audit_certificate')==1){
                $return_companyinfo['need_audit'] = 1;
            }else{
                $return_companyinfo['need_audit'] = 0;
            }
            $category_data = model('Category')->getCache();
            $return_companyinfo['logo'] =
            $companyinfo['logo'] > 0
            ? model('Uploadfile')->getFileUrl($companyinfo['logo'])
            : default_empty('logo');

            $return_companyinfo['nature_text'] = isset(
                $category_data['QS_company_type'][$companyinfo['nature']]
            )
            ? $category_data['QS_company_type'][$companyinfo['nature']]
            : '';
            $return_companyinfo['trade_text'] = isset(
                $category_data['QS_trade'][$companyinfo['trade']]
            )
            ? $category_data['QS_trade'][$companyinfo['trade']]
            : '';
            $return_companyinfo['scale_text'] = isset(
                $category_data['QS_scale'][$companyinfo['scale']]
            )
            ? $category_data['QS_scale'][$companyinfo['scale']]
            : '';
        }
        $bind_data = model('MemberBind')
            ->where(['uid' => $this->userinfo->uid])
            ->select();
        $return_companyinfo['bind_qq'] = 0;
        $return_companyinfo['bind_sina'] = 0;
        $return_companyinfo['bind_weixin'] = 0;
        if (!empty($bind_data)) {
            foreach ($bind_data as $key => $value) {
                if ($value['type'] == 'qq') {
                    $return_companyinfo['bind_qq'] = 1;
                    continue;
                }
                if ($value['type'] == 'sina') {
                    $return_companyinfo['bind_sina'] = 1;
                    continue;
                }
                if ($value['type'] == 'weixin' && $value['is_subscribe'] == 1) {
                    $return_companyinfo['bind_weixin'] = 1;
                    continue;
                }
            }
        }
        //求职管理===================
        //收到投递
        $job_apply_list = model('JobApply')
            ->alias('a')
            ->field('a.is_look')
            ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
            ->where('a.company_uid', $this->userinfo->uid)
            ->where('b.id','not null')
            ->select();
        $return_manage['job_apply'] = ['red_point' => 0, 'number' => 0];
        foreach ($job_apply_list as $key => $value) {
            if (
                $return_manage['job_apply']['red_point'] == 0 &&
                $value['is_look'] == 0
            ) {
                $return_manage['job_apply']['red_point'] = 1;
            }
            $return_manage['job_apply']['number']++;
        }
        //下载简历
        $return_manage['down_resume'] = [
            'red_point' => 0,
            'number' => model('CompanyDownResume')
                ->alias('a')
                ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
                ->where('a.uid', $this->userinfo->uid)
                ->where('b.id','not null')
                ->count(),
        ];
        //面试邀请
        $return_manage['interview'] = [
            'red_point' => 0,
            'number' => model('CompanyInterview')
                ->alias('a')
                ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
                ->where('a.uid', $this->userinfo->uid)
                ->where('b.id','not null')
                ->count(),
        ];
        //我的收藏
        $return_manage['fav'] = [
            'red_point' => 0,
            'number' => model('FavResume')
                ->alias('a')
                ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
                ->where('a.company_uid', $this->userinfo->uid)
                ->where('b.id','not null')
                ->count(),
        ];
        //浏览记录
        $return_manage['view'] = [
            'red_point' => 0,
            'number' => model('ViewJob')
                ->alias('a')
                /**
                 * 【BUG-ID1000257】
                 * PS：企业会员中心看过我数据统计不准确
                 * yx - 2022.08.01
                 * 【旧】
                 * ->join(config('database.prefix') . 'resume b', 'a.personal_uid=b.id', 'left')
                 */
                ->join(config('database.prefix') . 'resume b', 'a.personal_uid=b.uid', 'left')
                ->where('a.company_uid', $this->userinfo->uid)
                ->where('b.id','not null')
                ->count(),
        ];

        $member_setmeal = model('Member')->getMemberSetmeal($this->userinfo->uid);
        $check_refreshlog = model('RefreshJobLog')->where('uid', $this->userinfo->uid)->where('addtime', '>=', strtotime('today'))->count();

        $message_list = model('Message')
            ->field('content,is_readed')
            ->where('uid', $this->userinfo->uid)
            ->order('id desc')
            ->limit(10)
            ->select();

        //是否提醒完善认证资料
        if(!isset($return_companyinfo['company_audit'])){
            $return_companyinfo['notice_auth_complete'] = 1;
        }else{
            if($return_companyinfo['company_audit']==1){
                if(config('global_config.audit_com_project')==1 && ($auth['legal_person_idcard_front']==0 || $auth['legal_person_idcard_back']==0 || $auth['license']==0 || $auth['proxy']==0)){
                    $return_companyinfo['notice_auth_complete'] = 1;
                }else if(config('global_config.audit_com_project')==0 && $auth['license']==0){
                    $return_companyinfo['notice_auth_complete'] = 1;
                }else{
                    $return_companyinfo['notice_auth_complete'] = 0;
                }
            }else{
                $return_companyinfo['notice_auth_complete'] = 0;
            }
        }
        // 可发布职位数
        $enable_num = model('Job')->getEnableJobaddNum($this->userinfo->uid);

        $return['companyinfo'] = $return_companyinfo;
        $return['manage'] = $return_manage;
        $return['setmeal'] = $member_setmeal;
        $return['refresh_count'] = $check_refreshlog;
        $return['enable_num'] = $enable_num;
        $return['message_list'] = $message_list;
        $return['mypoints'] = model('Member')->getMemberPoints($this->userinfo->uid);
        $return['resumelist_url_web'] = url('index/resume/index');
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function joball()
    {
        $jobid = input('get.jobid/d',0,'intval');
        $where = [
            'j.uid' => $this->userinfo->uid,
            'j.audit' => 1,
            'j.is_display' => 1,
        ];
        if ($jobid > 0)
        {
            $where['j.id'] = $jobid;
        }
        //修改面试邀请调用联系方式 zch 2022/07/20
        $list = model('Job')->alias('j')
            ->join(config('database.prefix') . 'job_contact b', 'b.jid=j.id', 'left')
            ->join(config('database.prefix') . 'company_contact c', 'c.comid=j.company_id', 'left')
            ->where($where)
            ->field('j.id,j.jobname,b.use_company_contact,b.contact as job_contact,b.mobile as job_mobile,c.contact as company_contact,c.mobile as company_mobile,j.address')
            ->select();
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $value['id'];
            $arr['jobname'] = $value['jobname'];
            $arr = [
                'id' => $value['id'],
                'jobname' => $value['jobname'],
                'use_company_contact' => $value['use_company_contact'],
                'job_contact' => $value['job_contact'],
                'job_mobile' => $value['job_mobile'],
                'company_contact' => $value['company_contact'],
                'company_mobile' => $value['company_mobile'],
                'address' => $value['address']
            ];
            $return[] = $arr;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 备注简历
     */
    public function remarkResume()
    {
        $resume_id = input('post.resume_id/d', 0, 'intval');
        $remark = input('post.remark/s', '', 'trim');
        model('Resume')
            ->allowField(true)
            ->save(['remark' => $remark], ['id' => $resume_id]);
        $this->writeMemberActionLog($this->userinfo->uid,'备注简历【简历id：'.$resume_id.'，备注内容：'.$remark.'】');
        $this->ajaxReturn(200, '备注成功');
    }
    /**
     * 会员中心首页招聘效果统计
     */
    public function stat(){
        $id = input('post.id/d',0,'intval');
        $today = strtotime('today');
        $starttime = $today-15*3600*24;
        $map = [
            'company_uid'=>$this->userinfo->uid,
            'jobid'=>$id,
            'addtime'=>['egt',$starttime]
        ];
        // 如果没有传职位ID就去获取全部职位 chenyang 2022年3月22日16:32:33
        if (empty($id)) {
            $joblist = model('Job')
                ->field('id,jobname')
                ->where([
                    'audit'      => 1,
                    'uid'        => $this->userinfo->uid,
                    'is_display' => 1,
                ])
                ->select();
            if (empty($joblist) || $joblist === null) {
                $map['jobid'] = 0;
            }else{
                $id = array_column($joblist, 'id');
                $map['jobid'] = $id[0];
                if (count($id) > 1) {
                    $map['jobid'] = ['in', $id];
                }
            }
        }

        $viewDataAll = model('StatViewJob')->where($map)->select();
        $applyDataAll = model('JobApply')->where($map)->select();
        $allData = $dateArr = $viewData = $applyData = [];
        for ($i=$starttime; $i <= $today; $i+=3600*24) {
            $allData[0][$i] = 0;
            $allData[1][$i] = 0;
            $dateArr[] = date('m-d',$i);
        }
        foreach ($viewDataAll as $key => $value) {
            if(isset($allData[0][$value['addtime']])){
                $allData[0][$value['addtime']]++;
            }else{
                $allData[0][$value['addtime']] = 1;
            }
        }
        foreach ($allData[0] as $key => $value) {
            $viewData[date('m-d',$key)] = $value;
        }

        foreach ($applyDataAll as $key => $value) {
            if(isset($allData[1][$value['addtime']])){
                $allData[1][$value['addtime']]++;
            }else{
                $allData[1][$value['addtime']] = 1;
            }
        }
        foreach ($allData[1] as $key => $value) {
            $applyData[date('m-d',$key)] = $value;
        }

        $viewData = array_values($viewData);
        $applyData = array_values($applyData);
        $return = [
            'viewData'=>$viewData,
            'applyData'=>$applyData,
            'dateArr'=>$dateArr
        ];
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
}
