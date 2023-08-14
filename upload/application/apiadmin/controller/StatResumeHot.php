<?php
namespace app\apiadmin\controller;

class StatResumeHot extends \app\common\controller\Backend
{
    /**
     * 简历刷新量排行榜TOP100
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
        $datalist = model('RefreshResumeLog')
            ->alias('a')
            ->join(
                config('database.prefix') . 'resume b',
                'a.uid=b.uid',
                'LEFT'
            )
            ->where($where)
            ->group('a.uid')
            ->order('total desc,refreshtime desc')
            ->limit(100)
            ->column(
                'b.id,b.fullname,b.sex,b.birthday,b.education,b.enter_job_time,b.addtime,b.refreshtime,count(*) as total'
            );
        $rid_arr = $complete_arr = [];
        foreach ($datalist as $key => $value) {
            $rid_arr[] = $value['id'];
        }
        if (!empty($rid_arr)) {
            $complete_arr = model('Resume')->countCompletePercentBatch(
                $rid_arr
            );
        }
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['fullname'] = $value['fullname'];
                $arr['sex'] = isset(model('Resume')->map_sex[$value['sex']])
                    ? model('Resume')->map_sex[$value['sex']]
                    : '性别未知';
                $arr['age'] =
                    intval($value['birthday']) > 0
                        ? date('Y') - intval($value['birthday']) . '岁'
                        : '年龄未知';
                $arr['education'] = isset(
                    model('BaseModel')->map_education[$value['education']]
                )
                    ? model('BaseModel')->map_education[$value['education']]
                    : '学历未知';
                $arr['experience'] =
                    $value['enter_job_time'] == 0
                        ? '尚未工作'
                        : format_date($value['enter_job_time']) . '工作经验';
                $arr['sex'] = isset(model('Resume')->map_sex[$value['sex']])
                    ? model('Resume')->map_sex[$value['sex']]
                    : '性别未知';
                $arr['complete_percent'] = isset($complete_arr[$value['id']])
                    ? $complete_arr[$value['id']]
                    : 0;
                $arr['complete_percent'] .= '%';
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
     * 简历投递量排行榜TOP100
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
                config('database.prefix') . 'resume b',
                'a.resume_id=b.id',
                'LEFT'
            )
            ->where($where)
            ->group('a.resume_id')
            ->order('total desc,refreshtime desc')
            ->limit(100)
            ->column(
                'b.id,b.fullname,b.sex,b.birthday,b.education,b.enter_job_time,b.addtime,b.refreshtime,count(*) as total'
            );
        $rid_arr = $complete_arr = [];
        foreach ($datalist as $key => $value) {
            $rid_arr[] = $value['id'];
        }
        if (!empty($rid_arr)) {
            $complete_arr = model('Resume')->countCompletePercentBatch(
                $rid_arr
            );
        }
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['fullname'] = $value['fullname'];
                $arr['sex'] = isset(model('Resume')->map_sex[$value['sex']])
                    ? model('Resume')->map_sex[$value['sex']]
                    : '性别未知';
                $arr['age'] =
                    intval($value['birthday']) > 0
                        ? date('Y') - intval($value['birthday']) . '岁'
                        : '年龄未知';
                $arr['education'] = isset(
                    model('BaseModel')->map_education[$value['education']]
                )
                    ? model('BaseModel')->map_education[$value['education']]
                    : '学历未知';
                $arr['experience'] =
                    $value['enter_job_time'] == 0
                        ? '尚未工作'
                        : format_date($value['enter_job_time']) . '工作经验';
                $arr['sex'] = isset(model('Resume')->map_sex[$value['sex']])
                    ? model('Resume')->map_sex[$value['sex']]
                    : '性别未知';
                $arr['complete_percent'] = isset($complete_arr[$value['id']])
                    ? $complete_arr[$value['id']]
                    : 0;
                $arr['complete_percent'] .= '%';
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
     * 求职者登录排行榜TOP100
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
                config('database.prefix') . 'resume b',
                'a.uid=b.uid',
                'LEFT'
            )
            ->where($where)
            ->where('a.is_login', 1)
            ->where('a.utype', 2)
            ->where('b.id', 'NEQ', '')
            ->group('a.uid')
            ->order('total desc,addtime desc')
            ->limit(100)
            ->column(
                'b.id,b.fullname,b.sex,b.birthday,b.education,b.enter_job_time,b.addtime,count(*) as total'
            );
        $rid_arr = $complete_arr = [];
        foreach ($datalist as $key => $value) {
            $rid_arr[] = $value['id'];
        }
        if (!empty($rid_arr)) {
            $complete_arr = model('Resume')->countCompletePercentBatch(
                $rid_arr
            );
        }
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['fullname'] = $value['fullname'];
                $arr['sex'] = isset(model('Resume')->map_sex[$value['sex']])
                    ? model('Resume')->map_sex[$value['sex']]
                    : '性别未知';
                $arr['age'] =
                    intval($value['birthday']) > 0
                        ? date('Y') - intval($value['birthday']) . '岁'
                        : '年龄未知';
                $arr['education'] = isset(
                    model('BaseModel')->map_education[$value['education']]
                )
                    ? model('BaseModel')->map_education[$value['education']]
                    : '学历未知';
                $arr['experience'] =
                    $value['enter_job_time'] == 0
                        ? '尚未工作'
                        : format_date($value['enter_job_time']) . '工作经验';
                $arr['sex'] = isset(model('Resume')->map_sex[$value['sex']])
                    ? model('Resume')->map_sex[$value['sex']]
                    : '性别未知';
                $arr['complete_percent'] = isset($complete_arr[$value['id']])
                    ? $complete_arr[$value['id']]
                    : 0;
                $arr['complete_percent'] .= '%';
                $arr['total'] = $value['total'];
                $arr['addtime'] = daterange(time(), $value['addtime']);
                $return['dataset'][] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 求职者被下载排行榜TOP100
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
                config('database.prefix') . 'resume b',
                'a.resume_id=b.id',
                'LEFT'
            )
            ->where($where)
            ->where('b.id', 'NEQ', '')
            ->group('a.resume_id')
            ->order('total desc,refreshtime desc')
            ->limit(100)
            ->column(
                'b.id,b.fullname,b.sex,b.birthday,b.education,b.enter_job_time,b.addtime,b.refreshtime,count(*) as total'
            );
        $rid_arr = $complete_arr = [];
        foreach ($datalist as $key => $value) {
            $rid_arr[] = $value['id'];
        }
        if (!empty($rid_arr)) {
            $complete_arr = model('Resume')->countCompletePercentBatch(
                $rid_arr
            );
        }
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['fullname'] = $value['fullname'];
                $arr['sex'] = isset(model('Resume')->map_sex[$value['sex']])
                    ? model('Resume')->map_sex[$value['sex']]
                    : '性别未知';
                $arr['age'] =
                    intval($value['birthday']) > 0
                        ? date('Y') - intval($value['birthday']) . '岁'
                        : '年龄未知';
                $arr['education'] = isset(
                    model('BaseModel')->map_education[$value['education']]
                )
                    ? model('BaseModel')->map_education[$value['education']]
                    : '学历未知';
                $arr['experience'] =
                    $value['enter_job_time'] == 0
                        ? '尚未工作'
                        : format_date($value['enter_job_time']) . '工作经验';
                $arr['sex'] = isset(model('Resume')->map_sex[$value['sex']])
                    ? model('Resume')->map_sex[$value['sex']]
                    : '性别未知';
                $arr['complete_percent'] = isset($complete_arr[$value['id']])
                    ? $complete_arr[$value['id']]
                    : 0;
                $arr['complete_percent'] .= '%';
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
     * 热门简历排行榜TOP100
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
        $datalist = model('ViewResume')
            ->alias('a')
            ->join(
                config('database.prefix') . 'resume b',
                'a.resume_id=b.id',
                'LEFT'
            )
            ->where($where)
            ->group('a.resume_id')
            ->order('total desc,refreshtime desc')
            ->limit(100)
            ->column(
                'b.id,b.fullname,b.sex,b.birthday,b.education,b.enter_job_time,b.addtime,b.refreshtime,count(*) as total'
            );
        $rid_arr = $complete_arr = [];
        foreach ($datalist as $key => $value) {
            $rid_arr[] = $value['id'];
        }
        if (!empty($rid_arr)) {
            $complete_arr = model('Resume')->countCompletePercentBatch(
                $rid_arr
            );
        }
        if (!empty($datalist)) {
            foreach ($datalist as $key => $value) {
                $arr['fullname'] = $value['fullname'];
                $arr['sex'] = isset(model('Resume')->map_sex[$value['sex']])
                    ? model('Resume')->map_sex[$value['sex']]
                    : '性别未知';
                $arr['age'] =
                    intval($value['birthday']) > 0
                        ? date('Y') - intval($value['birthday']) . '岁'
                        : '年龄未知';
                $arr['education'] = isset(
                    model('BaseModel')->map_education[$value['education']]
                )
                    ? model('BaseModel')->map_education[$value['education']]
                    : '学历未知';
                $arr['experience'] =
                    $value['enter_job_time'] == 0
                        ? '尚未工作'
                        : format_date($value['enter_job_time']) . '工作经验';
                $arr['sex'] = isset(model('Resume')->map_sex[$value['sex']])
                    ? model('Resume')->map_sex[$value['sex']]
                    : '性别未知';
                $arr['complete_percent'] = isset($complete_arr[$value['id']])
                    ? $complete_arr[$value['id']]
                    : 0;
                $arr['complete_percent'] .= '%';
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
}
