<?php
namespace app\v1_0\controller\home;
use app\common\model\BaseModel;

class Jobfairol extends \app\v1_0\controller\common\Base{
    public function _initialize(){
        parent::_initialize();
    }
    /**
     * 招聘会列表
     */
    public function index(){
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
        $list = model('JobfairOnline')->where('display',1)->field($field)->order('score DESC, id DESC')->page($current_page, $pagesize)->select();
        $total = model('JobfairOnline')->where('display',1)->field($field)->count();
        $participate_company = $participate_personal = $jobfair_id_arr = $thumb_arr = $thumb_id_arr = [];
        foreach ($list as $key => $value) {
            $jobfair_id_arr[] = $value['id'];
            $value['thumb'] > 0 && ($thumb_id_arr[] = $value['thumb']);
        }
        if (!empty($thumb_id_arr)) {
            $thumb_arr = model('Uploadfile')->getFileUrlBatch($thumb_id_arr);
        }
        if (!empty($jobfair_id_arr)) {
            $participate_company = model('JobfairOnlineParticipate')->where('jobfair_id','in',$jobfair_id_arr)->where('utype',1)->where('audit',1)->group('jobfair_id')->column('jobfair_id,count(id)');
            $participate_personal = model('JobfairOnlineParticipate')
                ->alias('a')
                ->join(config('database.prefix') . 'resume_search_rtime b', 'a.uid=b.uid', 'right')
                ->where('a.jobfair_id','in',$jobfair_id_arr)
                ->where('a.utype',2)
                ->where('a.audit',1)
                ->group('a.jobfair_id')
                ->column('a.jobfair_id,count(a.id)');
        }
        $returnlist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['title'] = $value['title'];
            $tmp_arr['thumb_src'] = isset($thumb_arr[$value['thumb']])?$thumb_arr[$value['thumb']]:default_empty('jobfair_thumb');
            $tmp_arr['starttime'] = $value['starttime'];
            $tmp_arr['endtime'] = $value['endtime'];
            $tmp_arr['click'] = $value['click'];
            $tmp_arr['score'] = intval($value['score']);
            $tmp_arr['total_company'] = isset($participate_company[$value['id']])?$participate_company[$value['id']]:0;
            $tmp_arr['total_personal'] = isset($participate_personal[$value['id']])?$participate_personal[$value['id']]:0;
            $tmp_arr['jobfair_url'] = url('index/jobfairol/show', ['id' => $value['id']], true, $this->sub_site_domain);
            $tmp_arr['total_job'] = model('Job')
                ->alias('a')
                ->join(config('database.prefix') . 'jobfair_online_participate b', 'a.uid=b.uid', 'left')
                ->where('b.jobfair_id', $value['id'])
                ->where('b.utype', 1)
                ->where('b.audit', 1)
                ->where('a.is_display', 1)
                ->where('a.audit', 1)
                ->count();
            $returnlist[] = $tmp_arr;
        }
        $return['items'] = $returnlist;
        $return['total'] = $total;
        $return['total_page'] = $total == 0 ? 0 : ceil($total / $pagesize);
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
    /**
     * 招聘会详情
     */
    public function show(){
        $id = input('get.id/d', 0, 'intval');
        if(!$id){
            $this->ajaxReturn(500,'请选择招聘会');
        }
        $info = model('JobfairOnline')->field('id,title,thumb,click,content,mobile_header_logo,starttime,endtime')->where('id',$id)->find();
        if(null === $info){
            $this->ajaxReturn(500,'请选择招聘会');
        }
        $info['mobile_header_logo'] =
            $info['mobile_header_logo'] > 0
                ? model('Uploadfile')->getFileUrl($info['mobile_header_logo'])
                : default_empty('jobfair_banner');
        $info['thumb'] =
            $info['thumb'] > 0
                ? model('Uploadfile')->getFileUrl($info['thumb'])
                : default_empty('thumb');

        $info = $info->toArray();
        $info['total_company'] = model('JobfairOnlineParticipate')->where('jobfair_id',$id)->where('utype',1)->where('audit',1)->count();
        $info['total_job'] = model('Job')
            ->alias('a')
            ->join(config('database.prefix') . 'jobfair_online_participate b', 'a.uid=b.uid', 'left')
            ->where('b.jobfair_id', $id)
            ->where('b.utype', 1)
            ->where('b.audit', 1)
            ->where('a.is_display', 1)
            ->where('a.audit', 1)
            ->count();
        model('JobfairOnline')->where('id',$id)->setInc('click',1);
        $info['content'] = htmlspecialchars_decode($info['content'],ENT_QUOTES);
        $info['status'] = 0;
        $time = time();
        if ($info['starttime'] < $time && $info['endtime'] > $time)
        {
            $info['status'] = 1;
        }elseif ($info['endtime'] < $time)
        {
            $info['status'] = 2;
        }
        $return['info'] = $info;
        $return['log'] = $this->getLog();
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
    /**
     * 参会企业列表
     */
    public function comlist(){
        $jobfair_id = input('get.jobfair_id/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim,addslashes');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if(!$jobfair_id){
            $this->ajaxReturn(500,'请选择招聘会');
        }
        $list = model('JobfairOnlineParticipate')
            ->alias('a')
            ->field('a.qrcode,a.stick,b.*,d.qrcode as wx_qrcode,c.name as trade_name,e.name as scale_name')
            ->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'left')
            ->join(config('database.prefix') . 'category e', 'b.scale=e.id', 'left')
            ->join(config('database.prefix') . 'category c', 'b.trade=c.id', 'left')
            ->join(config('database.prefix') . 'jobfair_online d', 'd.id=a.jobfair_id', 'left')
            ->where('a.jobfair_id', $jobfair_id)
            ->where('a.utype', 1)
            ->where('a.audit', 1)
            ->where('b.district1', 'gt', 0)
            ->where('b.companyname', 'not null');
        if($keyword!=''){
            $list = $list->where('b.companyname','like','%'.$keyword.'%');
        }
        $list = $list->order('a.stick desc,b.refreshtime desc,b.id desc')->page($current_page, $pagesize)->select();

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
                ->alias('j')
                ->join(config('database.prefix') . 'category_district b', 'j.district1 =b.id', 'left')
                ->join(config('database.prefix') . 'category_district c', 'j.district2 =b.id', 'left')
                ->join(config('database.prefix') . 'category_district d', 'j.district3 =b.id', 'left')
                ->where('j.company_id', 'in', $comid_arr)
                ->where('j.is_display', 1)
                ->where('j.audit', 1)
                ->order('j.refreshtime DESC,j.id DESC') // 【ID1000570】【优化】网络招聘会企业的岗位排序按刷新先后排序 yx - 2023.03.03
                ->column('j.id,j.company_id,j.jobname,j.minwage,j.maxwage,j.negotiable,b.name as district1_name,c.name as district2_name,d.name as district3_name,j.experience,j.education', 'j.id');
            foreach ($job_data as $key => $value) {
                if(isset($job_list[$value['company_id']]) && count($job_list[$value['company_id']])>=3){
                    continue;
                }
                $job_tmp_arr = [];
                $job_tmp_arr['id'] = $value['id'];
                $job_tmp_arr['jobname'] = $value['jobname'];
                $job_tmp_arr['district_name'] = $value['district1_name'];
                if (!empty($value['district2_name']))
                {
                    $job_tmp_arr['district_name'].=' '.$job_tmp_arr['district2_name'];
                }
                if (!empty($value['district3_name']))
                {
                    $job_tmp_arr['district_name'].=' '.$job_tmp_arr['district3_name'];
                }
                $basemodel = new BaseModel();
                $job_tmp_arr['education'] = isset($basemodel->map_education[$value['education']]) ? $basemodel->map_education[$value['education']] : '不限学历';
                $job_tmp_arr['experience'] = isset($basemodel->map_experience[$value['experience']]) ? $basemodel->map_experience[$value['experience']] : '无经验';
                $job_tmp_arr['wage_text'] = model('BaseModel')->handle_wage(
                    $value['minwage'],
                    $value['maxwage'],
                    $value['negotiable']
                );
                $job_list[$value['company_id']][] = $job_tmp_arr;
            }
        }
        $category_district_data = model('CategoryDistrict')->getCache();
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
            $tmp_arr['company_url'] = url('index/company/show', ['id' => $value['id']], true, $this->sub_site_domain);
            $tmp_arr['scale_name'] = $value['scale_name'];
            $tmp_arr['trade_name'] = $value['trade_name'];
            $tmp_arr['district1_text'] = isset($category_district_data[$value['district1']]) ? $category_district_data[$value['district1']] : '';
            $tmp_arr['district2_text'] = isset($category_district_data[$value['district2']]) ? $category_district_data[$value['district2']] : '';
            $tmp_arr['district3_text'] = isset($category_district_data[$value['district3']]) ? $category_district_data[$value['district3']] : '';
            $returnlist[] = $tmp_arr;
        }
        $return['items'] = $returnlist;
        $total = model('JobfairOnlineParticipate')
            ->alias('a')
            ->field('a.qrcode,b.*,c.wx_qrcode')
            ->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'left')
            ->join(config('database.prefix') . 'customer_service c', 'b.cs_id=c.id', 'left')
            ->where('a.jobfair_id',$jobfair_id)
            ->where('a.utype',1)
            ->where('a.audit',1)
            ->where('b.district1','gt',0)
            ->where('b.companyname','not null')
            ->count();
        $return['total'] = $total;
        $return['total_page'] = $total == 0 ? 0 : ceil($total / $pagesize);
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
            $this->ajaxReturn(500,'请选择招聘会');
        }
        $list = model('JobfairOnlineParticipate')
            ->alias('a')
            ->field('a.qrcode,c.contact,c.use_company_contact,d.contact as company_contact,b.*')
            ->join(config('database.prefix') . 'job b', 'a.uid=b.uid', 'right')
            ->join(config('database.prefix') . 'job_contact c', 'b.id=c.jid', 'left')
            ->join(config('database.prefix') . 'company_contact d', 'd.comid=b.company_id', 'left')
            ->where('a.jobfair_id', $jobfair_id)
            ->where('a.utype', 1)
            ->where('a.audit', 1)
            ->where('b.is_display', 1)
            ->where('b.audit', 1);
        if($keyword!=''){
            $list = $list->where('b.jobname','like','%'.$keyword.'%');
        }
        $list = $list->order('b.refreshtime desc,b.id desc')->page($current_page, $pagesize)->select();

        $comid_arr = $cominfo_arr = $logo_arr = $logo_id_arr = $icon_id_arr = $icon_arr = $qrcode_arr = $qrcode_id_arr = $cs_id_arr = $cs_arr = [];

        foreach ($list as $key => $value) {
            $comid_arr[] = $value['company_id'];
            if($value['qrcode'] > 0){
                $qrcode_id_arr[] = $value['qrcode'];
            }
        }
        if (!empty($qrcode_id_arr)) {
            $qrcode_arr = model('Uploadfile')->getFileUrlBatch($qrcode_id_arr);
        }
        if (!empty($comid_arr)) {
            $cominfo_arr = model('Company')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'setmeal b',
                    'a.setmeal_id=b.id',
                    'LEFT'
                )
                ->join(config('database.prefix') . 'customer_service c', 'a.cs_id=c.id', 'LEFT')
                ->where('a.id', 'in', $comid_arr)
                ->column(
                    'a.id,a.companyname,a.audit,a.logo,a.setmeal_id,b.icon,c.wx_qrcode,a.scale,a.district1,a.district2,a.district3,a.scale,a.updatetime',
                    'a.id'
                );
            foreach ($cominfo_arr as $key => $value) {
                $value['logo'] > 0 && ($logo_id_arr[] = $value['logo']);
                $value['icon'] > 0 && ($icon_id_arr[] = $value['icon']);
                if($value['wx_qrcode']>0){
                    $cs_id_arr[] = $value['wx_qrcode'];
                }
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
            if (!empty($cs_id_arr)) {
                $cs_arr = model('Uploadfile')->getFileUrlBatch($cs_id_arr);
            }
        }

        $category_district_data = model('CategoryDistrict')->getCache();
        $category = model('Category')->getCache();
        $returnlist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['company_id'] = $value['company_id'];
            $tmp_arr['jobname'] = $value['jobname'];
            $tmp_arr['job_url'] = url('index/job/show', ['id' => $value['id']], true, $this->sub_site_domain);
            if (isset($cominfo_arr[$value['company_id']])) {
                $tmp_arr['company_id'] = $value['company_id'];
                $tmp_arr['companyname'] =
                    $cominfo_arr[$value['company_id']]['companyname'];
                $tmp_arr['company_audit'] =
                    $cominfo_arr[$value['company_id']]['audit'];
                $tmp_arr['company_logo'] = isset(
                    $logo_arr[$cominfo_arr[$value['company_id']]['logo']]
                )
                    ? $logo_arr[$cominfo_arr[$value['company_id']]['logo']]
                    : default_empty('logo');
                if(isset($qrcode_arr[$value['qrcode']])){
                    $tmp_arr['qrcode_src'] = $qrcode_arr[$value['qrcode']];
                }else if(isset($cs_arr[$cominfo_arr[$value['company_id']]['wx_qrcode']])){
                    $tmp_arr['qrcode_src'] = $cs_arr[$cominfo_arr[$value['company_id']]['wx_qrcode']];
                }else{
                    $tmp_arr['qrcode_src'] = '';
                }
                $tmp_arr['setmeal_icon'] = isset(
                    $icon_arr[$cominfo_arr[$value['company_id']]['icon']]
                )
                    ? $icon_arr[$cominfo_arr[$value['company_id']]['icon']]
                    : model('Setmeal')->getSysIcon($value['setmeal_id']);

                $tmp_arr['scale'] = isset($category['QS_scale'][$cominfo_arr[$value['company_id']]['scale']])?$category['QS_scale'][$cominfo_arr[$value['company_id']]['scale']]:'';
                $tmp_arr['district'] = '';
                $district1 = isset($category_district_data[$value['district1']]) ? $category_district_data[$value['district1']] : '';
                $district2 = isset($category_district_data[$value['district2']]) ? $category_district_data[$value['district2']] : '';
                $district3 = isset($category_district_data[$value['district3']]) ? $category_district_data[$value['district3']] : '';

                if (!empty($district1) && !empty($district2) && !empty($district3))
                {
                    $tmp_arr['district'] = $district1.' '.$district2.' '.$district3;
                }elseif (!empty($district1) && !empty($district2) && empty($district3))
                {
                    $tmp_arr['district'] = $district1.' '.$district2;
                }elseif (!empty($district1) && empty($district2) && empty($district3))
                {
                    $tmp_arr['district'] = $district1;
                }

                if (!empty($cominfo_arr[$value['company_id']]['updatetime']))
                {
                    $tmp_arr['updatetime'] = $this->time_tran($cominfo_arr[$value['company_id']]['updatetime']);
                }else{
                    $tmp_arr['updatetime'] = '';
                }
            } else {
                $tmp_arr['companyname'] = '';
                $tmp_arr['company_audit'] = 0;
                $tmp_arr['company_logo'] = '';
                $tmp_arr['setmeal_icon'] = '';
                $tmp_arr['qrcode_src'] = '';
                $tmp_arr['scale'] = '';//公司规模
                $tmp_arr['district'] = '';//公司地址
                $tmp_arr['updatetime'] = '';
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
            if ($value['category1'])
            {
                $tmp_arr['category'] = isset(
                    $category['QS_trade'][$value['category1']]
                )
                    ? $category['QS_trade'][$value['category1']]
                    : '';
            }else {
                $tmp_arr['category'] = '';
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
            $tmp_arr['contact'] = $value['contact'];
            if ($value['use_company_contact'] == 1) {
                $tmp_arr['contact'] = $value['company_contact'];
            }
            $returnlist[] = $tmp_arr;
        }

        $return['items'] = $returnlist;
        $total = model('JobfairOnlineParticipate')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.uid=b.uid', 'right')
            ->where('a.jobfair_id',$jobfair_id)
            ->where('a.utype',1)
            ->where('a.audit',1)
            ->where('b.is_display',1)
            ->where('b.audit',1);
        if($keyword!=''){
            $total = $total->where('b.jobname','like','%'.$keyword.'%');
        }
        $total = $total->count();

        $return['total'] = $total;
        $return['total_page'] = $total == 0 ? 0 : ceil($total / $pagesize);
        $this->ajaxReturn(200,'获取数据成功',$return);
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

                return '刚刚';

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
    /**
     * 简历列表
     */
    public function resumelist(){
        $jobfair_id = input('get.jobfair_id/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim,addslashes');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if(!$jobfair_id){
            $this->ajaxReturn(500,'请选择招聘会');
        }
        $list = model('JobfairOnlineParticipate')
            ->alias('a')
            ->where('a.jobfair_id', $jobfair_id)
            ->where('a.utype', 2)
            ->where('a.audit', 1);
        $against = '';
        if($keyword!=''){
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
        $resumeid_arr = $list->order('b.refreshtime desc,b.id desc')->distinct(true)->page($current_page, $pagesize)->column('b.id');
        $rids = [];
        if(!empty($resumeid_arr)){
            $rids = implode(',', $resumeid_arr);
            $resume = model('Resume')->where('id','in',$rids)->field(true)->orderRaw('field(id,' . $rids . ')')->select();
        }else{
            $resume = [];
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
            ->group('rid')
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
        $returnlist = [];
        foreach ($resume as $key => $val) {
            $tmp_arr = [];
            $tmp_arr['id'] = $val['id'];
            $tmp_arr['stick'] = $val['stick'];
            $tmp_arr['high_quality'] = $val['high_quality'];
            $tmp_arr['fullname'] = $val['fullname'];
            $tmp_arr['resume_url'] = url('index/resume/show', ['id' => $value['id']], true, $this->sub_site_domain);
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
            $tmp_arr['education_text'] = isset(
                model('BaseModel')->map_education[$val['education']]
            )
                ? model('BaseModel')->map_education[$val['education']]
                : '';

            $tmp_arr['experience_text'] =
                $val['enter_job_time'] == 0
                    ? '尚未工作'
                    : format_date($val['enter_job_time']).'经验';

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

                    for ($i=2;$i<=3;$i++)
                    {
                        $key = 'district'.$i;
                        if ($v['district']) {
                            $district[] = isset(
                                $category_district_data[$v[$key]]
                            )
                                ? $category_district_data[$v[$key]]
                                : '';
                        }
                    }
                    $district_arr[] = implode(' ',$district);
                    unset($district);
                }
            }
            if (!empty($category_arr)) {
                $category_arr = array_unique($category_arr);
                $tmp_arr['intention_jobs'] = !empty($category_arr) ? $category_arr[0] : '';
            } else {
                $tmp_arr['intention_jobs'] = '';
            }
            if (!empty($wage_arr)) {
                $wage_arr = array_unique($wage_arr);
                $tmp_arr['intention_wage'] = !empty($wage_arr) ? $wage_arr[0] : '';
            } else {
                $tmp_arr['intention_wage'] = '';
            }
            if (!empty($district_arr)) {
                $district_arr = array_unique($district_arr);
                $tmp_arr['intention_district'] = implode(
                    ' ',
                    $district_arr
                );
            } else {
                $tmp_arr['intention_district'] = '';
            }

            $returnlist[] = $tmp_arr;
        }
        $return['items'] = $returnlist;
        $total = model('JobfairOnlineParticipate')
            ->alias('a')
            ->where('a.jobfair_id',$jobfair_id)
            ->where('a.utype',2)
            ->where('a.audit',1);
        if($against!=''){
            $total = $total->join(config('database.prefix') . 'resume_search_key b', 'a.uid=b.uid', 'right')->where("MATCH (`intention_jobs`) AGAINST ('" .$against ."' IN BOOLEAN MODE)");
        }else{
            $total = $total->join(config('database.prefix') . 'resume_search_rtime b', 'a.uid=b.uid', 'right');
        }
        $total = $total->count('distinct b.id');
        $return['total'] = $total;
        $return['total_page'] = $total == 0 ? 0 : ceil($total / $pagesize);
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
        if($this->userinfo->utype==1){
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
            $compelete_percent = model('Resume')->countCompletePercent(0,$this->userinfo->uid);
            if($compelete_percent < $info['min_complete_percent']){
                $this->ajaxReturn(500,'你的简历完整度不足'.$info['min_complete_percent'].'%，不能参加此招聘会');
            }
        }
        if(null!==model('JobfairOnlineParticipate')->where('jobfair_id',$jobfair_id)->where('uid',$this->userinfo->uid)->find()){
            $this->ajaxReturn(500,'您已经报名过此招聘会了');
        }
        $timestamp = time();
        if($info['endtime'] < $timestamp){
            $this->ajaxReturn(500,'此招聘会已结束');
        }
        if($info['starttime'] > $timestamp){
            $this->ajaxReturn(500,'此招聘会还未开始');
        }
        $insertData = [
            'jobfair_id'=>$jobfair_id,
            'utype'=>$this->userinfo->utype,
            'uid'=>$this->userinfo->uid,
            'audit'=>0,
            'qrcode'=>0,
            'source'=>0,
            'stick'=>0,
            'addtime'=>time()
        ];
        if (
            false === model('JobfairOnlineParticipate')->save($insertData)
        ) {
            $this->ajaxReturn(500, model('JobfairOnlineParticipate')->getError());
        }
        $this->ajaxReturn(200, '报名成功，请等待管理员审核');
    }

    /**
     *   logAdd
     *  加入查看日志
     */
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

        if (false === model('JobfairOnlineViewLog')->insert($arr)){
            $this->ajaxReturn(500, model('JobfairOnlineViewLog')->getError());
        }
        $this->ajaxReturn(200, '添加成功');

    }

    //查看log
    public function getLog()
    {
        $time = strtotime(date('Y-m-d'));
        $time = strtotime(date('Y-m-d'));
        $prefix = config('database.prefix');
        $field = 'a.*,
            case when a.type = 1 then (select companyname from '.$prefix.'company where id=a.content_id)
             when a.type = 2 then (select jobname from '.$prefix.'job where id = a.content_id)
             when a.type = 3 then (select fullname from '.$prefix.'resume where id = a.content_id) end as content_name,
             case when b.utype = 1 then (select companyname from '.$prefix.'company where uid = a.uid)
              when b.utype = 2 then (select fullname from '.$prefix.'resume where uid = a.uid) end as member_name';
        $data = model('JobfairOnlineViewLog')->alias('a')
            ->join($prefix.'member b','b.uid=a.uid','left')
            ->where('a.addtime','gt',$time)
            ->field($field)->order('a.addtime','desc')
            ->select();
        $res = [];
        foreach($data as $k=>$v)
        {
            $user_name = empty($v['uid']) ? '游客' : $v['member_name'];
            $date = $this->time_tran($v['addtime']);
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
                'logo' => $v['photo_img'] > 0
                    ? model('Uploadfile')->getFileUrl($v['photo_img'])
                    : default_empty('photo'),
                'content' => $user_name.$date.$content
            ];
        }
        return $res;
    }
}
