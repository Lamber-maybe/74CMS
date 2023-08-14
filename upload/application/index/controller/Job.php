<?php
namespace app\index\controller;

class Job extends \app\index\controller\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('navSelTag','job');
    }
    public function index()
    {
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'joblist',302);
            exit;
        }
        $keyword = request()->route('keyword/s', '', 'trim');
        $listtype = request()->route('listtype/s','','trim');
        $category1 = request()->route('c1/d',0,'intval');
        $category2 = request()->route('c2/d',0,'intval');
        $category3 = request()->route('c3/d',0,'intval');
        $district1 = request()->route('d1/d',0,'intval');
        $district2 = request()->route('d2/d',0,'intval');
        $district3 = request()->route('d3/d',0,'intval');
        $minwage = request()->route('w1/d',0,'intval');
        $maxwage = request()->route('w2/d',0,'intval');
        $trade = request()->route('trade/d',0,'intval');
        $scale = request()->route('scale/d',0,'intval');
        $nature = request()->route('nat/d',0,'intval');
        $education = request()->route('edu/d',0,'intval');
        $experience = request()->route('exp/d',0,'intval');
        $tag = request()->route('tag/s', '', 'trim');
        $settr = request()->route('settr/d',0,'intval');
        $sort = request()->route('sort/s', '', 'trim');
        $famous = request()->route('famous/d',0,'intval');
        $license = request()->route('license/d',0,'intval');
        $filter_apply = request()->route('filter_apply/d',0,'intval');
        $current_page = request()->get('page/d',1,'intval');
        $pagesize = request()->get('pagesize/d',10,'intval');
        $selectedTagArr = [];

        if ($keyword != '') {
            $params['keyword'] = $keyword;
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
        if ($category1 > 0) {
            $params['category1'] = $category1;
        }
        if ($category2 > 0) {
            $params['category2'] = $category2;
        }
        if ($category3 > 0) {
            $params['category3'] = $category3;
        }
        if($listtype=='emergency'){
            $params['emergency'] = 1;
        }
        if ($minwage > 0) {
            $params['minwage'] = $minwage;
        }
        if ($maxwage > 0) {
            $params['maxwage'] = $maxwage;
        }
        if ($trade > 0) {
            $params['trade'] = $trade;
        }
        if ($scale > 0) {
            $params['scale'] = $scale;
        }
        if ($nature > 0) {
            $params['nature'] = $nature;
        }
        if ($education > 0) {
            $params['education'] = $education;
        }
        if ($experience > 0) {
            $params['experience'] = $experience;
        }
        if ($tag != '') {
            $params['tag'] = $tag;
            $selectedTagArr = explode("_",$tag);
        }
        if ($settr > 0) {
            $params['settr'] = $settr;
        }
        if ($sort != '') {
            $params['sort'] = $sort;
        }
        if ($famous > 0) {
            $params['famous'] = $famous;
        }
        if ($license > 0) {
            $params['license'] = $license;
        }
        if ($filter_apply > 0 && $this->visitor!==null && $this->visitor['utype']==2) {
            $params['filter_apply_uid'] = $this->visitor['uid'];
        }

        if(config('global_config.job_search_login')==1){
            if($this->visitor===null){
                $show_mask = 1;
                if(!empty($params)){
                    $params['district1'] = -1;
                }
                $params['count_total'] = 0;
                $params['current_page'] = 1;
                $params['pagesize'] = config('global_config.job_search_login_num')==0?1:config('global_config.job_search_login_num');
            }else{
                $show_mask = 0;
                $params['count_total'] = 1;
                $params['current_page'] = $current_page;
                $params['pagesize'] = $pagesize;
            }
        }else{
            $show_mask = 0;
            $params['count_total'] = 1;
            $params['current_page'] = $current_page;
            $params['pagesize'] = $pagesize;
        }

        $instance = new \app\common\lib\JobSearchEngine($params);

        $searchResult = $instance->run();
        $pagerHtml = $searchResult['items']->render();
        $return['items'] = $this->get_datalist($searchResult['items']);
        $return['total'] = $searchResult['total'];
        $return['total_page'] = $searchResult['total_page'];

        if($district2>0){
            $district_level = 3;
            $category_district = model('CategoryDistrict')->getCache($district2);
        }else if($district1>0){
            $district_level = 2;
            $category_district = model('CategoryDistrict')->getCache($district1);
        }else {
            $district_level = 1;
            $category_district = model('CategoryDistrict')->getCache('0');
        }
        $options_district = [];
        foreach ($category_district as $key => $value) {
            if($district_level==1){
                $params = ['d1'=>$key,'d2'=>null,'d3'=>null];
            }else if($district_level==2){
                $params = ['d1'=>$district1,'d2'=>$key,'d3'=>null];
            }else if($district_level==3){
                $params = ['d1'=>$district1,'d2'=>$district2,'d3'=>$key];
            }
            
            $arr['id'] = $key;
            $arr['url'] = P($params);
            $arr['text'] = $value;
            $options_district[] = $arr;
        }

        
        if($category2>0){
            $category_level = 3;
            $category_category = model('CategoryJob')->getCache($category2);
        }else if($category1>0){
            $category_level = 2;
            $category_category = model('CategoryJob')->getCache($category1);
        }else {
            $category_level = 1;
            $category_category = model('CategoryJob')->getCache('0');
        }
        $options_categoryjob = [];
        foreach ($category_category as $key => $value) {
            if($category_level==1){
                $params = ['c1'=>$key,'c2'=>null,'c3'=>null];
            }else if($category_level==2){
                $params = ['c1'=>$category1,'c2'=>$key,'c3'=>null];
            }else if($category_level==3){
                $params = ['c1'=>$category1,'c2'=>$category2,'c3'=>$key];
            }
            
            $arr['id'] = $key;
            $arr['url'] = P($params);
            $arr['text'] = $value;
            $options_categoryjob[] = $arr;
        }

        $category_all = model('Category')->getCache('');
        $options_exp = model('BaseModel')->map_experience;
        $options_tag = $category_all['QS_jobtag'];
        $options_sex = model('Job')->map_sex;
        $options_trade = $category_all['QS_trade'];
        $options_edu = model('BaseModel')->map_education;
        $options_scale = $category_all['QS_scale'];
        $options_nature = model('Job')->map_nature;

        $hotjob_list = $this->getHotjob();
        
        $category_district_data = model('CategoryDistrict')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $seoData['keyword'] = $keyword;
        if($district3>0){
            $seoData['citycategory'] = isset($category_district_data[$district3]) ? $category_district_data[$district3] : '';
        }else if($district2>0){
            $seoData['citycategory'] = isset($category_district_data[$district2]) ? $category_district_data[$district2] : '';
        }else if($district1>0){
            $seoData['citycategory'] = isset($category_district_data[$district1]) ? $category_district_data[$district1] : '';
        }else{
            $seoData['citycategory'] = '';
        }
        if($category3>0){
            $seoData['jobcategory'] = isset($category_job_data[$category3]) ? $category_job_data[$category3] : '';
        }else if($category2>0){
            $seoData['jobcategory'] = isset($category_job_data[$category2]) ? $category_job_data[$category2] : '';
        }else if($category1>0){
            $seoData['jobcategory'] = isset($category_job_data[$category1]) ? $category_job_data[$category1] : '';
        }else{
            $seoData['jobcategory'] = '';
        }
        $this->initPageSeo('joblist',$seoData);
        
        $this->assign('selectedTagArr',$selectedTagArr);
        $this->assign('hotjob_list',$hotjob_list);
        $this->assign('currentPage',$current_page);
        $this->assign('prevPage',$current_page-1);
        $this->assign('nextPage',$current_page+1);
        $this->assign('pagerHtml',$pagerHtml);
        $this->assign('dataset',$return);
        $this->assign('district_level',$district_level);
        $this->assign('options_district',$options_district);
        $this->assign('category_level',$category_level);
        $this->assign('options_categoryjob',$options_categoryjob);
        $this->assign('options_exp',$options_exp);
        $this->assign('options_tag',$options_tag);
        $this->assign('options_sex',$options_sex);
        $this->assign('options_trade',$options_trade);
        $this->assign('options_edu',$options_edu);
        $this->assign('options_scale',$options_scale);
        $this->assign('options_nature',$options_nature);
        $this->assign('show_mask',$show_mask);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('index');
    }
    public function contrast()
    {
        $this->pageHeader['title'] = '职位对比 - '.$this->pageHeader['title'];
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('contrast');
    }
    public function show()
    {
        $id = request()->route('id/d',0,'intval');
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'job/'.$id,302);
            exit;
        }
        $field_rule_data = model('FieldRule')->getCache();
        $field_rule = $field_rule_data['Job'];
        foreach ($field_rule as $field => $field_attr) {
            $_arr = [
                'field_name' => $field_attr['field_name'],
                'is_require' => $field_attr['is_require'],
                'is_display' => $field_attr['is_display'],
                'field_cn' => $field_attr['field_cn'],
            ];
            $field_rule[$field] = $_arr;
        }
        //读取页面缓存配置
        $pageCache = model('Page')->getCache('jobshow');
        //如果缓存有效期为0，则不使用缓存
        if($pageCache['expire']>0){
            $return = model('Page')->getCacheByAlias('jobshow',$id);
        }else{
            $return = false;
        }
        if (!$return) {
            $return = $this->writeShowCache($id,$pageCache);
            if($return===false){
                abort(404,'页面不存在');
            }
        }
        $return['field_rule'] = $field_rule;
        $return['share_url'] = config('global_config.mobile_domain').'job/'.$return['base_info']['id'];
        $return['im_url'] = config('global_config.mobile_domain').'im/imlist';
        $seoData['jobname'] = $return['base_info']['jobname'];
        $seoData['companyname'] = $return['com_info']['companyname'];
        $seoData['nature'] = $return['base_info']['nature_text'];
        $seoData['category'] = $return['base_info']['category_text'];
        $seoData['district'] = $return['base_info']['district_text'];
        $this->assign('phone_protect_open', false);
        if(intval(config('global_config.alicloud_phone_protect_open'))){
            $protectTarget = array_map('intval', explode(',', config('global_config.alicloud_phone_protect_target')));
            if(in_array(1, $protectTarget)){
                $this->assign('phone_protect_open', true);
            }
        }
        $this->initPageSeo('jobshow',$seoData);
        $this->assign('return',$return);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('show');
    }
    protected function writeShowCache($id,$pageCache){
        $jobinfo = model('Job')
            ->where('id', $id)
            ->field(true)
            ->find();
        if ($jobinfo === null) {
            return false;
        }
        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $base_info['id'] = $jobinfo['id'];
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
        $base_info['negotiable'] = $jobinfo['negotiable'];
        $base_info['wage_text'] = model('BaseModel')->handle_wage(
            $jobinfo['minwage'],
            $jobinfo['maxwage'],
            $jobinfo['negotiable']
        );
        $base_info['education_text'] = isset(
            model('BaseModel')->map_education[$jobinfo['education']]
        )
        ? model('BaseModel')->map_education[$jobinfo['education']]
        : '不限';
        $base_info['experience_text'] = isset(
            model('BaseModel')->map_experience[$jobinfo['experience']]
        )
        ? model('BaseModel')->map_experience[$jobinfo['experience']]
        : '不限';

        $base_info['tag_text_arr'] = [];
        if ($jobinfo['tag'] != '') {
            $tag_arr = explode(',', $jobinfo['tag']);
            foreach ($tag_arr as $k => $v) {
                isset($category_data['QS_jobtag'][$v]) &&
                    ($base_info['tag_text_arr'][] =
                    $category_data['QS_jobtag'][$v]);
            }
        }

        $base_info['amount_text'] = $jobinfo['amount'] == 0 ? '若干' : $jobinfo['amount'];
        if ($jobinfo['age_na'] == 1) {
            $base_info['age_text'] = '不限';
        } else if ($jobinfo['minage'] > 0 || $jobinfo['maxage'] > 0) {
            $base_info['age_text'] =
                $jobinfo['minage'] . '-' . $jobinfo['maxage'];
        } else {
            $base_info['age_text'] = '';
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
            ->field(
                'a.id,a.companyname,a.logo,a.district,a.nature,a.scale,a.trade,a.audit,b.address,a.setmeal_id,c.icon,a.addtime'
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
            $return['com_info']['setmeal_icon'] =
            $companyinfo['icon'] > 0
            ? model('Uploadfile')->getFileUrl($companyinfo['icon'])
            : model('Setmeal')->getSysIcon($companyinfo['setmeal_id']);
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
        
        
        $similar_data = [
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
        $return['near_district_list'] = $this->getNearDistrict($jobinfo['district1'],$jobinfo['district2'],$jobinfo['district3']);
        $return['hotword_list'] = model('Hotword')->getList(49);
        $return['hotjob_list'] = $this->getHotjob($jobinfo['id']);
        if($pageCache['expire']>0){
            model('Page')->writeCacheByAlias('jobshow',$return,$pageCache['expire'],$id);
        }
        return $return;
    }
    protected function get_datalist($list)
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
                    ->where('a.id', 'in', $comid_arr)
                    ->column(
                        'a.id,a.companyname,a.audit,a.logo,a.nature,a.scale,a.trade,a.setmeal_id,b.icon',
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
                'id,company_id,jobname,emergency,stick,minwage,maxwage,negotiable,education,experience,tag,district,addtime,refreshtime,map_lat,map_lng,amount,content,setmeal_id';
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
                $tmp_arr['amount_text'] = $val['amount'] == 0 ? '若干' : $val['amount'];
                $tmp_arr['content'] = $val['content'];
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
                    $tmp_arr['setmeal_icon'] = isset(
                        $icon_arr[$cominfo_arr[$val['company_id']]['icon']]
                    )
                    ? $icon_arr[$cominfo_arr[$val['company_id']]['icon']]
                    : model('Setmeal')->getSysIcon($val['setmeal_id']);
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
                $tmp_arr['negotiable'] = $val['negotiable'];
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
                $result_data_list[] = $tmp_arr;
            }
        }
        return $result_data_list;
    }
    protected function getNearDistrict($district1,$district2,$district3){
        if($district2==0){
            $level = 1;
            $parentDistrict = 0;
        }else if($district3=0){
            $level = 2;
            $parentDistrict = $district1;
        }else{
            $level = 3;
            $parentDistrict = $district2;
        }
        $district_list = model('CategoryDistrict')->getCache($parentDistrict);
        $return = [];
        foreach ($district_list as $key => $value) {
            if($level==1){
                $params = ['d1'=>$key,'d2'=>0,'d3'=>0];
            }else if($level==2){
                $params = ['d1'=>$district1,'d2'=>$key,'d3'=>0];
            }else if($level==3){
                $params = ['d1'=>$district1,'d2'=>$district2,'d3'=>$key];
            }
            $return[] = ['id'=>$key,'text'=>$value,'params'=>$params];
        }
        return $return;
    }
    /**
     * 热门职位
     */
    protected function getHotjob($id=0){
        $params['count_total'] = 0;
        $params['pagesize'] = 5;
        $params['sort'] = 'emergency';
        $instance = new \app\common\lib\JobSearchEngine($params);
        $runMap = '';
        if($id>0){
            $runMap = 'id!='.$id;
        }
        $searchResult = $instance->run($runMap);
        $list = $this->get_datalist($searchResult['items']);
        return $list;
    }
}
