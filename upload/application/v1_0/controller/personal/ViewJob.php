<?php
/**
 * 足迹（查看职位记录）
 */
namespace app\v1_0\controller\personal;

class ViewJob extends \app\v1_0\controller\common\Base
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
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');

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
            $tmp_arr['job_link_url_web'] = config('global_config.sitedomain').url('index/job/show',['id'=>$value['jobid']]);
            $tmp_arr['company_link_url_web'] = config('global_config.sitedomain').url('index/company/show',['id'=>$value['company_id']]);
            $returnlist[] = $tmp_arr;
        }

        $return['items'] = $returnlist;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    
    public function total()
    {
        $where['a.personal_uid'] = $this->userinfo->uid;

        $total = model('ViewJob')
            ->alias('a')
            ->join(config('database.prefix').'company b','a.company_uid=b.uid','LEFT')
            ->join(config('database.prefix').'job c','a.jobid=c.id','LEFT')
            ->where($where)
            ->where('b.companyname','not null')
            ->where('c.jobname','not null')
            ->count();
        
        $this->ajaxReturn(200, '获取数据成功', $total);
    }
    public function delete()
    {
        $id = input('post.id/d', 0, 'intval');
        model('ViewJob')
            ->where([
                'id' => ['eq', $id],
                'personal_uid' => $this->userinfo->uid
            ])
            ->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'删除已查看职位记录【记录ID：'.$id.'】');
        $this->ajaxReturn(200, '删除成功');
    }
    public function deleteBatch()
    {
        $id = input('post.id/a',[]);
        if(empty($id)){
            $this->ajaxReturn(500,'请选择记录');
        }
        model('ViewJob')
            ->whereIn('id',$id)
            ->where('personal_uid',$this->userinfo->uid)
            ->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'删除已查看职位记录【记录ID：'.implode(",",$id).'】');
        $this->ajaxReturn(200, '删除成功');
    }
}
