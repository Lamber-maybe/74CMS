<?php
namespace app\v1_0\controller\home;

class Contrast extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function job()
    {
        $id = input('get.id/s', '', 'trim');
        $idarr = explode(",",$id);
        $whereInArr = [];
        $counter = 1;
        foreach ($idarr as $key => $value) {
            $tmpid = intval($value);
            if($tmpid>0 && $counter<=5){
                $whereInArr[] = $tmpid;
                $counter++;
            }
        }
        $whereInArr = array_unique($whereInArr);
        $return = [];
        if(!empty($whereInArr)){
            $category_data = model('Category')->getCache();
            $category_district_data = model('CategoryDistrict')->getCache();
            $list = model('Job')->alias('a')->join(config('database.prefix').'company b','a.uid=b.uid','LEFT')->field('a.*,b.companyname,b.audit as company_audit,b.id as company_id,b.trade,b.scale,b.nature as company_nature,b.district as company_district,b.setmeal_id,b.addtime as company_addtime')->whereIn('a.id',$whereInArr)->select();
            foreach ($list as $key => $val) {
                $tmp_arr = [];
                $tmp_arr['id'] = $val['id'];
                $tmp_arr['company_id'] = $val['company_id'];
                $tmp_arr['jobname'] = $val['jobname'];
                $tmp_arr['wage'] = model('BaseModel')->handle_wage(
                    $val['minwage'],
                    $val['maxwage'],
                    $val['negotiable']
                );
                $tmp_arr['companyname'] = $val['companyname'];
                $tmp_arr['nature'] = isset(model('Job')->map_nature[$val['nature']]) ? model('Job')->map_nature[$val['nature']] : '学历不限';
                $tmp_arr['amount'] = $val['amount'] == 0 ? '若干' : $val['amount'];
                $tmp_arr['education'] = isset(model('BaseModel')->map_education[$val['education']]) ? model('BaseModel')->map_education[$val['education']] : '学历不限';
                $tmp_arr['experience'] = isset(model('BaseModel')->map_experience[$val['experience']]) ? model('BaseModel')->map_experience[$val['experience']] : '经验不限';
                if ($val['district']) {
                    $tmp_arr['district'] = isset($category_district_data[$val['district']]) ? $category_district_data[$val['district']] : '';
                } else {
                    $tmp_arr['district'] = '';
                }
                $tmp_arr['company_audit'] = $val['company_audit'];
                $setmeal = model('Setmeal')->where('id',$val['setmeal_id'])->find();
                $tmp_arr['company_setmeal'] = $setmeal['name'];
                $report = model('CompanyReport')->where('company_id', $val['company_id'])->field('id')->find();
                if ($report === null) {
                    $tmp_arr['company_report'] = 0;
                } else {
                    $tmp_arr['company_report'] = 1;
                }
                $tmp_arr['reg_duration'] = $this->getDuration($val['company_addtime']);
                //简历查看率
                $endtime = time();
                $starttime = $endtime - 3600 * 24 * 14;
                $apply_data = model('JobApply')
                    ->field('id,is_look')
                    ->where('company_uid',$val['uid'])
                    ->where('addtime','between',[$starttime, $endtime])
                    ->select();
                if (!empty($apply_data)) {
                    $total = $looked = 0;
                    foreach ($apply_data as $key => $value) {
                        $value['is_look'] == 1 && $looked++;
                        $total++;
                    }
                    $tmp_arr['company_watch_percent'] = round($looked / $total, 2) * 100 . '%';
                } else {
                    $tmp_arr['company_watch_percent'] = '100%';
                }
                $tmp_arr['company_nature'] = isset($category_data['QS_company_type'][$val['company_nature']])?$category_data['QS_company_type'][$val['company_nature']]:'';
                $tmp_arr['company_trade'] = isset($category_data['QS_trade'][$val['trade']])?$category_data['QS_trade'][$val['trade']]:'';
                $tmp_arr['company_scale'] = isset($category_data['QS_scale'][$val['scale']])?$category_data['QS_scale'][$val['scale']]:'';
                if ($val['company_district']) {
                    $tmp_arr['company_district'] = isset(
                        $category_district_data[$val['company_district']]
                    )
                    ? $category_district_data[$val['company_district']]
                    : '';
                } else {
                    $tmp_arr['company_district'] = '';
                }
                $return[] = $tmp_arr;
            }
        }

        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function resume()
    {
        $id = input('get.id/s', '', 'trim');
        $idarr = explode(",",$id);
        $whereInArr = [];
        $counter = 1;
        foreach ($idarr as $key => $value) {
            $tmpid = intval($value);
            if($tmpid>0 && $counter<=5){
                $whereInArr[] = $tmpid;
                $counter++;
            }
        }
        $whereInArr = array_unique($whereInArr);
        $return = [];
        if(!empty($whereInArr)){
            $resume = model('Resume')->whereIn('id',$whereInArr)->select();
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
                ->whereIn('rid', $whereInArr)
                ->order('id asc')
                ->select();
            $intention_arr = [];
            foreach ($intention_data as $key => $value) {
                $intention_arr[$value['rid']][] = $value;
            }
            $work_list = $education_list = [];
            $work_data = model('ResumeWork')
                ->whereIn('rid', $whereInArr)
                ->order('id desc')
                ->select();
            foreach ($work_data as $key => $value) {
                $work_list[$value['rid']][] = $value;
            }
            $education_data = model('ResumeEducation')
                ->whereIn('rid', $whereInArr)
                ->order('id desc')
                ->select();
            foreach ($education_data as $key => $value) {
                $education_list[$value['rid']][] = $value;
            }
            foreach ($resume as $key => $val) {
                $tmp_arr = [];
                $tmp_arr['id'] = $val['id'];
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

                //求职意向
                $district_arr = $category_arr = $wage_arr = $nature_arr = [];
                if (isset($intention_arr[$val['id']])) {
                    foreach ($intention_arr[$val['id']] as $k => $v) {
                        
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


                if(isset($work_list[$val['id']])){
                    $total_year = 0;
                    $total_month = 0;
                    $timerange = '';
                    foreach ($work_list[$val['id']] as $k => $v) {
                        $tmp_arr['work_count'] = isset($tmp_arr['work_count'])?intval($tmp_arr['work_count'])+1:1;
                        $start = date('Y-m',$v['starttime']);
                        $end = $v['todate']==1?date('Y-m'):date('Y-m',$v['endtime']);
                        $duration = ddate($start,$end);
                        $current_duration = strpos($duration,'年');
                        if($current_duration===false){
                            $total_month += intval($duration);
                        }else{
                            $arr = explode("年", $duration);
                            $total_year += intval($arr[0]);
                            $total_month += intval($arr[1]);
                        }
                    }
                    $add_year = intval($total_month/12);
                    $total_year += $add_year;
                    $total_month = intval($total_month%12);
                    if($total_year>0){
                        $timerange .= $total_year.'年';
                    }
                    if($total_month>0){
                        $timerange .= $total_month.'个月';
                    }
                    $tmp_arr['work_time'] = $timerange;
                }else{
                    $tmp_arr['work_count'] = '';
                    $tmp_arr['work_time'] = '';
                }
                if(isset($education_list[$val['id']])){
                    foreach ($education_list[$val['id']] as $k => $v) {
                        $tmp_arr['education_count'] = isset($tmp_arr['education_count'])?intval($tmp_arr['education_count'])+1:1;
                        
                    }
                }else{
                    $tmp_arr['education_count'] = '';
                }

                $return[] = $tmp_arr;
            }
        }

        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    protected function _get_duration($list){
        if(!empty($list)){
            foreach ($list as $key => $value) {
                $start = $value['startyear'].'-'.$value['startmonth'];
                $end = $value['todate']==1?date('Y-m'):($value['endyear'].'-'.$value['endmonth']);
                $list[$key]['duration'] = ddate($start,$end);
            }
        }
        return $list;
    }
}
