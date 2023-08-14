<?php
/**
 * 被关注列表
 */
namespace app\v1_0\controller\personal;

class AttentionMe extends \app\v1_0\controller\common\Base
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
                ->column('id,uid,jobname', 'id');
            foreach ($job_data as $key => $value) {
                $job_list[$value['uid']][] = $value['jobname'];
            }
        } else {
            $job_list = [];
        }
        //解决企业下载个人简历，在个人会员中心的对我感兴趣中的标签还是显示未下载PC 触屏
        $downlist = model('CompanyDownResume')
            ->where('personal_uid', 'eq', $this->userinfo->uid)
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
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function total()
    {
        $where['a.personal_uid'] = $this->userinfo->uid;
        $total = model('ViewResume')
            ->alias('a')
            ->join(config('database.prefix').'company b','a.company_uid=b.uid','LEFT')
            ->where($where)
            ->where('b.companyname','not null')
            ->count();
        
        $this->ajaxReturn(200, '获取数据成功', $total);
    }
}
