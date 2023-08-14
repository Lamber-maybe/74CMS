<?php

namespace app\apiadmin\controller\b2burm;

use app\common\controller\Backend;
use app\common\logic\AuditTemplateLogic;
use app\common\model\shortvideo\SvPersonalVideo;
use app\common\model\Uploadfile;
use Think\Db;
use think\Validate;

class Resume extends Backend
{
    /**
     * @return void
     * 简历列表
     */
    public function index()
    {
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
        $citycategory = input('post.citycategory/a', []);
        $jobcategory = input('post.jobcategory/a', []);
        $platform = input('post.platform/s', '', 'trim');
        $enclosure_resume = input('post.enclosure_resume/d', 0, 'intval'); // 附件简历
        $img_audit = input('post.img_audit/d', 0, 'intval'); // 简历作品审核状态
        $sex = input('post.sex/d', 0, 'intval'); // 性别
        $age = input('post.age/d', 0, 'intval'); // 年龄
        $is_bind = input('post.is_bind/d', 0, 'intval'); // 是否绑定微信
        if ($platform != '') {
            $where['m.platform'] = ['=', $platform];
        }
        if ($sort_type != '' && $sort != '') { // 排序
            if ($sort_type == 'refreshtime') {
                $order = 'r.refreshtime ' . $sort;
            }
            if ($sort_type == 'last_login_time') {
                $order = 'm.last_login_time ' . $sort;
            }
            if ($sort_type == 'reg_time') {
                $order = 'm.reg_time ' . $sort;
            }
            if ($sort_type == 'final_follow') {
                $order = 'r.last_visit_time ' . $sort;
            }
        }
        if ($education > 0) {//学历
            $where['r.education'] = ['=', $education];
        }
        if ($high_quality != '') {//简历等级
            $where['r.high_quality'] = ['=', $high_quality];
        }
        if ($audit != '') {//审核状态
            $where['r.audit'] = ['=', $audit];
        }

        if ($experience > 0) {
            switch ($experience) {
                case 1: //无经验/应届生
                    $where['r.enter_job_time'] = ['=', 0];
                    break;
                case 2://1年以下
                    $where['r.enter_job_time'] = ['>', strtotime('-1 year')];
                    break;
                case 3://1年-2年
                    $where['r.enter_job_time'] = [['<=', strtotime('-1 year')], ['>', strtotime('-2 year')], 'and'];
                    break;
                case 4://2年-3年
                    $where['r.enter_job_time'] = [['<=', strtotime('-2 year')], ['>', strtotime('-3 year')], 'and'];
                    break;
                case 5://3年-5年
                    $where['r.enter_job_time'] = [['<=', strtotime('-3 year')], ['>', strtotime('-5 year')], 'and'];
                    break;
                case 6://5年-10年
                    $where['r.enter_job_time'] = [['<=', strtotime('-5 year')], ['>', strtotime('-10 year')], 'and'];
                    break;
                case 7://10年以上
                    $where['r.enter_job_time'] = [['<', strtotime('-10 year')], ['<>', 0], 'and'];
                    break;
                default:
                    break;
            }
        }

        if ($contact_status > 0) {
            switch ($contact_status) {
                case 1: //电话联系
                    $where['r.is_status_phone'] = ['=', 2];
                    break;
                case 2: //微信联系
                    $where['r.is_status_weixin'] = ['=', 2];
                    break;
                case 3: //从未联系
                    $where['r.is_status_weixin'] = ['=', 1];
                    $where['r.is_status_phone'] = ['=', 1];
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
            r.sex,
            r.birthday,
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
                    $where['m.mobile'] = ['like', "%{$keyword}%"];
                    break;
                case 3: // 企业手机号
                    $total_join[] = ['resume_contact c', 'r.id=c.rid', 'LEFT'];
                    $where['c.mobile'] = ['like', "%{$keyword}%"];
                    break;
                case 4: // 简历ID
                    $where['r.id'] = ['=', $keyword];
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

        if (!empty($citycategory) || !empty($jobcategory)) {
            $total_join[] = ['ResumeIntention ris', 'ris.rid=r.id', 'LEFT'];
            $list_join[] = ['ResumeIntention ris', 'ris.rid=r.id', 'LEFT'];
            if (!empty($citycategory)) {//意向地区
                if (isset($citycategory[0]) && !empty($citycategory[0])) {
                    $where['ris.district1'] = $citycategory[0];
                }
                if (isset($citycategory[1]) && !empty($citycategory[1])) {
                    $where['ris.district2'] = $citycategory[1];
                }
                if (isset($citycategory[2]) && !empty($citycategory[2])) {
                    $where['ris.district3'] = $citycategory[2];
                }
            }
            if (!empty($jobcategory)) {//意向职位
                if (isset($jobcategory[0]) && !empty($jobcategory[0])) {
                    $where['ris.category1'] = $jobcategory[0];
                }
                if (isset($jobcategory[1]) && !empty($jobcategory[1])) {
                    $where['ris.category2'] = $jobcategory[1];
                }
                if (isset($jobcategory[2]) && !empty($jobcategory[2])) {
                    $where['ris.category3'] = $jobcategory[2];
                }
            }
        }

        // 附件简历筛选条件
        if (!empty($enclosure_resume)) {
            switch ($enclosure_resume) {
                case 1:
                    $total_join[] = ['ResumeEnclosure re', 're.rid=r.id', 'LEFT'];
                    $list_join[] = ['ResumeEnclosure re', 're.rid=r.id', 'LEFT'];
                    $where['re.id'] = ['not null', ''];
                    break;

                case 2:
                    $total_join[] = ['ResumeEnclosure re', 're.rid=r.id', 'LEFT'];
                    $list_join[] = ['ResumeEnclosure re', 're.rid=r.id', 'LEFT'];
                    $where['re.id'] = ['null', ''];
                    break;

                default:
                    break;
            }
        }

        /**
         * [新增]:
         * 是否绑定微信
         * 参数:0-不限|1-已绑定|2-未绑定
         * yx - 2023.01.04
         */
        if (!empty($is_bind)) {
            switch ($is_bind) {
                case 1:
                    $total_join[] = ['member_bind bind', "bind.uid = r.uid and bind.type='weixin' and bind.is_subscribe=1", 'LEFT'];
                    $where['bind.id'] = ['not null', ''];
                    break;

                case 2:
                    $total_join[] = ['member_bind bind', "bind.uid = r.uid and bind.type='weixin' and bind.is_subscribe=1", 'LEFT'];
                    $where['bind.id'] = ['null', ''];
                    break;

                default:
                    break;
            }
        }

        // 性别筛选条件
        if (!empty($sex)) {//审核状态
            $where['r.sex'] = ['=', $sex];
        }

        /**
         * [新增]:
         * 年龄
         * 参数:0-不限|1-16~20岁|2-20~30岁|3-30~40岁|4-40~50岁|5-50岁以上
         * yx - 2023.01.04
         */
        if (!empty($age)) {
            switch ($age) {
                case 1:
                    $where['r.birthday'] = [
                        ['<=', (date('Y') - 16)],
                        ['>=', (date('Y') - 20)]
                    ];
                    break;

                case 2:
                    $where['r.birthday'] = [
                        ['<=', (date('Y') - 20)],
                        ['>=', (date('Y') - 30)]
                    ];
                    break;

                case 3:
                    $where['r.birthday'] = [
                        ['<=', (date('Y') - 30)],
                        ['>=', (date('Y') - 40)]
                    ];
                    break;

                case 4:
                    $where['r.birthday'] = [
                        ['<=', (date('Y') - 40)],
                        ['>=', (date('Y') - 50)]
                    ];
                    break;

                case 5:
                    $where['r.birthday'] = ['<=', (date('Y') - 50)];
                    break;

                default:
                    break;
            }
        }

        /**
         * [新增]:
         * 简历作品
         * 参数:0-全部|1-未上传|2-待审核|3-未通过|4-已通过
         * 审核状态:0-待审核|1-审核通过|2-审核未通过
         * yx - 2023.01.04
         */
        if (!empty($img_audit)) {
            switch ($img_audit) {
                case 1:
                    $total_join[] = ['ResumeImg ri', 'ri.rid=r.id', 'LEFT'];
                    $list_join[] = ['ResumeImg ri', 'ri.rid=r.id', 'LEFT'];
                    $where['ri.id'] = ['null', ''];
                    break;

                case 2:
                    $total_join[] = ['ResumeImg ri0', 'ri0.rid=r.id and ri0.audit=0', 'LEFT'];
                    $list_join[] = ['ResumeImg ri0', 'ri0.rid=r.id and ri0.audit=0', 'LEFT'];
                    $where['ri0.id'] = ['not null', ''];
                    break;

                case 3:
                    $total_join[] = ['ResumeImg ri0', 'ri0.rid=r.id and ri0.audit=0', 'LEFT'];
                    $list_join[] = ['ResumeImg ri0', 'ri0.rid=r.id and ri0.audit=0', 'LEFT'];
                    $total_join[] = ['ResumeImg ri2', 'ri2.rid=r.id and ri2.audit=2', 'LEFT'];
                    $list_join[] = ['ResumeImg ri2', 'ri2.rid=r.id and ri2.audit=2', 'LEFT'];
                    $where['ri0.id'] = ['null', ''];
                    $where['ri2.id'] = ['not null', ''];
                    break;

                case 4:
                    $total_join[] = ['ResumeImg ri0', 'ri0.rid=r.id and ri0.audit=0', 'LEFT'];
                    $list_join[] = ['ResumeImg ri0', 'ri0.rid=r.id and ri0.audit=0', 'LEFT'];
                    $total_join[] = ['ResumeImg ri1', 'ri1.rid=r.id and ri1.audit=1', 'LEFT'];
                    $list_join[] = ['ResumeImg ri1', 'ri1.rid=r.id and ri1.audit=1', 'LEFT'];
                    $total_join[] = ['ResumeImg ri2', 'ri2.rid=r.id and ri2.audit=2', 'LEFT'];
                    $list_join[] = ['ResumeImg ri2', 'ri2.rid=r.id and ri2.audit=2', 'LEFT'];
                    $where['ri0.id'] = ['null', ''];
                    $where['ri1.id'] = ['not null', ''];
                    $where['ri2.id'] = ['null', ''];
                    break;

                default:
                    break;
            }
        }

        $total = model('Resume')->alias('r');
        foreach ($total_join as $k => $v) {
            $total = $total->join($v[0], $v[1], $v[2]);
        }
        $total = $total->join('member m', 'r.uid=m.uid', 'LEFT')
            ->where($where)
            ->field($total_field)
            ->group('r.id')
            ->having($having_where)
            ->count();
        $list = model('Resume')->alias('r');
        foreach ($list_join as $k => $v) {
            $list = $list->join($v[0], $v[1], $v[2]);
        }
        $list = $list->join('member m', 'r.uid=m.uid', 'LEFT')
            ->join('resume_contact c', 'r.id=c.rid', 'LEFT')
            ->join('member_bind bind', "bind.uid = r.uid and bind.type='weixin' and bind.is_subscribe=1", 'LEFT')
            ->where($where)
            ->field($field)
            ->group('r.id')
            ->order($order)
            ->having($having_where)
            ->page($current_page . ',' . $pagesize)
            ->select();
        $id_arr = array_column($list, 'id');
        if ($delivery_num <= 0) {
            $job_apply = db('job_apply')->alias('ja')
                ->join('job j', 'ja.jobid = j.id')
                ->where('j.jobname', 'not null')
                ->where('ja.resume_id', 'in', $id_arr)
                ->field('count(ja.id) as num,ja.resume_id')
                ->group('ja.resume_id')
                ->select();
            if (!empty($job_apply)) {
                $job_apply = array_column($job_apply, null, 'resume_id');
            }
        }
        if ($downloaded <= 0) {
            $company_down_resume = db('company_down_resume')
                ->alias('a')
                ->where('a.resume_id', 'in', $id_arr)
                ->field('count(a.id) as num,a.resume_id')
                ->group('a.resume_id')
                ->select();
            if (!empty($company_down_resume)) {
                $company_down_resume = array_column($company_down_resume, null, 'resume_id');
            }
        }
        if ($viewed <= 0) {
            $view_resume = db('view_resume')
                ->where('resume_id', 'in', $id_arr)
                ->field('count(id) as num,resume_id')
                ->group('resume_id')
                ->select();
            if (!empty($view_resume)) {
                $view_resume = array_column($view_resume, null, 'resume_id');
            }
        }
        if ($invitation <= 0) {
            //被面邀
            $company_interview = db('company_interview')->alias('ci')
                ->join('job j', 'ci.jobid = j.id')
                ->where('j.jobname', 'not null')
                ->where('ci.resume_id', 'in', $id_arr)
                ->field('count(ci.id) as num,ci.resume_id')
                ->group('ci.resume_id')
                ->select();
            if (!empty($company_interview)) {
                $company_interview = array_column($company_interview, null, 'resume_id');
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


        foreach ($list as $k => $v) {
            $v['photo_img_src'] = $v['photo_img'] != 0
                ? model('Uploadfile')->getFileUrl(
                    $v['photo_img']
                )
                : default_empty('photo', $v['sex']);
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
            if ($delivery_num <= 0) {
                $v['delivery_num'] = 0;
                if (isset($job_apply[$v['id']]) && !empty($job_apply[$v['id']])) {
                    $v['delivery_num'] = $job_apply[$v['id']]['num'];
                }
            }
            if ($downloaded <= 0) {
                //被下载
                $v['downloaded'] = 0;
                if (isset($company_down_resume[$v['id']]) && !empty($company_down_resume[$v['id']])) {
                    $v['downloaded'] = $company_down_resume[$v['id']]['num'];
                }
            }
            if ($viewed <= 0) {
                //被查看
                $v['viewed'] = 0;
                if (isset($view_resume[$v['id']]) && !empty($view_resume[$v['id']])) {
                    $v['viewed'] = $view_resume[$v['id']]['num'];
                }
            }
            if ($invitation <= 0) {
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
            $v['follow_num'] = model('b2bcrm.CrmFollowUp')->where('relation_id', $v['id'])->count();
            if ($v['last_visit_time'] != null) {
                $v['final_follow'] = date('Y-m-d H:i:s', $v['last_visit_time']);
            } else {
                $v['final_follow'] = 0;
            }

            // 附件简历
            $enclosure_resume = model('ResumeEnclosure')->getEnclosure(['uid' => $v['uid']]);
            $v['enclosure_resume'] = !empty($enclosure_resume) ? $enclosure_resume['enclosure'] : '';

            /**
             * [新增]:
             * 简历作品
             * 审核状态：0-待审核|1-审核通过|2-审核未通过
             * yx - 2023.01.04
             */
            $img_audit_0_num = model('ResumeImg')->where('rid', $v['id'])->where('audit', 0)->count('id');
            $img_audit_1_num = model('ResumeImg')->where('rid', $v['id'])->where('audit', 1)->count('id');
            $img_audit_2_num = model('ResumeImg')->where('rid', $v['id'])->where('audit', 2)->count('id');
            $img_audit_totai_num = $img_audit_0_num + $img_audit_1_num + $img_audit_2_num;
            if ($img_audit_totai_num <= 0) {
                $v['img_audit_id'] = 1;
                $v['img_audit_cn'] = '未上传';
            } else {
                if ($img_audit_0_num > 0) {
                    $v['img_audit_id'] = 2;
                    $v['img_audit_cn'] = '待审核（' . $img_audit_0_num . '/' . $img_audit_totai_num . '）';
                } elseif ($img_audit_2_num > 0) {
                    $v['img_audit_id'] = 3;
                    $v['img_audit_cn'] = '未通过（' . $img_audit_2_num . '/' . $img_audit_totai_num . '）';
                } else {
                    $v['img_audit_id'] = 4;
                    $v['img_audit_cn'] = '已通过（' . $img_audit_1_num . '/' . $img_audit_totai_num . '）';
                }
            }
            $v['resume_img_audit_num'] = [
                'img_audit_0_num' => $img_audit_0_num,
                'img_audit_1_num' => $img_audit_1_num,
                'img_audit_2_num' => $img_audit_2_num
            ];

            // 年龄
            $v['age'] = intval($v['birthday']) == 0 ? '年龄未知' : date('Y') - intval($v['birthday']) . '岁';
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
    public function resumeDetail()
    {
        $resume_id = input('get.id/d', 0, 'intval');
        $start = input('get.start/s', '', 'trim');
        $end = input('get.end/s', '', 'trim');
        if ($resume_id == 0) {
            $this->ajaxReturn(500, '请选择简历');
        }
        // r.last_visit_time,
        $data = model('Resume')
            ->alias('r')
            ->where('r.id', $resume_id)
            ->join('member m', 'r.uid=m.uid', 'LEFT')
            ->join('resume_contact c', 'r.id=c.rid', 'LEFT')
            ->join('member_points mp', 'r.uid = mp.uid', 'LEFT')
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

        $data['photo_img_src'] = $data['photo_img'] > 0
            ? model('Uploadfile')->getFileUrl(
                $data['photo_img']
            )
            : default_empty('photo', $data['sex']);
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
        $data['platform_cn'] = isset(model('BaseModel')->map_platform[$data['platform']]) ? model('BaseModel')->map_platform[$data['platform']] : '未知平台';
        $data['follow_up_num'] = model('b2bcrm.CrmFollowUp')->where('uid', $data['uid'])->count();
        $where = [];
        if ($start != '' && $end != '') {
            $start = strtotime($start . ' 00:00:00');
            $end = strtotime($end . ' 23:59:59');
            $where['a.addtime'] = [['>', $start], ['<=', $end], 'and'];
        }
        //浏览职位
        $data['view_job'] = model('ViewJob')
            ->alias('a')
            ->where('a.personal_uid', $data['uid'])
            ->where($where)
            ->count();
        //投递数
        $data['delivery_num'] = model('JobApply')
            ->alias('a')
            ->join('job j', 'a.jobid = j.id')
            ->where('j.jobname', 'not null')
            ->where('a.resume_id', $data['id'])
            ->where($where)
            ->count();
        //被下载
        $data['downloaded'] = model('CompanyDownResume')
            ->alias('a')
            ->where('a.resume_id', $data['id'])
            ->where($where)
            ->count();
        //被查看
        $data['viewed'] = model('ViewResume')
            ->alias('a')
            ->where('a.resume_id', $data['id'])
            ->where($where)
            ->count();
        //被面邀
        $data['invitation'] = model('CompanyInterview')
            ->alias('a')
            ->join('job j', 'a.jobid = j.id')
            ->where('j.jobname', 'not null')
            ->where('a.resume_id', $data['id'])
            ->where($where)
            ->count();
        //主动刷新
        $data['refresh'] = model('RefreshResumeLog')
            ->alias('a')
            ->where('a.uid', $data['uid'])
            ->where($where)
            ->count();
        $resume_img = model('ResumeImg')->where('rid', $data['id'])->field('img,audit,id')->select();
        $resume_img_arr = [];
        foreach ($resume_img as $k => $v) {
            $img_src = model('Uploadfile')->getFileUrl(
                $v['img']
            );
            $resume_img_arr[] = [
                'id' => $v['id'],
                'img_id' => $v['img'],
                'img_src' => $img_src,
                'audit' => $v['audit']
            ];
        }
        $data['resume_img_arr'] = $resume_img_arr;
        $m = new SvPersonalVideo();
        $resume_video = $m->where('uid', $data['uid'])->field('id,is_public,audit,filesize,fid,view_count')->select();
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

        $enclosure_resume = model('ResumeEnclosure')->getEnclosure(['rid' => $resume_id]);
        if (isset($enclosure_resume) && !empty($enclosure_resume)) {
            $data['enclosure_resume'] = $enclosure_resume->toArray();
        } else {
            $data['enclosure_resume'] = '';
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
            ->where('j.jobname', 'not null')
            ->count();
        $list = model('JobApply')
            ->alias('a')
            ->field(
                'a.id,a.comid,a.companyname,a.jobid,a.jobname,a.resume_id,a.fullname,a.note,a.addtime,a.is_look,a.handle_status,j.education,j.experience,j.district,j.minwage,j.maxwage,j.negotiable,j.click,j.is_display'
            )
            ->join(config('database.prefix') . 'job j', 'j.id=a.jobid', 'left')
            ->where($where)
            ->where('j.jobname', 'not null')
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
            $tmp_arr['job_link_url_web'] = url('index/job/show', ['id' => $value['jobid']]);
            $tmp_arr['company_link_url_web'] = url('index/company/show', ['id' => $value['comid']]);

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
            ->where('b.id', 'not null')
            ->count();
        $list = model('CompanyInterview')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.jobid=b.id', 'left')
            ->field('a.id,a.comid,a.companyname,a.jobid,a.jobname,a.resume_id,a.fullname,a.interview_time,a.contact,a.address,a.tel,a.note,a.addtime,a.is_look,b.education,b.experience,b.nature,b.minwage,b.maxwage,b.negotiable,b.map_lat,b.map_lng')
            ->where($where)
            ->where('b.id', 'not null')
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
            $value['job_link_url_web'] = url('index/job/show', ['id' => $value['jobid']]);
            $value['company_link_url_web'] = url('index/company/show', ['id' => $value['comid']]);

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
            ->join(config('database.prefix') . 'company b', 'a.company_uid=b.uid', 'LEFT')
            ->where($where)
            ->where('b.companyname', 'not null')
            ->count();
        $list = model('ViewResume')
            ->alias('a')
            ->join(config('database.prefix') . 'company b', 'a.company_uid=b.uid', 'LEFT')
            ->field('a.*,b.companyname,b.id as company_id,b.audit as company_audit,b.district,b.scale,b.nature')
            ->where($where)
            ->where('b.companyname', 'not null')
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
            ->column('comid,id', 'comid');
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
            $tmp_arr['company_link_url_web'] = url('index/company/show', ['id' => $value['company_id']]);
            $returnlist[] = $tmp_arr;
        }
        $return['items'] = $returnlist;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function viewjob()
    {
        $uid = input('get.uid/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        $where['a.personal_uid'] = $uid;
        $total = model('ViewJob')
            ->alias('a')
            ->join(config('database.prefix') . 'company b', 'a.company_uid=b.uid', 'LEFT')
            ->join(config('database.prefix') . 'job c', 'a.jobid=c.id', 'LEFT')
            ->where($where)
            ->where('b.companyname', 'not null')
            ->where('c.jobname', 'not null')
            ->count();
        $list = model('ViewJob')
            ->alias('a')
            ->join(config('database.prefix') . 'company b', 'a.company_uid=b.uid', 'LEFT')
            ->join(config('database.prefix') . 'job c', 'a.jobid=c.id', 'LEFT')
            ->field('a.*,b.companyname,b.audit as company_audit,b.id as company_id,c.jobname,c.education,c.experience,c.district,c.negotiable,c.minwage,c.maxwage,c.click')
            ->where($where)
            ->where('b.companyname', 'not null')
            ->where('c.jobname', 'not null')
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
            $tmp_arr['job_link_url_web'] = url('index/job/show', ['id' => $value['jobid']]);
            $tmp_arr['company_link_url_web'] = url('index/company/show', ['id' => $value['company_id']]);
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
        if (!$uid) {
            $this->ajaxReturn(500, '参数错误');
        }
        $type = input('post.type/s', '', 'trim');
        $needle = model('resume')->needle;
        if ($type == '' && !isset($needle[$type])) {
            $this->ajaxReturn(500, '请选择正确的简历编辑项');
        } else {
            $needle_arr = $needle[$type];
        }
        $input_data = [];
        foreach ($needle_arr as $k => $v) {
            $data = input('post.' . $k . $v['type'],
                $v['default'],
                $v['filter']);
            if (!empty($v['validate'])) {
                $rule = [
                    $k => $v['validate']['rule'],
                ];
                $msg = [
                    $k => $v['validate']['message'],
                ];
                $validate = new Validate($rule, $msg);
                if (!$validate->check([$k => $data])) {
                    $this->ajaxReturn(500, $validate->getError());
                }
            }
            $input_data[$k] = $data;
        }
        try {
            Db::startTrans();

            $member_info = model('Member')
                ->where('uid', $uid)
                ->field('password,mobile,email')
                ->find();
            $fullname = model('Resume')->getFullnameByUid($uid, '-');
            $resumeId = model('Resume')->where('uid', $uid)->value('id');

            $log_type = 3;

            switch ($type) {
                case 'password':
                    if (isset($input_data['password'])) {
                        $log_field = '修改{' . $fullname . '}(会员ID:' . $uid . ')的账号密码，新密码:' . $input_data['password'];
                    }
                case 'member_mobile':
                    if (isset($input_data['member_mobile'])) {
                        $log_field = '修改{' . $fullname . '}(会员ID:' . $uid . ')的会员手机，' . $member_info['mobile'] . ' -> ' . $input_data['member_mobile'];
                    }
                case 'email':
                    if (isset($input_data['email'])) {
                        $old_email = (!empty($member_info['email'])) ? $member_info['email'] : '无';
                        $new_email = (!empty($input_data['email'])) ? $input_data['email'] : '未填写';
                        $log_field = '对{' . $fullname . '}(会员ID:' . $uid . ')修改会员绑定邮箱， ' . $old_email . ' -> ' . $new_email;
                    }
                    $this->updateMember($uid, $input_data, $type);
                    break;
                case 'resume_mobile':
                    $resume_contact = model('ResumeContact')->where('uid', $uid)->value('mobile');
                    $this->updateResumeMobile($uid, $input_data);
                    $old_contact = (!empty($resume_contact)) ? $resume_contact : '无';
                    $log_field = '修改{' . $fullname . '}(简历ID:' . $resumeId . ')的简历手机，' . $old_contact . ' -> ' . $input_data['resume_mobile'];
                    break;
                case 'resume_img':
                    $this->auditResumeImg($input_data);
                    $log_field = '审核{' . $fullname . '}(简历ID:' . $resumeId . ')的简历作品，' . model('ResumeImg')->map_audit[$input_data['examine']];
                    $log_type = 6;
                    break;
                case 'is_display':
                    if (isset($input_data['is_display'])) {
                        $resume_display = model('Resume')->where('uid', $uid)->value('is_display');
                        $log_field = '对{' . $fullname . '}(简历ID:' . $resumeId . ')的简历设置公开状态，' . model('Resume')->map_is_display[$resume_display] . ' -> ' . model('Resume')->map_is_display[$input_data['is_display']];
                    }
                case 'high_quality':
                    if (isset($input_data['high_quality'])) {
                        $high_quality = model('Resume')->where('uid', $uid)->value('high_quality');
                        $log_field = '设置简历等级{' . $fullname . '}(简历ID:' . $resumeId . ')，简历等级:' . model('Resume')->map_high_quality[$high_quality] . ' -> ' . model('Resume')->map_high_quality[$input_data['high_quality']];
                    }
                case 'audit':
                    if (isset($input_data['audit'])) {
                        if ($input_data['audit'] === 2) {
                            /**
                             * 【ID1000663】
                             * 【优化】简历未通过新增审核模板及审核说明
                             * cy 2023-7-3
                             */
                            // 添加模板
                            if (isset($input_data['add_template']) && $input_data['add_template'] == 1 && !empty($input_data['reason'])) {
                                $templateParams = [
                                    'type' => 1,
                                    'content' => $input_data['reason']
                                ];
                                $auditTemplateLogic = new AuditTemplateLogic();
                                $result = $auditTemplateLogic->addTemplate($templateParams, $this->admininfo);
                                if (false === $result['status']) {
                                    throw new \Exception($result['msg']);
                                }
                            }
                            // 填写了原因的话不获取模板内容
                            if (empty($input_data['reason']) && isset($input_data['template_id']) && !empty($input_data['template_id'])) {
                                // 获取模板内容
                                $templateWhere = [
                                    'id' => $input_data['template_id'],
                                    'is_del' => 0
                                ];
                                $templateContent = model('audit_template')->getValue($templateWhere, 'content');
                                $input_data['reason'] = !empty($templateContent) ? $templateContent : $input_data['reason'];
                            }
                        }

                        $resume_audit = model('Resume')->where('uid', $uid)->value('audit');
                        $log_field = '审核简历{' . $fullname . '}(简历ID:' . $resumeId . ')，审核状态:' . model('Resume')->map_audit[$resume_audit] . ' -> ' . model('Resume')->map_audit[$input_data['audit']];
                        if ($input_data['audit'] === 2) {
                            $reason = (!empty($input_data['reason'])) ? $input_data['reason'] : '未填写';
                            $log_field .= '，原因:' . $reason;
                        }
                        $log_type = 6;
                    }
                case 'comment':
                    if (isset($input_data['comment'])) {
                        $resume_comment = model('Resume')->where('uid', $uid)->value('comment');
                        $old_comment = (!empty($resume_comment)) ? $resume_comment : '无';
                        $new_comment = (!empty($input_data['comment'])) ? $input_data['comment'] : '未填写';
                        $log_field = '对{' . $fullname . '}(简历ID:' . $resumeId . ')的简历填写客服评价，内容:' . $old_comment . ' -> ' . $new_comment;
                    }
                case 'remark':
                    if (isset($input_data['remark'])) {
                        $resume_remark = model('Resume')->where('uid', $uid)->value('remark');
                        $old_remark = (!empty($resume_remark)) ? $resume_remark : '无';
                        $new_remark = (!empty($input_data['remark'])) ? $input_data['remark'] : '未填写';
                        $log_field = '修改{' . $fullname . '}(简历ID:' . $resumeId . ')的简历备注，' . $old_remark . ' -> ' . $new_remark;
                    }
                default:
                    $this->editResume($uid, $input_data, $type);
                    break;
            }
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, $e->getMessage());
        }

        model('AdminLog')->writeLog(
            $log_field,
            $this->admininfo,
            0,
            $log_type
        );

        Db::commit();

        $this->ajaxReturn(200, '修改成功');
    }

    // 审核简历作品
    private function auditResumeImg($data)
    {
        Db::startTrans();
        try {
            model('ResumeImg')
                ->where('id', 'in', implode(',', $data['resume_img']))
                ->setField('audit', $data['examine']);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new \Exception($e->getMessage());
        }
        return true;
    }

    // 修改简历手机号
    private function updateResumeMobile($uid, $input_data)
    {
        $data = ['uid' => $uid];
        $preg_phone = '/^1[3456789]\d{9}$/ims';
        if (!preg_match($preg_phone, $input_data['resume_mobile'])) {
            throw new \Exception('手机号格式错误');
        }
        $data['mobile'] = $input_data['resume_mobile'];
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
            throw new \Exception(model('ResumeContact')->getError());
        }
        return true;
    }

    // 修改简历
    private function editResume($uid, $data, $type)
    {
        $resume_id = model('Resume')
            ->where(['uid' => ['in', $uid]])
            ->column('id');
        if (empty($resume_id)) {
            throw new \Exception('简历uid错误');
        }
        if ($type == 'audit') {
            model('Resume')->setAudit($resume_id, $data['audit'], $data['reason']);
        } else {
            $is_refreshSearch = false;
            if ($type == 'high_quality' || $type == 'is_display') {
                $is_refreshSearch = true;
            }
            $update_member = model('Resume')
                ->allowField(true)
                ->save($data, ['uid' => $uid]);
            if (false === $update_member) {
                throw new \Exception(model('Resume')->getError());
            }
            if ($is_refreshSearch) {
                model('Resume')->refreshSearch(0, $uid);//更新简历索引
            }
        }
        return true;
    }

    // 修改密码或手机号
    private function updateMember($uid, $data, $type)
    {
        $clean_token = false;
        switch ($type) {
            case 'password':
                $pwd_hash = model('Member')->where('uid', $uid)->value('pwd_hash');
                $input_data['password'] = model('Member')->makePassword(
                    $data['password'],
                    $pwd_hash
                );
                $clean_token = true;
                break;
            case 'member_mobile':
                $preg_phone = '/^1[345789]\d{9}$/ims';
                if (!preg_match($preg_phone, $data['member_mobile'])) {
                    throw new \Exception('手机号格式错误');
                }
                /**
                 * 【ID1000439】
                 * 【bug】原个人会员手机号存在，企业客户详情不能修改，会员能修改的问题
                 * 说明：
                 * 修改途中发现，URM中修改手机号查询全部`Member`表，新增只查询个人会员
                 * yx - 2022.11.24
                 * [新增]:
                 * 'utype' => 2
                 */
                $member_uid = model('Member')
                    ->where([
                        'mobile' => $data['member_mobile'],
                        'utype' => 2
                    ])
                    ->value('mobile');
                if (!empty($member_uid) && $member_uid != $uid) {
                    throw new \Exception('手机号已存在');
                }
                $input_data['mobile'] = $data['member_mobile'];
                $clean_token = true;
                break;
            case 'email':
                if (isset($data['email']) && $data['email'] != '') {
                    $preg_email = '/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims';

                    if (!preg_match($preg_email, $data['email'])) {
                        throw new \Exception('请输入正确的邮箱格式');
                    }
                }
                $input_data['email'] = $data['email'];
                break;
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
        if ($clean_token) {
            model('IdentityToken')->where(['uid' => $uid])->delete(); //修改密码即删除token,
        }
        return true;
    }

    /**
     * 简历联系状态
     */
    public function setContactStatus()
    {
        $id = input('get.id/d', 0, 'intval');
        $type = input('get.type/s', '', 'trim');
        if ($id <= 0) {
            $this->ajaxReturn(500, '简历ID错误');
        }
        $resume = model('Resume')->where('id', $id)->find();
        if (empty($resume)) {
            $this->ajaxReturn(500, '简历信息错误');
        }
        $data = [];
        $msg = '';

        $log_field = '对{' . $resume['fullname'] . '}(简历ID:' . $id . ')的简历修改联系状态，';

        if ($type == 'weixin') {
            $msg = '设置微信';
            if ($resume['is_status_weixin'] == 1) {
                $data['is_status_weixin'] = 2;
                $log_field .= '未添加微信 -> 已添加微信';
            } else {
                $data['is_status_weixin'] = 1;
                $log_field .= '已添加微信 -> 未添加微信';
            }
        } else {
            $msg = '设置电话';
            if ($resume['is_status_phone'] == 1) {
                $data['is_status_phone'] = 2;
                $log_field .= '未电话联系 -> 已电话联系';

            } else {
                $data['is_status_phone'] = 1;
                $log_field .= '已电话联系 -> 未电话联系';
            }
        }
        $res = model('Resume')->save($data, ['id' => $id]);
        if (false === $res) {
            $this->ajaxReturn(500, $msg . '失败');
        }

        model('AdminLog')->writeLog(
            $log_field,
            $this->admininfo,
            0,
            1
        );

        $this->ajaxReturn(200, $msg . '成功');
    }
}
