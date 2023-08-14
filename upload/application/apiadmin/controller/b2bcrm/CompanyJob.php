<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;

class CompanyJob extends Backend
{
    /*
     * 企业在招职位
     * */
    public function index()
    {
        $company_id = input('get.company_id/d',0,'intval');
        $type = input('get.type/d', 1, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        if ($company_id <= 0)
        {
            $this->ajaxReturn(500, '请选择企业');
        }
        $where['j.company_id'] = ['eq', $company_id];
        $list = model('Job')
            ->alias('j')
            ->join('company c','c.id=j.company_id','LEFT'
            )
            ->join('member m','j.uid=m.uid','LEFT')
            ->field('j.*,c.companyname,m.mobile as member_mobile');

        $wheres = '';
        switch ($type)
        {
            case 1: // 发布中
                $where['j.audit'] = 1;
                $where['j.is_display'] = 1;
                break;
            case 2: // 待审核
                $where['j.audit'] = 0;
                break;
            case 3: // 已下线
                $wheres = 'j.is_display=0 or j.audit=2';
                break;
        }
        $order = 'refreshtime desc';
        $list = $list
            ->where($where)
            ->where($wheres)
            ->order($order)
            ->page($current_page . ',' . $pagesize)
            ->select();

        $total = model('Job')
            ->alias('j')
            ->join('company c','c.id=j.company_id','LEFT')
            ->join('member m','j.uid=m.uid','LEFT')
            ->where($where)
            ->where($wheres)
            ->count();

        $jobid_arr = $comid_arr = [];
        foreach ($list as $key => $value) {
            $jobid_arr[] = $value['id'];
            $comid_arr[] = $value['company_id'];
        }
        if (!empty($jobid_arr)) {
            $contact_list = model('JobContact')
                ->where('jid', 'in', $jobid_arr)
                ->column('jid,contact,mobile,use_company_contact', 'jid');
            $contact_company_list = model('CompanyContact')
                ->where('comid', 'in', $comid_arr)
                ->column('comid,contact,mobile', 'comid');
            $job_apply_list =  model('JobApply')
                ->where('jobid', 'in', $jobid_arr)
                ->group('jobid')
                ->column('count(*) as num,jobid', 'jobid');
        } else {
            $contact_list = [];
            $contact_company_list = [];
            $job_apply_list = [];
        }
        $category_district_data = model('CategoryDistrict')->getCache();
        foreach ($list as $key => $value) {
            $arr = [];
            $arr['uid'] = $value['uid'];
            $arr['job_link'] = url('index/job/show', ['id' => $value['id']]);
            $arr['id'] = $value['id'];
            $arr['amount'] = $value['amount'];
            $arr['click'] = $value['click'];
            $arr['updatetime'] = $value['updatetime'];
            $arr['audit'] = $value['audit'];
            $arr['is_display'] = $value['is_display'];
            $arr['job_apply_count'] = isset($job_apply_list[$value['id']])
                ? $job_apply_list[$value['id']]
                : 0; // 收到简历数
            $arr['district1_text'] = isset($category_district_data[$value['district1']]) ? $category_district_data[$value['district1']] : '';
            $arr['district2_text'] = isset($category_district_data[$value['district2']]) ? $category_district_data[$value['district2']] : '';
            $arr['district3_text'] = isset($category_district_data[$value['district3']]) ? $category_district_data[$value['district3']] : '';
            if(isset($contact_list[$value['id']])){
                if($contact_list[$value['id']]['use_company_contact']==1){
                    $arr['contact'] = isset($contact_company_list[$value['company_id']])?$contact_company_list[$value['company_id']]['contact']:'';
                    $arr['mobile'] = isset($contact_company_list[$value['company_id']])?$contact_company_list[$value['company_id']]['mobile']:'';
                }else{
                    $arr['contact'] = isset($contact_list[$value['id']])?$contact_list[$value['id']]['contact']:'';
                    $arr['mobile'] = isset($contact_list[$value['id']])?$contact_list[$value['id']]['mobile']:'';
                }
            }else{
                $arr['contact'] = '';
                $arr['mobile'] = '';
            }
            $arr['jobname'] = htmlspecialchars_decode($value['jobname'],ENT_QUOTES);
            $arr['companyname'] = htmlspecialchars_decode($value['companyname'],ENT_QUOTES);
            $arr['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '不限';
            $arr['experience_text'] = isset(
                model('BaseModel')->map_experience[$value['experience']]
            )
                ? model('BaseModel')->map_experience[$value['experience']]
                : '不限';
            $arr['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            $list[$key] = $arr;
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /*
     * 职位关闭
     * */
    public function setDisplay()
    {
        $id = input('post.id/d', 0, 'intval');
        $uid = input('post.uid/d',0,'intval');


        $is_display = input('post.is_display/d', 1, 'intval');
        $jobinfo = model('Job')
            ->field('is_display')
            ->where('id', $id)
            ->where('uid', $uid)
            ->find();
        if ($jobinfo === null) {
            $this->ajaxReturn(500, '没有找到职位信息');
        }
        if ($jobinfo['is_display'] == $is_display) {
            $this->ajaxReturn(200, '设置成功');
        }
        if ($is_display == 1) {
            $enable_num = model('Job')->getEnableJobaddNum($uid);
            if ($enable_num <= 0) {
                $this->ajaxReturn(500, '当前可发布职位数为0，无法恢复');
            }
        }
        $jobinfo->uid = $uid;
        $jobinfo->is_display = $is_display;
        $jobinfo->save();
        model('Job')->refreshSearch($id);
        $this->ajaxReturn(200, '设置成功');
    }
}