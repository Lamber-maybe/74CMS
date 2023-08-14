<?php
/**
 * 职位收藏列表
 */
namespace app\v1_0\controller\personal;

class FavJob extends \app\v1_0\controller\common\Base
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

        $list = model('FavJob')
            ->alias('a')
            ->join(config('database.prefix').'company b','a.company_uid=b.uid','LEFT')
            ->join(config('database.prefix').'job c','a.jobid=c.id','LEFT')
            ->field('a.*,b.id as company_id,b.companyname,b.audit as company_audit,c.jobname,c.education,c.experience,c.district,c.negotiable,c.minwage,c.maxwage,c.click')
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
            $tmp_arr['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            $tmp_arr['click'] = $value['click'];
            $tmp_arr['job_link_url_web'] = url('index/job/show',['id'=>$value['jobid']]);
            $tmp_arr['company_link_url_web'] = url('index/company/show',['id'=>$value['company_id']]);
            $returnlist[] = $tmp_arr;
        }

        $return['items'] = $returnlist;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function total()
    {
        $where['personal_uid'] = $this->userinfo->uid;

        $total = model('FavJob')
            ->alias('a')
            ->join(config('database.prefix').'company b','a.company_uid=b.uid','LEFT')
            ->join(config('database.prefix').'job c','a.jobid=c.id','LEFT')
            ->where($where)
            ->where('b.companyname','not null')
            ->where('c.jobname','not null')
            ->count();
        
        $this->ajaxReturn(200, '获取数据成功', $total);
    }
    public function cancel()
    {
        $id = input('post.id/d', 0, 'intval');
        $info = model('FavJob')
            ->where([
                'id' => ['eq', $id],
                'personal_uid' => $this->userinfo->uid
            ])
            ->find();
        model('FavJob')
            ->where([
                'id' => ['eq', $id],
                'personal_uid' => $this->userinfo->uid
            ])
            ->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'取消收藏职位【职位ID：'.$info->jobid.'】');
        $this->ajaxReturn(200, '取消收藏成功');
    }
    public function cancelBatch()
    {
        $id = input('post.id/a',[]);
        if(empty($id)){
            $this->ajaxReturn(500,'请选择职位');
        }
        $list = model('FavJob')
            ->whereIn('id',$id)
            ->where('personal_uid',$this->userinfo->uid)
            ->column('jobid');
        model('FavJob')
            ->whereIn('id',$id)
            ->where('personal_uid',$this->userinfo->uid)
            ->delete();
        $list = is_array($list)?$list:[$list];
        $this->writeMemberActionLog($this->userinfo->uid,'取消收藏职位【职位ID：'.implode(",",$list).'】');
        $this->ajaxReturn(200, '取消收藏成功');
    }
}
