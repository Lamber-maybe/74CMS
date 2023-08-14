<?php
namespace app\v1_0\controller\home;

use phpService\PdfService;

class Resume extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $keyword = input('get.keyword/s', '', 'trim,addslashes');
        $district1 = input('get.district1/d', 0, 'intval');
        $district2 = input('get.district2/d', 0, 'intval');
        $district3 = input('get.district3/d', 0, 'intval');
        $experience = input('get.experience/d', 0, 'intval');
        $education = input('get.education/d', 0, 'intval');
        $sex = input('get.sex/d', 0, 'intval');
        $minage = input('get.minage/d', 0, 'intval');
        $maxage = input('get.maxage/d', 0, 'intval');
        $major = input('get.major/d', 0, 'intval');
        $minwage = input('get.minwage/d', 0, 'intval');
        $maxwage = input('get.maxwage/d', 0, 'intval');
        $tag = input('get.tag/s', '', 'trim');
        $settr = input('get.settr/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        if ($keyword != '') {
            $params['keyword'] = $keyword;
        }

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
        if ($experience > 0) {
            $params['experience'] = $experience;
        }
        if ($education > 0) {
            $params['education'] = $education;
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
        if ($major > 0) {
            $params['major'] = $major;
        }
        if ($minwage > 0) {
            $params['minwage'] = $minwage;
        }
        if ($maxwage > 0) {
            $params['maxwage'] = $maxwage;
        }
        if ($tag != '') {
            $tag = str_replace(",","_",$tag);
            $params['tag'] = $tag;
        }
        if ($settr > 0) {
            $params['settr'] = $settr;
        }
        if ($this->userinfo && $this->userinfo->utype == 1) {
            $shield_find = model('Shield')
                ->where('company_uid', $this->userinfo->uid)
                ->find();
            if ($shield_find !== null) {
                $params['shield_company_uid'] = $this->userinfo->uid;
            }
        }

        if(config('global_config.resume_search_login')==1 && $this->platform=='mobile'){
            if($this->userinfo===null){
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
        $return['items'] = $this->get_datalist($searchResult['items']);
        $return['total'] = $searchResult['total'];
        $return['total_page'] = $searchResult['total_page'];
        $return['show_mask'] = $show_mask;
        $this->ajaxReturn(200, '获取数据成功', $return);
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

            $fullname_arr = model('Resume')->formatFullname($resumeid_arr,$this->userinfo);

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
            $resume_tag = model('Category')->getCache('QS_resumetag');
            $resume_work = model('ResumeWork')
                ->field('rid,uid,starttime,endtime,todate,companyname,jobname,duty')
                ->where('rid','in',$rids)
                ->group('rid')
                ->order('id','desc')
                ->select();
            foreach ($resume as $key => $val) {
                $tmp_arr = [];
                $tmp_arr['tag'] = [];
                foreach(explode(',',$val['tag']) as $k=>$v)
                {
                    $tag = isset($resume_tag[$v]) ? $resume_tag[$v] : '';
                    if (!empty($tag))
                    {
                        $tmp_arr['tag'][] = $tag;
                    }
                }
                // app增加字段
                $tmp_arr['companyname'] = '';
                $tmp_arr['jobname'] = '';
                $tmp_arr['starttime'] = '';
                $tmp_arr['endtime'] = '';
                $tmp_arr['specialty'] = $val['specialty'];
                foreach ($resume_work as $k=>$v)
                {
                    if ($val['id'] == $v['rid'])
                    {
                        $tmp_arr['companyname'] = $v['companyname'];
                        $tmp_arr['jobname'] = $v['jobname'];
                        $tmp_arr['starttime'] = date('Y',$v['starttime']);
                        if ($v['todate'] == 1)
                        {
                            $tmp_arr['endtime'] = '至今';
                        }else
                        {
                            $tmp_arr['endtime'] = date('Y',$v['endtime']);
                        }
                    }
                }
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
            ->limit(3) // 【优化】简历详情页-导出简历，三条以上求职意向都显示第三意向问题   和武哥确认改为只显示3条  zch 2022.9.30
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

            $tmp_arr['district_full_text'] = isset(
                $category_district_data[$value['district1']]
            )
                ? $category_district_data[$value['district1']]
                : '';
            if ($tmp_arr['district_full_text'] != '' && $value['district2'] > 0) {
                $tmp_arr['district_full_text'] .= isset(
                    $category_district_data[$value['district2']]
                )
                    ? '-' . $category_district_data[$value['district2']]
                    : '';
            }
            if ($tmp_arr['district_full_text'] != '' && $value['district3'] > 0) {
                $tmp_arr['district_full_text'] .= isset(
                    $category_district_data[$value['district3']]
                )
                    ? '-' . $category_district_data[$value['district3']]
                    : '';
            }

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
            $return['base_info']['intention_district_full_text'][] = $tmp_arr['district_full_text'];
            $intention_list[] = $tmp_arr;
        }
        if(!empty($return['base_info']['intention_jobs_text'])){
            $return['base_info']['intention_jobs_text'] = array_unique($return['base_info']['intention_jobs_text']);
            $return['base_info']['intention_jobs_text'] = implode(",",$return['base_info']['intention_jobs_text']);
        }
        if(!empty($return['base_info']['intention_district_text'])){
            $return['base_info']['intention_district_text'] = array_unique($return['base_info']['intention_district_text']);
            $return['base_info']['intention_district_text'] = implode(",",$return['base_info']['intention_district_text']);
        }

        if (!empty($return['base_info']['intention_district_full_text'])) {
            $return['base_info']['intention_district_full_text'] = array_unique($return['base_info']['intention_district_full_text']);
            $return['base_info']['intention_district_full_text'] = implode(" ; ", $return['base_info']['intention_district_full_text']);
        } else {
            $return['base_info']['intention_district_full_text'] = '';
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
        //照片作品
        if ($resume_module['img']['is_display'] == 1) {
            $img_list = model('ResumeImg')->getList(['rid'=>$basic['id'],'audit'=>1]);
        } else {
            $img_list = [];
        }
        $return['img_list'] = $img_list;
        if($pageCache['expire']>0){
            model('PageMobile')->writeCacheByAlias('resumeshow',$return,$pageCache['expire'],$id);
        }
        return $return;
    }
    /**
     * 获取简历详情
     */
    public function getDetail($id)
    {
        $id = intval($id);
        $field_rule_data = model('FieldRule')->getCache();
        $field_rule = [
            'basic' => $field_rule_data['Resume'],
            'contact' => $field_rule_data['ResumeContact'],
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
        $pageCache = model('PageMobile')->getCache('resumeshow');
        //如果缓存有效期为0，则不使用缓存
        if($pageCache['expire']>0){
            $return = model('PageMobile')->getCacheByAlias('resumeshow',$id);
        }else{
            $return = false;
        }
        if(!$return){
            $return = $this->writeShowCache($id,$resume_module,$pageCache);
            if($return===false){
                $this->ajaxReturn(500, '简历信息为空');
            }
        }
        $return['base_info']['fullname'] = model('Resume')->formatFullname([$return['base_info']['id']],$this->userinfo,true);
        $return['field_rule'] = $field_rule;
        $return['resume_module'] = $resume_module;

        //联系方式
        $getResumeContact = model('Resume')->getContact($return['base_info'],$this->userinfo);
        $return['show_contact'] = $getResumeContact['show_contact'];
        $return['show_contact_note'] = $getResumeContact['show_contact_note'];
        $return['contact_info'] = $getResumeContact['contact_info'];

        // 附件简历
        $enclosure_resume = model('ResumeEnclosure')->getEnclosure(['rid' => $id]);
        $return['enclosure_resume'] = $enclosure_resume;

        // 简历审核状态： 0|'待审核';1|'已通过';2|'未通过'
        if (1 === intval($return['base_info']['audit']) && !empty($return['intention_list'])) {
            $return['is_invalid'] = 0;
        } else {
            $return['is_invalid'] = 1;
        }

        return $return;
    }
    /**
     * 简历详情
     */
    public function show()
    {
        $id = input('get.id/d', 0, 'intval');
        $info = $this->getDetail($id);
        if ($info === false) {
            $this->ajaxReturn(500, '简历不存在');
        }
        $info['apply_num'] = model('JobApply')
            ->where([
                'resume_id' => $info['base_info']['id'],
                'source' => 0,
                'addtime' => ['egt', strtotime('-14 day')]
            ])
            ->count();
        $info['download_num'] = model('CompanyDownResume')
            ->where([
                'resume_id' => $info['base_info']['id'],
                'addtime' => ['egt', strtotime('-14 day')]
            ])
            ->count();
        $info['click'] = $info['base_info']['click'];

        if ($this->userinfo != null && $this->userinfo->utype == 1) {
            $fav_info = model('FavResume')
                ->where('resume_id', $id)
                ->where('company_uid', $this->userinfo->uid)
                ->find();
            if ($fav_info === null) {
                $info['has_fav'] = 0;
            } else {
                $info['has_fav'] = 1;
            }
        } else {
            $info['has_fav'] = 0;
        }
        $info['base_info']['im_userid'] = '';

        /**
         * 【BUG】
         * 微信通知查看简历，会员中心显示未查看
         * yx - 2022.09.22
         */
        $company_uid = input('get.company_uid/d', 0, 'intval');
        $job_apply_id = input('get.job_apply_id/d', 0, 'intval');
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
                    $info['base_info']['id'],
                    $company_uid,
                    $info['base_info']['uid']
                );
                $this->writeMemberActionLog($company_uid, '收到的简历设为已查看【简历id：' . $id . '】');
            }
        }else{
            model('Resume')->addViewLog(
                $info['base_info']['id'],
                $this->userinfo !== null && $this->userinfo->utype == 1
                    ? $this->userinfo->uid
                    : 0,
                $info['base_info']['uid']
            );
        }

        unset($info['base_info']['uid']);
        $info['share_url'] = $this->sub_site_domain_m.'resume/'.$info['base_info']['id'];
        $info['phone_protect_open'] =  false;
        $info['phone_protect_timeout'] = 180;
        $info['phone_protect_type'] = '';
        if(intval(config('global_config.alicloud_phone_protect_open'))){
            $protectTarget = array_map('intval', explode(',', config('global_config.alicloud_phone_protect_target')));
            if(in_array(2, $protectTarget)){
                $info['phone_protect_open'] =  true;
            }
            if(intval(config('global_config.alicloud_phone_protect_type'))==2){
                $info['phone_protect_timeout'] = 120;
            }
            $info['phone_protect_type'] = intval(config('global_config.alicloud_phone_protect_type'));
            if($info['phone_protect_type']==1 && $this->userinfo===null){
                $info['show_contact'] = 0;
                $info['show_contact_note'] = 'need_login';
            }
        }
        $info['cur_com_mobile'] = '';
        if($info['show_contact'] && $this->userinfo!==null){
            $company_contact = model('CompanyContact')->where('uid',$this->userinfo->uid)->find();
            if($company_contact){
                $info['cur_com_mobile'] = $company_contact['mobile'];
            }else{
                $info['cur_com_mobile'] = $this->userinfo->mobile;
            }
        }
        if($this->userinfo != null && $this->userinfo->utype == 2)
        {
            $resume = model('Resume')->where('uid',$this->userinfo->uid)->find();
            $info['logo_resume_id'] = !empty($resume) ? $resume['id'] : 0;
            if (!empty($resume) && $id ==  $info['logo_resume_id'])
            {
                $info['base_info']['fullname'] = $resume['fullname'];
                $contact_info = model('ResumeContact')
                    ->field('id,rid,uid', true)
                    ->where(['rid' => ['eq', $id]])
                    ->find();
                $info['contact_info'] = $contact_info;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $info);
    }
    public function getContact(){
        $id = input('get.id/d',0,'intval');
        $basic = model('Resume')
            ->where('id',$id)
            ->field(true)
            ->find();
        if ($basic === null) {
            $this->ajaxReturn(500, '简历信息为空');
        }
        $getResumeContact = model('Resume')->getContact($basic,$this->userinfo);
        $return['show_contact'] = $getResumeContact['show_contact'];
        $return['show_contact_note'] = $getResumeContact['show_contact_note'];
        $return['contact_info'] = $getResumeContact['contact_info'];

        $field_rule_data = model('FieldRule')->getCache();
        $field_rule = $field_rule_data['ResumeContact'];
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
    /**
     * 获取简历收藏状态
     */
    public function checkFav(){
        $id = input('get.id/d',0,'intval');
        if ($this->userinfo != null && $this->userinfo->utype == 1) {
            $fav_info = model('FavResume')
                ->where('resume_id', $id)
                ->where('company_uid', $this->userinfo->uid)
                ->find();
            if ($fav_info === null) {
                $has_fav = 0;
            } else {
                $has_fav = 1;
            }
        } else {
            $has_fav = 0;
        }
        $this->ajaxReturn(200,'获取数据成功',$has_fav);
    }
    /**
     * 简历点击量加1
     */
    public function click(){
        $id = input('post.id/d',0,'intval');
        $resumeinfo = model('Resume')
            ->where('id', 'eq', $id)
            ->field('id,uid,click')
            ->find();
        if ($resumeinfo !== null) {
            model('Resume')->addViewLog(
                $resumeinfo['id'],
                $this->userinfo !== null && $this->userinfo->utype == 1
                ? $this->userinfo->uid
                : 0,
                $resumeinfo['uid']
            );
            $click = $resumeinfo['click']+1;
        }else{
            $click = 0;
        }
        $this->ajaxReturn(200, '数据添加成功',$click);
    }
    public function supplementary(){
        $id = input('get.id/d', 0, 'intval');
        $return['apply_num'] = model('JobApply')
            ->where([
                'resume_id' => $id,
                'source' => 0,
                'addtime' => ['egt', strtotime('-14 day')]
            ])
            ->count();
        $return['download_num'] = model('CompanyDownResume')
            ->where([
                'resume_id' => $id,
                'addtime' => ['egt', strtotime('-14 day')]
            ])
            ->count();
        $interview_num = model('CompanyInterview')
            ->where([
                'resume_id' => $id,
                'addtime' => ['egt', strtotime('-14 day')]
            ])
            ->count();
        $video_interview_num = model('CompanyInterviewVideo')
                ->where([
                    'resume_id' => $id,
                    'addtime' => ['egt', strtotime('-14 day')]
                ])
                ->count();
        $return['interview_num'] = $interview_num + $video_interview_num;
        $return['img_list'] = model('ResumeImg')->getList(['rid'=>$id,'audit'=>1]);
        $this->ajaxReturn(200,'获取数据成功',$return);
    }

    public function exportPdfByPhp()
    {
        $id = input('get.id/d', 0, 'intval');
        $info = $this->getDetail($id);

        if ($this->userinfo != null && $this->userinfo->uid === $info['base_info']['uid']) {
            $resume = model('Resume')->where('uid', $this->userinfo->uid)->field('id,uid,fullname')->find();
            $info['base_info']['fullname'] = $resume['fullname'];
        }

        $info['base_info']['specialty'] = json_encode($info['base_info']['specialty'], JSON_UNESCAPED_UNICODE);
        $info['base_info']['specialty'] = json_decode(str_replace('\n', '<br>', $info['base_info']['specialty']), true);
        if ($info === false) {
            $this->ajaxReturn(400, '会员id错误', null);
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
        $info['resume_module'] = $resume_module;
        $getResumeContact = model('Resume')->getContact($info['base_info'], $this->userinfo);
        $show_contact = $getResumeContact['show_contact'];
        $content = '
        <div id="saveBox" class="resume_preview">
            <div>
                <div class="resume_name">' . $info['base_info']['fullname'] . '的个人简历 </div>
                <div class="data_wrapper">
                <div class="baseData">
                <div class="list">
                        <div>
                            <span>姓名：</span> ' . $info['base_info']['fullname'] . '
                        </div>
                    </div>
                    <div class="list">
                        <div>
                            <span>性别：</span> ' . $info['base_info']['sex_text'] . '
                        </div>
                    </div>
                    <div class="list">
                        <div>
                            <span>年龄：</span> ' . $info['base_info']['age'] . '岁 
                        </div>
                    </div>
                    <div class="list">
                        <div>
                            <span >工作经验：</span> ' . $info['base_info']['experience_text'] . '
                        </div>
                    </div>
                    <div class="list">
                        <div>
                            <span>学历：</span> ' . $info['base_info']['education_text'] . '
                        </div>
                    </div>';

        if ($info['field_rule']['basic']['marriage']['is_display'] === 1 && !empty($info['base_info']['marriage_text'])) {
            $content .= ' <div  class="list">
                        <div>
                            <span>婚姻状况：</span> ' . $info['base_info']['marriage_text'] . '
                        </div>
                    </div>';
        }

        if ($info['field_rule']['basic']['height']['is_display'] === 1 && !empty($info['base_info']['height'])) {
            $content .= '<div class="list">
                        <div>
                            <span>身高：</span> ' . $info['base_info']['height'] . ' CM
                        </div>
                    </div>';
        }

        if ($info['field_rule']['basic']['major']['is_display'] === 1 && !empty($info['base_info']['major_text'])) {
            $content .= '<div class="list">
                        <div>
                            <span>专业：</span> ' . $info['base_info']['major_text'] . '
                        </div>
                    </div>';
        }

        if ($info['field_rule']['basic']['householdaddress']['is_display'] === 1 && !empty($info['base_info']['householdaddress'])) {
            $content .= '<div class="list">
                        <div>
                            <span>籍贯：</span> ' . $info['base_info']['householdaddress'] . '
                        </div>
                    </div>';
        }

        if ($info['field_rule']['basic']['residence']['is_display'] === 1 && !empty($info['base_info']['residence'])) {
            $content .= '<div class="list">
                        <div>
                            <span>现居住地：</span> ' . $info['base_info']['residence'] . '
                        </div>
                    </div>';
        }

        # 头像
        $content .= '<div class="clearfix">
                    </div>
                </div>
                    <div class="photo">
                        <img style="width: 120px; height: 120px;" src="' . $info['base_info']['photo_img_src'] . '" />
                    </div>
                </div>
            </div>';


        # 求职意向
        if ($info['resume_module']['intention']['is_display'] === 1 && count($info['intention_list']) > 0) {
            $content .= '<div class="bottom-line">
                <div class="title">求职意向</div>
                <div class="intention_list">';
            for ($i = 0; $i < count($info['intention_list']); $i++) {
                if ($i < 3) {
                    $intention = '';
                    switch ($i) {
                        case 0:
                            $intention = '第一意向';
                            break;
                        case 1:
                            $intention = '第二意向';
                            break;
                        case 2:
                            $intention = '第三意向';
                            break;
                    }
                    $content .= ' <p>
                        <span>
                            ' . $intention . '：
                        </span>
                        [' . $info['intention_list'][$i]['district_text'] . ']' . $info['intention_list'][$i]['category_text'] . '，' . $info['intention_list'][$i]['nature_text'] . '，' . $info['intention_list'][$i]['wage_text'] . '/月
                    </p>';
                }
            }

            $content .= '</div>
            </div>';
        }

        # 联系方式
        $content .= '<div class="bottom-line">
                <div class="title">
                    联系方式
                </div>';

        if ($this->userinfo != null && $this->userinfo->uid === $info['base_info']['uid']) {
            $show_contact = 1;
            $resume_contact = model('ResumeContact')->where('rid', $id)->field('mobile,weixin,email,qq')->find();
            if ($resume_contact != null) {
                $info['contact_info'] = $resume_contact;
            }
        }

        if ($show_contact === 0) {
            $content .= '<div class="intention_list">
                    <p>
                        <span>
                            下载后查看联系方式
                        </span>
                    </p>
                </div>';
        } else {
            $content .= '<div class="intention_list">';

            if (!empty($info['contact_info']['mobile'])) {
                $content .= '<p><span>手机号：</span>' . $info['contact_info']['mobile'] . '</p>';
            }

            if (!empty($info['contact_info']['weixin'])) {
                $content .= '<p><span>微信：</span>' . $info['contact_info']['weixin'] . '</p>';
            }

            if (!empty($info['contact_info']['email'])) {
                $content .= '<p><span>邮箱：</span>' . $info['contact_info']['email'] . '</p>';
            }

            if (!empty($info['contact_info']['qq'])) {
                $content .= '<p><span>QQ：</span>' . $info['contact_info']['qq'] . '</p>';
            }

            $content .= '</div>';
        }

        $content .= '</div>';

        if ($info['resume_module']['specialty']['is_display'] === 1 && !empty($info['base_info']['specialty'])) {
            $content .= '<div class="bottom-line">
                <div class="title">
                    自我描述
                </div>
                <div class="intention_list">
                    <p style="white-space: pre-line;">
                        ' . $info['base_info']['specialty'] . '
                    </p>
                </div>
            </div>';
        }

        if ($info['resume_module']['education']['is_display'] === 1 && count($info['education_list']) > 0) {
            $content .= '<div class="bottom-line">
                <div class="title">
                    教育经历
                </div>
                <div class="undergo_con">';

            for ($i = 0; $i < count($info['education_list']); $i++) {
                $content .= '<div class="undergo_title">
                        <span>' .
                    date('Y-m', $info['education_list'][$i]['starttime']);
                if ($info['education_list'][$i]['todate'] === 1) {
                    $content .= ' ~ 至今';
                } else {
                    $content .= ' 至 ' . date('Y-m', $info['education_list'][$i]['endtime']);
                }
                $content .= '</span>
                        <p>
                            <span class="border">
                                ' . $info['education_list'][$i]['school'] . '
                            </span>
                            <span class="border">
                                ' . $info['education_list'][$i]['education_text'] . '
                            </span>
                            <span class="border">
                                ' . $info['education_list'][$i]['major'] . '
                            </span>
                        </p>
                    </div>';
            }


            $content .= '</div>
                </div>';
        }

        if ($info['resume_module']['work']['is_display'] === 1 && count($info['work_list']) > 0) {
            $content .= '<div class="bottom-line">
                <div class="title">
                    工作经历
                </div>';

            for ($i = 0; $i < count($info['work_list']); $i++) {
                $content .= '<div class="undergo_con">
                    <div class="undergo_title">
                        <span>
                            ' . date('Y-m', $info['work_list'][$i]['starttime']);
                if ($info['work_list'][$i]['todate'] === 1) {
                    $content .= ' ~ 至今';
                } else {
                    $content .= ' 至 ' . date('Y-m', $info['work_list'][$i]['endtime']);
                }

                $info['work_list'][$i]['duty'] = json_encode($info['work_list'][$i]['duty'], JSON_UNESCAPED_UNICODE);
                $info['work_list'][$i]['duty'] = json_decode(str_replace('\n', '<br>', $info['work_list'][$i]['duty']), true);
                $content .= '</span>
                        <p>
                            <span class="border">
                                ' . $info['work_list'][$i]['companyname'] . '
                            </span>
                            <span class="border">
                                ' . $info['work_list'][$i]['jobname'] . '
                            </span>
                        </p>
                    </div>
                    <div class="undergo_list" style="margin-top: -10px;">
                        <p>
                            <span>
                                工作内容：
                            </span>
                            ' . $info['work_list'][$i]['duty'] . '
                        </p>
                    </div>
                </div>';
            }

            $content .= '</div>';

        }

        if ($info['resume_module']['project']['is_display'] === 1 && count($info['project_list']) > 0) {
            $content .= '<div class="bottom-line">
                <div class="title">
                    项目经历
                </div>';


            for ($i = 0; $i < count($info['project_list']); $i++) {
                $content .= '<div class="undergo_con">
                    <div class="undergo_title">
                        <span>' . date('Y-m', $info['project_list'][$i]['starttime']);
                if ($info['project_list'][$i]['todate'] === 1) {
                    $content .= ' ~ 至今';
                } else {
                    $content .= ' 至 ' . date('Y-m', $info['project_list'][$i]['endtime']);
                }

                $info['project_list'][$i]['description'] = json_encode($info['project_list'][$i]['description'], JSON_UNESCAPED_UNICODE);
                $info['project_list'][$i]['description'] = json_decode(str_replace('\n', '<br>', $info['project_list'][$i]['description']), true);
                $content .= '</span>
                        <p>
                            <span class="border">
                                ' . $info['project_list'][$i]['projectname'] . '
                            </span>
                            <span class="border">
                                ' . $info['project_list'][$i]['role'] . '
                            </span>
                        </p>
                    </div>
                    <div class="undergo_list" style="margin-top: -10px;">
                        <p>
                            <span>
                                项目描述：
                            </span>
                            ' . $info['project_list'][$i]['description'] . '
                        </p>
                    </div>
                </div>';
            }
            $content .= '</div>';
        }

        if ($info['resume_module']['training']['is_display'] === 1 && count($info['training_list']) > 0) {
            $content .= '<div class="bottom-line">
                <div class="title">
                    培训经历
                </div>';

            for ($i = 0; $i < count($info['training_list']); $i++) {
                $content .= '<div class="undergo_con">
                        <div class="undergo_title">
                            <span>' . date('Y-m', $info['training_list'][$i]['starttime']);
                if ($info['training_list'][$i]['todate'] === 1) {
                    $content .= ' ~ 至今';
                } else {
                    $content .= ' 至 ' . date('Y-m', $info['training_list'][$i]['endtime']);
                }
                $info['training_list'][$i]['description'] = json_encode($info['training_list'][$i]['description'], JSON_UNESCAPED_UNICODE);
                $info['training_list'][$i]['description'] = json_decode(str_replace('\n', '<br>', $info['training_list'][$i]['description']), true);
                $content .= '</span>
                            <p>
                                <span class="border">
                                    ' . $info['training_list'][$i]['agency'] . '
                                </span>
                                <span class="border">
                                    ' . $info['training_list'][$i]['course'] . '
                                </span>
                            </p>
                        </div>
                        <div class="undergo_list" style="margin-top: -10px;">
                            <p>
                                <span>
                                    培训内容：
                                </span>
                                ' . $info['training_list'][$i]['description'] . '
                            </p>
                        </div>
                    </div>';
            }


            $content .= '</div>';
        }

        if ($info['resume_module']['certificate']['is_display'] === 1 && count($info['certificate_list']) > 0) {
            $content .= '<div class="certificate bottom-line">
                <div class="title">
                    获得证书
                </div>
                <div class="certificate_list">';

            for ($i = 0; $i < count($info['certificate_list']); $i++) {
                $content .= '
                        <p>
                            <span class="li-item">
                                <span class="border2">
                                    ' . $info['certificate_list'][$i]['name'] . '
                                </span>
                                <span class="border2">
                                        | ' . date('Y-m', $info['certificate_list'][$i]['obtaintime']) . '
                                </span>
                            </span>
                        </p>';
            }

            $content .= '</div></div>';
        }

        if ($info['resume_module']['language']['is_display'] === 1 && count($info['language_list']) > 0) {
            $content .= '<div class="language bottom-line">
                <div class="title">
                    语言能力
                </div>
                <div class="language_list">';

            for ($i = 0; $i < count($info['language_list']); $i++) {
                $content .= '
                            <p><span class="li-item">
                                <span class="border2">
                                    ' . $info['language_list'][$i]['language_text'] . '
                                </span>
                                <span class="border2">
                                        |
                                    ' . $info['language_list'][$i]['level_text'] . '
                                </span>
                            </span></p>';
            }

            $content .= '</div></div>';
        }

        $content .= '</div>';


        $api = new PdfService();
        //生成pdf

        $pdf_path = 'resumePdf' . DS . date('Ym') . DS;
        $upload_path = SYS_UPLOAD_PATH . $pdf_path;
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
            chmod($upload_path, 0777);
        }

        $save_anme = $info['base_info']['fullname'] . '的个人简历-' . config('global_config.sitename');
        $file_name = $upload_path . $save_anme;

        $css = file_get_contents(PUBLIC_PATH . 'assets/css/download_resume.css');
        $css = '<style>' . $css . '</style>';
        $content = $css . html_entity_decode($content);

        $sitename = config('global_config.sitename') . '(' . config('global_config.sitedomain') . ')';
        $logo = config('global_config.logo');
        $logo_url = !empty($logo) ? model('Uploadfile')->getFileUrl($logo) : '';

        $data = [
            'file_name' => $file_name,
            'content' => $content,
            'title_header' => $logo_url,
            'title_footer' => $sitename,
            'is_down' => 1
        ];

        $api->strToPdf($data);

        $url = config('global_config.sitedomain') .
            config('global_config.sitedir') .
            SYS_UPLOAD_DIR_NAME .
            DS .
            $pdf_path .
            $save_anme . '.pdf';

        $this->ajaxReturn(200, '获取成功', ['url' => $url]);
    }
}
