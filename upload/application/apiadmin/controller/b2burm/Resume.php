<?php

namespace app\apiadmin\controller\b2burm;

use app\common\controller\Backend;
use app\common\model\shortvideo\SvPersonalVideo;
use app\common\model\Uploadfile;
use Think\Db;

class Resume extends Backend
{
    /**
     * @return void
     * 简历列表
     */
    public function index(){
        $where = [];
        $having_where = '';
        $order = 'r.refreshtime desc';
        $current_page = input('post.page/d', 1, 'intval');
        $pagesize = input('post.pagesize/d', 10, 'intval');
        $key_type = input('post.key_type/d', 0, 'intval');
        $keyword = input('post.keyword/s', '', 'trim');
        $education = input('post.education/d', 0, 'intval');
        $experience = input('post.experience/d', 0, 'intval');
        $high_quality = input('post.high_quality/s', '', 'trim');
        $audit = input('post.audit/s', '', 'trim');
        $delivery_num = input('post.delivery_num/d', 0, 'intval');
        $downloaded = input('post.downloaded/d', 0, 'intval');
        $viewed = input('post.viewed/d', 0, 'intval');
        $invitation = input('post.invitation/d', 0, 'intval');
        $works = input('post.works/d', 0, 'intval');
        $sort_type = input('post.sort_type/s', '', 'trim');
        $contact_status = input('post.contact_status/d', 0, 'intval');
        $sort = input('post.sort/s', '', 'trim');
        $citycategory = input('post.citycategory/a',[]);
        $jobcategory = input('post.jobcategory/a',[]);
        $platform = input('post.platform/s', '', 'trim');
        if($platform != ''){
            $where['m.platform'] = ['=',$platform];
        }
        if($sort_type != '' && $sort != ''){ // 排序
            if($sort_type == 'refreshtime'){
                $order = 'r.refreshtime '.$sort;
            }
            if($sort_type == 'last_login_time'){
                $order = 'm.last_login_time '.$sort;
            }
            if($sort_type == 'reg_time'){
                $order = 'm.reg_time '.$sort;
            }
        }
        if($education > 0){//学历
            $where['r.education'] = ['=',$education];
        }
        if($high_quality != ''){//简历等级
            $where['r.high_quality'] = ['=',$high_quality];
        }
        if($audit != ''){//审核状态
            $where['r.audit'] = ['=',$audit];
        }

        if($experience > 0){
            switch ($experience) {
                case 1: //无经验/应届生
                    $where['r.enter_job_time'] = ['=',0];
                    break;
                case 2://1年以下
                    $where['r.enter_job_time'] = ['>', strtotime('-1 year')];
                    break;
                case 3://1年-2年
                    $where['r.enter_job_time'] = [['<=',strtotime('-1 year')],['>',strtotime('-2 year')],'and'];
                    break;
                case 4://2年-3年
                    $where['r.enter_job_time'] = [['<=', strtotime('-2 year')],['>',strtotime('-3 year')],'and'];
                    break;
                case 5://3年-5年
                    $where['r.enter_job_time'] = [['<=', strtotime('-3 year')],['>',strtotime('-5 year')],'and'];
                    break;
                case 6://5年-10年
                    $where['r.enter_job_time'] = [['<=', strtotime('-5 year')],['>',strtotime('-10 year')],'and'];
                    break;
                case 7://10年以上
                    $where['r.enter_job_time'] = [['<', strtotime('-10 year')],['<>',0],'and'];
                    break;
                default:
                    break;
            }
        }

        if($contact_status > 0){
            switch ($contact_status) {
                case 1: //电话联系
                    $where['r.is_status_phone'] = ['=',2];
                    break;
                case 2: //微信联系
                    $where['r.is_status_weixin'] = ['=',2];
                    break;
                case 3: //从未联系
                    $where['r.is_status_weixin'] = ['=',1];
                    $where['r.is_status_phone'] = ['=',1];
                    break;
            }
        }
        $total_join = [];
        $list_join = [];
        $field = 'r.id,
            r.uid,
            r.is_display,
            r.audit,
            r.fullname,
            r.high_quality,
            r.education,
            r.enter_job_time,
            r.comment,
            r.remark,   
            r.photo_img,
            r.last_visit_time,
            r.is_status_weixin,
            r.is_status_phone,
            r.refreshtime,
            c.mobile as contact_mobile,
            m.mobile as member_mobile,
            m.last_login_time,
            m.reg_time,
            ifnull(bind.id,0) as is_bind';
         // 查询条件 - 关键词
        if (!empty($keyword) && !empty($key_type)) {
            switch ($key_type) {
                case 1: // 企业名称
                    $where['r.fullname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2: // 会员手机号
                    $where['m.mobile'] = ['like',"%{$keyword}%"];
                    break;
                case 3: // 企业手机号
                    $total_join[] = ['resume_contact c','r.id=c.rid','LEFT'];
                    $where['c.mobile'] = ['like',"%{$keyword}%"];
                    break;
                case 4: // 简历ID
                    $where['r.id'] = ['=',$keyword];
                    break;
                default:
                    break;
            }
        }

        $total_field = '';
//        if($delivery_num > 0){
//            $total_join[] = ['JobApply ja','ja.resume_id=r.id','LEFT'];
//            $list_join[] = ['JobApply ja','ja.resume_id=r.id','LEFT'];
//            switch ($delivery_num) {
//                case 1: // 今日投递
//                    $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
//                    $where['ja.addtime'] = ['>=',$beginToday];
//                    $field .= ',count(DISTINCT ja.id) as delivery_num';
//                    break;
//                case 2: // 30天内投递
//                    $time = strtotime(date("Y-m-d", strtotime("-30 day")));
//                    $where['ja.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT ja.id) as delivery_num';
//                    break;
//                case 3: // 7天内投递
//                    $time = strtotime(date("Y-m-d", strtotime("-7 day")));
//                    $where['ja.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT ja.id) as delivery_num';
//                    break;
//                case 4: // 3天内投递
//                    $time = strtotime(date("Y-m-d", strtotime("-3 day")));
//                    $where['ja.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT ja.id) as delivery_num';
//                    break;
//                case 5: // 从未投递过
//                    $field .= ',count(DISTINCT ja.id) as delivery_num';
////                    $total_field[] =  'count(DISTINCT ja.id) as delivery_num';
//                    if($total_field != ''){
//                        $total_field .= ',count(DISTINCT ja.id) as delivery_num';
//                    }else{
//                        $total_field .= 'count(DISTINCT ja.id) as delivery_num';
//                    }
//                    if($having_where != ''){
//                        $having_where .= 'and delivery_num = 0';
//                    }else{
//                        $having_where .= 'delivery_num = 0';
//                    }
//                    break;
//                default:
//                    break;
//            }
//        }
//        if($downloaded > 0){
//            $total_join[] = ['CompanyDownResume cd','cd.resume_id=r.id','LEFT'];
//            $list_join[] = ['CompanyDownResume cd','cd.resume_id=r.id','LEFT'];
//            switch ($downloaded) {
//                case 1: // 今日下载
//                    $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
//                    $where['cd.addtime'] = ['>=',$beginToday];
//                    $field .= ',count(DISTINCT cd.id) as downloaded';
//                    break;
//                case 2: // 30天内下载
//                    $time = strtotime(date("Y-m-d", strtotime("-30 day")));
//                    $where['cd.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT cd.id) as downloaded';
//                    break;
//                case 3: // 7天内下载
//                    $time = strtotime(date("Y-m-d", strtotime("-7 day")));
//                    $where['cd.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT cd.id) as downloaded';
//                    break;
//                case 4: // 3天内下载
//                    $time = strtotime(date("Y-m-d", strtotime("-3 day")));
//                    $where['cd.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT cd.id) as downloaded';
//                    break;
//                case 5: // 从未下载
//                    $field .= ',count(DISTINCT cd.id) as downloaded';
//                    if($total_field != ''){
//                        $total_field .= ',count(DISTINCT cd.id) as downloaded';
//                    }else{
//                        $total_field .= 'count(DISTINCT cd.id) as downloaded';
//                    }
//                    if($having_where != ''){
//                        $having_where .= 'and downloaded = 0';
//                    }else{
//                        $having_where .= 'downloaded = 0';
//                    }
//                    break;
//                default:
//                    break;
//            }
//        }
//        if($viewed > 0){
//            $total_join[] = ['ViewResume vr','vr.resume_id=r.id','LEFT'];
//            $list_join[] = ['ViewResume vr','vr.resume_id=r.id','LEFT'];
//            switch ($viewed) {
//                case 1: // 今日被查看
//                    $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
//                    $where['vr.addtime'] = ['>=',$beginToday];
//                    $field .= ',count(DISTINCT vr.id) as viewed';
//                    break;
//                case 2: // 30天被查看
//                    $time = strtotime(date("Y-m-d", strtotime("-30 day")));
//                    $where['vr.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT vr.id) as viewed';
//                    break;
//                case 3: // 7天内被查看
//                    $time = strtotime(date("Y-m-d", strtotime("-7 day")));
//                    $where['vr.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT vr.id) as viewed';
//                    break;
//                case 4: // 3天内被查看
//                    $time = strtotime(date("Y-m-d", strtotime("-3 day")));
//                    $where['vr.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT vr.id) as viewed';
//                    break;
//                case 5: // 从未被查看
//                    $field .= ',count(DISTINCT vr.id) as viewed';
//                    if($total_field != ''){
//                        $total_field .= ',count(DISTINCT vr.id) as viewed';
//                    }else{
//                        $total_field .= 'count(DISTINCT vr.id) as viewed';
//                    }
//                    if($having_where != ''){
//                        $having_where .= 'and viewed = 0';
//                    }else{
//                        $having_where .= 'viewed = 0';
//                    }
//                    break;
//                default:
//                    break;
//            }
//        }
//        if($invitation > 0){
//            $total_join[] = ['CompanyInterview ci','ci.resume_id=r.id','LEFT'];
//            $list_join[] = ['CompanyInterview ci','ci.resume_id=r.id','LEFT'];
//            switch ($invitation) {
//                case 1: // 今日被查看
//                    $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
//                    $where['ci.addtime'] = ['>=',$beginToday];
//                    $field .= ',count(DISTINCT ci.id) as invitation';
//                    break;
//                case 2: // 30天被查看
//                    $time = strtotime(date("Y-m-d", strtotime("-30 day")));
//                    $where['ci.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT ci.id) as invitation';
//                    break;
//                case 3: // 7天内被查看
//                    $time = strtotime(date("Y-m-d", strtotime("-7 day")));
//                    $where['ci.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT ci.id) as invitation';
//                    break;
//                case 4: // 3天内被查看
//                    $time = strtotime(date("Y-m-d", strtotime("-3 day")));
//                    $where['ci.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT ci.id) as invitation';
//                    break;
//                case 5: // 从未被查看
//                    $field .= ',count(DISTINCT ci.id) as invitation';
//                    if($total_field != ''){
//                        $total_field .= ',count(DISTINCT ci.id) as invitation';
//                    }else{
//                        $total_field .= 'count(DISTINCT ci.id) as invitation';
//                    }
//                    if($having_where != ''){
//                        $having_where .= 'and invitation = 0';
//                    }else{
//                        $having_where .= 'invitation = 0';
//                    }
//                    break;
//                default:
//                    break;
//            }
//        }
//        if($works > 0){
//            $total_join[] = ['ResumeImg ri','ri.rid=r.id','LEFT'];
//            $list_join[] = ['ResumeImg ri','ri.rid=r.id','LEFT'];
//            switch ($works) {
//                case 1: // 今日发布的作品
//                    $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
//                    $where['ri.addtime'] = ['>=',$beginToday];
//                    $field .= ',count(DISTINCT ri.id) as works';
//                    break;
//                case 2: // 30天发布的作品
//                    $time = strtotime(date("Y-m-d", strtotime("-30 day")));
//                    $where['ri.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT ri.id) as works';
//                    break;
//                case 3: // 7天内发布的作品
//                    $time = strtotime(date("Y-m-d", strtotime("-7 day")));
//                    $where['ri.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT ri.id) as works';
//                    break;
//                case 4: // 3天内发布的作品
//                    $time = strtotime(date("Y-m-d", strtotime("-3 day")));
//                    $where['ri.addtime'] = ['>=',$time];
//                    $field .= ',count(DISTINCT ri.id) as works';
//                    break;
//                case 5: // 从未发布的作品
//                    $field .= ',count(DISTINCT ri.id) as works';
//                    if($total_field != ''){
//                        $total_field .= ',count(DISTINCT ri.id) as works';
//                    }else{
//                        $total_field .= 'count(DISTINCT ri.id) as works';
//                    }
//                    if($having_where != ''){
//                        $having_where .= 'and works = 0';
//                    }else{
//                        $having_where .= 'works = 0';
//                    }
//                    break;
//                default:
//                    break;
//            }
//        }

        if(!empty($citycategory) || !empty($jobcategory)){
            $total_join[] = ['ResumeIntention ris','ris.rid=r.id','LEFT'];
            $list_join[] = ['ResumeIntention ris','ris.rid=r.id','LEFT'];
            if(!empty($citycategory)){//意向地区
                if (isset($citycategory[0]) && !empty($citycategory[0]))
                {
                    $where['ris.district1'] = $citycategory[0];
                }
                if (isset($citycategory[1]) && !empty($citycategory[1]))
                {
                    $where['ris.district2'] = $citycategory[1];
                }
                if (isset($citycategory[2]) && !empty($citycategory[2]))
                {
                    $where['ris.district3'] = $citycategory[2];
                }
            }
            if(!empty($jobcategory)){//意向职位
                if (isset($jobcategory[0]) && !empty($jobcategory[0]))
                {
                    $where['ris.category1'] = $jobcategory[0];
                }
                if (isset($jobcategory[1]) && !empty($jobcategory[1]))
                {
                    $where['ris.category2'] = $jobcategory[1];
                }
                if (isset($jobcategory[2]) && !empty($jobcategory[2]))
                {
                    $where['ris.category3'] = $jobcategory[2];
                }
            }
        }
        $total = model('Resume')->alias('r');
        foreach ($total_join as $k=>$v){
            $total = $total->join($v[0],$v[1],$v[2]);
        }
        $total = $total->join('member m','r.uid=m.uid','LEFT')
            ->where($where)
            ->field($total_field)
            ->group('r.id')
            ->having($having_where)
            ->count();
        $list = model('Resume')->alias('r');
        foreach ($list_join as $k=>$v){
            $list = $list->join($v[0],$v[1],$v[2]);
        }
        $list = $list->join('member m','r.uid=m.uid','LEFT')
            ->join('resume_contact c','r.id=c.rid','LEFT')
            ->join('member_bind bind',"bind.uid = c.uid and bind.type='weixin'",'LEFT')
            ->where($where)
            ->field($field)
            ->group('r.id')
            ->order($order)
            ->having($having_where)
            ->page($current_page . ',' . $pagesize)
            ->select();
        $id_arr = array_column($list,'id');
        if($delivery_num <= 0){
            $job_apply = db('job_apply')->alias('ja')
                ->join('job j','ja.jobid = j.id')
                ->where('j.jobname','not null')
                ->where('ja.resume_id','in',$id_arr)
                ->field('count(ja.id) as num,ja.resume_id')
                ->group('ja.resume_id')
                ->select();
            if(!empty($job_apply)){
                $job_apply = array_column($job_apply,null,'resume_id');
            }
        }
        if($downloaded <= 0){
            $company_down_resume = db('company_down_resume')
                ->alias('a')
                ->where('a.resume_id','in',$id_arr)
                ->field('count(a.id) as num,a.resume_id')
                ->group('a.resume_id')
                ->select();
            if(!empty($company_down_resume)){
                $company_down_resume = array_column($company_down_resume,null,'resume_id');
            }
        }
        if($viewed <= 0){
            $view_resume = db('view_resume')
                ->where('resume_id','in',$id_arr)
                ->field('count(id) as num,resume_id')
                ->group('resume_id')
                ->select();
            if(!empty($view_resume)){
                $view_resume = array_column($view_resume,null,'resume_id');
            }
        }
        if($invitation <= 0){
                //被面邀
            $company_interview = db('company_interview')->alias('ci')
                ->join('job j','ci.jobid = j.id')
                ->where('j.jobname','not null')
                ->where('ci.resume_id','in',$id_arr)
                ->field('count(ci.id) as num,ci.resume_id')
                ->group('ci.resume_id')
                ->select();
            if(!empty($company_interview)){
                $company_interview = array_column($company_interview,null,'resume_id');
            }
        }
//        if($works <= 0){
//            $resume_img = db('resume_img')
//                ->where('rid','in',$id_arr)
//                ->field('count(id) as num,rid')
//                ->group('rid')
//                ->select();
//            if(!empty($resume_img)){
//                $resume_img = array_column($resume_img,null,'rid');
//            }
//        }


        foreach ($list as $k=>$v){
            $v['photo_img_src'] = $v['photo_img'] != 0
                ? model('Uploadfile')->getFileUrl(
                    $v['photo_img']
                )
                : default_empty('photo');
            $v['education_cn'] = isset(
                model('BaseModel')->map_education[$v['education']]
            )
                ? model('BaseModel')->map_education[$v['education']]
                : '学历未知';
            $v['experience_cn'] =
                $v['enter_job_time'] == 0
                    ? '无经验'
                    : format_date($v['enter_job_time']);
            $v['link'] = url('index/resume/show', ['id' => $v['id']]);
            //完整度
            $v['complete_percent'] = isset($v['id'])
                ? model('Resume')->countCompletePercent($v['id'])
                : 0;
            //投递数
            if($delivery_num <= 0){
                $v['delivery_num'] = 0;
                if (isset($job_apply[$v['id']]) && !empty($job_apply[$v['id']])) {
                    $v['delivery_num'] = $job_apply[$v['id']]['num'];
                }
            }
            if($downloaded <= 0){
                //被下载
                $v['downloaded'] = 0;
                if (isset($company_down_resume[$v['id']]) && !empty($company_down_resume[$v['id']])) {
                    $v['downloaded'] = $company_down_resume[$v['id']]['num'];
                }
            }
            if($viewed <= 0){
                //被查看
                $v['viewed'] = 0;
                if (isset($view_resume[$v['id']]) && !empty($view_resume[$v['id']])) {
                    $v['viewed'] = $view_resume[$v['id']]['num'];
                }
            }
            if($invitation <= 0){
                //被面邀
                $v['invitation'] = 0;
                if (isset($company_interview[$v['id']]) && !empty($company_interview[$v['id']])) {
                    $v['invitation'] = $company_interview[$v['id']]['num'];
                }
            }
//            if($works <= 0){
//                //简历作品
//                $v['works'] = 0;
//                if (isset($resume_img[$v['id']]) && !empty($resume_img[$v['id']])) {
//                    $v['works'] = $resume_img[$v['id']]['num'];
//                }
//            }
            $v['follow_num'] = model('b2bcrm.CrmFollowUp')->where('relation_id',$v['id'])->count();
            if($v['last_visit_time'] != null ){
                $v['final_follow'] = date('Y-m-d H:i:s',$v['last_visit_time']);
            }else{
                $v['final_follow'] = 0;
            }

        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 简历详情
     */
    public function resumeDetail(){
        $resume_id = input('get.id/d', 0, 'intval');
        $start = input('get.start/s', '', 'trim');
        $end = input('get.end/s', '', 'trim');
        if ($resume_id == 0) {
            $this->ajaxReturn(500, '请选择简历');
        }
        // r.last_visit_time,
        $data = model('Resume')
            ->alias('r')
            ->where('r.id',$resume_id)
            ->join('member m','r.uid=m.uid','LEFT')
            ->join('resume_contact c','r.id=c.rid','LEFT')
            ->join('member_points mp','r.uid = mp.uid','LEFT')
            ->field('r.id,
            r.uid,
            r.high_quality,
            r.fullname,
            r.refreshtime,
            r.tag,
            r.is_display,
            r.audit,
            r.remark,
            r.comment,
            r.last_visit_time,
            r.sex,r.education,
            r.enter_job_time,
            c.mobile as resume_mobile,
            m.mobile as member_mobile,
            m.reg_time,m.platform,
            m.username,
            m.email,
            m.status as member_status,
            m.last_login_time,
            r.birthday,
            mp.points,
            r.photo_img')
            ->find();

        /**
         * 【ID1000412】【优化】微信状态前后台不一致
         * yx - 2022.11.08
         */
        $weixin_bind = model('MemberBind')
            ->where([
                'type' => 'weixin',
                'uid' => $data['uid'],
                'is_subscribe' => 1
            ]) // 【bug】是否绑定微信，与个人中心不一致，新增”'is_subscribe' => 1“ yx - 2022.11.07
            ->find();
        $data['weixin_bind'] = empty($weixin_bind) ? 0 : 1;

        $data['photo_img_src'] =$data['photo_img'] > 0
                ? model('Uploadfile')->getFileUrl(
                $data['photo_img']
                )
                : default_empty('photo');
        $data['education_cn'] = isset(
                model('BaseModel')->map_education[$data['education']]
            )
                ? model('BaseModel')->map_education[$data['education']]
                : '学历未知';
        $data['experience_cn'] =
            $data['enter_job_time'] == 0
                    ? '无经验'
                    : format_date($data['enter_job_time']);
        $data['link'] = url('index/resume/show', ['id' => $data['id']]);
            //完整度
        $data['complete_percent'] = isset($data['id'])
                ? model('Resume')->countCompletePercent($data['id'])
                : 0;
        $data['age'] =
                intval($data['birthday']) == 0
                    ? '年龄未知'
                    : date('Y') - intval($data['birthday']) . '岁';
        $data['platform_cn'] = isset(model('BaseModel')->map_platform[$data['platform']])?model('BaseModel')->map_platform[$data['platform']]:'未知平台';
        $data['follow_up_num'] = model('b2bcrm.CrmFollowUp')->where('uid',$data['uid'])->count();
        $where = [];
        if($start != '' && $end != ''){
            $start = strtotime($start.' 00:00:00');
            $end = strtotime($end.' 23:59:59');
            $where['a.addtime'] = [['>',$start],['<=',$end],'and'];
        }
        //浏览职位
        $data['view_job'] = model('ViewJob')
            ->alias('a')
            ->where('a.personal_uid',$data['uid'])
            ->where($where)
            ->count();
        //投递数
        $data['delivery_num'] = model('JobApply')
            ->alias('a')
            ->join('job j','a.jobid = j.id')
            ->where('j.jobname','not null')
            ->where('a.resume_id',$data['id'])
            ->where($where)
            ->count();
        //被下载
        $data['downloaded'] = model('CompanyDownResume')
            ->alias('a')
            ->where('a.resume_id',$data['id'])
            ->where($where)
            ->count();
        //被查看
        $data['viewed'] = model('ViewResume')
            ->alias('a')
            ->where('a.resume_id',$data['id'])
            ->where($where)
            ->count();
        //被面邀
        $data['invitation'] = model('CompanyInterview')
            ->alias('a')
            ->join('job j','a.jobid = j.id')
            ->where('j.jobname','not null')
            ->where('a.resume_id',$data['id'])
            ->where($where)
            ->count();
        //主动刷新
        $data['refresh'] = model('RefreshResumeLog')
            ->alias('a')
            ->where('a.uid',$data['uid'])
            ->where($where)
            ->count();
        $resume_img = model('ResumeImg')->where('rid',$data['id'])->field('img,audit,id')->select();
        $resume_img_arr = [];
        foreach ($resume_img as $k=>$v){
            $img_src = model('Uploadfile')->getFileUrl(
                $v['img']
            );
            $resume_img_arr[] = [
                'id'=> $v['id'],
                'img_id'=> $v['img'],
                'img_src'=>$img_src,
                'audit'=>$v['audit']
            ];
        }
        $data['resume_img_arr'] = $resume_img_arr;
        $m = new SvPersonalVideo();
        $resume_video = $m->where('uid',$data['uid'])->field('id,is_public,audit,filesize,fid,view_count')->select();
        $up = new Uploadfile();
        $up->getFileUrlBatch2($resume_video, 'fid', 'video_src');
        $data['resume_video'] = $resume_video;


        $data['tag'] = $data['tag'] == '' ? [] : explode(',', $data['tag']);
        $data['tag_text'] = '';
        $data['tag_text_arr'] = [];
        $category_data = model('Category')->getCache();
        if (!empty($data['tag'])) {
            $tag_text_arr = [];
            foreach ($data['tag'] as $k => $v) {
                if (
                    is_numeric($v) &&
                    isset($category_data['QS_resumetag'][$v])
                ) {
                    $tag_text_arr[] = $category_data['QS_resumetag'][$v];
                } else {
                    $tag_text_arr[] = $v;
                }
            }
            if (!empty($tag_text_arr)) {
                $data['tag_text_arr'] = $tag_text_arr;
                $data['tag_text'] = implode(',', $tag_text_arr);
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $data);
    }

    public function jobApply()
    {
        $uid = input('get.uid/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        $where['a.personal_uid'] = $uid;
        $total = model('JobApply')
            ->alias('a')
            ->join(config('database.prefix') . 'job j', 'j.id=a.jobid', 'left')
            ->where($where)
            ->where('j.jobname','not null')
            ->count();
        $list = model('JobApply')
            ->alias('a')
            ->field(
                'a.id,a.comid,a.companyname,a.jobid,a.jobname,a.resume_id,a.fullname,a.note,a.addtime,a.is_look,a.handle_status,j.education,j.experience,j.district,j.minwage,j.maxwage,j.negotiable,j.click,j.is_display'
            )
            ->join(config('database.prefix') . 'job j', 'j.id=a.jobid', 'left')
            ->where($where)
            ->where('j.jobname','not null')
            ->order('a.id desc')
            ->page($current_page, $pagesize)
            ->select();
        $comid_arr = [];
        foreach ($list as $key => $value) {
            $comid_arr[] = $value['comid'];
        }
        if (!empty($comid_arr)) {
            $comlist = model('Company')
                ->where('id', 'in', $comid_arr)
                ->column('id,uid,audit', 'id');
        } else {
            $comlist = [];
        }
        $category_district_data = model('CategoryDistrict')->getCache();
        $returnlist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['jobid'] = $value['jobid'];
            $tmp_arr['companyname'] = $value['companyname'];
            $tmp_arr['jobname'] = $value['jobname'];
            $tmp_arr['addtime'] = $value['addtime'];
            $tmp_arr['click'] = $value['click'];
            $tmp_arr['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            $tmp_arr['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '不限';
            $tmp_arr['experience_text'] = isset(
                model('BaseModel')->map_experience[$value['experience']]
            )
                ? model('BaseModel')->map_experience[$value['experience']]
                : '不限';
            $tmp_arr['district_text'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            if (isset($comlist[$value['comid']])) {
                $cominfo = $comlist[$value['comid']];
                $tmp_arr['company_audit'] = $cominfo['audit'];
            } else {
                $tmp_arr['company_audit'] = 0;
            }
            if ($value['is_display'] == 0) {
                $tmp_arr['status_code'] = 'pause';
                $tmp_arr['status_text'] = '暂停招聘';
            } elseif ($value['handle_status'] == 1) {
                $tmp_arr['status_code'] = 'agree';
                $tmp_arr['status_text'] = '同意面试';
            } elseif ($value['handle_status'] == 2) {
                $tmp_arr['status_code'] = 'refuse';
                $tmp_arr['status_text'] = '已被婉拒';
            } elseif ($value['is_look'] == 1) {
                $tmp_arr['status_code'] = 'is_look';
                $tmp_arr['status_text'] = 'HR已查看';
            } else {
                $tmp_arr['status_code'] = 'no_look';
                $tmp_arr['status_text'] = 'HR未查看';
            }
            $tmp_arr['job_link_url_web'] = url('index/job/show',['id'=>$value['jobid']]);
            $tmp_arr['company_link_url_web'] = url('index/company/show',['id'=>$value['comid']]);

            $returnlist[] = $tmp_arr;
        }
        $return['items'] = $returnlist;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function interview()
    {
        $uid = input('get.uid/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        $where['a.personal_uid'] = $uid;
        $total = model('CompanyInterview')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.jobid=b.id', 'left')
            ->where($where)
            ->where('b.id','not null')
            ->count();
        $list = model('CompanyInterview')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.jobid=b.id', 'left')
            ->field('a.id,a.comid,a.companyname,a.jobid,a.jobname,a.resume_id,a.fullname,a.interview_time,a.contact,a.address,a.tel,a.note,a.addtime,a.is_look,b.education,b.experience,b.nature,b.minwage,b.maxwage,b.negotiable,b.map_lat,b.map_lng')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.id desc')
            ->page($current_page, $pagesize)
            ->select();

        foreach ($list as $key => $value) {
            $value['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            $value['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '';
            $value['experience_text'] = isset(
                model('BaseModel')->map_experience[$value['experience']]
            )
                ? model('BaseModel')->map_experience[$value['experience']]
                : '';
            $value['nature_text'] = isset(
                model('Job')->map_nature[$value['nature']]
            )
                ? model('Job')->map_nature[$value['nature']]
                : '';

            $value['overtime'] = $value['interview_time'] > time() ? 0 : 1;
            $value['job_link_url_web'] = url('index/job/show',['id'=>$value['jobid']]);
            $value['company_link_url_web'] = url('index/company/show',['id'=>$value['comid']]);

            $list[$key] = $value;
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function attentionMe()
    {
        $uid = input('get.uid/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        $where['a.personal_uid'] = $uid;
        $total = model('ViewResume')
            ->alias('a')
            ->join(config('database.prefix').'company b','a.company_uid=b.uid','LEFT')
            ->where($where)
            ->where('b.companyname','not null')
            ->count();
        $list = model('ViewResume')
            ->alias('a')
            ->join(config('database.prefix').'company b','a.company_uid=b.uid','LEFT')
            ->field('a.*,b.companyname,b.id as company_id,b.audit as company_audit,b.district,b.scale,b.nature')
            ->where($where)
            ->where('b.companyname','not null')
            ->order('a.addtime desc')
            ->page($current_page, $pagesize)
            ->select();
        $comuid_arr = [];
        foreach ($list as $key => $value) {
            $comuid_arr[] = $value['company_uid'];
        }
        if (!empty($comuid_arr)) {
            $job_list = [];
            $job_data = model('Job')
                ->where('uid', 'in', $comuid_arr)
                ->where('is_display', 1)
                ->where('audit', 1)
                ->column('id,uid,jobname', 'id');
            foreach ($job_data as $key => $value) {
                $job_list[$value['uid']][] = $value['jobname'];
            }
        } else {
            $job_list = [];
        }
        //解决企业下载个人简历，在个人会员中心的对我感兴趣中的标签还是显示未下载PC 触屏
        $downlist = model('CompanyDownResume')
            ->where('personal_uid', 'eq', $uid)
            ->column('comid,id','comid');
        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $returnlist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['addtime'] = $value['addtime'];
            $tmp_arr['company_id'] = $value['company_id'];
            $tmp_arr['companyname'] = $value['companyname'];
            $tmp_arr['company_audit'] = $value['company_audit'];
            $tmp_arr['district_text'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $tmp_arr['scale_text'] = isset(
                $category_data['QS_scale'][$value['scale']]
            )
                ? $category_data['QS_scale'][$value['scale']]
                : '';
            $tmp_arr['nature_text'] = isset(
                $category_data['QS_company_type'][$value['nature']]
            )
                ? $category_data['QS_company_type'][$value['nature']]
                : '';
            $tmp_arr['has_download'] = isset($downlist[$value['company_id']]) ? 1 : 0;
            $tmp_arr['jobnum'] = isset($job_list[$value['company_uid']])
                ? count($job_list[$value['company_uid']])
                : 0;
            $tmp_arr['first_jobname'] = isset($job_list[$value['company_uid']])
                ? $job_list[$value['company_uid']][0]
                : '';
            $tmp_arr['company_link_url_web'] = url('index/company/show',['id'=>$value['company_id']]);
            $returnlist[] = $tmp_arr;
        }
        $return['items'] = $returnlist;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function viewjob(){
        $uid = input('get.uid/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        $where['a.personal_uid'] = $uid;
        $total = model('ViewJob')
            ->alias('a')
            ->join(config('database.prefix').'company b','a.company_uid=b.uid','LEFT')
            ->join(config('database.prefix').'job c','a.jobid=c.id','LEFT')
            ->where($where)
            ->where('b.companyname','not null')
            ->where('c.jobname','not null')
            ->count();
        $list = model('ViewJob')
            ->alias('a')
            ->join(config('database.prefix').'company b','a.company_uid=b.uid','LEFT')
            ->join(config('database.prefix').'job c','a.jobid=c.id','LEFT')
            ->field('a.*,b.companyname,b.audit as company_audit,b.id as company_id,c.jobname,c.education,c.experience,c.district,c.negotiable,c.minwage,c.maxwage,c.click')
            ->where($where)
            ->where('b.companyname','not null')
            ->where('c.jobname','not null')
            ->order('a.id desc')
            ->page($current_page, $pagesize)
            ->select();
        $category_district_data = model('CategoryDistrict')->getCache();
        $returnlist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['addtime'] = $value['addtime'];
            $tmp_arr['companyname'] = $value['companyname'];
            $tmp_arr['company_audit'] = $value['company_audit'];
            $tmp_arr['jobid'] = $value['jobid'];
            $tmp_arr['jobname'] = $value['jobname'];
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

            $tmp_arr['district_text'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            if ($value['negotiable'] == 1) {
                $tmp_arr['wage_text'] = '面议';
            } else {
                $tmp_arr['wage_text'] = model('BaseModel')->handle_wage(
                    $value['minwage'],
                    $value['maxwage'],
                    $value['negotiable']
                );
            }
            $tmp_arr['click'] = $value['click'];
            $tmp_arr['job_link_url_web'] = url('index/job/show',['id'=>$value['jobid']]);
            $tmp_arr['company_link_url_web'] = url('index/company/show',['id'=>$value['company_id']]);
            $returnlist[] = $tmp_arr;
        }
        $return['items'] = $returnlist;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /*
     * 客户详情修改（侧拉）
     */
    public function updateResume()
    {
        $uid = input('post.uid/s', '', 'trim');
        $post = input('post.');
        $reason = isset($post['reason']) ? trim($post['reason']) : '';
        $examine = isset($post['examine']) ? intval($post['examine']) : 0;
        if (!$uid) {
            $this->ajaxReturn(500, '参数错误');
        }
        $info = model('Member')->find($uid);
        if (!$info) {
            $this->ajaxReturn(500, '数据获取失败');
        }
        $arr = [];
        if (isset($post['high_quality'])) {
            $arr['high_quality'] = intval($post['high_quality']);
        }
        if (isset($post['remark'])) {
            $arr['remark'] = trim($post['remark']);
        }
        if (isset($post['comment'])) {
            $arr['comment'] = trim($post['comment']);
        }
        if (isset($post['audit'])) {
            $arr['audit'] = intval($post['audit']);
        }
        if (isset($post['is_display'])) {
            $arr['is_display'] = intval($post['is_display']);
        }
        try {
            if (isset($post['password']) || isset($post['member_mobile']) || isset($post['email'])) {
                $this->updateMember($uid, $post, $info); // 修改密码或手机号
            }
            if (isset($post['resume_mobile'])) {
                $post['resume_mobile'] = empty($post['resume_mobile']) ? '' : $post['resume_mobile'];
                $this->updateEmail($uid, $post['resume_mobile']);
            }
            if (!empty($arr)) {
                $this->editCompany($uid, $arr,$reason);
            }
            if (isset($post['resume_img'])) {
                $post['resume_img'] = empty($post['resume_img']) ? '' : $post['resume_img'];
                $this->setAudit($post['resume_img'], $examine);
            }
            $this->ajaxReturn(200, '修改成功');
        } catch (\Exception $e) {
            $this->ajaxReturn(500, $e->getMessage());
        }

    }
    // 审核简历作品
    private function setAudit($id, $audit)
    {
        if (!is_array($id)) {
            throw new \Exception('简历作品id请传入数组格式');
        }
        model('ResumeImg')
            ->where('id', 'in', $id)
            ->setField('audit', $audit);
        model('AdminLog')->record(
            '将简历作品状态变更为【' .
            model('ResumeImg')->map_audit[$audit] .
            '】。作品ID【' .
            implode(",", $id) .
            '】',
            $this->admininfo
        );
        return true;
    }

    // 修改简历手机号
    private function updateEmail($uid, $resume_mobile)
    {
        $data = ['uid' => $uid];
        if (!empty($resume_mobile)) {
            $preg_phone = '/^1[3456789]\d{9}$/ims';
            if (!preg_match($preg_phone, $resume_mobile)) {
                throw new \Exception('手机号格式错误');
            }
            $data['mobile'] = $resume_mobile;
        }
        $resume_contact = model('ResumeContact')->where(['uid' => $uid])->find();
        $isupdate = false;
        if (!empty($resume_contact)) {
            $resume_contact = $resume_contact->toArray();
            $isupdate = true;
            $data['id'] = $resume_contact['id'];
        }
        $update_email = model('ResumeContact')
            ->isUpdate($isupdate)
            ->save($data);
        if (false === $update_email) {
            throw new \Exception(model('CompanyContact')->getError());
        }
        model('AdminLog')->record(
            '编辑简历手机。简历UID【' . $uid . '】' . '修改后简历手机【' . $resume_mobile . '】',
            $this->admininfo
        );

        return true;
    }

    // 修改简历
    private function editCompany($uid, $input_data, $reason = '')
    {
        $resume_id = model('Resume')
            ->where(['uid' => ['in', $uid]])
            ->column('id');
        if (empty($resume_id)) {
            throw new \Exception('简历uid错误');
        }
        if (isset($input_data['audit']) && !empty($input_data['audit'])) {
            model('Resume')->setAudit($resume_id, $input_data['audit'], $reason);
            model('AdminLog')->record(
                '将简历审核变更为【' .
                model('Resume')->map_audit[$input_data['audit']] .
                '】。简历ID【' .
                implode(',', $resume_id) .
                '】',
                $this->admininfo
            );
        }else{
            $update_member = model('Resume')
                ->allowField(true)
                ->save($input_data, ['uid' => $uid]);

            if (false === $update_member) {
                throw new \Exception(model('Company')->getError());
            }
            model('AdminLog')->record(
                '编辑简历。简历UID【' . $uid . '】',
                $this->admininfo
            );
        }
        return true;
    }

    // 修改密码或手机号
    private function updateMember($uid, $post, $info)
    {
        if (!empty($post['password'])) {
            $input_data['password'] = model('Member')->makePassword(
                $post['password'],
                $info['pwd_hash']
            );
        }

        if (isset($post['email'])) {
            if (!empty($post['email'])) {
                $preg_email = '/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims';

                if (!preg_match($preg_email, $post['email'])) {
                    throw new \Exception('请输入正确的邮箱格式');
                }
            }
            $input_data['email'] = $post['email'];
        }

        if (isset($post['member_mobile'])) {
            $preg_phone = '/^1[345789]\d{9}$/ims';
            if (!preg_match($preg_phone, $post['member_mobile'])) {
                throw new \Exception('手机号格式错误');
            }
            $member_uid = model('Member')->where(['mobile' => $post['member_mobile']])->value('mobile');
            if (!empty($member_uid) && $member_uid != $uid) {
                throw new \Exception('手机号已存在');
            }
            $input_data['mobile'] = $post['member_mobile'];
        }
        $update_member = model('Member')
            ->allowField(true)
            ->save($input_data, ['uid' => $uid]);

        if (false === $update_member) {
            throw new \Exception(model('Member')->getError());
        }

        /**
         * 修改会员手机号、密码。及后台修改时，清除所有登录状态，需重新登录
         * yx - 2022.11.09
         */
        if (
            (isset($input_data['password']) && !empty($input_data['password']))
            ||
            (!empty($input_data['mobile']) && $input_data['mobile'] != $info['mobile'])
        ) {
            model('IdentityToken')->where(['uid' => $uid])->delete(); //修改密码即删除token,
        }

        model('AdminLog')->record(
            '编辑会员。会员UID【' . $uid . '】',
            $this->admininfo
        );
        return true;
    }
    /**
     * 简历联系状态
     */
    public function setContactStatus(){
        $id = input('get.id/d', 0, 'intval');
        $type = input('get.type/s', '', 'trim');
        if($id <= 0){
            $this->ajaxReturn(500,'简历ID错误');
        }
        $resume = model('Resume')->where('id',$id)->find();
        if(empty($resume)){
            $this->ajaxReturn(500,'简历信息错误');
        }
        $data = [];
        $msg = '';
        if($type == 'weixin'){
            $msg = '设置微信';
            if($resume['is_status_weixin'] == 1){
                $data['is_status_weixin'] = 2;
            }else{
                $data['is_status_weixin'] = 1;
            }
        }else{
            $msg = '设置电话';
            if($resume['is_status_phone'] == 1){
                $data['is_status_phone'] = 2;
            }else{
                $data['is_status_phone'] = 1;
            }
        }
        $res = model('Resume')->save($data,['id'=>$id]);
        if(false === $res){
            $this->ajaxReturn(500,$msg.'失败');
        }
        $this->ajaxReturn(200,$msg.'成功');
    }
}