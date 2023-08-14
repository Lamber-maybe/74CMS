<?php
/**
 * 关注的企业列表
 */
namespace app\v1_0\controller\personal;

class AttentionCompany extends \app\v1_0\controller\common\Base
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

        $list = model('AttentionCompany')
            ->alias('a')
            ->join(config('database.prefix') . 'company b', 'a.company_uid=b.uid', 'left')
            ->field('a.*,b.logo,b.companyname,b.district,b.scale,b.nature,b.trade,b.audit as company_audit')
            ->where($where)
            ->where('b.companyname','not null')
            ->order('a.id desc')
            ->page($current_page, $pagesize)
            ->select();
        $comuid_arr = $logo_arr = $logo_id_arr = [];
        foreach ($list as $key => $value) {
            $comuid_arr[] = $value['company_uid'];
            $value['logo'] > 0 && ($logo_id_arr[] = $value['logo']);
        }
        if (!empty($logo_id_arr)) {
            $logo_arr = model('Uploadfile')->getFileUrlBatch($logo_id_arr);
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

        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $returnlist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['company_id'] = $value['comid'];
            $tmp_arr['companyname'] = $value['companyname'];
            $tmp_arr['company_audit'] = $value['company_audit'];
            $tmp_arr['addtime'] = $value['addtime'];
            $tmp_arr['district_text'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $tmp_arr['trade_text'] = isset(
                $category_data['QS_trade'][$value['trade']]
            )
                ? $category_data['QS_trade'][$value['trade']]
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
            $tmp_arr['jobnum'] = isset($job_list[$value['company_uid']])
                ? count($job_list[$value['company_uid']])
                : 0;
            $tmp_arr['first_jobname'] = isset($job_list[$value['company_uid']])
                ? $job_list[$value['company_uid']][0]
                : '';
            $tmp_arr['logo_src'] = isset($logo_arr[$value['logo']])
                ? $logo_arr[$value['logo']]
                : default_empty('logo');
            $tmp_arr['company_link_url_web'] = config('global_config.sitedomain').url('index/company/show',['id'=>$value['comid']]);

            $returnlist[] = $tmp_arr;
        }

        $return['items'] = $returnlist;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function total()
    {
        $where['a.personal_uid'] = $this->userinfo->uid;

        $total = model('AttentionCompany')
            ->alias('a')
            ->join(config('database.prefix') . 'company b', 'a.company_uid=b.uid', 'left')
            ->where($where)
            ->where('b.companyname','not null')
            ->count();
        
        $this->ajaxReturn(200, '获取数据成功', $total);
    }
    public function cancel()
    {
        $id = input('post.id/d', 0, 'intval');
        $info = model('AttentionCompany')
            ->where([
                'id' => ['eq', $id],
                'personal_uid' => $this->userinfo->uid
            ])
            ->find();
        model('AttentionCompany')
            ->where([
                'id' => ['eq', $id],
                'personal_uid' => $this->userinfo->uid
            ])
            ->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'取消关注企业【企业ID：'.$info->comid.'】');
        $this->ajaxReturn(200, '取消关注成功');
    }
    public function cancelBatch()
    {
        $id = input('post.id/a',[]);
        if(empty($id)){
            $this->ajaxReturn(500,'请选择企业');
        }
        $list = model('AttentionCompany')
            ->where([
                'id' => ['eq', $id],
                'personal_uid' => $this->userinfo->uid
            ])
            ->column('comid');
        model('AttentionCompany')
            ->whereIn('id',$id)
            ->where('personal_uid',$this->userinfo->uid)
            ->delete();
        $list = is_array($list)?$list:[$list];
        $this->writeMemberActionLog($this->userinfo->uid,'取消关注企业【企业ID：'.implode(",",$list).'】');
        $this->ajaxReturn(200, '取消关注成功');
    }
}
