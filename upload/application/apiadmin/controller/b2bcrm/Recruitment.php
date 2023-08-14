<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;

class Recruitment extends Backend
{
    /*
     * 收到投递
     * */
    public function jobApply()
    {
        $company_id = input('get.company_id/d',0,'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($company_id == 0)
        {
            $this->ajaxReturn(500, '请选择企业');
        }
        $where['a.comid'] = $company_id;

        $total = model('JobApply')->alias('a')->where($where)->count();
        $list = model('JobApply')
            ->alias('a')
            ->join('resume b', 'a.resume_id=b.id', 'left')
            ->field('a.id,a.comid,a.companyname,a.jobid,a.jobname,a.resume_id,a.fullname,a.note,a.addtime,a.is_look,a.handle_status as interview,a.source,a.platform,b.display_name,b.high_quality,b.birthday,b.sex,b.education,b.enter_job_time,b.photo_img,b.current,b.remark,b.audit')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.id desc')
            ->page($current_page, $pagesize)
            ->select();

        $intention_arr = [];
        $fullname_arr = [];

        foreach ($list as $value) {
            $ridarr[] = $value['resume_id'];
        }

        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        if (!empty($ridarr)) {
            $complete_list = model('Resume')->countCompletePercentBatch(
                $ridarr
            );

            $intention_data = model('ResumeIntention')
                ->where('rid', 'in', $ridarr)
                ->order('id asc')
                ->select();
            foreach ($intention_data as $key => $value) {
                $intention_arr[$value['rid']][] = $value;
            }
        }
        $lists = [];
        foreach ($list as $value) {
            $arr['link'] = url('index/resume/show', ['id' => $value['resume_id']]);
            $arr['interview'] = $value['interview'];
            $arr['resume_id'] = $value['resume_id'];
            $arr['fullname'] = isset($fullname_arr[$value['resume_id']])?$fullname_arr[$value['resume_id']]:$value['fullname'];
            $arr['sex_text'] = model('Resume')->map_sex[$value['sex']];
            $arr['age'] = date('Y') - intval($value['birthday']);

            $arr['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[
                $value['education']
                ]
                : '';
            $arr['experience_text'] =
                $value['enter_job_time'] == 0
                    ? '无经验'
                    : format_date($value['enter_job_time']) . '经验';

            $district_arr = $category_arr = [];
            if (isset($intention_arr[$value['resume_id']])) {
                foreach ($intention_arr[$value['resume_id']] as $k => $v) {
                    if ($v['trade']) {
                        $trade_arr[] =
                            $category_data['QS_trade'][$v['trade']];
                    }
                    if ($v['nature']) {
                        $nature_arr[] = model('Resume')->map_nature[
                        $v['nature']
                        ];
                    }
                    $wage_arr[0] = $v['minwage'] . '-' . $v['maxwage'];
                    if ($v['category']) {
                        $category_arr[] = isset(
                            $category_job_data[$v['category']]
                        )
                            ? $category_job_data[$v['category']]
                            : '';
                    }
                    if ($v['district']) {
                        $district_arr[] = isset(
                            $category_district_data[$v['district']]
                        )
                            ? $category_district_data[$v['district']]
                            : '';
                    }
                }
            }
            if (!empty($category_arr)) {
                $category_arr = array_unique($category_arr);
                $arr['intention_jobs'] = implode(',', $category_arr);
            } else {
                $arr['intention_jobs'] = '';
            }

            if (!empty($district_arr)) {
                $district_arr = array_unique($district_arr);
                $arr['intention_district'] = implode(',', $district_arr);
            } else {
                $arr['intention_district'] = '';
            }
            $arr['jobname'] = $value['jobname'];
            // 【bug】CRM客户详情 投递时间显示为时间戳问题修改  zch 2022.9.20
            $arr['addtime'] = !empty($value['addtime']) ? date('Y-m-d H:i:s',$value['addtime']) : '';
            $arr['is_look'] = $value['is_look'];
            $arr['handle_status'] = $value['handle_status'];
            $arr['complete_percent'] = isset($complete_list[$value['resume_id']])
                ? $complete_list[$value['resume_id']]
                : 0;
            $lists[] = $arr;
        }
        $return['items'] = $lists;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /*
     * 主动下载
     * */
    public function downResume()
    {
        $company_id = input('get.company_id/d',0,'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($company_id == 0)
        {
            $this->ajaxReturn(500, '请选择企业');
        }
        $where['a.comid'] = $company_id;
        $total = model('CompanyDownResume')
            ->alias('a')
            ->join('resume b', 'a.resume_id=b.id', 'left')
            ->where($where)
            ->where('b.id','not null')
            ->count();
        $list = model('CompanyDownResume')
            ->alias('a')
            ->join('resume b', 'a.resume_id=b.id', 'left')
            ->field('a.id,a.resume_id,a.addtime,b.fullname,b.display_name,b.high_quality,b.birthday,b.sex,b.education,b.enter_job_time,b.photo_img,b.current,b.remark,b.audit,a.addtime')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.id desc')
            ->page($current_page, $pagesize)
            ->select();
        $resumeid_arr = [];
        $intention_arr = [];
        $photo_id_arr = [];
        $photo_data = [];
        $work_list = [];
        foreach ($list as $key => $value) {
            $resumeid_arr[] = $value['resume_id'];
            $value['photo_img'] > 0 && ($photo_id_arr[] = $value['photo_img']);
        }
        if (!empty($photo_id_arr)) {
            $photo_data = model('Uploadfile')->getFileUrlBatch(
                $photo_id_arr
            );
        }
        if (!empty($resumeid_arr)) {
            $intention_data = model('ResumeIntention')
                ->where('rid', 'in', $resumeid_arr)
                ->order('id asc')
                ->select();
            foreach ($intention_data as $key => $value) {
                $intention_arr[$value['rid']][] = $value;
            }

            $work_data = model('ResumeWork')
                ->where('rid', 'in', $resumeid_arr)
                ->order('id desc')
                ->select();
            foreach ($work_data as $key => $value) {
                if (isset($work_list[$value['rid']])) {
                    //只取第一份工作经历（最后填写的一份）
                    continue;
                }
                $work_list[$value['rid']] = $value;
            }
        }

        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $lists = [];
        foreach ($list as $value) {
            $arr = [];
            $arr['link'] = url('index/resume/show', ['id' => $value['resume_id']]);
            $arr['addtime'] = $value['addtime'];
            $arr['fullname'] = $value['fullname'];
            $arr['sex_text'] = model('Resume')->map_sex[
            $value['sex']
            ];
            $arr['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[
                $value['education']
                ]
                : '';
            $arr['experience_text'] =
                $value['enter_job_time'] == 0
                    ? '无经验'
                    : format_date($value['enter_job_time']) . '经验';

            $arr['age'] = date('Y') - intval($value['birthday']);
            $district_arr = $category_arr = [];
            if (isset($intention_arr[$value['resume_id']])) {
                foreach ($intention_arr[$value['resume_id']] as $k => $v) {
                    if ($v['trade']) {
                        $trade_arr[] =
                            $category_data['QS_trade'][$v['trade']];
                    }
                    if ($v['nature']) {
                        $nature_arr[] = model('Resume')->map_nature[
                        $v['nature']
                        ];
                    }
                    $wage_arr[0] = $v['minwage'] . '-' . $v['maxwage'];
                    if ($v['category']) {
                        $category_arr[] = isset(
                            $category_job_data[$v['category']]
                        )
                            ? $category_job_data[$v['category']]
                            : '';
                    }
                    if ($v['district']) {
                        $district_arr[] = isset(
                            $category_district_data[$v['district']]
                        )
                            ? $category_district_data[$v['district']]
                            : '';
                    }
                }
            }
            if (!empty($category_arr)) {
                $category_arr = array_unique($category_arr);
                $arr['intention_jobs'] = implode(',', $category_arr);
            } else {
                $arr['intention_jobs'] = '';
            }
            if (!empty($district_arr)) {
                $district_arr = array_unique($district_arr);
                $arr['intention_district'] = implode(',', $district_arr);
            } else {
                $arr['intention_district'] = '';
            }
            $arr['photo_img_src'] = isset(
                $photo_data[$value['photo_img']]
            )
                ? $photo_data[$value['photo_img']]
                : default_empty('photo', $value['sex']);

            $arr['resume_link_url_web'] = url('index/resume/show',['id'=>$value['resume_id']]);
            $lists[] = $arr;
        }
        $return['items'] = $lists;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /*
     * 近期查看
     * */
    public function viewResume()
    {
        $company_uid = input('get.company_uid/d',0,'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($company_uid == 0)
        {
            $this->ajaxReturn(500, '请选择企业');
        }
        $where['a.company_uid'] = $company_uid;
        $total = model('ViewResume')
            ->alias('a')
            ->join('resume b', 'a.resume_id=b.id', 'left')
            ->where($where)
            ->where('b.id','not null')
            ->count();
        $list = model('ViewResume')
            ->alias('a')
            ->join('resume b', 'a.resume_id=b.id', 'left')
            ->field('a.id,a.resume_id,a.addtime,b.fullname,b.display_name,b.high_quality,b.birthday,b.sex,b.education,b.enter_job_time,b.photo_img,b.current')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.addtime desc') //【优化】企业会员中心求职管理-浏览记录 排序方式
            ->page($current_page, $pagesize)
            ->select();
        $resumeid_arr = [];
        $intention_arr = [];
        $photo_id_arr = [];
        $photo_data = [];
        $work_list = [];
        foreach ($list as $value) {
            $resumeid_arr[] = $value['resume_id'];
            $value['photo_img'] > 0 && ($photo_id_arr[] = $value['photo_img']);
        }
        if (!empty($photo_id_arr)) {
            $photo_data = model('Uploadfile')->getFileUrlBatch(
                $photo_id_arr
            );
        }
        if (!empty($resumeid_arr)) {
            $intention_data = model('ResumeIntention')
                ->where('rid', 'in', $resumeid_arr)
                ->order('id asc')
                ->select();
            foreach ($intention_data as $key => $value) {
                $intention_arr[$value['rid']][] = $value;
            }
            $work_data = model('ResumeWork')
                ->where('rid', 'in', $resumeid_arr)
                ->order('id desc')
                ->select();
            foreach ($work_data as $key => $value) {
                if (isset($work_list[$value['rid']])) {
                    //只取第一份工作经历（最后填写的一份）
                    continue;
                }
                $work_list[$value['rid']] = $value;
            }
        }

        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $lists = [];
        foreach ($list as $value) {
            $arr = [];
            $arr['link'] = url('index/resume/show', ['id' => $value['resume_id']]);
            $arr['fullname'] = $value['fullname'];
            $value['high_quality'] = $value['high_quality'];
            $arr['sex_text'] = model('Resume')->map_sex[
            $value['sex']
            ];
            $arr['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[
                $value['education']
                ]
                : '';
            $arr['addtime'] = $value['addtime'];
            $arr['experience_text'] =
                $value['enter_job_time'] == 0
                    ? '无经验'
                    : format_date($value['enter_job_time']) . '经验';

            $arr['age'] = date('Y') - intval($value['birthday']);
            $district_arr = $category_arr = [];
            if (isset($intention_arr[$value['resume_id']])) {
                foreach ($intention_arr[$value['resume_id']] as $k => $v) {
                    if ($v['trade']) {
                        $trade_arr[] =
                            $category_data['QS_trade'][$v['trade']];
                    }
                    if ($v['nature']) {
                        $nature_arr[] = model('Resume')->map_nature[
                        $v['nature']
                        ];
                    }
                    $wage_arr[0] = $v['minwage'] . '-' . $v['maxwage'];
                    if ($v['category']) {
                        $category_arr[] = isset(
                            $category_job_data[$v['category']]
                        )
                            ? $category_job_data[$v['category']]
                            : '';
                    }
                    if ($v['district']) {
                        $district_arr[] = isset(
                            $category_district_data[$v['district']]
                        )
                            ? $category_district_data[$v['district']]
                            : '';
                    }
                }
            }
            if (!empty($category_arr)) {
                $category_arr = array_unique($category_arr);
                $arr['intention_jobs'] = implode(',', $category_arr);
            } else {
                $arr['intention_jobs'] = '';
            }
            if (!empty($district_arr)) {
                $district_arr = array_unique($district_arr);
                $arr['intention_district'] = implode(',', $district_arr);
            } else {
                $arr['intention_district'] = '';
            }
            $arr['photo_img_src'] = isset(
                $photo_data[$value['photo_img']]
            )
                ? $photo_data[$value['photo_img']]
                : default_empty('photo', $value['sex']);

            $arr['resume_link_url_web'] = url('index/resume/show',['id'=>$value['resume_id']]);
            $lists[] = $arr;
        }

        $return['items'] = $lists;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /*
     * 职位被查看
     * */
    public function viewBeBrowsed()
    {
        $company_uid = input('get.company_uid/d',0,'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($company_uid == 0)
        {
            $this->ajaxReturn(500, '请选择企业');
        }
        $where['a.company_uid'] = $company_uid;
        $total = model('ViewJob')
            ->alias('a')
            ->join('resume b', 'a.personal_uid=b.uid', 'left')
            ->where($where)
            ->where('b.id','not null')
            ->count();
        $list = model('ViewJob')
            ->alias('a')
            ->join('resume b', 'a.personal_uid=b.uid', 'left')
            ->field('a.id,a.jobid,a.personal_uid,a.addtime,b.fullname,b.display_name,b.high_quality,
            b.birthday,b.sex,b.education,b.enter_job_time,b.photo_img,b.current,b.refreshtime,b.id as resume_id,b.fullname')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.addtime desc') // 【优化】企业会员中心求职管理-浏览记录 排序方式
            ->page($current_page, $pagesize)
            ->select();
        $resumeid_arr = [];
        $intention_arr = [];
        $photo_id_arr = [];
        $photo_data = [];
        $work_list = [];
        foreach ($list as $key => $value) {
            $resumeid_arr[] = $value['resume_id'];
            $value['photo_img'] > 0 && ($photo_id_arr[] = $value['photo_img']);
        }
        if (!empty($photo_id_arr)) {
            $photo_data = model('Uploadfile')->getFileUrlBatch(
                $photo_id_arr
            );
        }
        if (!empty($resumeid_arr)) {
            $intention_data = model('ResumeIntention')
                ->where('rid', 'in', $resumeid_arr)
                ->order('id asc')
                ->select();
            foreach ($intention_data as $key => $value) {
                $intention_arr[$value['rid']][] = $value;
            }
            $work_data = model('ResumeWork')
                ->where('rid', 'in', $resumeid_arr)
                ->order('id desc')
                ->select();
            foreach ($work_data as $key => $value) {
                if (isset($work_list[$value['rid']])) {
                    //只取第一份工作经历（最后填写的一份）
                    continue;
                }
                $work_list[$value['rid']] = $value;
            }
        }

        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $lists = [];
        foreach ($list as $key => $value) {
            $arr = [];
            $arr['link'] = url('index/resume/show', ['id' => $value['resume_id']]);
            $arr['fullname'] = $value['fullname'];
            $arr['addtime'] = $value['addtime'];
            $arr['sex_text'] = model('Resume')->map_sex[
            $value['sex']
            ];
            $arr['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[
                $value['education']
                ]
                : '';
            $arr['experience_text'] =
                $value['enter_job_time'] == 0
                    ? '无经验'
                    : format_date($value['enter_job_time']) . '经验';

            $arr['age'] = date('Y') - intval($value['birthday']);
            $district_arr = $category_arr = [];
            if (isset($intention_arr[$value['resume_id']])) {
                foreach ($intention_arr[$value['resume_id']] as $k => $v) {
                    if ($v['trade']) {
                        $trade_arr[] =
                            $category_data['QS_trade'][$v['trade']];
                    }
                    if ($v['nature']) {
                        $nature_arr[] = model('Resume')->map_nature[
                        $v['nature']
                        ];
                    }
                    $wage_arr[0] = $v['minwage'] . '-' . $v['maxwage'];
                    if ($v['category']) {
                        $category_arr[] = isset(
                            $category_job_data[$v['category']]
                        )
                            ? $category_job_data[$v['category']]
                            : '';
                    }
                    if ($v['district']) {
                        $district_arr[] = isset(
                            $category_district_data[$v['district']]
                        )
                            ? $category_district_data[$v['district']]
                            : '';
                    }
                }
            }
            if (!empty($category_arr)) {
                $category_arr = array_unique($category_arr);
                $arr['intention_jobs'] = implode(',', $category_arr);
            } else {
                $arr['intention_jobs'] = '';
            }
            if (!empty($district_arr)) {
                $district_arr = array_unique($district_arr);
                $arr['intention_district'] = implode(',', $district_arr);
            } else {
                $arr['intention_district'] = '';
            }
            $arr['photo_img_src'] = isset(
                $photo_data[$value['photo_img']]
            )
                ? $photo_data[$value['photo_img']]
                : default_empty('photo', $value['sex']);

            $arr['resume_link_url_web'] = url('index/resume/show',['id'=>$value['resume_id']]);
            $arr['jobname'] = model('job')->where('id',$value['jobid'])->value('jobname');
            $lists[] = $arr;
        }
        $return['items'] = $lists;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}