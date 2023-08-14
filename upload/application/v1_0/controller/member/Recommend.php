<?php
/**
 * 智能推荐
 */
namespace app\v1_0\controller\member;

class Recommend extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function getIntentions()
    {
        $this->checkLogin(2);
        $category_jobs = model('CategoryJob')->getCache();
        $list = model('ResumeIntention')
            ->field(
                'id,nature,category1,category2,category3,category,district1,district2,district3,district,minwage,maxwage,trade'
            )
            ->where(['uid' => ['eq', $this->userinfo->uid]])
            ->select();
        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        foreach ($list as $key => $value) {
            $list[$key]['category_text'] = isset(
                $category_jobs[$value['category']]
            )
                ? $category_jobs[$value['category']]
                : '';
            $list[$key]['nature_text'] = isset(
                model('Resume')->map_nature[$value['nature']]
            )
                ? model('Resume')->map_nature[$value['nature']]
                : '全职';
            $list[$key]['district_text'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $list[$key]['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                0
            );
            $list[$key]['trade_text'] = isset(
                $category_data['QS_trade'][$value['trade']]
            )
                ? $category_data['QS_trade'][$value['trade']]
                : '';
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }
    public function getJobs()
    {
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
        $type = input('get.type/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        $list = model('Job')
            ->alias('a')
            ->field(
                'id,jobname,category1,category2,category3,district1,district2,district3,minwage,maxwage,nature,education,experience,minage,maxage'
            )
            ->where('audit', 1)
            ->where('is_display', 1)
            ->where('uid', 'eq', $this->userinfo->uid)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['trade'] = $this->company_profile->trade;
        }

        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function job()
    {
        $this->checkLogin(2);
        $this->interceptPersonalResume();
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
        $return['items'] = $this->get_job_datalist($searchResult['items']);
        $return['joblist_link_url_web'] = config('global_config.sitedomain').url('index/job/index');
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function jobTotal()
    {
        $this->checkLogin(2);
        $this->interceptPersonalResume();
        $intentionid = input('get.id/d', 0, 'intval');
        if (!$intentionid) {
            $this->ajaxReturn(500, '请选择求职意向');
        }
        $intention_info = model('ResumeIntention')
            ->where('id', $intentionid)
            ->find();
        if ($intention_info === null) {
            $this->ajaxReturn(500, '没有找到求职意向');
        }
        $total = model('JobSearchRtime')->where('category1',$intention_info['category1'])->where('refreshtime','gt',time() - 3600 * 24 * 360)->count();
        $this->ajaxReturn(200, '获取数据成功', $total);
    }
    public function resume()
    {
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
        $jobid = input('get.id/d', 0, 'intval');
        $current_page = input('get.page/d', 0, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if (!$jobid) {
            $this->ajaxReturn(500, '请选择职位');
        }
        $jobinfo = model('Job')
            ->where('id', $jobid)
            ->find();
        if ($jobinfo === null) {
            $this->ajaxReturn(500, '没有找到职位');
        }
        $companyinfo = model('Company')
            ->where('id', $jobinfo['company_id'])
            ->field('trade')
            ->find();
        $params = [
            'category1' => $jobinfo['category1'],
            'category2' => $jobinfo['category2'],
            'category3' => $jobinfo['category3'],
            'district1' => $jobinfo['district1'],
            'district2' => $jobinfo['district2'],
            'district3' => $jobinfo['district3'],
            'trade' => intval($companyinfo['trade']),
            'minwage' => $jobinfo['minwage'],
            'maxwage' => $jobinfo['maxwage'],
            'nature' => $jobinfo['nature'],
            'education' => $jobinfo['education'],
            'experience' => $jobinfo['experience'],
            'minage' => $jobinfo['minage'],
            'maxage' => $jobinfo['maxage'],
            'current_page' => $current_page,
            'pagesize' => $pagesize
        ];
        if ($this->userinfo && $this->userinfo->utype == 1) {
            $shield_find = model('Shield')
                ->where('company_uid', $this->userinfo->uid)
                ->find();
            if ($shield_find !== null) {
                $params['shield_company_uid'] = $this->userinfo->uid;
            }
        }
        $instance = new \app\common\lib\ResumeRecommend($params);
        $searchResult = $instance->run();
        $return['items'] = $this->get_resume_datalist($searchResult['items']);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function resumeTotal()
    {
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
        $jobid = input('get.id/d', 0, 'intval');
        if (!$jobid) {
            $this->ajaxReturn(500, '请选择职位');
        }
        $jobinfo = model('Job')
            ->where('id', $jobid)
            ->find();
        if ($jobinfo === null) {
            $this->ajaxReturn(500, '没有找到职位');
        }
        $total = model('ResumeSearchRtime')->alias('a')->join(config('database.prefix').'resume_intention b','a.id=b.rid','LEFT')->where('b.category1',$jobinfo['category1'])->where('a.refreshtime','gt',time() - 3600 * 24 * 360);
        if ($this->userinfo && $this->userinfo->utype == 1) {
            $shield_find = model('Shield')
                ->where('company_uid', $this->userinfo->uid)
                ->find();
            if ($shield_find !== null) {
                $total = $total->join(config('database.prefix') . 'shield c','a.uid=c.personal_uid','LEFT')->where(function ($query) {
                    $query->where('c.company_uid', 'neq',$this->userinfo->uid)->whereOr('c.id', null);
                });
            }
        }
        $total = $total->count('distinct a.uid');
        $this->ajaxReturn(200, '获取数据成功', $total);
    }

    protected function get_job_datalist($list)
    {
        $result_data_list = $jobid_arr = $comid_arr = $cominfo_arr = $logo_id_arr = $logo_arr = [];
        foreach ($list as $key => $value) {
            $jobid_arr[] = $value['id'];
            $comid_arr[] = $value['company_id'];
        }
        if ($jobid_arr) {
            if (!empty($comid_arr)) {
                $cominfo_arr = model('Company')
                    ->where('id', 'in', $comid_arr)
                    ->column(
                        'id,companyname,audit,logo,nature,scale,trade',
                        'id'
                    );
                foreach ($cominfo_arr as $key => $value) {
                    $value['logo'] > 0 && ($logo_id_arr[] = $value['logo']);
                }
                if (!empty($logo_id_arr)) {
                    $logo_arr = model('Uploadfile')->getFileUrlBatch(
                        $logo_id_arr
                    );
                }
            }
            $rids = implode(',', $jobid_arr);
            $field =
                'a.id,a.company_id,a.jobname,a.emergency,a.stick,a.minwage,a.maxwage,a.negotiable,a.education,a.experience,a.tag,a.district,a.addtime,a.refreshtime,a.map_lat,a.map_lng,a.setmeal_id,b.icon';
            $joblist = model('Job')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'setmeal b',
                    'a.setmeal_id=b.id',
                    'LEFT'
                )
                ->where('a.id', 'in', $rids)
                ->orderRaw('field(a.id,' . $rids . ')')
                ->field($field)
                ->select();
            $category_data = model('Category')->getCache();
            $category_district_data = model('CategoryDistrict')->getCache();

            foreach ($joblist as $key => $val) {
                $tmp_arr = [];
                $tmp_arr['id'] = $val['id'];
                $tmp_arr['jobname'] = $val['jobname'];
                $tmp_arr['emergency'] = $val['emergency'];
                $tmp_arr['stick'] = $val['stick'];
                if (isset($cominfo_arr[$val['company_id']])) {
                    $tmp_arr['companyname'] =
                        $cominfo_arr[$val['company_id']]['companyname'];
                    $tmp_arr['company_audit'] =
                        $cominfo_arr[$val['company_id']]['audit'];
                    $tmp_arr['company_logo'] = isset(
                        $logo_arr[$cominfo_arr[$val['company_id']]['logo']]
                    )
                        ? $logo_arr[$cominfo_arr[$val['company_id']]['logo']]
                        : default_empty('logo');
                    $tmp_arr['company_trade_text'] = isset(
                        $category_data['QS_trade'][
                            $cominfo_arr[$val['company_id']]['trade']
                        ]
                    )
                        ? $category_data['QS_trade'][
                            $cominfo_arr[$val['company_id']]['trade']
                        ]
                        : '';
                    $tmp_arr['company_scale_text'] = isset(
                        $category_data['QS_scale'][
                            $cominfo_arr[$val['company_id']]['scale']
                        ]
                    )
                        ? $category_data['QS_scale'][
                            $cominfo_arr[$val['company_id']]['scale']
                        ]
                        : '';
                    $tmp_arr['company_nature_text'] = isset(
                        $category_data['QS_company_type'][
                            $cominfo_arr[$val['company_id']]['nature']
                        ]
                    )
                        ? $category_data['QS_company_type'][
                            $cominfo_arr[$val['company_id']]['nature']
                        ]
                        : '';
                } else {
                    $tmp_arr['companyname'] = '';
                    $tmp_arr['company_audit'] = 0;
                    $tmp_arr['company_logo'] = '';
                    $tmp_arr['company_trade_text'] = '';
                    $tmp_arr['company_scale_text'] = '';
                    $tmp_arr['company_nature_text'] = '';
                }

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
                $tmp_arr['tag_text_arr'] = [];
                if ($val['tag']) {
                    $tag_arr = explode(',', $val['tag']);
                    foreach ($tag_arr as $k => $v) {
                        if (
                            is_numeric($v) &&
                            isset($category_data['QS_jobtag'][$v])
                        ) {
                            $tmp_arr['tag_text_arr'][] =
                                $category_data['QS_jobtag'][$v];
                        } else {
                            $tmp_arr['tag_text_arr'][] = $v;
                        }
                    }
                }
                $tmp_arr['refreshtime'] = daterange_format(
                    $val['addtime'],
                    $val['refreshtime']
                );
                $tmp_arr['map_lat'] = $val['map_lat'];
                $tmp_arr['map_lng'] = $val['map_lng'];
                $tmp_arr['setmeal_icon'] =
                    $val['icon'] > 0
                        ? model('Uploadfile')->getFileUrl($val['icon'])
                        : model('Setmeal')->getSysIcon($val['setmeal_id']);
                $tmp_arr['job_link_url_web'] = config('global_config.sitedomain').url('index/job/show',['id'=>$tmp_arr['id']]);

                $result_data_list[] = $tmp_arr;
            }
        }
        return $result_data_list;
    }
    protected function get_resume_datalist($list)
    {
        $result_data_list = [];
        $resumeid_arr = [];
        $work_list = [];
        foreach ($list as $key => $value) {
            $resumeid_arr[] = $value['id'];
        }
        if ($resumeid_arr) {
            $rids = implode(',', $resumeid_arr);
            $field = true;
            $resume = model('Resume')
                ->where('id', 'in', $rids)
                ->orderRaw('field(id,' . $rids . ')')
                ->field($field)
                ->select();

            $photo_arr = $photo_id_arr = [];
            foreach ($resume as $key => $value) {
                $value['photo_img'] > 0 &&
                    ($photo_id_arr[] = $value['photo_img']);
            }
            if (!empty($photo_id_arr)) {
                $photo_arr = model('Uploadfile')->getFileUrlBatch(
                    $photo_id_arr
                );
            }

            $category_data = model('Category')->getCache();
            $category_major_data = model('CategoryMajor')->getCache();
            $category_job_data = model('CategoryJob')->getCache();
            $category_district_data = model('CategoryDistrict')->getCache();
            $intention_data = model('ResumeIntention')
                ->where('rid', 'in', $rids)
                ->order('id asc')
                ->select();
            $intention_arr = [];
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
            foreach ($resume as $key => $val) {
                $tmp_arr = [];
                $tmp_arr['id'] = $val['id'];
                $tmp_arr['stick'] = $val['stick'];
                $tmp_arr['high_quality'] = $val['high_quality'];
                $tmp_arr['fullname'] = $val['fullname'];
                if ($val['display_name'] == 0) {
                    if ($val['sex'] == 1) {
                        $tmp_arr['fullname'] = cut_str(
                            $val['fullname'],
                            1,
                            0,
                            '先生'
                        );
                    } elseif ($val['sex'] == 2) {
                        $tmp_arr['fullname'] = cut_str(
                            $val['fullname'],
                            1,
                            0,
                            '女士'
                        );
                    } else {
                        $tmp_arr['fullname'] = cut_str(
                            $val['fullname'],
                            1,
                            0,
                            '**'
                        );
                    }
                }
                $tmp_arr['photo_img_src'] = isset($photo_arr[$val['photo_img']])
                    ? $photo_arr[$val['photo_img']]
                    : default_empty('photo');
                $tmp_arr['service_tag'] = $val['service_tag'];
                $tmp_arr['sex'] = $val['sex'];
                $tmp_arr['sex_text'] = model('Resume')->map_sex[$val['sex']];
                $tmp_arr['age_text'] = date('Y') - intval($val['birthday']);
                $tmp_arr['residence'] = $val['residence'];
                $tmp_arr['height'] = $val['height'];
                $tmp_arr['marriage_text'] = isset(
                    model('Resume')->map_marriage[$val['marriage']]
                )
                    ? model('Resume')->map_marriage[$val['marriage']]
                    : '';
                $tmp_arr['education_text'] = isset(
                    model('BaseModel')->map_education[$val['education']]
                )
                    ? model('BaseModel')->map_education[$val['education']]
                    : '';

                $tmp_arr['experience_text'] =
                    $val['enter_job_time'] == 0
                        ? '尚未工作'
                        : format_date($val['enter_job_time']);
                $tmp_arr['householdaddress'] = $val['householdaddress'];
                $tmp_arr['major_text'] =
                    $val['major'] && isset($category_major_data[$val['major']])
                        ? $category_major_data[$val['major']]
                        : '';

                $tmp_arr['current_text'] = isset(
                    $category_data['QS_current'][$val['current']]
                )
                    ? $category_data['QS_current'][$val['current']]
                    : '';
                if (isset($work_list[$val['id']])) {
                    $tmp_arr['recent_work'] = $work_list[$val['id']]['jobname'];
                } else {
                    $tmp_arr['recent_work'] = '';
                }
                $tmp_arr['refreshtime'] = daterange_format(
                    $val['addtime'],
                    $val['refreshtime']
                );

                //求职意向
                $district_arr = $category_arr = $wage_arr = $nature_arr = $trade_arr = [];
                if (isset($intention_arr[$val['id']])) {
                    foreach ($intention_arr[$val['id']] as $k => $v) {
                        if ($v['trade']) {
                            $trade_arr[] =
                                $category_data['QS_trade'][$v['trade']];
                        }
                        if ($v['nature']) {
                            $nature_arr[] = model('Resume')->map_nature[
                                $v['nature']
                            ];
                        }
                        $wage_arr[0] = model('BaseModel')->handle_wage(
                            $v['minwage'],
                            $v['maxwage']
                        );
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
                if (!empty($trade_arr)) {
                    $trade_arr = array_unique($trade_arr);
                    $tmp_arr['intention_trade'] = implode(',', $trade_arr);
                } else {
                    $tmp_arr['intention_trade'] = '';
                }
                if (!empty($category_arr)) {
                    $category_arr = array_unique($category_arr);
                    $tmp_arr['intention_jobs'] = implode(',', $category_arr);
                } else {
                    $tmp_arr['intention_jobs'] = '';
                }
                if (!empty($wage_arr)) {
                    $wage_arr = array_unique($wage_arr);
                    $tmp_arr['intention_wage'] = implode(',', $wage_arr);
                } else {
                    $tmp_arr['intention_wage'] = '';
                }
                if (!empty($district_arr)) {
                    $district_arr = array_unique($district_arr);
                    $tmp_arr['intention_district'] = implode(
                        ',',
                        $district_arr
                    );
                } else {
                    $tmp_arr['intention_district'] = '';
                }
                if (!empty($nature_arr)) {
                    $nature_arr = array_unique($nature_arr);
                    $tmp_arr['intention_nature'] = implode(',', $nature_arr);
                } else {
                    $tmp_arr['intention_nature'] = '';
                }
                $tmp_arr['resume_link_url_web'] = config('global_config.sitedomain').url('index/resume/show',['id'=>$tmp_arr['id']]);

                $result_data_list[] = $tmp_arr;
            }
        }
        return $result_data_list;
    }
}
