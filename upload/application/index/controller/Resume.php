<?php
namespace app\index\controller;

class Resume extends \app\index\controller\Base
{
    public function _initialize(){
        parent::_initialize();
        $this->assign('navSelTag','resume');
    }
    public function index()
    {
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'resumelist',302);
            exit;
        }
        $keyword = request()->route('keyword/s', '', 'trim,addslashes');
        $listtype = request()->route('listtype/s','','trim');
        $category1 = request()->route('c1/d',0,'intval');
        $category2 = request()->route('c2/d',0,'intval');
        $category3 = request()->route('c3/d',0,'intval');
        $district1 = request()->route('d1/d',0,'intval');
        $district2 = request()->route('d2/d',0,'intval');
        $district3 = request()->route('d3/d',0,'intval');
        $experience = request()->route('exp/d',0,'intval');
        $tag = request()->route('tag/s', '', 'trim');
        $sex = request()->route('sex/d',0,'intval');
        $minage = request()->route('a1/d',0,'intval');
        $maxage = request()->route('a2/d',0,'intval');
        $trade = request()->route('trade/d',0,'intval');
        $major = request()->route('major/d',0,'intval');
        $education = request()->route('edu/d',0,'intval');
        $nature = request()->route('nat/d',0,'intval');
        $minwage = request()->route('w1/d',0,'intval');
        $maxwage = request()->route('w2/d',0,'intval');
        $settr = request()->route('settr/d',0,'intval');
        $photo = request()->route('photo/d',0,'intval');
        $img = request()->route('img/d',0,'intval');
        $sort = request()->route('sort/s', '', 'trim');
        $current_page = request()->get('page/d',1,'intval');
        $pagesize = request()->get('pagesize/d',10,'intval');
        $selectedTagArr = [];

        if ($keyword != '') {
            $params['keyword'] = $keyword;
        }
        $subsiteCondition = get_subsite_condition();
        $subsite_district_level = 0;
        if(!empty($subsiteCondition)){
            foreach ($subsiteCondition as $key => $value) {
                if($key=='district1'){
                    $district1 = $value;
                    $subsite_district_level = 1;
                    break;
                }
                if($key=='district2'){
                    $district2 = $value;
                    $subsite_district_level = 2;
                    break;
                }
                if($key=='district3'){
                    $district3 = $value;
                    $subsite_district_level = 3;
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
        if ($category1 > 0) {
            $params['category1'] = $category1;
        }
        if ($category2 > 0) {
            $params['category2'] = $category2;
        }
        if ($category3 > 0) {
            $params['category3'] = $category3;
        }
        if ($experience > 0) {
            $params['experience'] = $experience;
        }
        if ($tag != '') {
            /**
             * SQL注入
             * yx - 2022.07.26 10:17
             * 【旧】
             * $params['tag'] = $tag;
             * $selectedTagArr = explode("_",$tag);
             */
            $params['tag'] = $tag;
            $params_tags = explode("_", $tag);
            foreach ($params_tags as $key => $value) {
                $selectedTagArr[] = intval($value);
            }
            $params['tag'] = implode($selectedTagArr, '_');
        }
        if ($sex > 0) {
            $params['sex'] = $sex;
        }
        if ($minage > 0) {
            $params['minage'] = $minage;
        }
        if ($maxage > 0) {
            $params['maxage'] = $maxage;
        }
        if ($trade > 0) {
            $params['trade'] = $trade;
        }
        if ($major > 0) {
            $params['major'] = $major;
        }
        if ($education > 0) {
            $params['education'] = $education;
        }
        if ($nature > 0) {
            $params['nature'] = $nature;
        }
        if ($minwage > 0) {
            $params['minwage'] = $minwage;
        }
        if ($maxwage > 0) {
            $params['maxwage'] = $maxwage;
        }
        if ($photo > 0) {
            $params['photo'] = $photo;
        }
        if ($img > 0) {
            $params['img'] = $img;
        }
        if ($settr > 0) {
            $params['settr'] = $settr;
        }
        if($listtype=='great'){
            $params['high_quality'] = 1;
        }
        if ($sort != '') {
            $params['sort'] = $sort;
        }

        if ($this->visitor!==null && $this->visitor['utype'] == 1) {
            $shield_find = model('Shield')
                ->where('company_uid', $this->visitor['uid'])
                ->find();
            if ($shield_find !== null) {
                $params['shield_company_uid'] = $this->visitor['uid'];
            }
        }


        if(config('global_config.resume_search_login')==1){
            if($this->visitor===null){
                $show_mask = 1;
                if(!empty($params)){
                    $params['district1'] = -1;
                }
                $params['count_total'] = 0;
                $params['current_page'] = 1;
                $params['pagesize'] = config('global_config.resume_search_login_num')==0?1:config('global_config.resume_search_login_num');
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

        $instance = new \app\common\lib\ResumeSearchEngine($params);

        $searchResult = $instance->run();
        $pagerHtml = $searchResult['items']->render();
        $return['items'] = $this->get_datalist($searchResult['items']);
        $return['total'] = $searchResult['total'];
        $return['total_page'] = $searchResult['total_page'];

        if($this->subsite!==null && $this->subsite->district3>0){
            $district_level = 0;
            $category_district = [];
        }else if($district2>0){
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
        $options_tag = $category_all['QS_resumetag'];
        $options_sex = model('Resume')->map_sex;
        $options_edu = model('BaseModel')->map_education;
        $options_nature = model('Resume')->map_nature;
        $options_trade = $category_all['QS_trade'];
        $options_major = model('CategoryMajor')->getCache('');


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
        $this->initPageSeo('resumelist',$seoData);

        $this->assign('subsite_district_level',$subsite_district_level);
        $this->assign('selectedTagArr',$selectedTagArr);
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
        $this->assign('options_edu',$options_edu);
        $this->assign('options_nature',$options_nature);
        $this->assign('options_trade',$options_trade);
        $this->assign('options_major',$options_major);
        $this->assign('show_mask',$show_mask);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('index');
    }
    public function contrast()
    {
        $this->pageHeader['title'] = '简历对比 - '.$this->pageHeader['title'];
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('contrast');
    }
    public function show()
    {
        $id = request()->route('id/d',0,'intval');
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'resume/'.$id,302);
            exit;
        }
        $field_rule_data = model('FieldRule')->getCache();
        $field_rule = [
            'basic' => $field_rule_data['Resume'],
            'intention' => $field_rule_data['ResumeIntention'],
            'education' => $field_rule_data['ResumeEducation']
        ];
        foreach ($field_rule as $key => $rule) {
            foreach ($rule as $field => $field_attr) {
                $_arr = [
                    'is_display' => intval($field_attr['is_display']),
                    'field_cn' => $field_attr['field_cn']
                ];
                $field_rule[$key][$field] = $_arr;
            }
        }
        $resume_module_data = model('ResumeModule')->getCache();
        $resume_module = [];
        foreach ($resume_module_data as $module_name => $module_attr) {
            $_arr = [
                'module_cn' => $module_attr['module_cn'],
                'is_display' => intval($module_attr['is_display'])
            ];
            $resume_module[$module_name] = $_arr;
        }
        //读取页面缓存配置
        $pageCache = model('Page')->getCache('resumeshow');
        //如果缓存有效期为0，则不使用缓存
        if($pageCache['expire']>0){
            $return = model('Page')->getCacheByAlias('resumeshow',$id);
        }else{
            $return = false;
        }
        if(!$return){
            $return = $this->writeShowCache($id,$resume_module,$pageCache);
            if($return===false){
                abort(404,'页面不存在');
            }
        }
        $this->assign('phone_protect_open', false);
        if(intval(config('global_config.alicloud_phone_protect_open'))){
            $protectTarget = array_map('intval', explode(',', config('global_config.alicloud_phone_protect_target')));
            if(in_array(2, $protectTarget)){
                $this->assign('phone_protect_open', true);
            }
        }
        $return['field_rule'] = $field_rule;
        $return['resume_module'] = $resume_module;
        $return['share_url'] = config('global_config.mobile_domain').'resume/'.$return['base_info']['id'];
        $return['base_info']['fullname'] = model('Resume')->formatFullname([$return['base_info']['id']],$this->visitor,true);
        $this->pageHeader['title'] = $return['base_info']['fullname'].'的简历 - '.$this->pageHeader['title'];
        $this->pageHeader['keywords'] = $return['base_info']['fullname'].'的简历,'.$this->pageHeader['keywords'];
        $this->pageHeader['description'] = $return['base_info']['fullname'].'的简历,'.$this->pageHeader['description'];
        $seoData['fullname'] = $return['base_info']['fullname'];
        $seoData['sex'] = $return['base_info']['sex_text'];
        $seoData['education'] = $return['base_info']['education_text'];
        $seoData['experience'] = $return['base_info']['experience_text'];
        $seoData['district'] = $return['base_info']['intention_district_text'];
        $seoData['jobcategory'] = $return['base_info']['intention_jobs_text'];
        $seoData['specialty'] = $return['base_info']['specialty'];

        /**
         * 【BUG】
         * 微信通知查看简历，会员中心显示未查看
         * yx - 2022.09.22
         */
        $company_uid = request()->get('company_uid/d', 0, 'intval');
        $job_apply_id = request()->get('job_apply_id/d', 0, 'intval');
        if (!empty($company_uid) && !empty($job_apply_id)) {
            $JobApplyInfo = model('JobApply')
                ->where('id', $job_apply_id)
                ->where('company_uid', $company_uid)
                ->where('resume_id', $id)
                ->where('is_look', 0)
                ->find();
            if (!empty($JobApplyInfo)) {
                model('JobApply')
                    ->where('id', $job_apply_id)
                    ->setField('is_look', 1);
                model('Resume')->addViewLog(
                    $return['base_info']['id'],
                    $company_uid,
                    $return['base_info']['uid']
                );
                $ip = get_client_ip();
                model('MemberActionLog')->save([
                    'utype' => 1,
                    'uid' => $company_uid,
                    'content' => '收到的简历设为已查看【简历id：' . $id . '】',
                    'addtime' => time(),
                    'ip_addr' => get_client_ipaddress($ip),
                    'ip' => $ip . ':' . get_client_port(),
                    'platform' => 'web',
                    'is_login' => 0
                ]);
            }
        }

        $this->initPageSeo('resumeshow',$seoData);

        $this->assign('return',$return);
        $this->assign('pageHeader',$this->pageHeader);

        $resume_show_tpl = !empty(config('global_config.resume_show_tpl')) ? config('global_config.resume_show_tpl') : 'def';
        return $this->fetch('resume/showTpl/'.$resume_show_tpl.'/show');
    }
    protected function writeShowCache($id,$resume_module,$pageCache){
        $where['id'] = $id;
        $basic = model('Resume')
            ->where($where)
            ->field(true)
            ->find();
        if ($basic === null) {
            return false;
        }
        $category_data = model('Category')->getCache();
        $category_major_data = model('CategoryMajor')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();

        $basic_info['id'] = $basic['id'];
        $basic_info['uid'] = $basic['uid'];
        $basic_info['audit'] = $basic['audit'];
        $basic_info['high_quality'] = $basic['high_quality'];
        $basic_info['comment'] = $basic['comment'];
        $basic_info['service_tag'] = $basic['service_tag'];
        $basic_info['residence'] = $basic['residence'];
        $basic_info['height'] = $basic['height'];
        $basic_info['specialty'] = $basic['specialty'];
        $basic_info['refreshtime'] = daterange_format(
            $basic['addtime'],
            $basic['refreshtime']
        );
        $basic_info['click'] = $basic['click'];
        $basic_info['custom_field_1'] = $basic['custom_field_1'];
        $basic_info['custom_field_2'] = $basic['custom_field_2'];
        $basic_info['custom_field_3'] = $basic['custom_field_3'];
        $basic_info['sex'] = $basic['sex'];
        $basic_info['sex_text'] = model('Resume')->map_sex[$basic['sex']];
        $basic_info['age'] = date('Y') - intval($basic['birthday']);
        $basic_info['education_text'] = isset(
            model('BaseModel')->map_education[$basic['education']]
        )
            ? model('BaseModel')->map_education[$basic['education']]
            : '';
        $basic_info['experience_text'] =
            $basic['enter_job_time'] == 0
                ? '无经验'
                : format_date($basic['enter_job_time']) . '经验';
        $basic_info['householdaddress'] = $basic['householdaddress'];
        $basic_info['tag_text_arr'] = [];
        if ($basic['tag'] != '') {
            $tag_text_arr = [];
            $tag_arr = explode(',', $basic['tag']);
            foreach ($tag_arr as $k => $v) {
                if (
                    is_numeric($v) &&
                    isset($category_data['QS_resumetag'][$v])
                ) {
                    $basic_info['tag_text_arr'][] =
                        $category_data['QS_resumetag'][$v];
                } else {
                    $basic_info['tag_text_arr'][] = $v;
                }
            }
        }
        $basic_info['major_text'] = isset($category_major_data[$basic['major']])
            ? $category_major_data[$basic['major']]
            : '';

        $basic_info['current_text'] = isset(
            $category_data['QS_current'][$basic['current']]
        )
            ? $category_data['QS_current'][$basic['current']]
            : '';
        $basic_info['complete_percent'] = model('Resume')->countCompletePercent(
            $basic['id']
        );

        $basic_info['marriage_text'] = isset(
            model('Resume')->map_marriage[$basic['marriage']]
        )
            ? model('Resume')->map_marriage[$basic['marriage']]
            : '保密';

        $basic_info['photo_img_src'] =
            $basic['photo_img'] > 0
                ? model('Uploadfile')->getFileUrl($basic['photo_img'])
                : default_empty('photo');
        $return['base_info'] = $basic_info;

        //求职意向
        $intention_data = model('ResumeIntention')
            ->field('id,rid,uid', true)
            ->where(['rid' => ['eq', $basic['id']]])
            ->select();
        $intention_list = [];
        foreach ($intention_data as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['nature_text'] = isset(
                model('Resume')->map_nature[$value['nature']]
            )
                ? model('Resume')->map_nature[$value['nature']]
                : '全职';
            $tmp_arr['category_text'] = isset(
                $category_job_data[$value['category']]
            )
                ? $category_job_data[$value['category']]
                : '';
            $tmp_arr['district_text'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $tmp_arr['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                0
            );
            $tmp_arr['trade_text'] = isset(
                $category_data['QS_trade'][$value['trade']]
            )
                ? $category_data['QS_trade'][$value['trade']]
                : '';

            // 【新增】求职状态唯一展示
            $return['base_info']['nature_text_unique'] = !empty($return['base_info']['nature_text_unique']) ? $return['base_info']['nature_text_unique'] : $tmp_arr['nature_text'];
            // 【新增】薪资唯一展示
            $return['base_info']['wage_text_unique'] = !empty($return['base_info']['wage_text_unique']) ? $return['base_info']['wage_text_unique'] : $tmp_arr['wage_text'];

            $return['base_info']['intention_jobs_text'][] = $tmp_arr['category_text'];
            $return['base_info']['intention_district_text'][] = $tmp_arr['district_text'];
            $intention_list[] = $tmp_arr;
        }
        if(!empty($return['base_info']['intention_jobs_text'])){
            $return['base_info']['intention_jobs_text'] = array_unique($return['base_info']['intention_jobs_text']);
            $return['base_info']['intention_jobs_text'] = implode(",",$return['base_info']['intention_jobs_text']);
        }else{
            $return['base_info']['intention_jobs_text'] = '';
        }
        if(!empty($return['base_info']['intention_district_text'])){
            $return['base_info']['intention_district_text'] = array_unique($return['base_info']['intention_district_text']);
            $return['base_info']['intention_district_text'] = implode(",",$return['base_info']['intention_district_text']);
        }else{
            $return['base_info']['intention_district_text'] = '';
        }

        $return['intention_list'] = $intention_list;

        //工作经历
        $work_list = model('ResumeWork')
            ->field('id,rid,uid', true)
            ->where(['rid' => ['eq', $basic['id']]])
            ->select();
        $return['work_list'] = $work_list;
        //教育经历
        $education_list = model('ResumeEducation')
            ->field('id,rid,uid', true)
            ->where(['rid' => ['eq', $basic['id']]])
            ->select();
        foreach ($education_list as $key => $value) {
            $education_list[$key]['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '';
        }
        $return['education_list'] = $education_list;
        //项目经历
        if ($resume_module['project']['is_display'] == 1) {
            $project_list = model('ResumeProject')
                ->field('id,rid,uid', true)
                ->where(['rid' => ['eq', $basic['id']]])
                ->select();
        } else {
            $project_list = [];
        }
        $return['project_list'] = $project_list;
        //培训经历
        if ($resume_module['training']['is_display'] == 1) {
            $training_list = model('ResumeTraining')
                ->field('id,rid,uid', true)
                ->where(['rid' => ['eq', $basic['id']]])
                ->select();
        } else {
            $training_list = [];
        }
        $return['training_list'] = $training_list;

        //语言能力
        if ($resume_module['language']['is_display'] == 1) {
            $language_data = model('ResumeLanguage')
                ->field('id,rid,uid', true)
                ->where(['rid' => ['eq', $basic['id']]])
                ->select();
            $language_list = [];
            foreach ($language_data as $key => $value) {
                $tmp_arr = [];
                $tmp_arr['language_text'] = isset(
                    $category_data['QS_language'][$value['language']]
                )
                    ? $category_data['QS_language'][$value['language']]
                    : '';
                $tmp_arr['level_text'] = isset(
                    $category_data['QS_language_level'][$value['level']]
                )
                    ? $category_data['QS_language_level'][$value['level']]
                    : '';
                $language_list[] = $tmp_arr;
            }
        } else {
            $language_list = [];
        }
        $return['language_list'] = $language_list;
        //证书
        if ($resume_module['certificate']['is_display'] == 1) {
            $certificate_list = model('ResumeCertificate')
                ->field('id,rid,uid', true)
                ->where(['rid' => ['eq', $basic['id']]])
                ->select();
        } else {
            $certificate_list = [];
        }
        $return['certificate_list'] = $certificate_list;

        $bind_data = model('MemberBind')
            ->where('uid',$basic['uid'])
            ->where('type','weixin')
            ->where('is_subscribe',1)
            ->find();
        $return['bind_weixin'] = $bind_data!==null?1:0;

        if($pageCache['expire']>0){
            model('Page')->writeCacheByAlias('resumeshow',$return,$pageCache['expire'],$id);
        }
        return $return;
    }

    protected function get_datalist($list)
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
            $fullname_arr = model('Resume')->formatFullname($resumeid_arr,$this->visitor);

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
                $tmp_arr['fullname'] = $fullname_arr[$val['id']];
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

                $result_data_list[] = $tmp_arr;
            }
        }
        return $result_data_list;
    }
}
