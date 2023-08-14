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
            ->field('logo,companyname,scale,nature,trade,audit,audit_complete,district1')
            ->where('uid', $this->userinfo->uid)
            ->find();
        $return_companyinfo = [];
        if ($companyinfo !== null) {
            $return_companyinfo['companyname'] = $companyinfo['companyname'];
            $return_companyinfo['district1'] = $companyinfo['district1'];
            $return_companyinfo['company_audit'] = $companyinfo['audit'];
            if ($companyinfo['audit'] == 0) {
                //待审核
                $auth = model('CompanyAuth')
                    ->where('uid', $this->userinfo->uid)
                    ->find();
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
            'number' => model('ViewResume')
                ->alias('a')
                ->join(config('database.prefix') . 'resume b', 'a.resume_id=b.id', 'left')
                ->where('a.company_uid', $this->userinfo->uid)
                ->where('b.id','not null')
                ->count(),
        ];
        
        $member_setmeal = model('Member')->getMemberSetmeal($this->userinfo->uid);
        
        $message_list = model('Message')
            ->field('content,is_readed')
            ->where('uid', $this->userinfo->uid)
            ->order('id desc')
            ->limit(10)
            ->select();

        //是否提醒完善认证资料
        if($return_companyinfo['company_audit']==1 && $companyinfo['audit_complete']==0){
            $return_companyinfo['notice_auth_complete'] = 1;
        }else{
            $return_companyinfo['notice_auth_complete'] = 0;
        }
        $return['companyinfo'] = $return_companyinfo;
        $return['manage'] = $return_manage;
        $return['setmeal'] = $member_setmeal;
        $return['message_list'] = $message_list;
        $return['mypoints'] = model('Member')->getMemberPoints($this->userinfo->uid);
        $return['resumelist_url_web'] = config('global_config.sitedomain').url('index/resume/index');
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function joball()
    {
        $list = model('Job')
            ->where('uid', $this->userinfo->uid)
            ->where('audit', 1)
            ->where('is_display', 1)
            ->field('id,jobname')
            ->select();
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $value['id'];
            $arr['jobname'] = $value['jobname'];
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
