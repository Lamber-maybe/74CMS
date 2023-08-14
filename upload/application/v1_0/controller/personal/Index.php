<?php
/**
 * 个人会员中心首页
 */
namespace app\v1_0\controller\personal;

class Index extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(2);
    }
    public function index()
    {
        //求职管理===================
        //面试邀请
        $interview_list = model('CompanyInterview')
            ->alias('a')
            ->field('a.is_look')
            ->join(config('database.prefix') . 'job b', 'a.jobid=b.id', 'left')
            ->where('a.personal_uid', $this->userinfo->uid)
            ->where('b.id','not null')
            ->select();
        $return_manage['interview'] = ['red_point' => 0, 'number' => 0];
        foreach ($interview_list as $key => $value) {
            if (
                $return_manage['interview']['red_point'] == 0 &&
                $value['is_look'] == 0
            ) {
                $return_manage['interview']['red_point'] = 1;
            }
            $return_manage['interview']['number']++;
        }
        //我的投递
        $return_manage['job_apply'] = [
            'red_point' => 0,
            'number' => model('JobApply')
                ->alias('a')
                ->join(config('database.prefix') . 'job b', 'a.jobid=b.id', 'left')
                ->where('a.personal_uid', $this->userinfo->uid)
                ->where('b.id','not null')
                ->count()
        ];
        //对我感兴趣
        $return_manage['attention_me'] = [
            'red_point' => 0,
            'number' => model('ViewResume')
                ->alias('a')
                ->join(config('database.prefix') . 'company b', 'a.company_uid=b.uid', 'left')
                ->where('a.personal_uid', $this->userinfo->uid)
                ->where('b.companyname','not null')
                ->count(),
        ];
        //浏览记录
        $return_manage['view'] = [
            'red_point' => 0,
            'number' => model('ViewJob')
                ->alias('a')
                ->join(config('database.prefix').'company b','a.company_uid=b.uid','LEFT')
                ->join(config('database.prefix').'job c','a.jobid=c.id','LEFT')
                ->where('a.personal_uid', $this->userinfo->uid)
                ->where('b.companyname','not null')
                ->where('c.jobname','not null')
                ->count(),
        ];

        // 收藏职位统计
        $return_manage['fav_job'] = [
            'red_point' => 0,
            'number' => model('FavJob')
                ->alias('a')
                ->join(config('database.prefix').'company b','a.company_uid=b.uid','LEFT')
                ->join(config('database.prefix').'job c','a.jobid=c.id','LEFT')
                ->field('a.*,b.id as company_id,b.companyname,b.audit as company_audit,c.jobname,c.education,c.experience,c.district,c.negotiable,c.minwage,c.maxwage,c.click')
                ->where('a.personal_uid', $this->userinfo->uid)
                ->where('b.companyname','not null')
                ->where('c.jobname','not null')
                ->order('a.id desc')
                ->count()
        ];

        $return['manage'] = $return_manage;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
