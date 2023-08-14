<?php

namespace app\apiadmin\controller;

class Entrust extends \app\common\controller\Backend
{
    public function index()
    {
        //清除过期委托
        model('Entrust')->where('deadline','lt',time())->delete();
        $where = [];
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['b.fullname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['a.uid'] = ['eq', intval($keyword)];
                    break;
                default:
                    break;
            }
        }
        $total = model('Entrust')->alias('a')
            ->join(config('database.prefix').'resume b','a.uid=b.uid','LEFT')
            ->where($where)
            ->where('b.id','not null')
            ->count();
        $list = model('Entrust')->alias('a')
            ->join(config('database.prefix').'resume b','a.uid=b.uid','LEFT')
            ->field('a.id,a.days,a.deadline,b.id as resume_id,b.fullname,b.birthday,b.sex,b.education,b.enter_job_time,b.refreshtime,b.addtime')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        

        foreach ($list as $key => $value) {
            $value['age'] =
                date('Y') - intval($value['birthday']) . '岁';
            $value['sex_'] = model('Resume')->map_sex[$value['sex']];
            $value['education_'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[
                    $value['education']
                ]
                : '';
            $value['experience_'] =
                $value['enter_job_time'] == 0
                    ? '无经验'
                    : format_date($value['enter_job_time']);
            
            $value['resume_link'] = url('index/resume/show', [
                'id' => $value['resume_id']
            ]);

            $list[$key] = $value;
        }

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function getIntentions()
    {
        $resumeid = input('get.resumeid/d', 0, 'intval');
        if (!$resumeid) {
            $this->ajaxReturn(500, '请选择简历');
        }
        $category_jobs = model('CategoryJob')->getCache();
        $list = model('ResumeIntention')
            ->field('id,category')
            ->where(['rid' => ['eq', $resumeid]])
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['category_text'] = isset(
                $category_jobs[$value['category']]
            )
                ? $category_jobs[$value['category']]
                : '';
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
    public function matchList(){
        $intentionid = input('get.id/d', 0, 'intval');
        $current_page = input('get.page/d', 0, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if (!$intentionid) {
            $this->ajaxReturn(500, '请选择求职意向');
        }
        $intention_info = model('ResumeIntention')
            ->where('id', $intentionid)
            ->find();
        if ($intention_info === null) {
            $this->ajaxReturn(500, '没有找到求职意向');
        }
        $params = [
            'category1' => $intention_info['category1'],
            'category2' => $intention_info['category2'],
            'category3' => $intention_info['category3'],
            'district1' => $intention_info['district1'],
            'district2' => $intention_info['district2'],
            'district3' => $intention_info['district3'],
            'trade' => $intention_info['trade'],
            'minwage' => $intention_info['minwage'],
            'maxwage' => $intention_info['maxwage'],
            'nature' => $intention_info['nature'],
            'current_page' => $current_page,
            'pagesize' => $pagesize
        ];
        $instance = new \app\common\lib\JobRecommend($params);
        $searchResult = $instance->run(
            'refreshtime>' . (time() - 3600 * 24 * 360)
        );
        $return['items'] = $this->get_job_datalist($searchResult['items'],$intention_info['rid']);
        $return['total'] = model('JobSearchRtime')->where('category1',$intention_info['category1'])->where('refreshtime','gt',time() - 3600 * 24 * 360)->count();
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($return['total'] / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    
    protected function get_job_datalist($list,$resumeid)
    {
        $result_data_list = $jobid_arr = [];
        foreach ($list as $key => $value) {
            $jobid_arr[] = $value['id'];
        }
        if (!empty($jobid_arr)) {
            $field =
                'a.id,a.company_id,a.jobname,a.emergency,a.stick,a.minwage,a.maxwage,a.negotiable,a.education,a.experience,a.district,a.addtime,a.refreshtime,c.companyname';
            $joblist = model('Job')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'company c',
                    'a.uid=c.uid',
                    'LEFT'
                )
                ->whereIn('a.id', $jobid_arr)
                ->where('c.uid','not null')
                ->orderRaw('field(a.id,' . implode(",",$jobid_arr) . ')')
                ->field($field)
                ->select();
            $category_district_data = model('CategoryDistrict')->getCache();

            foreach ($joblist as $key => $val) {
                $tmp_arr = [];
                $tmp_arr['id'] = $val['id'];
                $tmp_arr['company_id'] = $val['company_id'];
                $tmp_arr['jobname'] = $val['jobname'];
                $tmp_arr['emergency'] = $val['emergency'];
                $tmp_arr['stick'] = $val['stick'];
                $tmp_arr['companyname'] = $val['companyname'];
                if ($val['district']) {
                    $tmp_arr['district_text'] = isset(
                        $category_district_data[$val['district']]
                    )
                        ? $category_district_data[$val['district']]
                        : '';
                } else {
                    $tmp_arr['district_text'] = '';
                }
                $tmp_arr['wage_text'] = model('BaseModel')->handle_wage(
                    $val['minwage'],
                    $val['maxwage'],
                    $val['negotiable']
                );

                $tmp_arr['education_text'] = isset(
                    model('BaseModel')->map_education[$val['education']]
                )
                    ? model('BaseModel')->map_education[$val['education']]
                    : '学历不限';
                $tmp_arr['experience_text'] = isset(
                    model('BaseModel')->map_experience[$val['experience']]
                )
                    ? model('BaseModel')->map_experience[$val['experience']]
                    : '经验不限';
                
                $tmp_arr['refreshtime'] = daterange_format(
                    $val['addtime'],
                    $val['refreshtime']
                );
                $tmp_arr['job_link_url_web'] = url('index/job/show',['id'=>$tmp_arr['id']]);
                $tmp_arr['company_link_url_web'] = url('index/company/show',['id'=>$tmp_arr['company_id']]);
                $apply_data = model('JobApply')->where('jobid',$tmp_arr['id'])->where('resume_id',$resumeid)->order('id desc')->find();
                if($apply_data===null){
                    $tmp_arr['enable'] = 1;
                }else if(config('global_config.apply_jobs_space')>0){
                    if($apply_data['addtime']>=strtotime('-' . config('global_config.apply_jobs_space') . 'day')){
                        $tmp_arr['enable'] = 0;
                    }else{
                        $tmp_arr['enable'] = 1;
                    }
                }else{
                    $tmp_arr['enable'] = 0;
                }

                $result_data_list[] = $tmp_arr;
            }
        }
        return $result_data_list;
    }
    public function apply(){
        $jobid = input('post.jobid/d', 0, 'intval');
        $resumeid = input('post.resumeid/d', 0, 'intval');
        if(!$jobid || !$resumeid){
            $this->ajaxReturn(500,'请选择职位或简历');
        }
        $job_info = model('Job')
            ->field('id,uid,jobname,audit')
            ->where('id', 'eq', $jobid)
            ->find();
        $resume_info = model('Resume')
            ->field('id,uid,fullname,audit')
            ->where('id', $resumeid)
            ->find();
        $company_info = model('Company')
            ->field('id,companyname,uid')
            ->where('uid', 'eq', $job_info['uid'])
            ->find();
        $input_data['comid'] = $company_info['id'];
        $input_data['companyname'] = $company_info['companyname'];
        $input_data['company_uid'] = $company_info['uid'];
        $input_data['jobid'] = $job_info['id'];
        $input_data['jobname'] = $job_info['jobname'];
        $input_data['personal_uid'] = $resume_info['uid'];
        $input_data['resume_id'] = $resume_info['id'];
        $input_data['fullname'] = $resume_info['fullname'];
        $input_data['note'] = '管理员投递';
        $input_data['addtime'] = time();
        $input_data['is_look'] = 0;
        $input_data['handle_status'] = 0;
        $input_data['source'] = 1;
        $input_data['platform'] = config('platform');
        $result = model('JobApply')->save($input_data);
        if (false !== $result) {
            model('AdminLog')->record(
                '委托投递简历。姓名【' .
                    $resume_info['fullname'] .
                    '】;职位名称【' .
                    $job_info['jobname'] .
                    '】;企业名称【' .
                    $company_info['companyname'] .
                    '】',
                $this->admininfo
            );
            //通知
            model('NotifyRule')->notify(
                $company_info['uid'],
                1,
                'job_apply',
                [
                    'fullname' => $resume_info['fullname'],
                    'jobname' => $job_info['jobname']
                ],
                $resume_info['id']
            );
            //微信通知
            model('WechatNotifyRule')->notify(
                $company_info['uid'],
                1,
                'job_apply',
                [
                    $resume_info['fullname'].'刚刚投递了您的职位。',
                    $resume_info['fullname'],
                    $job_info['jobname'],
                    '点击立即查看简历详情'
                ],
                'resume/'.$resume_info['id']
            );
        }
        $this->ajaxReturn(200,'投递成功');
    }
    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        model('Entrust')->destroy($id);
        model('AdminLog')->record(
            '删除委托投递。委托ID【' .
                implode(',', $id) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
}
