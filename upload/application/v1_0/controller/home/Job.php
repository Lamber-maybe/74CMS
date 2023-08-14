<?php
namespace app\v1_0\controller\home;

class Job extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $search_type = input('get.search_type/s', '', 'trim');
        $keyword = input('get.keyword/s', '', 'trim');
        $emergency = input('get.emergency/d', 0, 'intval');
        $famous = input('get.famous/d', 0, 'intval');
        $company_id = input('get.company_id/d', 0, 'intval');
        $category1 = input('get.category1/d', 0, 'intval');
        $category2 = input('get.category2/d', 0, 'intval');
        $category3 = input('get.category3/d', 0, 'intval');
        $district1 = input('get.district1/d', 0, 'intval');
        $district2 = input('get.district2/d', 0, 'intval');
        $district3 = input('get.district3/d', 0, 'intval');
        $experience = input('get.experience/d', 0, 'intval');
        $minwage = input('get.minwage/d', 0, 'intval');
        $maxwage = input('get.maxwage/d', 0, 'intval');
        $filter_apply = input('get.filter_apply/d', 0, 'intval');
        $nature = input('get.nature/d', 0, 'intval');
        $education = input('get.education/d', 0, 'intval');
        $trade = input('get.trade/d', 0, 'intval');
        $tag = input('get.tag/s', '', 'trim');
        $settr = input('get.settr/d', 0, 'intval');
        $lat = input('get.lat/f', 0, 'floatval');
        $lng = input('get.lng/f', 0, 'floatval');
        $range = input('get.range/d', 0, 'intval');
        $south_west_lat = input('get.south_west_lat/f', 0, 'floatval');
        $south_west_lng = input('get.south_west_lng/f', 0, 'floatval');
        $north_east_lat = input('get.north_east_lat/f', 0, 'floatval');
        $north_east_lng = input('get.north_east_lng/f', 0, 'floatval');
        $sort = input('get.sort/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $count_total = input('get.count_total/d', 0, 'intval');


        if ($keyword != '') {
            $params['keyword'] = $keyword;
        }
        $distanceData = [];
        if ($lat > 0 && $lng > 0) {
            $params['lat'] = $lat;
            $params['lng'] = $lng;
            if ($range > 0) {
                $params['range'] = $range;
            }
            $distanceData = [
                'current_lat'=>$lat,
                'current_lng'=>$lng
            ];
            $count_distance = true;
        } else {
            $subsiteCondition = get_subsite_condition();
            if(!empty($subsiteCondition)){
                foreach ($subsiteCondition as $key => $value) {
                    if($key=='district1'){
                        $district1 = $value;
                        break;
                    }
                    if($key=='district2'){
                        $district2 = $value;
                        break;
                    }
                    if($key=='district3'){
                        $district3 = $value;
                        break;
                    }
                }
            }
            if ($district1 > 0) {
                $params['district1'] = $district1;
            }
            if ($district2 > 0) {
                $params['district2'] = $district2;
            }
            if ($district3 > 0) {
                $params['district3'] = $district3;
            }
        }

        if ($company_id > 0) {
            $params['company_id'] = $company_id;
        }
        if ($category1 > 0) {
            $params['category1'] = $category1;
        }
        if ($category2 > 0) {
            $params['category2'] = $category2;
        }
        if ($category3 > 0) {
            $params['category3'] = $category3;
        }
        if ($emergency > 0) {
            $params['emergency'] = $emergency;
        }
        if ($famous > 0) {
            $params['famous'] = $famous;
        }
        if ($experience > 0) {
            $params['experience'] = $experience;
        }
        if ($minwage > 0) {
            $params['minwage'] = $minwage;
        }
        if ($maxwage > 0) {
            $params['maxwage'] = $maxwage;
        }
        if (
            $filter_apply == 1 &&
            $this->userinfo !== null &&
            $this->userinfo->utype == 2
        ) {
            $params['filter_apply_uid'] = $this->userinfo->uid;
        }
        if ($nature > 0) {
            $params['nature'] = $nature;
        }
        if ($education > 0) {
            $params['education'] = $education;
        }
        if ($trade > 0) {
            $params['trade'] = $trade;
        }
        if ($tag != '') {
            $tag = str_replace(",","_",$tag);
            $params['tag'] = $tag;
        }
        if ($settr > 0) {
            $params['settr'] = $settr;
        }
        if ($sort != '') {
            $params['sort'] = $sort;
        }
        if (
            $south_west_lat > 0 &&
            $south_west_lng > 0 &&
            $north_east_lat > 0 &&
            $north_east_lng > 0
        ) {
            $params['south_west_lat'] = $south_west_lat;
            $params['south_west_lng'] = $south_west_lng;
            $params['north_east_lat'] = $north_east_lat;
            $params['north_east_lng'] = $north_east_lng;
        }

        if(config('global_config.job_search_login')==1 && $search_type=='list'){
            if($this->userinfo===null){
                $show_mask = 1;
                if(!empty($params)){
                    $params['district1'] = -1;
                }
                $params['count_total'] = 0;
                $params['current_page'] = 1;
                $params['pagesize'] = config('global_config.job_search_login_num')==0?1:config('global_config.job_search_login_num');
            }else{
                $show_mask = 0;
                $params['count_total'] = $count_total;
                $params['current_page'] = $current_page;
                $params['pagesize'] = $pagesize;
            }
        }else{
            $show_mask = 0;
            $params['count_total'] = $count_total;
            $params['current_page'] = $current_page;
            $params['pagesize'] = $pagesize;
        }

        $instance = new \app\common\lib\JobSearchEngine($params);

        $searchResult = $instance->run();
        $return['items'] = $this->get_datalist($searchResult['items'],$distanceData);
        $return['total'] = $searchResult['total'];
        $return['total_page'] = $searchResult['total_page'];
        $return['show_mask'] = $show_mask;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 附近职位
     */
    public function nearby()
    {
        $this->index();
    }
    /**
     * 地图找工作
     */
    public function map()
    {
        $keyword = input('get.keyword/s', '', 'trim');
        $south_west_lat = input('get.south_west_lat/f', 0, 'floatval');
        $south_west_lng = input('get.south_west_lng/f', 0, 'floatval');
        $north_east_lat = input('get.north_east_lat/f', 0, 'floatval');
        $north_east_lng = input('get.north_east_lng/f', 0, 'floatval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $marker_num = input('get.marker_num/d', 100, 'intval');

        $params['count_total'] = 0;
        $params['current_page'] = $current_page;
        $params['pagesize'] = $pagesize;

        if ($keyword != '') {
            $params['keyword'] = $keyword;
        }

        $params['south_west_lat'] = $south_west_lat;
        $params['south_west_lng'] = $south_west_lng;
        $params['north_east_lat'] = $north_east_lat;
        $params['north_east_lng'] = $north_east_lng;

        $instance = new \app\common\lib\JobSearchEngine($params);

        $searchResult = $instance->run();
        $return['items'] = $this->get_datalist($searchResult['items']);

        //获取标注点的数据
        if ($keyword != '') {
            $params_mark['keyword'] = $keyword;
        }
        $params_mark['south_west_lat'] = $south_west_lat;
        $params_mark['south_west_lng'] = $south_west_lng;
        $params_mark['north_east_lat'] = $north_east_lat;
        $params_mark['north_east_lng'] = $north_east_lng;
        $params_mark['count_total'] = 0;
        $params_mark['pagesize'] = $marker_num;
        $instance = new \app\common\lib\JobSearchEngine($params_mark);
        $searchResult = $instance->run();
        $return['marks'] = $this->get_marklist($searchResult['items']);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    protected function get_marklist($list)
    {
        $joblist = $jobid_arr = [];
        foreach ($list as $key => $value) {
            $jobid_arr[] = $value['id'];
        }
        if ($jobid_arr) {
            $jids = implode(',', $jobid_arr);
            $joblist = model('JobSearchRtime')->alias('a')->join(config('database.prefix').'job b','a.id=b.id','LEFT')->join(config('database.prefix').'company c','a.uid=c.uid','LEFT')
                ->field('a.id,b.jobname,a.map_lat,a.map_lng,b.address,b.negotiable,b.minwage,b.maxwage,b.district,b.education,b.experience,c.id as company_id,c.companyname,c.audit as company_audit')
                ->where('a.id', 'in', $jids)
                ->select();
            $category_district_data = model('CategoryDistrict')->getCache();
            foreach ($joblist as $key => $value) {
                $joblist[$key]['wage_text'] = model('BaseModel')->handle_wage(
                    $value['minwage'],
                    $value['maxwage'],
                    $value['negotiable']
                );
                $joblist[$key]['education_text'] = isset(
                    model('BaseModel')->map_education[$value['education']]
                )
                ? model('BaseModel')->map_education[$value['education']]
                : '学历不限';
                $joblist[$key]['experience_text'] = isset(
                    model('BaseModel')->map_experience[$value['experience']]
                )
                ? model('BaseModel')->map_experience[$value['experience']]
                : '经验不限';
                if ($value['district']) {
                    $joblist[$key]['district_text'] = isset(
                        $category_district_data[$value['district']]
                    )
                    ? $category_district_data[$value['district']]
                    : '';
                } else {
                    $joblist[$key]['district_text'] = '';
                }
            }
        }
        return $joblist;
    }
    protected function get_datalist($list,$distanceData=[])
    {
        $result_data_list = $jobid_arr = $comid_arr = $cominfo_arr = $logo_id_arr = $logo_arr = $icon_id_arr = $icon_arr = [];
        foreach ($list as $key => $value) {
            $jobid_arr[] = $value['id'];
            $comid_arr[] = $value['company_id'];
        }
        if ($jobid_arr) {
            if (!empty($comid_arr)) {
                $cominfo_arr = model('Company')
                    ->alias('a')
                    ->join(
                        config('database.prefix') . 'setmeal b',
                        'a.setmeal_id=b.id',
                        'LEFT'
                    )
                    ->join(
                        config('database.prefix') . 'member_setmeal c',
                        'a.uid=c.uid',
                        'LEFT'
                    )
                    ->where('a.id', 'in', $comid_arr)
                    ->column(
                        'a.id,a.companyname,a.audit,a.logo,a.nature,a.scale,a.trade,a.setmeal_id,b.icon,c.deadline as setmeal_deadline',
                        'a.id'
                    );
                foreach ($cominfo_arr as $key => $value) {
                    $value['logo'] > 0 && ($logo_id_arr[] = $value['logo']);
                    $value['icon'] > 0 && ($icon_id_arr[] = $value['icon']);
                }
                if (!empty($logo_id_arr)) {
                    $logo_arr = model('Uploadfile')->getFileUrlBatch(
                        $logo_id_arr
                    );
                }
                if (!empty($icon_id_arr)) {
                    $icon_arr = model('Uploadfile')->getFileUrlBatch(
                        $icon_id_arr
                    );
                }
            }
            $jids = implode(',', $jobid_arr);
            $field =
                'id,company_id,jobname,emergency,stick,minwage,maxwage,negotiable,education,experience,tag,district,addtime,refreshtime,map_lat,map_lng,setmeal_id,nature';
            $joblist = model('Job')
                ->where('id', 'in', $jids)
                ->orderRaw('field(id,' . $jids . ')')
                ->field($field)
                ->select();
            $category_data = model('Category')->getCache();
            $category_district_data = model('CategoryDistrict')->getCache();
            foreach ($joblist as $key => $val) {
                $tmp_arr = [];
                $tmp_arr['id'] = $val['id'];
                $tmp_arr['jobname'] = $val['jobname'];
                $tmp_arr['company_id'] = $val['company_id'];
                $tmp_arr['emergency'] = $val['emergency'];
                $tmp_arr['stick'] = $val['stick'];
                $tmp_arr['nature_text'] = isset(
                    model('Job')->map_nature[$val['nature']]
                )
                ? model('Job')->map_nature[$val['nature']]
                : '全职';
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
                    if($cominfo_arr[$val['company_id']]['setmeal_deadline']>time() || $cominfo_arr[$val['company_id']]['setmeal_deadline']==0){
                        $tmp_arr['setmeal_icon'] = isset(
                            $icon_arr[$cominfo_arr[$val['company_id']]['icon']]
                        )
                        ? $icon_arr[$cominfo_arr[$val['company_id']]['icon']]
                        : model('Setmeal')->getSysIcon($val['setmeal_id']);
                    }else{
                        $tmp_arr['setmeal_icon'] = '';
                    }
                    
                } else {
                    $tmp_arr['companyname'] = '';
                    $tmp_arr['company_audit'] = 0;
                    $tmp_arr['company_logo'] = '';
                    $tmp_arr['company_trade_text'] = '';
                    $tmp_arr['company_scale_text'] = '';
                    $tmp_arr['company_nature_text'] = '';
                    $tmp_arr['setmeal_icon'] = '';
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
                $tmp_arr['tag'] = [];
                if ($val['tag']) {
                    $tag_arr = explode(',', $val['tag']);
                    foreach ($tag_arr as $k => $v) {
                        if (
                            is_numeric($v) &&
                            isset($category_data['QS_jobtag'][$v])
                        ) {
                            $tmp_arr['tag'][] = $category_data['QS_jobtag'][$v];
                        } else {
                            $tmp_arr['tag'][] = $v;
                        }
                    }
                }
                $tmp_arr['refreshtime'] = daterange_format(
                    $val['addtime'],
                    $val['refreshtime']
                );
                $tmp_arr['map_lat'] = $val['map_lat'];
                $tmp_arr['map_lng'] = $val['map_lng'];
                if(!empty($distanceData)){
                    $tmp_arr['distance'] = get_distance($distanceData['current_lat'],$distanceData['current_lng'],$val['map_lat'],$val['map_lng']);
                }else{
                    $tmp_arr['distance'] = '';
                }
                $tmp_arr['job_link_url_web'] = config('global_config.sitedomain').url('index/job/show',['id'=>$tmp_arr['id']]);
                $tmp_arr['company_link_url_web'] = config('global_config.sitedomain').url('index/company/show',['id'=>$tmp_arr['company_id']]);
                $result_data_list[] = $tmp_arr;
            }
        }
        return $result_data_list;
    }
    protected function writeShowCache($id,$pageCache){
        $jobinfo = model('Job')
            ->where('id', 'eq', $id)
            ->field(true)
            ->find();
        if ($jobinfo === null) {
            $this->ajaxReturn(500, '职位信息为空');
        }
        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $base_info['id'] = $jobinfo['id'];
        $base_info['company_id'] = $jobinfo['company_id'];
        $base_info['uid'] = $jobinfo['uid'];
        $base_info['jobname'] = $jobinfo['jobname'];
        $base_info['emergency'] = $jobinfo['emergency'];
        $base_info['stick'] = $jobinfo['stick'];
        $base_info['content'] = $jobinfo['content'];
        $base_info['department'] = $jobinfo['department'];
        $base_info['nature_text'] = isset(
            model('Job')->map_nature[$jobinfo['nature']]
        )
        ? model('Job')->map_nature[$jobinfo['nature']]
        : '全职';
        $base_info['sex_text'] = isset(model('Job')->map_sex[$jobinfo['sex']])
        ? model('Job')->map_sex[$jobinfo['sex']]
        : '不限';
        $base_info['district_text'] = isset(
            $category_district_data[$jobinfo['district']]
        )
        ? $category_district_data[$jobinfo['district']]
        : '';
        $base_info['category_text'] = isset(
            $category_job_data[$jobinfo['category']]
        )
        ? $category_job_data[$jobinfo['category']]
        : '';
        $base_info['wage_text'] = model('BaseModel')->handle_wage(
            $jobinfo['minwage'],
            $jobinfo['maxwage'],
            $jobinfo['negotiable']
        );
        $base_info['education_text'] = isset(
            model('BaseModel')->map_education[$jobinfo['education']]
        )
        ? model('BaseModel')->map_education[$jobinfo['education']]
        : '学历不限';
        $base_info['experience_text'] = isset(
            model('BaseModel')->map_experience[$jobinfo['experience']]
        )
        ? model('BaseModel')->map_experience[$jobinfo['experience']]
        : '经验不限';

        $base_info['tag_text_arr'] = [];
        if ($jobinfo['tag'] != '') {
            $tag_arr = explode(',', $jobinfo['tag']);
            foreach ($tag_arr as $k => $v) {
                isset($category_data['QS_jobtag'][$v]) &&
                    ($base_info['tag_text_arr'][] =
                    $category_data['QS_jobtag'][$v]);
            }
        }

        $base_info['amount_text'] =
        $jobinfo['amount'] == 0 ? '若干' : $jobinfo['amount'] . '人';
        if ($jobinfo['age_na'] == 1) {
            $base_info['age_text'] = '不限';
        } else if ($jobinfo['minage'] > 0 || $jobinfo['maxage'] > 0) {
            $base_info['age_text'] =
                $jobinfo['minage'] . '-' . $jobinfo['maxage'];
        } else {
            $base_info['minage'] = '';
        }
        $base_info['click'] = $jobinfo['click'];
        $base_info['map_lat'] = $jobinfo['map_lat'];
        $base_info['map_lng'] = $jobinfo['map_lng'];
        $base_info['map_zoom'] = $jobinfo['map_zoom'];
        $base_info['address'] = $jobinfo['address'];
        $base_info['custom_field_1'] = $jobinfo['custom_field_1'];
        $base_info['custom_field_2'] = $jobinfo['custom_field_2'];
        $base_info['custom_field_3'] = $jobinfo['custom_field_3'];
        $base_info['refreshtime'] = daterange_format(
            $jobinfo['addtime'],
            $jobinfo['refreshtime']
        );
        $return['base_info'] = $base_info;

        $apply_map['company_uid'] = $jobinfo['uid'];
        $endtime = time();
        $starttime = $endtime - 3600 * 24 * 14;
        $apply_map['addtime'] = ['between', [$starttime, $endtime]];
        $apply_data = model('JobApply')
            ->field('id,is_look')
            ->where($apply_map)
            ->select();
        if (!empty($apply_data)) {
            $total = $looked = 0;
            foreach ($apply_data as $key => $value) {
                $value['is_look'] == 1 && $looked++;
                $total++;
            }
            $return['watch_percent'] = round($looked / $total, 2) * 100 . '%';
        } else {
            $return['watch_percent'] = '100%';
        }
        $last_login_time = model('Member')
            ->field('last_login_time')
            ->where('uid', 'eq', $jobinfo['uid'])
            ->find();
        $return['last_login_time'] =
        $last_login_time['last_login_time'] == 0
        ? '从未登录'
        : format_last_login_time(
            $last_login_time['last_login_time']
        );
        $companyinfo = model('Company')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company_info b',
                'a.uid=b.uid',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'setmeal c',
                'a.setmeal_id=c.id',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'member_setmeal d',
                'a.uid=d.uid',
                'LEFT'
            )
            ->field(
                'a.id,a.companyname,a.logo,a.district,a.nature,a.scale,a.trade,a.audit,b.address,a.setmeal_id,c.icon,d.deadline as setmeal_deadline'
            )
            ->where('a.uid', 'eq', $jobinfo['uid'])
            ->find();
        if ($companyinfo === null) {
            $return['com_info'] = [];
        } else {
            $return['com_info']['id'] = $companyinfo['id'];
            $return['com_info']['companyname'] = $companyinfo['companyname'];
            $return['com_info']['audit'] = $companyinfo['audit'];
            $return['com_info']['address'] = $companyinfo['address'];
            $return['com_info']['logo_src'] =
            $companyinfo['logo'] > 0
            ? model('Uploadfile')->getFileUrl($companyinfo['logo'])
            : default_empty('logo');
            $return['com_info']['district_text'] = isset(
                $category_district_data[$companyinfo['district']]
            )
            ? $category_district_data[$companyinfo['district']]
            : '';
            $return['com_info']['scale_text'] = isset(
                $category_data['QS_scale'][$companyinfo['scale']]
            )
            ? $category_data['QS_scale'][$companyinfo['scale']]
            : '';
            $return['com_info']['nature_text'] = isset(
                $category_data['QS_company_type'][$companyinfo['nature']]
            )
            ? $category_data['QS_company_type'][$companyinfo['nature']]
            : '';
            $return['com_info']['trade_text'] = isset(
                $category_data['QS_trade'][$companyinfo['trade']]
            )
            ? $category_data['QS_trade'][$companyinfo['trade']]
            : '';
            if($companyinfo['setmeal_deadline']==0 || $companyinfo['setmeal_deadline']>time()){
                $return['com_info']['setmeal_icon'] = $companyinfo['icon'] > 0 ? model('Uploadfile')->getFileUrl($companyinfo['icon']) : model('Setmeal')->getSysIcon($companyinfo['setmeal_id']);
            }else{
                $return['com_info']['setmeal_icon'] = '';
            }
            
            $job_list = model('Job')
                ->field('id,jobname')
                ->where('company_id', 'eq', $companyinfo['id'])
                ->where('is_display', 1)
                ->where('audit', 1)
                ->select();
            $return['com_info']['jobnum'] = count($job_list);
            $return['com_info']['first_jobname'] = !empty($job_list)
            ? $job_list[0]['jobname']
            : '';
        }
        $subsiteCondition = get_subsite_condition();
        $similar_data = [
            'subsiteCondition'=>$subsiteCondition,
            'category1' => $jobinfo['category1'],
            'category2' => $jobinfo['category2'],
            'category3' => $jobinfo['category3'],
            'district1' => $jobinfo['district1'],
            'district2' => $jobinfo['district2'],
            'district3' => $jobinfo['district3'],
            'trade' => isset($companyinfo['trade']) ? $companyinfo['trade'] : 0,
            'minwage' => $jobinfo['minwage'],
            'maxwage' => $jobinfo['maxwage'],
            'nature' => $jobinfo['nature'],
            'current_page' => 1,
            'pagesize' => 10,
        ];
        $instance = new \app\common\lib\JobRecommend($similar_data);
        $similar_list = $instance->run('id != ' . $jobinfo['id']);
        $return['similar'] = $this->get_datalist($similar_list['items']);
        if($pageCache['expire']>0){
            model('PageMobile')->writeCacheByAlias('jobshow',$return,$pageCache['expire'],$id);
        }
        return $return;
    }
    /**
     * 职位详情
     */
    public function show()
    {
        $id = input('get.id/d', 0, 'intval');
        $field_rule_data = model('FieldRule')->getCache();
        $field_rule = [
            'basic' => $field_rule_data['Job'],
            'contact' => $field_rule_data['JobContact'],
        ];
        foreach ($field_rule as $key => $rule) {
            foreach ($rule as $field => $field_attr) {
                $_arr = [
                    'field_name' => $field_attr['field_name'],
                    'is_require' => intval($field_attr['is_require']),
                    'is_display' => intval($field_attr['is_display']),
                    'field_cn' => $field_attr['field_cn'],
                ];
                $field_rule[$key][$field] = $_arr;
            }
        }
        //读取页面缓存配置
        $pageCache = model('PageMobile')->getCache('jobshow');
        //如果缓存有效期为0，则不使用缓存
        if($pageCache['expire']>0){
            $return = model('PageMobile')->getCacheByAlias('jobshow',$id);
        }else{
            $return = false;
        }
        if (!$return) {
            $return = $this->writeShowCache($id,$pageCache);
            if($return===false){
                $this->ajaxReturn(500, '职位信息为空');
            }
        }
        $return['field_rule'] = $field_rule;

        $getJobContact = model('Job')->getContact($return['base_info'],$this->userinfo);
        $return['show_contact'] = $getJobContact['show_contact'];
        $return['show_contact_note'] = $getJobContact['show_contact_note'];
        $return['contact_info'] = $getJobContact['contact_info'];
        if($this->userinfo===null){
            $return['has_apply'] = 0;
        }else if($this->userinfo->utype == 2){
            $check_apply = model('JobApply')->where('personal_uid',$this->userinfo->uid)->where('jobid',$return['base_info']['id'])->find();
            if($check_apply===null){
                $return['has_apply'] = 0;
            }else{
                $return['has_apply'] = 1;
            }
        }else{
            $return['has_apply'] = 0;
        }

        if ($this->userinfo != null && $this->userinfo->utype == 2) {
            $fav_info = model('FavJob')
                ->where('jobid', $id)
                ->where('personal_uid', $this->userinfo->uid)
                ->find();
            if ($fav_info === null) {
                $return['has_fav'] = 0;
            } else {
                $return['has_fav'] = 1;
            }
        } else {
            $return['has_fav'] = 0;
        }
        $imuser_info = model('ImToken')->where('uid', $return['base_info']['uid'])->find();
        if ($imuser_info !== null) {
            $return['base_info']['im_userid'] = $imuser_info['im_userid'];
        } else {
            $return['base_info']['im_userid'] = '';
        }
        $return['share_url'] = config('global_config.mobile_domain').'job/'.$return['base_info']['id'];
        model('Job')->addViewLog(
            $return['base_info']['id'],
            $return['base_info']['uid'],
            $this->userinfo !== null && $this->userinfo->utype == 2
            ? $this->userinfo->uid
            : 0
        );
        unset($return['base_info']['uid']);
        $return['phone_protect_open'] =  false;
        $return['phone_protect_timeout'] = 180;
        $return['phone_protect_type'] = '';
        if(intval(config('global_config.alicloud_phone_protect_open'))){
            $protectTarget = array_map('intval', explode(',', config('global_config.alicloud_phone_protect_target')));
            if(in_array(1, $protectTarget)){
                $return['phone_protect_open'] =  true;
            }
            if(intval(config('global_config.alicloud_phone_protect_type'))==2){
                $return['phone_protect_timeout'] = 120;
            }
            $return['phone_protect_type'] = intval(config('global_config.alicloud_phone_protect_type'));
            if($return['phone_protect_type']==1 && $this->userinfo===null){
                $return['show_contact'] = 0;
                $return['show_contact_note'] = 'need_login';
            }
        }
        $return['cur_user_mobile'] = '';
        if($return['show_contact'] && $this->userinfo!==null){
            $return['cur_user_mobile'] = $this->userinfo->mobile;
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 获取友好距离
     */
    public function getDistance()
    {
        $current_lat = input('get.current_lat/f', 0, 'floatval');
        $current_lng = input('get.current_lng/f', 0, 'floatval');
        $target_lat = input('get.target_lat/f', 0, 'floatval');
        $target_lng = input('get.target_lng/f', 0, 'floatval');
        $this->ajaxReturn(200, '获取数据成功', [
            'distance' => get_distance(
                $current_lat,
                $current_lng,
                $target_lat,
                $target_lng
            ),
        ]);
    }
    /**
     * 竞争力分析
     */
    public function competitiveness()
    {
        $this->checkLogin(2);
        $id = input('get.id/d', 0, 'intval');
        $job_info = model('Job')
            ->field(
                'education,experience,minwage,maxwage,negotiable,category1,category2,category3,category,district1,district2,district3,district'
            )
            ->where('id', 'eq', $id)
            ->find();
        if ($job_info === null) {
            $this->ajaxReturn(500, '没有找到职位信息');
        }
        $apply_data = model('JobApply')
            ->field('id,is_look')
            ->where([
                'jobid' => ['eq', $id],
                'personal_uid' => ['neq', $this->userinfo->uid],
            ])
            ->select();
        $return['looked_total'] = $return['competitor_total'] = 0;
        foreach ($apply_data as $key => $value) {
            if ($value['is_look'] == 1) {
                $return['looked_total']++;
            }
            $return['competitor_total']++;
        }
        $resume_info = model('Resume')
            ->field('id,education,enter_job_time')
            ->where('uid', $this->userinfo->uid)
            ->find();
        if($resume_info===null){
            $this->ajaxReturn(200, '请先创建一份简历',[]);
        }
        //匹配信息
        $config_apply_job_min_percent = config(
            'global_config.apply_job_min_percent'
        );
        $category_district_data = model('CategoryDistrict')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $return['match_result'] = [
            'education' => [
                'cn' => isset(
                    model('BaseModel')->map_education[$job_info['education']]
                )
                ? model('BaseModel')->map_education[$job_info['education']]
                : '',
                'is_match' => 0,
            ],
            'experience' => [
                'cn' => isset(
                    model('BaseModel')->map_experience[$job_info['experience']]
                )
                ? model('BaseModel')->map_experience[
                    $job_info['experience']
                ]
                : '',
                'is_match' => 0,
            ],
            'category' => [
                'cn' => isset($category_job_data[$job_info['category']])
                ? $category_job_data[$job_info['category']]
                : '',
                'is_match' => 0,
            ],
            'district' => [
                'cn' => isset($category_district_data[$job_info['district']])
                ? $category_district_data[$job_info['district']]
                : '',
                'is_match' => 0,
            ],
            'wage' => [
                'cn' => model('BaseModel')->handle_wage(
                    $job_info['minwage'],
                    $job_info['maxwage'],
                    $job_info['negotiable']
                ),
                'is_match' => 0,
            ],
            'complete_percent' => [
                'cn' => $config_apply_job_min_percent . '%',
                'is_match' => 0,
            ],
        ];
        if (
            $job_info['education'] == 0 ||
            $resume_info['education'] == $job_info['education']
        ) {
            $return['match_result']['education']['is_match'] = 1;
        }
        if (
            $job_info['experience'] == 0 ||
            ($job_info['experience'] == 1 &&
                $resume_info['enter_job_time'] == 0) ||
            ($job_info['experience'] == 2 &&
                $resume_info['enter_job_time'] > strtotime('-2 year')) ||
            ($job_info['experience'] == 3 &&
                $resume_info['enter_job_time'] > strtotime('-3 year') &&
                $resume_info['enter_job_time'] < strtotime('-2 year')) ||
            ($job_info['experience'] == 4 &&
                $resume_info['enter_job_time'] > strtotime('-4 year') &&
                $resume_info['enter_job_time'] < strtotime('-3 year')) ||
            ($job_info['experience'] == 5 &&
                $resume_info['enter_job_time'] > strtotime('-6 year') &&
                $resume_info['enter_job_time'] < strtotime('-3 year')) ||
            ($job_info['experience'] == 6 &&
                $resume_info['enter_job_time'] > strtotime('-11 year') &&
                $resume_info['enter_job_time'] < strtotime('-6 year')) ||
            ($job_info['experience'] == 6 &&
                $resume_info['enter_job_time'] < strtotime('-11 year'))
        ) {
            $return['match_result']['experience']['is_match'] = 1;
        }
        $intention_list = model('ResumeIntention')
            ->field(
                'category1,category2,category3,district1,district2,district3,minwage,maxwage'
            )
            ->where('uid', 'eq', $this->userinfo->uid)
            ->select();
        foreach ($intention_list as $key => $value) {
            if (
                $this->handle_one(
                    [
                        $job_info['category1'],
                        $job_info['category2'],
                        $job_info['category3'],
                    ],
                    [
                        $value['category1'],
                        $value['category2'],
                        $value['category3'],
                    ]
                )
            ) {
                $return['match_result']['category']['is_match'] = 1;
                break;
            }
        }
        foreach ($intention_list as $key => $value) {
            if (
                $this->handle_one(
                    [
                        $job_info['district1'],
                        $job_info['district2'],
                        $job_info['district3'],
                    ],
                    [
                        $value['district1'],
                        $value['district2'],
                        $value['district3'],
                    ]
                )
            ) {
                $return['match_result']['district']['is_match'] = 1;
                break;
            }
        }
        if ($job_info['negotiable'] == 1) {
            //面议的话默认全部匹配
            $return['match_result']['wage']['is_match'] = 1;
        } else {
            foreach ($intention_list as $key => $value) {
                if (
                    $this->handle_wage_one(
                        [$job_info['minwage'], $job_info['maxwage']],
                        [$value['minwage'], $value['maxwage']]
                    )
                ) {
                    $return['match_result']['wage']['is_match'] = 1;
                    break;
                }
            }
        }
        if (
            model('Resume')->countCompletePercent($resume_info['id']) >=
            $config_apply_job_min_percent
        ) {
            $return['match_result']['complete_percent']['is_match'] = 1;
        }
        $match_level = 0;
        foreach ($return['match_result'] as $key => $value) {
            if ($value['is_match'] == 1) {
                $match_level++;
            }
        }
        if ($match_level < 3) {
            $return['match_level'] = 1;
        } elseif ($match_level < 6) {
            $return['match_level'] = 2;
        } else {
            $return['match_level'] = 3;
        }
        $statistics_education = $this->statistics_education(
            $job_info['category1'],
            $resume_info['education']
        );
        $statistics_experience = $this->statistics_experience(
            $job_info['category1'],
            $resume_info['enter_job_time']
        );
        //取出意向薪资的最大值
        $max_wage = 0;
        foreach ($intention_list as $key => $value) {
            if ($value['maxwage'] > $max_wage) {
                $max_wage = $value['maxwage'];
            }
        }
        $statistics_wage = $this->statistics_wage(
            $job_info['category1'],
            $max_wage
        );
        $return['statistics_education'] = $statistics_education;
        $return['statistics_experience'] = $statistics_experience;
        $return['statistics_wage'] = $statistics_wage;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 获取统计数据-学历
     */
    protected function statistics_education($category, $resume_education)
    {
        $statistics_resumelist = model('ResumeSearchRtime')
            ->alias('r')
            ->join(
                config('database.prefix') . 'resume_intention i',
                'i.rid=r.id',
                'LEFT'
            )
            ->field('count(*) as num,r.education')
            ->where([
                'i.category1' => $category,
            ])
            ->group('r.education')
            ->select();
        $total = 0;
        foreach ($statistics_resumelist as $key => $value) {
            if (
                !isset(model('BaseModel')->map_education[$value['education']])
            ) {
                continue;
            }
            $total += $value['num'];
        }
        $returnlist = [];
        $count_num = 0;
        foreach ($statistics_resumelist as $key => $value) {
            //如果总类别数大于5项，则把后面的几项全部合并
            if ($count_num < 5) {
                if (
                    !isset(
                        model('BaseModel')->map_education[$value['education']]
                    )
                ) {
                    continue;
                }
                $arr['label'] = model('BaseModel')->map_education[
                    $value['education']
                ];
                $arr['total'] = $value['num'];
                $arr['percent'] =
                $total == 0 ? 0 : round($value['num'] / $total, 2) * 100;
                if ($resume_education == $value['education']) {
                    $arr['here'] = 1;
                } else {
                    $arr['here'] = 0;
                }
                $returnlist[] = $arr;
                $count_num++;
            } else {
                $returnlist[4]['label'] = '其他';
                $returnlist[4]['total'] =
                    $returnlist[4]['total'] + $value['num'];
                $returnlist[4]['percent'] =
                $total == 0
                ? 0
                : round($returnlist[4]['total'] / $total, 2) * 100;
                if (
                    $returnlist[4]['here'] == 1 ||
                    $resume_education == $value['education']
                ) {
                    $returnlist[4]['here'] = 1;
                }
            }
        }
        return $returnlist;
    }

    /**
     * 获取统计数据-经验
     */
    protected function statistics_experience($category, $resume_enter_job_time)
    {
        $where1 = 'i.category1=' . $category . ' AND r.enter_job_time=0'; //无经验
        $where2 =
        'i.category1=' .
        $category .
        ' AND r.enter_job_time!=0 AND r.enter_job_time>' .
        strtotime('-4 year'); //1-3年
        $where3 =
        'i.category1=' .
        $category .
        ' AND r.enter_job_time<=' .
        strtotime('-4 year') .
        ' AND r.enter_job_time>' .
        strtotime('-6 year'); //3-5年
        $where4 =
        'i.category1=' .
        $category .
        ' AND r.enter_job_time<=' .
        strtotime('-6 year') .
        ' AND r.enter_job_time>' .
        strtotime('-11 year'); //5-10年
        $where5 =
        'i.category1=' .
        $category .
        ' AND r.enter_job_time<=' .
        strtotime('-11 year'); //10年以上

        $total1 = model('ResumeSearchRtime')
            ->alias('r')
            ->join(
                config('database.prefix') . 'resume_intention i',
                'i.rid=r.id',
                'LEFT'
            )
            ->where($where1)
            ->count();

        $total2 = model('ResumeSearchRtime')
            ->alias('r')
            ->join(
                config('database.prefix') . 'resume_intention i',
                'i.rid=r.id',
                'LEFT'
            )
            ->where($where2)
            ->count();
        $total3 = model('ResumeSearchRtime')
            ->alias('r')
            ->join(
                config('database.prefix') . 'resume_intention i',
                'i.rid=r.id',
                'LEFT'
            )
            ->where($where3)
            ->count();
        $total4 = model('ResumeSearchRtime')
            ->alias('r')
            ->join(
                config('database.prefix') . 'resume_intention i',
                'i.rid=r.id',
                'LEFT'
            )
            ->where($where4)
            ->count();
        $total5 = model('ResumeSearchRtime')
            ->alias('r')
            ->join(
                config('database.prefix') . 'resume_intention i',
                'i.rid=r.id',
                'LEFT'
            )
            ->where($where5)
            ->count();
        $total = $total1 + $total2 + $total3 + $total4 + $total5;

        $returnlist = [
            [
                'label' => '无经验',
                'total' => $total1,
                'percent' => $total == 0 ? 0 : round($total1 / $total, 2) * 100,
                'here' => 0,
            ],
            [
                'label' => '1-3年',
                'total' => $total2,
                'percent' => $total == 0 ? 0 : round($total2 / $total, 2) * 100,
                'here' => 0,
            ],
            [
                'label' => '3-5年',
                'total' => $total3,
                'percent' => $total == 0 ? 0 : round($total3 / $total, 2) * 100,
                'here' => 0,
            ],
            [
                'label' => '5-10年',
                'total' => $total4,
                'percent' => $total == 0 ? 0 : round($total4 / $total, 2) * 100,
                'here' => 0,
            ],
            [
                'label' => '10年以上',
                'total' => $total5,
                'percent' => $total == 0 ? 0 : round($total5 / $total, 2) * 100,
                'here' => 0,
            ],
        ];
        if ($resume_enter_job_time == 0) {
            $returnlist[0]['here'] = 1;
        } elseif ($resume_enter_job_time > strtotime('-4 year')) {
            $returnlist[1]['here'] = 1;
        } elseif ($resume_enter_job_time > strtotime('-6 year')) {
            $returnlist[2]['here'] = 1;
        } elseif ($resume_enter_job_time > strtotime('-11 year')) {
            $returnlist[3]['here'] = 1;
        } else {
            $returnlist[4]['here'] = 1;
        }

        return $returnlist;
    }
    /**
     * 获取统计数据-薪资
     */
    protected function statistics_wage($category, $wage_val)
    {
        $where1 =
            'category1=' . $category . ' AND maxwage>=1000 AND maxwage<3000'; //1-3k
        $where2 =
            'category1=' . $category . ' AND maxwage>=3000 AND maxwage<5000'; //3-5k
        $where3 =
            'category1=' . $category . ' AND maxwage>=5000 AND maxwage<8000'; //5-8k
        $where4 =
            'category1=' . $category . ' AND maxwage>=8000 AND maxwage<10000'; //8-10k
        $where5 = 'category1=' . $category . ' AND maxwage>=10000'; //10k以上

        $total1 = model('ResumeIntention')
            ->where($where1)
            ->count('DISTINCT rid');
        $total2 = model('ResumeIntention')
            ->where($where2)
            ->count('DISTINCT rid');
        $total3 = model('ResumeIntention')
            ->where($where3)
            ->count('DISTINCT rid');
        $total4 = model('ResumeIntention')
            ->where($where4)
            ->count('DISTINCT rid');
        $total5 = model('ResumeIntention')
            ->where($where5)
            ->count('DISTINCT rid');

        $total = $total1 + $total2 + $total3 + $total4 + $total5;

        $returnlist = [
            [
                'label' => '1-3k',
                'total' => $total1,
                'percent' => $total == 0 ? 0 : round($total1 / $total, 2) * 100,
                'here' => 0,
            ],
            [
                'label' => '3-5k',
                'total' => $total2,
                'percent' => $total == 0 ? 0 : round($total2 / $total, 2) * 100,
                'here' => 0,
            ],
            [
                'label' => '5-8k',
                'total' => $total3,
                'percent' => $total == 0 ? 0 : round($total3 / $total, 2) * 100,
                'here' => 0,
            ],
            [
                'label' => '8-10k',
                'total' => $total4,
                'percent' => $total == 0 ? 0 : round($total4 / $total, 2) * 100,
                'here' => 0,
            ],
            [
                'label' => '10k以上',
                'total' => $total5,
                'percent' => $total == 0 ? 0 : round($total5 / $total, 2) * 100,
                'here' => 0,
            ],
        ];
        if ($wage_val >= 10000) {
            $returnlist[4]['here'] = 1;
        } elseif ($wage_val >= 8000) {
            $returnlist[3]['here'] = 1;
        } elseif ($wage_val >= 5000) {
            $returnlist[2]['here'] = 1;
        } elseif ($wage_val >= 3000) {
            $returnlist[1]['here'] = 1;
        } else {
            $returnlist[0]['here'] = 1;
        }

        return $returnlist;
    }
    protected function handle_one($jobs_attr, $intention_attr)
    {
        if ($intention_attr[2] > 0) {
            return $intention_attr[2] == $jobs_attr[2];
        } elseif ($intention_attr[1] > 0) {
            return $intention_attr[1] == $jobs_attr[1];
        }
        return $intention_attr[0] == $jobs_attr[0];
    }
    protected function handle_wage_one($jobs_attr, $intention_attr)
    {
        if (
            $intention_attr[0] <= $jobs_attr[1] &&
            $intention_attr[1] >= $jobs_attr[0]
        ) {
            return true;
        }
        return false;
    }
    public function getContact(){
        $id = input('get.id/d',0,'intval');
        $jobinfo = model('Job')
            ->where('id', 'eq', $id)
            ->field(true)
            ->find();
        if ($jobinfo === null) {
            $this->ajaxReturn(500, '职位信息为空');
        }
        $getJobContact = model('Job')->getContact($jobinfo,$this->userinfo);
        $return['show_contact'] = $getJobContact['show_contact'];
        $return['show_contact_note'] = $getJobContact['show_contact_note'];
        $return['contact_info'] = $getJobContact['contact_info'];
        if($this->userinfo===null){
            $return['has_apply'] = 0;
        }else{
            $check_apply = model('JobApply')->where('personal_uid',$this->userinfo->uid)->where('jobid',$jobinfo['id'])->find();
            if($check_apply===null){
                $return['has_apply'] = 0;
            }else{
                $return['has_apply'] = 1;
            }
        }

        $field_rule_data = model('FieldRule')->getCache();
        $field_rule = $field_rule_data['JobContact'];
        foreach ($field_rule as $field => $rule) {
            $_arr = [
                'field_name' => $rule['field_name'],
                'is_require' => intval($rule['is_require']),
                'is_display' => intval($rule['is_display']),
                'field_cn' => $rule['field_cn'],
            ];
            $field_rule[$field] = $_arr;
        }
        $return['field_rule'] = $field_rule;
        $this->ajaxReturn(200, '获取数据成功',$return);
    }
    public function click(){
        $id = input('post.id/d',0,'intval');
        $jobinfo = model('Job')
            ->where('id', 'eq', $id)
            ->field('id,uid,click')
            ->find();
        if ($jobinfo !== null) {
            model('Job')->addViewLog(
                $jobinfo['id'],
                $jobinfo['uid'],
                $this->userinfo !== null && $this->userinfo->utype == 2
                ? $this->userinfo->uid
                : 0
            );
            $click = $jobinfo['click']+1;
        }else{
            $click = 0;
        }
        $this->ajaxReturn(200, '数据添加成功',$click);
    }
    public function checkFav(){
        $id = input('get.id/d',0,'intval');
        if ($this->userinfo != null && $this->userinfo->utype == 2) {
            $fav_info = model('FavJob')
                ->where('jobid', $id)
                ->where('personal_uid', $this->userinfo->uid)
                ->find();
            if ($fav_info === null) {
                $has_fav = 0;
            } else {
                $has_fav = 1;
            }
        } else {
            $has_fav = 0;
        }
        $this->ajaxReturn(200, '数据查询成功',$has_fav);
    }
    public function supplementary(){
        $id = input('get.id/d', 0, 'intval');
        $jobinfo = model('Job')
                ->where('id', $id)
                ->field('id,uid')
                ->find();
        if ($jobinfo === null) {
            $this->ajaxReturn(200,'获取数据成功',null);
        }
        $cominfo = model('Company')
            ->where('uid', 'eq', $jobinfo['uid'])
            ->field('id,uid,addtime')
            ->find();
        if ($cominfo === null) {
            $this->ajaxReturn(200,'获取数据成功',null);
        }
        //名企
        $return['famous_list'] = $this->getFamous();
        //入驻时长
        $return['reg_duration'] = $this->getDuration($cominfo['addtime']);
        //企业风采
        $return['img_list'] = model('CompanyImg')->getList($cominfo['id']);
        //即时通讯用户信息
        $imuser_info = model('ImToken')->where('uid', $jobinfo['uid'])->find();
        if ($imuser_info !== null) {
            $return['im_userid'] = $imuser_info['im_userid'];
        } else {
            $return['im_userid'] = '';
        }
        //实地认证
        $report = model('CompanyReport')
            ->where('company_id', $cominfo['id'])
            ->field('id')
            ->find();
        if ($report === null) {
            $return['report'] = 0;
        } else {
            $return['report'] = 1;
        }
        //上次登录时间
        $last_login_time = model('Member')
            ->field('last_login_time')
            ->where('uid', 'eq', $jobinfo['uid'])
            ->find();
        $return['last_login_time'] = $last_login_time['last_login_time'] == 0 ? '从未登录' : format_last_login_time($last_login_time['last_login_time']);
        //申请数量、查看率
        $endtime = time();
        $starttime = $endtime - 3600 * 24 * 14;
        $apply_data = model('JobApply')
            ->field('id,is_look')
            ->where('company_uid',$jobinfo['uid'])
            ->where('addtime','between',[$starttime, $endtime])
            ->select();
        if (!empty($apply_data)) {
            $total = $looked = 0;
            foreach ($apply_data as $key => $value) {
                $value['is_look'] == 1 && $looked++;
                $total++;
            }
            $return['apply_num'] = $total;
            $return['watch_percent'] = round($looked / $total, 2) * 100 . '%';
        } else {
            $return['watch_percent'] = '100%';
            $return['apply_num'] = 0;
        }
        $return['job_apply_num'] = model('JobApply')
            ->where('jobid',$jobinfo['id'])
            ->count();
        $this->ajaxReturn(200,'获取数据成功',$return);
    }

    /**
     * 名企
     */
    protected function getFamous()
    {
        $return = [];
        $famous_enterprises_setmeal = config('global_config.famous_enterprises');
        $famous_enterprises_setmeal = $famous_enterprises_setmeal == '' ? [] : explode(',', $famous_enterprises_setmeal);
        if (empty($famous_enterprises_setmeal)) {
            return $return;
        }
        $subsiteCondition = get_subsite_condition('c');
        $list = model('Company')
            ->alias('c')
            ->join(
                config('database.prefix') . 'member_setmeal s',
                's.uid=c.uid',
                'LEFT'
            )
            ->where('c.is_display',1)
            ->where('c.district1','gt',0)
            ->where($subsiteCondition)
            ->where('s.setmeal_id', 'in', $famous_enterprises_setmeal)
            ->field('c.id,c.logo,c.companyname')
            ->order('c.refreshtime desc')
            ->limit(5)
            ->select();
        $logo_id_arr = $logo_arr = [];
        foreach ($list as $key => $value) {
            $value['logo'] > 0 && ($logo_id_arr[] = $value['logo']);
        }
        if (!empty($logo_id_arr)) {
            $logo_arr = model('Uploadfile')->getFileUrlBatch($logo_id_arr);
        }
        foreach ($list as $key => $value) {
            $arr = $value->toArray();
            $arr['logo'] = isset($logo_arr[$value['logo']])
                ? $logo_arr[$arr['logo']]
                : default_empty('logo');
            $arr['link_url'] = url('index/company/show',['id'=>$arr['id']]);
            $return[] = $arr;
        }
        return $return;
    }

}
