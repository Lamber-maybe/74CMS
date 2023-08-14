<?php
namespace app\apiadmin\controller;

class StatJobHot extends \app\common\controller\Backend
{
    /**
     * 职位刷新量排行榜TOP100
     */
    public function refresh()
    {
        $where = [];
        $daterange = input('get.daterange/s', '', 'trim');
        switch ($daterange) {
            case 'week':
                $where['a.addtime'] = ['egt', strtotime('-7 day')];
                break;
            case 'month':
                $where['a.addtime'] = ['egt', strtotime('-30 day')];
                break;
            default:
                break;
        }
        $return = [
            'dataset' => []
        ];
        $datalist = model('RefreshJobLog')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.jobid=b.id', 'LEFT')
            ->where($where)
            ->group('a.jobid')
            ->order('total desc,b.refreshtime desc')
            ->limit(100)
            ->column(
                'b.jobname,b.education,b.experience,b.addtime,b.refreshtime,count(*) as total'
            );

        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['jobname'] = $value['jobname'];
                $arr['education'] = isset(
                    model('BaseModel')->map_education[$value['education']]
                )
                    ? model('BaseModel')->map_education[$value['education']]
                    : '不限';
                $arr['experience'] = isset(
                    model('BaseModel')->map_experience[$value['experience']]
                )
                    ? model('BaseModel')->map_experience[$value['experience']]
                    : '不限';
                $arr['total'] = $value['total'];
                $arr['refreshtime'] = daterange_format(
                    $value['addtime'],
                    $value['refreshtime']
                );
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 简历下载量排行榜TOP100
     */
    public function down()
    {
        $where = [];
        $daterange = input('get.daterange/s', '', 'trim');
        switch ($daterange) {
            case 'week':
                $where['a.addtime'] = ['egt', strtotime('-7 day')];
                break;
            case 'month':
                $where['a.addtime'] = ['egt', strtotime('-30 day')];
                break;
            default:
                break;
        }
        $return = [
            'dataset' => []
        ];
        $datalist = model('CompanyDownResume')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company b',
                'a.comid=b.id',
                'LEFT'
            )
            ->where($where)
            ->where('b.id', 'NEQ', '')
            ->group('a.comid')
            ->order('total desc,b.refreshtime desc')
            ->limit(100)
            ->column(
                'b.companyname,b.nature,b.trade,b.district,b.scale,count(*) as total'
            );

        if (!empty($datalist)) {
            $category_data = model('Category')->getCache();
            $category_district_data = model('CategoryDistrict')->getCache();
            foreach ($datalist as $key => $value) {
                $arr['companyname'] = $value['companyname'];
                $arr['district'] = isset(
                    $category_district_data[$value['district']]
                )
                    ? $category_district_data[$value['district']]
                    : '';
                $arr['trade'] = isset(
                    $category_data['QS_trade'][$value['trade']]
                )
                    ? $category_data['QS_trade'][$value['trade']]
                    : '';
                $arr['scale'] = isset(
                    $category_data['QS_scale'][$value['scale']]
                )
                    ? $category_data['QS_scale'][$value['scale']]
                    : '';
                $arr['nature'] = isset(
                    $category_data['QS_company_type'][$value['nature']]
                )
                    ? $category_data['QS_company_type'][$value['nature']]
                    : '';
                $arr['total'] = $value['total'];
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 企业登录排行榜TOP100
     */
    public function login()
    {
        $where = [];
        $daterange = input('get.daterange/s', '', 'trim');
        switch ($daterange) {
            case 'week':
                $where['a.addtime'] = ['egt', strtotime('-7 day')];
                break;
            case 'month':
                $where['a.addtime'] = ['egt', strtotime('-30 day')];
                break;
            default:
                break;
        }
        $return = [
            'dataset' => []
        ];
        $datalist = model('MemberActionLog')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company b',
                'a.uid=b.uid',
                'LEFT'
            )
            ->where($where)
            ->where('a.is_login', 1)
            ->where('a.utype', 1)
            ->where('b.id', 'NEQ', '')
            ->group('a.uid')
            ->order('total desc,b.refreshtime desc')
            ->limit(100)
            ->column(
                'b.companyname,b.nature,b.trade,b.district,b.scale,count(*) as total'
            );

        if (!empty($datalist)) {
            $category_data = model('Category')->getCache();
            $category_district_data = model('CategoryDistrict')->getCache();
            foreach ($datalist as $key => $value) {
                $arr['companyname'] = $value['companyname'];
                $arr['district'] = isset(
                    $category_district_data[$value['district']]
                )
                    ? $category_district_data[$value['district']]
                    : '';
                $arr['trade'] = isset(
                    $category_data['QS_trade'][$value['trade']]
                )
                    ? $category_data['QS_trade'][$value['trade']]
                    : '';
                $arr['scale'] = isset(
                    $category_data['QS_scale'][$value['scale']]
                )
                    ? $category_data['QS_scale'][$value['scale']]
                    : '';
                $arr['nature'] = isset(
                    $category_data['QS_company_type'][$value['nature']]
                )
                    ? $category_data['QS_company_type'][$value['nature']]
                    : '';
                $arr['total'] = $value['total'];
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /**
     * 职位被投递排行榜TOP100
     */
    public function jobapply()
    {
        $where = [];
        $daterange = input('get.daterange/s', '', 'trim');
        switch ($daterange) {
            case 'week':
                $where['a.addtime'] = ['egt', strtotime('-7 day')];
                break;
            case 'month':
                $where['a.addtime'] = ['egt', strtotime('-30 day')];
                break;
            default:
                break;
        }
        $return = [
            'dataset' => []
        ];
        $datalist = model('JobApply')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company b',
                'a.comid=b.id',
                'LEFT'
            )
            ->where($where)
            ->group('a.comid')
            ->order('total desc,b.refreshtime desc')
            ->limit(100)
            ->column(
                'b.companyname,b.nature,b.trade,b.district,b.scale,count(*) as total'
            );

        if (!empty($datalist)) {
            $category_data = model('Category')->getCache();
            $category_district_data = model('CategoryDistrict')->getCache();
            foreach ($datalist as $key => $value) {
                $arr['companyname'] = $value['companyname'];
                $arr['district'] = isset(
                    $category_district_data[$value['district']]
                )
                    ? $category_district_data[$value['district']]
                    : '';
                $arr['trade'] = isset(
                    $category_data['QS_trade'][$value['trade']]
                )
                    ? $category_data['QS_trade'][$value['trade']]
                    : '';
                $arr['scale'] = isset(
                    $category_data['QS_scale'][$value['scale']]
                )
                    ? $category_data['QS_scale'][$value['scale']]
                    : '';
                $arr['nature'] = isset(
                    $category_data['QS_company_type'][$value['nature']]
                )
                    ? $category_data['QS_company_type'][$value['nature']]
                    : '';
                $arr['total'] = $value['total'];
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 热门职位排行榜TOP100
     */
    public function view()
    {
        $where = [];
        $daterange = input('get.daterange/s', '', 'trim');
        switch ($daterange) {
            case 'week':
                $where['a.addtime'] = ['egt', strtotime('-7 day')];
                break;
            case 'month':
                $where['a.addtime'] = ['egt', strtotime('-30 day')];
                break;
            default:
                break;
        }
        $return = [
            'dataset' => []
        ];
        $datalist = model('ViewJob')
            ->alias('a')
            ->join(config('database.prefix') . 'job b', 'a.jobid=b.id', 'LEFT')
            ->where($where)
            ->group('a.jobid')
            ->order('total desc,b.refreshtime desc')
            ->limit(100)
            ->column(
                'b.jobname,b.education,b.experience,b.addtime,b.refreshtime,count(*) as total'
            );
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['jobname'] = $value['jobname'];
                $arr['education'] = isset(
                    model('BaseModel')->map_education[$value['education']]
                )
                    ? model('BaseModel')->map_education[$value['education']]
                    : '不限';
                $arr['experience'] = isset(
                    model('BaseModel')->map_experience[$value['experience']]
                )
                    ? model('BaseModel')->map_experience[$value['experience']]
                    : '不限';
                $arr['total'] = $value['total'];
                $arr['refreshtime'] = daterange_format(
                    $value['addtime'],
                    $value['refreshtime']
                );
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 热门企业排行榜TOP100
     */
    public function viewCom()
    {
        $where = [];
        $daterange = input('get.daterange/s', '', 'trim');
        switch ($daterange) {
            case 'week':
                $where['a.addtime'] = ['egt', strtotime('-7 day')];
                break;
            case 'month':
                $where['a.addtime'] = ['egt', strtotime('-30 day')];
                break;
            default:
                break;
        }
        $return = [
            'dataset' => []
        ];
        $datalist = model('ViewJob')
            ->alias('a')
            ->join(
                config('database.prefix') . 'company b',
                'a.company_uid=b.uid',
                'LEFT'
            )
            ->where($where)
            ->group('a.company_uid')
            ->order('total desc,b.refreshtime desc')
            ->limit(100)
            ->column(
                'b.companyname,b.nature,b.trade,b.district,b.scale,count(*) as total'
            );
        if (!empty($datalist)) {
            $category_data = model('Category')->getCache();
            $category_district_data = model('CategoryDistrict')->getCache();
            foreach ($datalist as $key => $value) {
                $arr['companyname'] = $value['companyname'];
                $arr['district'] = isset(
                    $category_district_data[$value['district']]
                )
                    ? $category_district_data[$value['district']]
                    : '';
                $arr['trade'] = isset(
                    $category_data['QS_trade'][$value['trade']]
                )
                    ? $category_data['QS_trade'][$value['trade']]
                    : '';
                $arr['scale'] = isset(
                    $category_data['QS_scale'][$value['scale']]
                )
                    ? $category_data['QS_scale'][$value['scale']]
                    : '';
                $arr['nature'] = isset(
                    $category_data['QS_company_type'][$value['nature']]
                )
                    ? $category_data['QS_company_type'][$value['nature']]
                    : '';
                $arr['total'] = $value['total'];
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
