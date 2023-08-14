<?php
/**
 * 面试邀请列表
 */
namespace app\v1_0\controller\personal;

class Interview extends \app\v1_0\controller\common\Base
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
        $is_look = input('get.is_look/s', '', 'trim');
        $settr = input('get.settr/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        if ($is_look != '') {
            $where['a.is_look'] = intval($is_look);
        }
        if ($settr != 0) {
            $where['a.interview_time'] = [['egt', strtotime('today')],['elt', strtotime('+' . $settr . 'day')],'and'];
        }

        $list = model('CompanyInterview')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.jobid=b.id', 'left')
            ->field('a.id,a.comid,a.companyname,a.jobid,a.jobname,a.resume_id,a.fullname,a.interview_time,a.contact,a.address,a.tel,a.note,a.addtime,a.is_look,b.education,b.experience,b.nature')
            ->where($where)
            ->where('b.id','not null')
            ->order('a.id desc')
            ->page($current_page, $pagesize)
            ->select();

        foreach ($list as $key => $value) {
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
            $value['job_link_url_web'] = config('global_config.sitedomain').url('index/job/show',['id'=>$value['jobid']]);
            $value['company_link_url_web'] = config('global_config.sitedomain').url('index/company/show',['id'=>$value['comid']]);

            $list[$key] = $value;
        }

        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function total()
    {
        $where['a.personal_uid'] = $this->userinfo->uid;
        $is_look = input('get.is_look/s', '', 'trim');
        $settr = input('get.settr/d', 0, 'intval');
        if ($is_look != '') {
            $where['a.is_look'] = intval($is_look);
        }
        if ($settr != 0) {
            $where['a.interview_time'] = [['egt', strtotime('today')],['elt', strtotime('+' . $settr . 'day')],'and'];
        }

        $total = model('CompanyInterview')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.jobid=b.id', 'left')
            ->where($where)
            ->where('b.id','not null')
            ->count();
        
        $this->ajaxReturn(200, '获取数据成功', $total);
    }
    public function setLook()
    {
        $id = input('post.id/d', 0, 'intval');
        model('CompanyInterview')
            ->where('id',$id)
            ->setField('is_look', 1);
        $this->ajaxReturn(200, '设置成功');
    }
}
