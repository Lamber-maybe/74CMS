<?php

namespace app\apiadmin\controller;

class CompanySetmeal extends \app\common\controller\Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }
    /**
     * 列表
     */
    public function index()
    {
        $where = [];
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $setmeal = input('get.setmeal/d', 0, 'intval');
        $expire = input('get.expire/d', 0, 'intval');

        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['a.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['a.id'] = ['eq', intval($keyword)];
                    break;
                case 3:
                    $map_userinfo = model('Member')
                        ->where(['mobile' => ['eq', $keyword]])
                        ->where(['utype' => ['eq', 1]])
                        ->find();
                    if ($map_userinfo === null) {
                        $where['a.id'] = 0;
                    } else {
                        $where['a.uid'] = ['eq', $map_userinfo['uid']];
                    }
                    break;
                case 4:
                    $where['a.uid'] = ['eq', intval($keyword)];
                    break;
                default:
                    break;
            }
        }
        if($setmeal>0){
            $where['c.setmeal_id'] = $setmeal;
        }
        if($expire>0){
            if($expire==1){
                $where['c.deadline'] = [['lt',time()],['neq',0],'and'];
            }else if($expire==2){
                $where['c.deadline'] = [['gt',time()],['lt',strtotime('+'.config('global_config.meal_min_remind').'day')],'and'];
            }
        }
        $total = model('Company')
            ->alias('a')
            ->join(config('database.prefix').'member_setmeal c','a.uid=c.uid','LEFT')
            ->where($where)
            ->count();
        $list = model('Company')
            ->alias('a')
            ->join(config('database.prefix').'company_contact b','a.uid=b.uid','LEFT')
            ->join(config('database.prefix').'member_setmeal c','a.uid=c.uid','LEFT')
            ->join(config('database.prefix').'setmeal d','d.id=c.setmeal_id','LEFT')
            ->field('a.id,a.uid,a.companyname,b.contact,b.mobile,c.setmeal_id,c.deadline,c.download_resume_point,d.name as setmeal_name')
            ->where($where)
            ->order('a.id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        
        foreach ($list as $key => $value) {
            if($value['deadline']==0){
                $value['deadline_cn'] = '无限期';
                $value['surplus_days'] = '-';
                $value['expire'] = 0;
            }else if($value['deadline']<time()){
                $value['deadline_cn'] = date('Y-m-d',$value['deadline']);
                $value['surplus_days'] = '0天';
                $value['expire'] = 1;
            }else{
                $value['deadline_cn'] = date('Y-m-d',$value['deadline']);
                $surplus_seconds = $value['deadline'] - time();
                $surplus_days = ceil($surplus_seconds/3600/24);
                $value['surplus_days'] = $surplus_days.'天';
                if($surplus_days<config('global_config.meal_min_remind')){
                    $value['expire'] = 2;
                }else{
                    $value['expire'] = 0;
                }
            }
            
            $value['link'] = url('index/company/show', ['id' => $value['id']]);
            $list[$key] = $value;
        }

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function log()
    {
        $where = [];
        $uid = input('get.uid/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($uid > 0) {
            $where['uid'] = $uid;
        }

        $total = model('MemberSetmealLog')
            ->where($where)
            ->count();
        $list = model('MemberSetmealLog')
            ->where($where)
            ->order('id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function edit()
    {
        $uid = input('get.uid/d', 0, 'intval');
        if ($uid) {
            $info = model('MemberSetmeal')->alias('a')->where('a.uid',$uid)->join(config('database.prefix').'setmeal b','a.setmeal_id=b.id','LEFT')->field('a.*,b.name as setmeal_name')->find();
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $info['deadline'] = $info['deadline']>0?date('Y-m-d',$info['deadline']):'';
            $this->ajaxReturn(200, '获取数据成功', $info);
        } else {
            $input_data = [
                'uid' => input('post.uid/d', 0, 'intval'),
                'days' => input('post.days/d', 0, 'intval'),
                'deadline' => input('post.deadline/s', 'trim', 'trim'),
                'jobs_meanwhile' => input('post.jobs_meanwhile/d', 0, 'intval'),
                'refresh_jobs_free_perday' => input(
                    'post.refresh_jobs_free_perday/d',
                    0,
                    'intval'
                ),
                'download_resume_point' => input(
                    'post.download_resume_point/d',
                    0,
                    'intval'
                ),
                'download_resume_max_perday' => input(
                    'post.download_resume_max_perday/d',
                    0,
                    'intval'
                ),
                'enable_video_interview' => input(
                    'post.enable_video_interview/d',
                    0,
                    'intval'
                ),
                'enable_poster' => input('post.enable_poster/d', 0, 'intval'),
                'show_apply_contact' => input(
                    'post.show_apply_contact/d',
                    0,
                    'intval'
                ),
                'explain' => input('post.explain/s', 'trim', 'trim'),
                'is_charge' => input('post.is_charge/d', 0, 'intval'),
                'charge_val' => input('post.charge_val/d', 0, 'floatval')
            ];
            $info = model('MemberSetmeal')->where('uid',$input_data['uid'])->find();
            
            if($input_data['days']!=0){
                if($info['deadline']==0){
                    $input_data['deadline'] = 0;
                }else{
                    $input_data['deadline'] = $info['deadline'] + $input_data['days'] * 3600 * 24;
                }
            }else{
                if($input_data['deadline']==''){
                    $input_data['deadline'] = $info['deadline'];
                }else{
                    $input_data['deadline'] = strtotime($input_data['deadline']);
                }
            }
            $result = model('MemberSetmeal')
                ->allowField(true)
                ->save($input_data, ['uid' => $input_data['uid']]);
            if (false === $result) {
                $this->ajaxReturn(500, model('MemberSetmeal')->getError());
            }
            $note = '系统操作【管理员：'.$this->admininfo->username.'】。' . $input_data['explain'];
            if ($input_data['is_charge'] == 1 && $input_data['charge_val'] > 0) {
                $note .= '；收费' . $input_data['charge_val'] . '元';
            }
            $log['uid'] = $input_data['uid'];
            $log['content'] = '修改企业套餐内容。' . $note;
            $log['addtime'] = time();
            model('MemberSetmealLog')
                ->allowField(true)
                ->save($log);
            model('AdminLog')->record(
                '修改企业套餐内容。企业UID【' .
                $input_data['uid'] .
                    '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function add()
    {
        $uid = input('post.uid/d', 0, 'intval');
        $setmeal_id = input('post.setmeal_id/d', 0, 'intval');
        
        model('Member')->setMemberSetmeal(['uid'=>$uid,'setmeal_id'=>$setmeal_id,'note'=>'管理员更换套餐']);
        
        model('AdminLog')->record(
            '更换企业套餐。企业UID【' .
            $uid .
                '】；套餐ID【'.$setmeal_id.'】',
            $this->admininfo
        );
    $this->ajaxReturn(200, '更换套餐成功');
    }
}
