<?php
namespace app\index\controller;
use app\common\lib\Pager;
class Jobfairol extends \app\index\controller\Base{
    public function _initialize(){
        parent::_initialize();
        $this->assign('navSelTag','jobfairol');
    }
    /**
     * [index 网络招聘会列表页]
     */
    public function index()
    {
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'jobfairol',302);
            exit;
        }
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $timestamp = time();
        $field =
            'id,title,thumb,starttime,endtime,click,addtime,CASE 
        WHEN starttime<=' .
            $timestamp .
            ' AND endtime>'.$timestamp.' THEN 2
        WHEN starttime>' .
            $timestamp .
            ' THEN 1
        ELSE 0
        END AS score';
        $list = model('JobfairOnline')->field($field)->order('score desc')->page($current_page, $pagesize)->select();
        $participate_company = $participate_personal = $jobfair_id_arr = $thumb_arr = $thumb_id_arr = [];
        foreach ($list as $key => $value) {
            $jobfair_id_arr[] = $value['id'];
            $value['thumb'] > 0 && ($thumb_id_arr[] = $value['thumb']);
        }
        if (!empty($thumb_id_arr)) {
            $thumb_arr = model('Uploadfile')->getFileUrlBatch($thumb_id_arr);
        }
        if (!empty($jobfair_id_arr)) {
            $participate_company = model('JobfairOnlineParticipate')
                ->where('jobfair_id','in',$jobfair_id_arr)
                ->where('utype',1)
                ->where('audit',1)
                ->group('jobfair_id')
                ->column('jobfair_id,count(id)');
            $participate_personal = model('JobfairOnlineParticipate')
                ->alias('a')
                ->join(config('database.prefix') . 'resume_search_rtime b', 'a.uid=b.uid', 'left')
                ->where('a.jobfair_id','in',$jobfair_id_arr)
                ->where('a.utype',2)
                ->where('a.audit',1)
                ->where('b.id','not null')
                ->group('a.jobfair_id')
                ->column('a.jobfair_id,count(a.id)');
        }
        $olist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['title'] = $value['title'];
            $tmp_arr['thumb_src'] = isset($thumb_arr[$value['thumb']])?$thumb_arr[$value['thumb']]:default_empty('jobfair_thumb');
            $tmp_arr['starttime'] = $value['starttime'];
            $tmp_arr['endtime'] = $value['endtime'];
            $tmp_arr['click'] = $value['click'];
            $tmp_arr['score'] = $value['score'];
            $tmp_arr['total_company'] = isset($participate_company[$value['id']])?$participate_company[$value['id']]:0;
            $tmp_arr['total_personal'] = isset($participate_personal[$value['id']])?$participate_personal[$value['id']]:0;
            $olist[] = $tmp_arr;
        }
        $time = time();
        $s_where = ['starttime'=>['gt',$time]];
        $e_where = ['starttime'=>['elt',$time]];
        $s_count = model('JobfairOnline')->where($s_where)->count();
        $e_count = model('JobfairOnline')->where($e_where)->count();
        $total = $s_count + $e_count;
        $pagination = Pager::make($list,$pagesize,$current_page,$total,false,['path'=>'']);
        $this->initPageSeo('jobfairollist');
        $this->assign('list',$olist);
        $this->assign('pageHeader',$this->pageHeader);
        $this->assign('pagerHtml',$pagerHtml = $pagination->render());
        return $this->fetch('index');
    }
    /**
     * 招聘会详情
     */
    public function show(){
        $id = request()->route('id/d',0,'intval');
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'jobfairol/'.$id,302);
            exit;
        }
        if(!$id){
            $this->ajaxReturn(500,'请选择网络招聘会');
        }
        $info = model('JobfairOnline')->field('id,title,click,content,starttime,endtime,pc_header_logo')->where('id',$id)->find();
        if(null === $info){
            $this->ajaxReturn(500,'请选择网络招聘会');
        }
        $info = $info->toArray();
        $info['content'] = strip_tags(htmlspecialchars_decode($info['content'],ENT_QUOTES));
        $timestamp = time();
        if ($info['starttime'] <= $timestamp && $info['endtime'] > $timestamp) {
            $info['score'] = 2;
        } else if ($info['starttime'] > $timestamp) {
            $info['score'] = 1;
        } else {
            $info['score'] = 0;
        }
        $info['total_company'] = model('JobfairOnlineParticipate')
            ->where('jobfair_id',$id)
            ->where('utype',1)
            ->where('audit',1)
            ->count();
        $info['total_job'] = model('Job')
            ->alias('a')
            ->join(config('database.prefix') . 'jobfair_online_participate b', 'a.uid=b.uid', 'left')
            ->where('b.jobfair_id',$id)
            ->where('b.utype',1)
            ->where('b.audit',1)
            ->where('a.is_display', 1)
            ->where('a.audit',1)
            ->count();

        $imgs = $imageUrl = [];

        if($info['pc_header_logo']) $imgs[] = $info['pc_header_logo'];
        if(!empty($imgs)){
            $imageUrl = model('Uploadfile')->getFileUrlBatch($imgs);
        }
        $info['banner'] = isset($imageUrl[$info['pc_header_logo']])?$imageUrl[$info['pc_header_logo']]:'';
        $this->logAdd();

        $info['log'] = $this->getLog();
        model('JobfairOnline')->where('id',$id)->setInc('click',1);
        $this->initPageSeo('jobfairolshow',['title'=>$info['title']]);
        $this->assign('pageHeader',$this->pageHeader);
        $this->assign('info',$info);
        return $this->fetch('show');
    }

    /**
     * 网络招聘会详情
     */
    public function details(){
        $id = request()->route('id/d',0,'intval');
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'jobfairol/'.$id,302);
            exit;
        }
        if(!$id){
            $this->ajaxReturn(500,'请选择网络招聘会');
        }
        $info = model('JobfairOnline')->field('id,title,click,content,starttime,endtime,pc_header_logo,addtime')->where('id',$id)->find();

        if(null === $info){
            $this->ajaxReturn(500,'请选择网络招聘会');
        }
        $info = $info->toArray();
        $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);
        $timestamp = time();

        if ($info['starttime'] <= $timestamp && $info['endtime'] > $timestamp) {
            $info['score'] = 2;
        } else if ($info['starttime'] > $timestamp) {
            $info['score'] = 1;
        } else {
            $info['score'] = 0;
        }
        $info['total_company'] = model('JobfairOnlineParticipate')
            ->where('jobfair_id',$id)
            ->where('utype',1)
            ->where('audit',1)
            ->count();
        $info['total_job'] = model('Job')
            ->alias('a')
            ->join(config('database.prefix') . 'jobfair_online_participate b', 'a.uid=b.uid', 'left')
            ->where('b.jobfair_id',$id)
            ->where('b.utype',1)
            ->where('b.audit',1)
            ->where('a.is_display', 1)
            ->where('a.audit',1)
            ->count();
        $imgs = $imageUrl = [];

        if($info['pc_header_logo']) $imgs[] = $info['pc_header_logo'];
        if(!empty($imgs)){
            $imageUrl = model('Uploadfile')->getFileUrlBatch($imgs);
        }
        $info['addtime'] = !empty($info['addtime']) ? date('Y-m-d',$info['addtime']):'';
        $info['banner'] = isset($imageUrl[$info['pc_header_logo']])?$imageUrl[$info['pc_header_logo']]:'';
        $info['log'] = $this->getLog();

        model('JobfairOnline')->where('id',$id)->setInc('click',1);
        $this->initPageSeo('jobfairolshow',['title'=>$info['title']]);
        $this->assign('pageHeader',$this->pageHeader);
        $this->assign('info',$info);
        return $this->fetch('details');
    }
    /**
     * 参会企业列表
     */
    public function comlist(){
        $jobfair_id = input('get.jobfair_id/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim,addslashes');
        $page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if(!$jobfair_id){
            $this->ajaxReturn(500,'请选择招聘会');
        }
        $list = model('JobfairOnlineParticipate')
            ->alias('a')
            ->field('a.qrcode,b.*,d.qrcode as wx_qrcode')
            ->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'left')
            ->join(config('database.prefix') . 'jobfair_online d', 'd.id=a.jobfair_id', 'left')
            ->where('a.utype',1)
            ->where('a.audit',1)
            ->where('b.district1','gt',0)
            ->where('b.companyname','not null');
        if($keyword!=''){
            $list = $list->where('b.companyname','like','%'.$keyword.'%');
        }
        $list = $list->order('b.refreshtime desc')->page($page, $pagesize)->select();

        $job_list = $comid_arr = $qrcode_arr = $qrcode_id_arr = $logo_arr = $logo_id_arr = $cs_id_arr = $cs_arr = [];
        foreach ($list as $key => $value) {
            $comid_arr[] = $value['id'];
            $value['logo'] > 0 && ($logo_id_arr[] = $value['logo']);
            if($value['qrcode'] > 0){
                $qrcode_id_arr[] = $value['qrcode'];
            }else if($value['wx_qrcode']>0){
                $cs_id_arr[] = $value['wx_qrcode'];
            }
        }
        if (!empty($cs_id_arr)) {
            $cs_arr = model('Uploadfile')->getFileUrlBatch($cs_id_arr);
        }
        if (!empty($logo_id_arr)) {
            $logo_arr = model('Uploadfile')->getFileUrlBatch($logo_id_arr);
        }
        if (!empty($qrcode_id_arr)) {
            $qrcode_arr = model('Uploadfile')->getFileUrlBatch($qrcode_id_arr);
        }
        if (!empty($comid_arr)) {
            $job_data = model('Job')
                ->where('company_id', 'in', $comid_arr)
                ->where('is_display', 1)
                ->where('audit', 1)
                ->column('id,company_id,jobname,minwage,maxwage,negotiable', 'id');
            foreach ($job_data as $key => $value) {
                if(isset($job_list[$value['company_id']]) && count($job_list[$value['company_id']])>=3){
                    continue;
                }
                $job_tmp_arr = [];
                $job_tmp_arr['id'] = $value['id'];
                $job_tmp_arr['jobname'] = $value['jobname'];
                $job_tmp_arr['wage_text'] = model('BaseModel')->handle_wage(
                    $value['minwage'],
                    $value['maxwage'],
                    $value['negotiable']
                );
                $job_list[$value['company_id']][] = $job_tmp_arr;
            }
        }

        $returnlist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['companyname'] = $value['companyname'];
            if(isset($qrcode_arr[$value['qrcode']])){
                $tmp_arr['qrcode_src'] = $qrcode_arr[$value['qrcode']];
            }else if(isset($cs_arr[$value['wx_qrcode']])){
                $tmp_arr['qrcode_src'] = $cs_arr[$value['wx_qrcode']];
            }else{
                $tmp_arr['qrcode_src'] = '';
            }
            $tmp_arr['logo_src'] = isset($logo_arr[$value['logo']])
                ? $logo_arr[$value['logo']]
                : default_empty('logo');

            $tmp_arr['joblist'] = isset($job_list[$value['id']])
                ? $job_list[$value['id']]
                : [];

            $returnlist[] = $tmp_arr;
        }
        $return['items'] = $returnlist;
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
    /**
     * 职位列表
     */
    public function joblist(){
        $jobfair_id = input('get.jobfair_id/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim,addslashes');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if(!$jobfair_id){
            $this->ajaxReturn(500,'请选择网络招聘会');
        }
        $list = model('JobfairOnlineParticipate')
            ->alias('a')
            ->field('b.*')
            ->join(config('database.prefix') . 'job b', 'a.uid=b.uid', 'right')
            ->where('a.utype',1)
            ->where('a.audit',1)
            ->where('b.is_display',1)
            ->where('b.audit',1);
        if($keyword!=''){
            $list = $list->where('b.jobname','like','%'.$keyword.'%');
        }
        $list = $list->order('b.refreshtime desc')->page($current_page, $pagesize)->select();

        $comid_arr = $cominfo_arr = $logo_arr = $logo_id_arr = $icon_id_arr = $icon_arr = [];

        foreach ($list as $key => $value) {
            $comid_arr[] = $value['company_id'];
        }
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
                    'a.id,a.companyname,a.audit,a.logo,a.setmeal_id,b.icon',
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

        $category_district_data = model('CategoryDistrict')->getCache();
        $returnlist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['jobname'] = $value['jobname'];
            if (isset($cominfo_arr[$value['company_id']])) {
                $tmp_arr['companyname'] =
                    $cominfo_arr[$value['company_id']]['companyname'];
                $tmp_arr['company_audit'] =
                    $cominfo_arr[$value['company_id']]['audit'];
                $tmp_arr['company_logo'] = isset(
                    $logo_arr[$cominfo_arr[$value['company_id']]['logo']]
                )
                    ? $logo_arr[$cominfo_arr[$value['company_id']]['logo']]
                    : default_empty('logo');

                $tmp_arr['setmeal_icon'] = isset(
                    $icon_arr[$cominfo_arr[$value['company_id']]['icon']]
                )
                    ? $icon_arr[$cominfo_arr[$value['company_id']]['icon']]
                    : model('Setmeal')->getSysIcon($value['setmeal_id']);
            } else {
                $tmp_arr['companyname'] = '';
                $tmp_arr['company_audit'] = 0;
                $tmp_arr['company_logo'] = '';
                $tmp_arr['setmeal_icon'] = '';
            }

            if ($value['district']) {
                $tmp_arr['district_text'] = isset(
                    $category_district_data[$value['district']]
                )
                    ? $category_district_data[$value['district']]
                    : '';
            } else {
                $tmp_arr['district_text'] = '';
            }
            $tmp_arr['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );

            $tmp_arr['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '学历不限';
            $tmp_arr['experience_text'] = isset(
                model('BaseModel')->map_experience[$value['experience']]
            )
                ? model('BaseModel')->map_experience[$value['experience']]
                : '经验不限';


            $returnlist[] = $tmp_arr;
        }
        $return['items'] = $returnlist;
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
    /**
     * 简历列表
     */
    public function resumelist(){
        $jobfair_id = input('get.jobfair_id/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim,addslashes');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if(!$jobfair_id){
            $this->ajaxReturn(500,'请选择网络招聘会');
        }
        $list = model('JobfairOnlineParticipate')
            ->alias('a')
            ->where('a.utype',2)
            ->where('a.audit',1);
        if($keyword!=''){
            $against = '';
            if (false !== stripos($keyword, ' ')) {
                $keyword = merge_spaces($keyword);
                $tmp_keyword_arr = explode(' ', $keyword);
                foreach ($tmp_keyword_arr as $key => $value) {
                    $against .= '+' . $value . ' ';
                }
                $against = trim($against);
            } else {
                $against = $keyword;
            }
            $list = $list->join(config('database.prefix') . 'resume_search_key b', 'a.uid=b.uid', 'right')->where("MATCH (`intention_jobs`) AGAINST ('" .$against ."' IN BOOLEAN MODE)");
        }else{
            $list = $list->join(config('database.prefix') . 'resume_search_rtime b', 'a.uid=b.uid', 'right');
        }
        $resumeid_arr = $list->order('b.refreshtime desc')->page($current_page, $pagesize)->column('b.id');
        if(!empty($resumeid_arr)){
            $rids = implode(',', $resumeid_arr);
            $resume = model('Resume')->where('id','in',$rids)->field(true)->orderRaw('field(id,' . $rids . ')')->select();
            $fullname_arr = model('Resume')->formatFullname($resumeid_arr,$this->visitor);
        }else{
            $resume = [];
            $fullname_arr = [];
        }
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
            $tmp_arr['education_text'] = isset(
                model('BaseModel')->map_education[$val['education']]
            )
                ? model('BaseModel')->map_education[$val['education']]
                : '';

            $tmp_arr['experience_text'] =
                $val['enter_job_time'] == 0
                    ? '尚未工作'
                    : format_date($val['enter_job_time']);

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

            $returnlist[] = $tmp_arr;
        }
        $return['items'] = $returnlist;
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
    /**
     * 参会
     */
    public function apply()
    {
        $jobfair_id = input('post.jobfair_id/d', 0, 'intval');
        if(!$jobfair_id){
            $this->ajaxReturn(500,'请选择招聘会');
        }
        $info = model('JobfairOnline')->where('id',$jobfair_id)->find();
        if(null===$info){
            $this->ajaxReturn(500,'请选择招聘会');
        }
        $this->checkLogin();
        if($this->visitor->utype==1){
            $this->interceptCompanyProfile();
            $this->interceptCompanyAuth();
            $setmeal_id_arr = $info['enable_setmeal_id']==''?[]:explode(",",$info['enable_setmeal_id']);
            if(empty($setmeal_id_arr) || (!empty($setmeal_id_arr) && !in_array($this->company_profile['setmeal_id'],$setmeal_id_arr))){
                $this->ajaxReturn(500,'您当前的会员套餐不能参加此招聘会');
            }
            if($info['must_company_audit']==1 && $this->company_profile['audit']!=1){
                $this->ajaxReturn(500,'您的企业资料还未通过认证，不能参加此招聘会');
            }
        }else{
            $this->interceptPersonalResume();
            $compelete_percent = model('Resume')->countCompletePercent(0,$this->visitor->uid);
            if($compelete_percent < $info['min_complete_percent']){
                $this->ajaxReturn(500,'你的简历完整度不足'.$info['min_complete_percent'].'%，不能参加此招聘会');
            }
        }
        if(null!==model('JobfairOnlineParticipate')->where('uid',$this->visitor->uid)->find()){
            $this->ajaxReturn(500,'您已经报名过此招聘会了');
        }
        $insertData = [
            'jobfair_id'=>$jobfair_id,
            'utype'=>$this->visitor->utype,
            'uid'=>$this->visitor->uid,
            'audit'=>0,
            'qrcode'=>0,
            'addtime'=>time()
        ];
        if (
            false === model('JobfairOnlineParticipate')->save($insertData)
        ) {
            $this->ajaxReturn(500, model('JobfairOnlineParticipate')->getError());
        }
        $this->ajaxReturn(200, '报名成功，请等待管理员审核');
    }

    //查看log
    public function getLog()
    {
        $time = strtotime(date('Y-m-d'));
        $data = model('JobfairOnlineViewLog')->alias('a')
            ->join('qs_member b','b.uid=a.uid','left')
            ->where('a.addtime','gt',$time)
            ->field("a.*,
            case when a.type = 1 then (select companyname from qs_company where id=a.content_id)
             when a.type = 2 then (select jobname from qs_job where id = a.content_id)
             when a.type = 3 then (select fullname from qs_resume where id = a.content_id) end as content_name,
             case when b.utype = 1 then (select companyname from qs_company where uid = a.uid)
              when b.utype = 2 then (select fullname from qs_resume where uid = a.uid) end as member_name
             ")->order('a.addtime','desc')
            ->select();
        $res = [];
        foreach($data as $k=>$v)
        {
            if (!empty($v['member_name']))
            {
                $member_name = substr($v['member_name'],3,strlen($v['member_name'])-3);
                $v['member_name'] = str_replace($member_name,'**',$v['member_name']);
            }
            $user_name = empty($v['uid']) ? '游客' : $v['member_name'];
            $date = $this->time_tran($v['addtime']);

            if (!empty($v['content_name']))
            {
                $content_name = substr($v['content_name'],3,strlen($v['content_name'])-3);
                $v['content_name'] = str_replace($content_name,'**',$v['content_name']);
            }
            switch ($v['type'])
            {
                case 0:
                    $content = '进入会场';
                    break;
                case 1:
                    $content = '查看了'.$v['content_name'].'企业';
                    break;
                case 2:
                    $content = '查看了'.$v['content_name'].'职位';
                    break;
                case 3:
                    $content = '查看了'.$v['content_name'].'简历';
                    break;
            }
            $res[] = [
                'logo' =>
                    $v['photo_img'] > 0
                        ? model('Uploadfile')->getFileUrl($v['photo_img'])
                        : default_empty('photo'),
                'content' => $user_name.$date.$content
            ];
        }
        return $res;
    }
    public function logAdd()
    {
        $arr = [
            'uid' => !empty($this->userinfo->uid) ? $this->userinfo->uid : 0,
            'addtime' => time(),
            'type' => input('get.type/d', 0, 'intval'),
            'content_id' => input('get.content_id/d', 0, 'intval'),
        ];
        $arr['photo_img'] = 0;
        $utype = isset($this->userinfo->utype) ? $this->userinfo->utype : 0;

        if ($arr['uid'] > 0 &&  $utype > 0)
        {
            if($utype == 2)
            {
                $arr['photo_img'] = model('resume')->where(['uid'=>$arr['uid']])->value('photo_img');
            }else
            {
                $arr['photo_img'] = model('company')->where(['uid'=>$arr['uid']])->value('logo');
            }
        }
        model('JobfairOnlineViewLog')->insert($arr);

    }
    public function time_tran($the_time)
    {
        $now_time = time();
        $show_time = $the_time;

        $dur = $now_time - $show_time;
        if ($dur < 0) {

            return date('Y-m-d',$the_time);

        } else {

            if ($dur < 60) {

                return $dur . '秒前';

            } else {

                if ($dur < 3600) {

                    return floor($dur / 60) . '分钟前';

                } else {

                    if ($dur < 86400) {

                        return floor($dur / 3600) . '小时前';

                    } else {

                        if ($dur < 259200) {//3天内

                            return floor($dur / 86400) . '天前';

                        } else {

                            return date('Y-m-d',$the_time);

                        }

                    }

                }
            }
        }
    }
}