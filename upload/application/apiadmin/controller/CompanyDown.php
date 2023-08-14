<?php

namespace app\apiadmin\controller;

class CompanyDown extends \app\common\controller\Backend
{
    /**
     * 简历下载列表
     */
    public function index()
    {
        $where = [];
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');

        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['a.comid'] = ['eq', intval($keyword)];
                    break;
                case 2:
                    $where['a.uid'] = ['eq', intval($keyword)];
                    break;
                case 3:
                    $where['a.resume_id'] = ['eq', intval($keyword)];
                    break;
                case 4:
                    $where['a.personal_uid'] = ['eq', intval($keyword)];
                    break;
                default:
                    break;
            }
        }
        $total = model('CompanyDownResume')
            ->alias('a')
            ->join(config('database.prefix').'resume b','a.resume_id=b.id','LEFT')
            ->join(config('database.prefix').'company c','a.comid=c.id','LEFT')
            ->where($where)
            ->where('b.id','not null')
            ->where('c.id','not null')
            ->count();
        $list = model('CompanyDownResume')
            ->alias('a')
            ->join(config('database.prefix').'resume b','a.resume_id=b.id','LEFT')
            ->join(config('database.prefix').'company c','a.comid=c.id','LEFT')
            ->where($where)
            ->where('b.id','not null')
            ->where('c.id','not null')
            ->field('a.*,b.fullname,b.birthday,b.sex,b.education,b.enter_job_time,c.companyname')
            ->order('a.id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();

        foreach ($list as $key => $value) {
            $value['age'] = date('Y') - intval($value['birthday']) . '岁';
            $value['sex_'] = model('Resume')->map_sex[$value['sex']];
            $value['education_'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[
                    $value['education']
                ]
                : '';
            $value['experience_'] =
                $value['enter_job_time'] == 0
                    ? '无经验'
                    : format_date($value['enter_job_time']);
            $value['company_link'] = config('global_config.sitedomain').url('index/company/show', [
                'id' => $value['comid']
            ]);
            $value['resume_link'] = config('global_config.sitedomain').url('index/resume/show', [
                'id' => $value['resume_id']
            ]);

            $list[$key] = $value;
        }

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
