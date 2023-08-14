<?php
/**
 * 申请职位列表
 */
namespace app\v1_0\controller\personal;

class JobApply extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(2);
        $this->interceptPersonalResume();
    }
    public function index()
    {
        $where['a.personal_uid'] = $this->userinfo->uid;
        $status = input('get.status/d', 0, 'intval');
        $settr = input('get.settr/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        switch ($status) {
            case 1: //hr未查看
                $where['a.is_look'] = 0;
                break;
            case 2: //hr已查看
                $where['a.is_look'] = 1;
                break;
            case 3: //同意面试
                $where['a.handle_status'] = 1;
                break;
            case 4: //已被婉拒
                $where['a.handle_status'] = 2;
                break;
            case 5: //停止招聘
                $where['j.is_display'] = 0;
                break;
        }

        if ($settr != 0) {
            $where['a.addtime'] = ['egt', strtotime('-' . $settr . 'day')];
        }

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
            $tmp_arr['job_link_url_web'] = url('index/job/show', ['id' => $value['jobid']], true, $this->sub_site_domain);
            $tmp_arr['company_link_url_web'] = url('index/company/show', ['id' => $value['comid']], true, $this->sub_site_domain);

            $returnlist[] = $tmp_arr;
        }

        $return['items'] = $returnlist;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function total()
    {
        $where['a.personal_uid'] = $this->userinfo->uid;
        $status = input('get.status/d', 0, 'intval');
        $settr = input('get.settr/d', 0, 'intval');
        switch ($status) {
            case 1: //hr未查看
                $where['a.is_look'] = 0;
                break;
            case 2: //hr已查看
                $where['a.is_look'] = 1;
                break;
            case 3: //同意面试
                $where['a.handle_status'] = 1;
                break;
            case 4: //已被婉拒
                $where['a.handle_status'] = 2;
                break;
            case 5: //停止招聘
                $where['j.is_display'] = 0;
                break;
        }

        if ($settr != 0) {
            $where['a.addtime'] = ['egt', strtotime('-' . $settr . 'day')];
        }

        $total = model('JobApply')
            ->alias('a')
            ->join(config('database.prefix') . 'job j', 'j.id=a.jobid', 'left')
            ->where($where)
            ->where('j.jobname','not null')
            ->count();
        
        $this->ajaxReturn(200, '获取数据成功', $total);
    }
    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        model('JobApply')
            ->where([
                'id' => ['eq', $id],
                'personal_uid' => $this->userinfo->uid
            ])
            ->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'删除已申请的职位记录【记录ID：'.$id.'】');
        $this->ajaxReturn(200, '删除成功');
    }
    public function deleteBatch()
    {
        $id = input('post.id/a',[]);
        if(empty($id)){
            $this->ajaxReturn(500,'请选择记录');
        }
        model('JobApply')
            ->whereIn('id',$id)
            ->where('personal_uid',$this->userinfo->uid)
            ->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'删除已申请的职位记录【记录ID：'.implode(",",$id).'】');
        $this->ajaxReturn(200, '删除成功');
    }
}
