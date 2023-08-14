<?php

namespace app\apiadmin\controller;

class CompanyInterviewVideo extends \app\common\controller\Backend
{
    /**
     * 视频面试列表
     */
    public function index()
    {
        $where = [];
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['comid'] = ['eq', intval($keyword)];
                    break;
                case 3:
                    $where['jobname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 4:
                    $where['jobid'] = ['eq', intval($keyword)];
                    break;
                case 5:
                    $where['fullname'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    break;
            }
        }
        $total = model('CompanyInterviewVideo')
            ->where($where)
            ->count();
        $list = model('CompanyInterviewVideo')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $resumeid_arr = [];
        foreach ($list as $key => $value) {
            $resumeid_arr[] = $value['resume_id'];
        }
        if (!empty($resumeid_arr)) {
            $resumelist = model('Resume')
                ->where('id', 'in', $resumeid_arr)
                ->column('id,sex,birthday,education,enter_job_time', 'id');
        } else {
            $resumelist = [];
        }

        foreach ($list as $key => $value) {
            if (isset($resumelist[$value['resume_id']])) {
                $resumeinfo = $resumelist[$value['resume_id']];
                $value['age'] =
                    date('Y') - intval($resumeinfo['birthday']) . '岁';
                $value['sex_'] = model('Resume')->map_sex[$resumeinfo['sex']];
                $value['education_'] = isset(
                    model('BaseModel')->map_education[$resumeinfo['education']]
                )
                    ? model('BaseModel')->map_education[
                        $resumeinfo['education']
                    ]
                    : '';
                $value['experience_'] =
                    $resumeinfo['enter_job_time'] == 0
                        ? '无经验'
                        : format_date($resumeinfo['enter_job_time']);
            } else {
                $value['age'] = '年龄未知';
                $value['sex_'] = '性别未知';
                $value['education_'] = '学历未知';
                $value['experience_'] = '工作经验未知';
            }
            $value['job_link'] = config('global_config.sitedomain').url('index/job/show', [
                'id' => $value['jobid']
            ]);
            $value['company_link'] = config('global_config.sitedomain').url('index/company/show', [
                'id' => $value['comid']
            ]);
            $value['resume_link'] = config('global_config.sitedomain').url('index/resume/show', [
                'id' => $value['resume_id']
            ]);
            if ($value['deadline'] < time()) {
                $value['room_status'] = 'overtime';
            } else {
                $interview_daytime = strtotime(date('Y-m-d', $value['interview_time']));
                if (time() < $interview_daytime) {
                    $value['room_status'] = 'nostart';
                } else {
                    $value['room_status'] = 'opened';
                }
            }

            $list[$key] = $value;
        }

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
