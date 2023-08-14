<?php
namespace app\index\controller;

class Index extends \app\index\controller\Base
{
    public function index()
    {
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain'),302);
            exit;
        }
        //读取页面缓存配置
        $pageCache = model('Page')->getCache('index');
        //如果缓存有效期为0，则不使用缓存
        if($pageCache['expire']>0){
            $return = model('Page')->getCacheByAlias('index');
        }else{
            $return = false;
        }
        if (!$return) {
            $return = $this->writeShowCache($pageCache);
        }
        foreach ($return as $key => $value) {
            $this->assign($key,$value);
        }
        $this->initPageSeo('index');
        $this->assign('pageHeader',$this->pageHeader);
        $this->assign('navSelTag','index');
        return $this->fetch('index');
    }
    protected function writeShowCache($pageCache){
        $return['category_jobs'] = $this->getCategoryJobs();
        $return['notice_list'] = $this->getNoticeList();
        $return['event_list'] = $this->getEventList();
        $return['tag_list'] = $this->getTagList();
        $return['new_today_list'] = $this->getNewTodayList();
        $return['emergency_list'] = $this->getEmergencyList();
        $return['hotword_list'] = $this->getHotwordList();
        $return['famous_list'] = $this->getFamousList();
        $return['hotjob_list'] = $this->getHotjobList();
        $return['company_list'] = $this->getCompanyList();
        $return['resume_list'] = $this->getResumeList();
        $return['article_list'] = $this->getArticleList();
        $return['hrtool_list'] = $this->getHrtoolList();
        $return['flink_list'] = $this->getFlinkList();
        $return['banner_list'] = $this->getBannerList();
        if($pageCache['expire']>0){
            model('Page')->writeCacheByAlias('index',$return,$pageCache['expire']);
        }
        return $return;
    }
    /**
     * 获取职位分类
     */
    protected function getCategoryJobs(){
        $list = model('CategoryJob')->getCache('');
        return $list;
    }
    /**
     * 公告列表
     */
    protected function getNoticeList(){
        $list = model('Notice')->where('is_display',1)->order('sort_id desc,id desc')->limit(2)->column('id,title,link_url');
        foreach ($list as $key => $value) {
            $list[$key]['link_url'] = $value['link_url']==''?url('index/notice/show',['id'=>$value['id']]):$value['link_url'];
            $list[$key]['target'] = $value['link_url']==''?'_self':'_blank';
        }
        return $list;
    }
    /**
     * 动态列表
     */
    protected function getEventList(){
        //发布职位（包含刷新职位）
        $list1 = model('JobSearchRtime')->alias('a')->join(config('database.prefix').'job b','a.id=b.id','LEFT')->join(config('database.prefix').'company c','a.uid=c.uid','LEFT')->where('c.id','not null')->order('a.refreshtime desc')->limit(15)->column('a.refreshtime,a.id,a.company_id,b.jobname,c.companyname','a.id');
        //申请职位
        $list2 = model('JobApply')->alias('a')->join(config('database.prefix').'resume b','a.resume_id=b.id','LEFT')->order('a.id desc')->limit(15)->column('a.addtime,a.resume_id,a.jobid,a.jobname,b.fullname,b.sex,b.display_name','a.id');
        //刷新简历
        $list3 = model('ResumeSearchRtime')->alias('a')->join(config('database.prefix').'resume b','a.id=b.id','LEFT')->order('a.refreshtime desc')->where('addtime','egt',strtotime('-1 hour'))->limit(15)->column('a.refreshtime,a.id,b.fullname,b.sex,b.display_name','a.id');
        $list = [];
        foreach ($list1 as $key => $value) {
            $arr = [];
            $arr['type'] = 'jobadd';
            $arr['job_url'] = url('index/job/show',['id'=>$value['id']]);
            $arr['jobname'] = $value['jobname'];
            $arr['company_url'] = url('index/company/show',['id'=>$value['company_id']]);
            $arr['companyname'] = $value['companyname'];
            $arr['time'] = $value['refreshtime'];
            $list[] = $arr;
        }
        foreach ($list2 as $key => $value) {
            $arr = [];
            $arr['type'] = 'jobapply';
            $arr['job_url'] = url('index/job/show',['id'=>$value['jobid']]);
            $arr['jobname'] = $value['jobname'];
            $arr['resume_url'] = url('index/resume/show',['id'=>$value['resume_id']]);
            $arr['fullname'] = $value['fullname'];
            if ($value['display_name'] == 0) {
                if ($value['sex'] == 1) {
                    $arr['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '先生'
                    );
                } elseif ($value['sex'] == 2) {
                    $arr['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '女士'
                    );
                } else {
                    $arr['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '**'
                    );
                }
            }
            $arr['time'] = $value['addtime'];
            $list[] = $arr;
        }
        foreach ($list3 as $key => $value) {
            $arr = [];
            $arr['type'] = 'resume_refresh';
            $arr['resume_url'] = url('index/resume/show',['id'=>$value['id']]);
            $arr['fullname'] = $value['fullname'];
            if ($value['display_name'] == 0) {
                if ($value['sex'] == 1) {
                    $arr['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '先生'
                    );
                } elseif ($value['sex'] == 2) {
                    $arr['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '女士'
                    );
                } else {
                    $arr['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '**'
                    );
                }
            }
            $arr['time'] = $value['refreshtime'];
            $list[] = $arr;
        }
        $sortArr = array_column($list, 'time');
        array_multisort($sortArr, SORT_DESC, $list);
        $list = array_slice($list,0,15);
        return $list;
    }
    /**
     * 职位福利
     */
    protected function getTagList(){
        $list = [];
        $data = model('Category')->getCache('QS_jobtag');
        $counter = 0;
        foreach ($data as $key => $value) {
            if($counter==12){
                break;
            }
            $list[] = ['id'=>$key,'name'=>$value];
            $counter++;
        }
        return $list;
    }
    /**
     * 今日更新
     */
    protected function getNewTodayList(){
        $companyList = model('Company')->alias('a')
                    ->join(config('database.prefix').'job_search_rtime b','a.uid=b.uid','LEFT')
                    ->where('b.id','not null')
                    ->order('a.refreshtime desc')
                    ->limit(8)
                    ->distinct('a.id')
                    ->column('a.id,a.companyname,a.audit,a.setmeal_id,a.refreshtime','a.id');
        $company_id_arr = $setmeal_id_arr = $setmeal_list = [];
        foreach ($companyList as $key => $value) {
            $company_id_arr[] = $value['id'];
            $setmeal_id_arr[] = $value['setmeal_id'];
        }
        if (!empty($setmeal_id_arr)) {
            $setmeal_list = model('Setmeal')
                ->where('id', 'in', $setmeal_id_arr)
                ->column('id,icon,name', 'id');
        }
        $company_job_arr = [];
        if(!empty($company_id_arr)){
            $jobAll = model('Job')->whereIn('company_id',$company_id_arr)->where('audit',1)->where('is_display',1)->column('id,jobname,company_id');
            foreach ($jobAll as $key => $value) {
                if(isset($company_job_arr[$value['company_id']]) && count($company_job_arr[$value['company_id']])>=2){
                    continue;
                }
                $company_job_arr[$value['company_id']][] = $value;
            }
        }
        $list = [];
        foreach ($companyList as $key => $value) {
            $arr = [];
            $arr['id'] = $value['id'];
            $arr['companyname'] = $value['companyname'];
            $arr['audit'] = $value['audit'];
            if (isset($setmeal_list[$value['setmeal_id']])) {
                $arr['setmeal_icon'] =
                    $setmeal_list[$value['setmeal_id']]['icon'] > 0
                        ? model('Uploadfile')->getFileUrl(
                            $setmeal_list[$value['setmeal_id']]['icon']
                        )
                        : model('Setmeal')->getSysIcon($value['setmeal_id']);
            } else {
                $arr['setmeal_icon'] = '';
            }
            $arr['joblist'] = isset($company_job_arr[$value['id']])?$company_job_arr[$value['id']]:[];
            $list[] = $arr;
        }
        return $list;
    }
    protected function getEmergencyList(){
        $list = model('JobSearchRtime')->alias('a')
                ->join(config('database.prefix').'job b','a.id=b.id','LEFT')
                ->join(config('database.prefix').'company c','a.uid=c.uid','LEFT')
                ->where('a.emergency',1)
                ->where('c.id','not null')
                ->order('a.refreshtime desc')
                ->limit(5)
                ->column('b.id,b.emergency,b.jobname,b.negotiable,b.minwage,b.maxwage,b.company_id,c.companyname');
        foreach ($list as $key => $value) {
            $arr = $value;
            $arr['wage_text'] = model('BaseModel')->handle_wage(
                $arr['minwage'],
                $arr['maxwage'],
                $arr['negotiable']
            );
            $list[$key] = $arr;
        }
        return $list;
    }
    /**
     * 热门关键字
     */
    protected function getHotwordList(){
        $list = model('Hotword')->getList(15);
        return $list;
    }
    /**
     * 优选职位
     */
    protected function getFamousList(){
        $famous_enterprises_setmeal = config(
            'global_config.famous_enterprises'
        );
        $famous_enterprises_setmeal =
            $famous_enterprises_setmeal == ''
                ? []
                : explode(',', $famous_enterprises_setmeal);
        $list = [];
        if (!empty($famous_enterprises_setmeal)) {
            $list = model('JobSearchRtime')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'job b',
                    'a.id=b.id',
                    'LEFT'
                )
                ->join(
                    config('database.prefix') . 'company c',
                    'a.uid=c.uid',
                    'LEFT'
                )
                ->join(
                    config('database.prefix') . 'setmeal d',
                    'a.setmeal_id=d.id',
                    'LEFT'
                )
                ->where('c.id','not null')
                ->where('a.setmeal_id', 'in', $famous_enterprises_setmeal)
                ->order('a.refreshtime desc')
                ->limit(15)
                ->column('b.id,b.addtime,b.jobname,b.refreshtime,b.district,b.education,b.experience,b.negotiable,b.minwage,b.maxwage,b.tag,b.setmeal_id,b.company_id,c.companyname,c.audit as company_audit,d.icon');
            $comid_arr = $companyList = $icon_id_arr = $icon_arr = [];
            foreach ($list as $key => $value) {
                $comid_arr[] = $value['id'];
                $value['icon'] > 0 && ($icon_id_arr[] = $value['icon']);
            }
            if (!empty($icon_id_arr)) {
                $icon_arr = model('Uploadfile')->getFileUrlBatch(
                    $icon_id_arr
                );
            }
            $category_data = model('Category')->getCache();
            $category_district_data = model('CategoryDistrict')->getCache();
            foreach ($list as $key => $value) {
                $arr = $value;
                if ($arr['district']) {
                    $arr['district_text'] = isset(
                        $category_district_data[$arr['district']]
                    )
                    ? $category_district_data[$arr['district']]
                    : '';
                } else {
                    $arr['district_text'] = '';
                }
                $arr['wage_text'] = model('BaseModel')->handle_wage(
                    $arr['minwage'],
                    $arr['maxwage'],
                    $arr['negotiable']
                );

                $arr['education_text'] = isset(
                    model('BaseModel')->map_education[$arr['education']]
                )
                ? model('BaseModel')->map_education[$arr['education']]
                : '学历不限';
                $arr['experience_text'] = isset(
                    model('BaseModel')->map_experience[$arr['experience']]
                )
                ? model('BaseModel')->map_experience[$arr['experience']]
                : '经验不限';
                $arr['refreshtime'] = daterange_format(
                    $arr['addtime'],
                    $arr['refreshtime']
                );
                $arr['tag_arr'] = [];
                if ($arr['tag']) {
                    $counter = 0;
                    $tag_arr = explode(',', $arr['tag']);
                    foreach ($tag_arr as $k => $v) {
                        if($counter>=4){
                            break;
                        }
                        $counter++;
                        if (
                            is_numeric($v) &&
                            isset($category_data['QS_jobtag'][$v])
                        ) {
                            $arr['tag_arr'][] = $category_data['QS_jobtag'][$v];
                        } else {
                            $arr['tag_arr'][] = $v;
                        }
                    }
                }else{
                    $arr['tag_arr'] = [];
                }
                $arr['setmeal_icon'] = isset($icon_arr[$arr['icon']]) ? $icon_arr[$arr['icon']] : model('Setmeal')->getSysIcon($arr['setmeal_id']);
                $list[$key] = $arr;
            }
        }
        return $list;
    }
    /**
     * 热门职位
     */
    protected function getHotjobList(){
        $list = model('JobSearchRtime')->alias('a')
                ->join(config('database.prefix').'job b','a.id=b.id','LEFT')
                ->join(config('database.prefix').'company c','a.uid=c.uid','LEFT')
                ->where('c.id','not null')
                ->order('b.click desc,a.refreshtime desc')
                ->limit(10)
                ->column('b.id,b.jobname,b.district,b.negotiable,b.minwage,b.maxwage,b.company_id,c.companyname');
        $category_district_data = model('CategoryDistrict')->getCache();
        foreach ($list as $key => $value) {
            $arr = $value;
            $arr['jobname'] = cut_str($arr['jobname'],16,0,'...');
            $arr['wage_text'] = model('BaseModel')->handle_wage(
                $arr['minwage'],
                $arr['maxwage'],
                $arr['negotiable']
            );
            if ($arr['district']) {
                $arr['district_text'] = isset(
                    $category_district_data[$arr['district']]
                )
                ? $category_district_data[$arr['district']]
                : '';
            } else {
                $arr['district_text'] = '';
            }
            $list[$key] = $arr;
        }
        return $list;
    }
    /**
     * 企业主页
     */
    protected function getCompanyList(){
        $list = model('Company')->where('district1','gt',0)->order('refreshtime desc')->limit(9)->column('id,logo,companyname');
        $logo_arr = $logo_id_arr = [];
        foreach ($list as $key => $value) {
            $value['logo'] > 0 && ($logo_id_arr[] = $value['logo']);
        }
        if (!empty($logo_id_arr)) {
            $logo_arr = model('Uploadfile')->getFileUrlBatch($logo_id_arr);
        }
        foreach ($list as $key => $value) {
            $arr = $value;
            $arr['logo_src'] = isset($logo_arr[$arr['logo']])
                ? $logo_arr[$arr['logo']]
                : default_empty('logo');
            $list[$key] = $arr;
        }
        return $list;
    }
    /**
     * 优选人才
     */
    protected function getResumeList(){
        
        $list = model('ResumeSearchRtime')->alias('a')
                ->join(config('database.prefix').'resume b','a.id=b.id','LEFT');
        if ($this->visitor!==null && $this->visitor['utype'] == 1) {
            $shield_find = model('Shield')
                ->where('company_uid', $this->visitor['uid'])
                ->find();
            if ($shield_find !== null) {
                $list = $list->join(config('database.prefix') . 'shield c','a.uid=c.personal_uid','LEFT')
                    ->where('c.company_uid<>' . $this->visitor['uid'] . ' OR c.id is NULL');
            }
        }
        $list = $list->order('a.refreshtime desc')
                ->limit(12)
                ->column('b.id,b.stick,b.high_quality,b.service_tag,b.photo_img,b.fullname,b.display_name,b.sex,b.birthday,b.education,b.enter_job_time');
        $resume_id_arr = $photo_arr = $photo_id_arr = [];
        foreach ($list as $key => $value) {
            $resume_id_arr[] = $value['id'];
            $value['photo_img'] > 0 && ($photo_id_arr[] = $value['photo_img']);
        }
        if(!empty($resume_id_arr)){
            if (!empty($photo_id_arr)) {
                $photo_arr = model('Uploadfile')->getFileUrlBatch(
                    $photo_id_arr
                );
            }
            $category_job_data = model('CategoryJob')->getCache();
            $category_district_data = model('CategoryDistrict')->getCache();
            $intention_data = model('ResumeIntention')
                ->where('rid', 'in', $resume_id_arr)
                ->order('id asc')
                ->select();
            $intention_arr = [];
            foreach ($intention_data as $key => $value) {
                $intention_arr[$value['rid']][] = $value;
            }
            foreach ($list as $key => $value) {
                $arr = $value;
                if ($arr['display_name'] == 0) {
                    if ($arr['sex'] == 1) {
                        $arr['fullname'] = cut_str(
                            $arr['fullname'],
                            1,
                            0,
                            '先生'
                        );
                    } elseif ($arr['sex'] == 2) {
                        $arr['fullname'] = cut_str(
                            $arr['fullname'],
                            1,
                            0,
                            '女士'
                        );
                    } else {
                        $arr['fullname'] = cut_str(
                            $arr['fullname'],
                            1,
                            0,
                            '**'
                        );
                    }
                }
                $arr['photo_img_src'] = isset($photo_arr[$arr['photo_img']])
                    ? $photo_arr[$arr['photo_img']]
                    : default_empty('photo');
                $arr['sex_text'] = model('Resume')->map_sex[$arr['sex']];
                $arr['age_text'] = date('Y') - intval($arr['birthday']);
                $arr['education_text'] = isset(
                    model('BaseModel')->map_education[$arr['education']]
                )
                    ? model('BaseModel')->map_education[$arr['education']]
                    : '';
    
                $arr['experience_text'] =
                    $arr['enter_job_time'] == 0
                        ? '尚未工作'
                        : format_date($arr['enter_job_time']);
    
                //求职意向
                $district_arr = $category_arr = [];
                if (isset($intention_arr[$arr['id']])) {
                    foreach ($intention_arr[$arr['id']] as $k => $v) {
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
                    $arr['intention_district'] = implode(
                        ',',
                        $district_arr
                    );
                } else {
                    $arr['intention_district'] = '';
                }
    
                $list[$key] = $arr;
            }
        }
        return $list;
    }
    /**
     * 职场资讯
     */
    protected function getArticleList(){
        $list = model('Article')
            ->alias('a')
            ->join(config('database.prefix').'article_category b','a.cid=b.id','LEFT')
            ->where('a.is_display', 1)
            ->limit(16)
            ->order('a.sort_id desc,a.id desc')
            ->column('a.id,a.title,a.link_url,a.addtime,a.cid,b.name as cname');
        foreach ($list as $key => $value) {
            $arr = $value;
            $arr['link_url'] = $arr['link_url']==''?url('index/article/show',['id'=>$arr['id']]):$arr['link_url'];
            $list[$key] = $arr;
        }
        return $list;
    }
    /**
     * hr工具箱
     */
    protected function getHrtoolList(){
        $list = model('Hrtool')->order('sort_id desc')->limit(6)->column('id,cid,filename');
        return $list;
    }
    /**
     * 友情链接
     */
    protected function getFlinkList(){
        $list = model('Link')->order('sort_id desc')->where('is_display',1)->column('id,name,link_url');
        return $list;
    }
    /**
     * 广告
     */
    protected function getBannerList(){
        $alias_arr = [
            'QS_index_a1@web',
            'QS_index_a2@web',
            'QS_index_a3@web',
            'QS_index_a4@web',
            'QS_index_a5@web',
            'QS_index_a6@web',
            'QS_index_a7@web',
            'QS_index_a8@web',
            'QS_index_a9@web',
            'QS_index_a10@web',
            'QS_index_a11@web',
            'QS_index_a12@web'
        ];
        $category_arr = model('AdCategory')->whereIn('alias', $alias_arr)->column('id,alias,ad_num', 'id');
        $cid_arr = [];
        foreach ($category_arr as $key => $value) {
            $cid_arr[] = $value['id'];
        }
        $timestamp = time();
        $dataset = model('Ad')
            ->where('is_display', 1)
            ->whereIn('cid', $cid_arr)
            ->where('starttime', '<=', $timestamp)
            ->where(function ($query) use ($timestamp) {
                $query->where('deadline', '>=', $timestamp)->whereOr('deadline', 0);
            })
            ->order('sort_id desc,id desc')
            ->column('id,cid,title,imageid,imageurl,target,link_url,inner_link,inner_link_params,company_id');
        $image_id_arr = $image_arr = [];
        foreach ($dataset as $key => $value) {
            $arr = $value;
            $arr['imageid'] > 0 && ($image_id_arr[] = $arr['imageid']);
            $dataset[$key] = $arr;
        }
        if (!empty($image_id_arr)) {
            $image_arr = model('Uploadfile')->getFileUrlBatch($image_id_arr);
        }
        $list = [];
        $allCompanyId = [];
        foreach ($dataset as $key => $value) {
            $value['image_src'] = isset($image_arr[$value['imageid']]) ? $image_arr[$value['imageid']] : $value['imageurl'];
            if (isset($list[$category_arr[$value['cid']]['alias']]) && count($list[$category_arr[$value['cid']]['alias']]) >= $category_arr[$value['cid']]['ad_num']) {
                continue;
            }
            $arr = [];
            $arr['title'] = $value['title'];
            $arr['image_src'] = $value['image_src'];
            $arr['link_url'] = $value['link_url'];
            $arr['inner_link'] = $value['inner_link'];
            $arr['inner_link_params'] = $value['inner_link_params'];
            $arr['company_id'] = $value['company_id'];
            $arr['companyname'] = '';
            $arr['joblist'] = [];
            $arr['jobnum'] = 0;
            $arr['web_link_url'] = model('Ad')->handlerWebLink($value);
            $list[$category_arr[$value['cid']]['alias']][] = $arr;
            $value['company_id'] && $allCompanyId[] = $value['company_id'];
        }
        foreach ($category_arr as $key => $value) {
            if (!isset($list[$value['alias']])) {
                $list[$value['alias']] = [];
            }
        }
        $allCompanyId = array_unique($allCompanyId);
        //获取广告位中涉及的所有企业信息
        $all_company_arr = $this->getAllCompany($allCompanyId);
        //获取广告位中涉及的所有职位信息
        $all_job_arr = $this->getAllJob($allCompanyId);
        //处理a2广告
        $list['QS_index_a2@web'] = $this->handlerA2($list['QS_index_a2@web'],$all_company_arr,$all_job_arr);
        //处理a3广告
        $list['QS_index_a3@web'] = $this->handlerA3($list['QS_index_a3@web'],$all_company_arr,$all_job_arr);
        //处理a8广告
        $list['QS_index_a8@web'] = $this->handlerA8($list['QS_index_a8@web'],$all_job_arr);
        //处理a9广告
        $list['QS_index_a9@web'] = $this->handlerA9($list['QS_index_a9@web'],$all_job_arr);
        //处理a11广告
        $list['QS_index_a11@web'] = $this->handlerA11($list['QS_index_a11@web'],$all_company_arr);
        return $list;
    }
    /**
     * 获取广告位中涉及的所有企业信息
     */
    protected function getAllCompany($allCompanyId){
        $companyData = [];
        if(!empty($allCompanyId)){
            $companyData = model('Company')->whereIn('id',$allCompanyId)->column('id,companyname');
        }
        return $companyData;
    }
    /**
     * 获取广告位中涉及的所有职位信息
     */
    protected function getAllJob($allCompanyId){
        $jobData = model('JobSearchRtime')
                ->alias('a')
                ->join(config('database.prefix').'job b','a.id=b.id','LEFT')
                ->whereIn('a.company_id',$allCompanyId)
                ->column('a.id,b.jobname,a.company_id');
        return $jobData;
    }
    /**
     * 处理a2广告
     */
    protected function handlerA2($list,$all_company_arr,$all_job_arr){
        $job_list = [];
        $counter = 0;
        foreach ($all_job_arr as $key => $value) {
            if($counter>=4){
                continue;
            }
            $job_list[$value['company_id']][] = $value;
            $counter++;
        }
        foreach ($list as $key => $value) {
            $list[$key]['companyname'] = isset($all_company_arr[$value['company_id']])?$all_company_arr[$value['company_id']]:$value['title'];
            $list[$key]['joblist'] = isset($job_list[$value['company_id']])?$job_list[$value['company_id']]:'';
        }
        return $list;
    }
    /**
     * 处理a3广告
     */
    protected function handlerA3($list,$all_company_arr,$all_job_arr){
        $job_list = [];
        foreach ($all_job_arr as $key => $value) {
            $job_list[$value['company_id']][] = $value;
        }
        foreach ($list as $key => $value) {
            $list[$key]['companyname'] = isset($all_company_arr[$value['company_id']])?$all_company_arr[$value['company_id']]:$value['title'];
            $list[$key]['jobnum'] = isset($job_list[$value['company_id']])?count($job_list[$value['company_id']]):0;
        }
        return $list;
    }
    /**
     * 处理a8广告
     */
    protected function handlerA8($list,$all_job_arr){
        $job_list = [];
        $counter = 0;
        foreach ($all_job_arr as $key => $value) {
            if($counter>=3){
                continue;
            }
            $job_list[$value['company_id']][] = $value;
            $counter++;
        }
        foreach ($list as $key => $value) {
            $list[$key]['joblist'] = isset($job_list[$value['company_id']])?$job_list[$value['company_id']]:'';
        }
        return $list;
    }
    /**
     * 处理a9广告
     */
    protected function handlerA9($list,$all_job_arr){
        $job_list = [];
        $counter = 0;
        foreach ($all_job_arr as $key => $value) {
            if($counter>=3){
                continue;
            }
            $job_list[$value['company_id']][] = $value;
            $counter++;
        }
        foreach ($list as $key => $value) {
            $list[$key]['joblist'] = isset($job_list[$value['company_id']])?$job_list[$value['company_id']]:'';
        }
        return $list;
    }
    /**
     * 处理a11广告
     */
    protected function handlerA11($list,$all_company_arr){
        foreach ($list as $key => $value) {
            $list[$key]['companyname'] = isset($all_company_arr[$value['company_id']])?$all_company_arr[$value['company_id']]:$value['title'];
        }
        return $list;
    }
}
