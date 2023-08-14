<?php

namespace app\apiadmin\controller;

use think\Db;

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
                'charge_val' => input('post.charge_val/d', 0, 'floatval'),
                'im_total' => input('post.im_total/d', 0, 'intval'),
                'im_max_perday' => input('post.im_max_perday/d', 0, 'intval'),
            ];
            $info = $member_setmeal = model('MemberSetmeal')->where('uid', $input_data['uid'])->find();
            
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

            /**
             * 【ID1000521】
             * 【bug】后台业务修改单企业套餐时间时，不影响数据库中的过期标识，清除过期（计划任务）无效
             * yx - 2023.02.01
             * 【新增】:
             * 如果套餐无限期或者修改为未过期时间，则重置`expired`字段为0
             * 以解决定时任务[清除过期套餐]，`expired=1`造成的影响
             */
            if ($input_data['deadline'] == 0 || $input_data['deadline'] > time()) {
                $input_data['expired'] = 0;
            }

            Db::startTrans();
            try {
                $result = model('MemberSetmeal')
                    ->allowField(true)
                    ->save($input_data, ['uid' => $input_data['uid']]);
                if (false === $result) {
                    throw new \Exception(model('MemberSetmeal')->getError());
                }

                $company_info = model('Company')->field('id,companyname')->where('uid', $input_data['uid'])->find();
                $log_field = '编辑{' . $company_info['companyname'] . '}(企业ID:' . $company_info['id'] . ')的套餐信息，';

                $note = '系统操作【管理员：' . $this->admininfo->username . '】。' . $input_data['explain'];
                if ($input_data['is_charge'] == 1 && $input_data['charge_val'] > 0) {
                    $note .= '；收费' . $input_data['charge_val'] . '元';
                    $log_field .= '收费金额: ' . $input_data['charge_val'] . '；';
                } else {
                    $log_field .= '收费金额: 0；';
                }

                $log['uid'] = $input_data['uid'];
                $log['content'] = '修改企业套餐内容。' . $note;
                $log['addtime'] = time();
                model('MemberSetmealLog')
                    ->allowField(true)
                    ->save($log);

                if ($input_data['deadline'] != $member_setmeal['deadline']) {
                    $old_deadline = $member_setmeal['deadline'] == 0 ? '无限期' : date('Y-m-d', $member_setmeal['deadline']);
                    $new_deadline = $input_data['deadline'] == 0 ? '无限期' : date('Y-m-d', $input_data['deadline']);
                    $log_field .= '到期时间:' . $old_deadline . '->' . $new_deadline . '；';
                }

                if ($input_data['jobs_meanwhile'] != $member_setmeal['jobs_meanwhile']) {
                    $log_field .= '同时在招职位数:' . $member_setmeal['jobs_meanwhile'] . '->' . $input_data['jobs_meanwhile'] . '；';
                }

                if ($input_data['download_resume_point'] != $member_setmeal['download_resume_point']) {
                    $log_field .= '下载简历点数:' . $member_setmeal['download_resume_point'] . '->' . $input_data['download_resume_point'] . '；';
                }

                if ($input_data['im_total'] != $member_setmeal['im_total']) {
                    $log_field .= '职聊次数:' . $member_setmeal['im_total'] . '->' . $input_data['im_total'] . '；';
                }

                if ($input_data['im_max_perday'] != $member_setmeal['im_max_perday']) {
                    $log_field .= '允许发起聊天数:' . $member_setmeal['im_max_perday'] . '次/天->' . $input_data['im_max_perday'] . '次/天；';
                }

                if ($input_data['refresh_jobs_free_perday'] != $member_setmeal['refresh_jobs_free_perday']) {
                    $log_field .= '免费刷新职位:' . $member_setmeal['refresh_jobs_free_perday'] . '次/天->' . $input_data['refresh_jobs_free_perday'] . '次/天；';
                }

                if ($input_data['enable_video_interview'] != $member_setmeal['enable_video_interview']) {
                    $log_field .= '使用视频面试:' . model('Setmeal')->map_enable[$member_setmeal['enable_video_interview']] . '->' . model('Setmeal')->map_enable[$input_data['enable_video_interview']] . '；';
                }

                if ($input_data['show_apply_contact'] != $member_setmeal['show_apply_contact']) {
                    $log_field .= '收到简历免费查看:' . model('Setmeal')->map_enable[$member_setmeal['show_apply_contact']] . '->' . model('Setmeal')->map_enable[$input_data['show_apply_contact']] . '；';
                }

                if ($input_data['download_resume_max_perday'] != $member_setmeal['download_resume_max_perday']) {
                    $log_field .= '下载简历上限:' . $member_setmeal['download_resume_max_perday'] . '份/天->' . $input_data['download_resume_max_perday'] . '份/天；';
                }

                $log_field .= '操作说明:' . (empty($input_data['explain']) ? '无' : $input_data['explain']);

                // 日志
                $log_result = model('AdminLog')->writeLog(
                    $log_field,
                    $this->admininfo,
                    0,
                    5
                );
                if (false === $log_result) {
                    throw new \Exception(model('AdminLog')->getError());
                }

                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollBack();
                $this->ajaxReturn(500, '保存失败', ['err_msg' => $e->getMessage()]);
            }

            $this->ajaxReturn(200, '保存成功');
        }
    }
    public function add()
    {
        $uid = input('post.uid/d', 0, 'intval');
        $setmeal_id = input('post.setmeal_id/d', 0, 'intval');

        Db::startTrans();
        try {
            $company_info = model('Company')
                ->alias('c')
                ->join('setmeal s', 's.id = c.setmeal_id', 'LEFT')
                ->field('c.id as comid,c.companyname,s.name as setmeal_name')
                ->where('uid', $uid)
                ->find();

            $new_setmeal = model('Setmeal')->where('id', $setmeal_id)->value('name');

            model('Member')->setMemberSetmeal(['uid' => $uid, 'setmeal_id' => $setmeal_id, 'note' => '管理员更换套餐'], 0, $this->admininfo->id);

            // 日志
            $log_result = model('AdminLog')->writeLog(
                '{' . $company_info['companyname'] . '}(企业ID:' . $company_info['comid'] . ')更换套餐，' . $company_info['setmeal_name'] . ' -> ' . $new_setmeal,
                $this->admininfo,
                0,
                5
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '更换套餐成功', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '更换套餐成功');
    }
    /**
     * 套餐开通记录
     */
    public function openlog()
    {
        $where = [];
        $setmeal_id = input('get.setmeal_id/d', 0, 'intval');
        $type = input('get.type/d', 0, 'intval');
        $admin_id = input('get.admin_id/d', 0, 'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['b.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['b.id'] = ['eq', intval($keyword)];
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
        if ($setmeal_id > 0) {
            $where['a.setmeal_id'] = $setmeal_id;
        }
        if ($type > 0) {
            $where['a.type'] = $type;
        }
        if ($admin_id > 0) {
            $where['a.admin_id'] = $admin_id;
        }
        $total = model('MemberSetmealOpenLog')
            ->alias('a')
            ->field('a.*,b.companyname')
            ->join(config('database.prefix').'company b','a.uid=b.uid','LEFT')
            ->where($where)
            ->count();
        $list = model('MemberSetmealOpenLog')
            ->alias('a')
            ->field('a.*,b.companyname,c.oid,c.service_type,c.service_name,c.amount,c.service_amount,c.service_amount_after_discount,c.deduct_amount,c.deduct_points,c.payment,c.addtime as order_addtime,c.paytime,c.status,c.extra,c.note,c.add_platform,c.pay_platform,c.service_id')
            ->join(config('database.prefix').'company b','a.uid=b.uid','LEFT')
            ->join(config('database.prefix').'order c','a.order_id=c.id','LEFT')
            ->where($where)
            ->order('a.id desc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            $value['amount_detail'] = '';
            if (
                $value['service_amount_after_discount'] !=
                $value['service_amount']
            ) {
                $value['amount_detail'] .=
                    '折扣价' . $value['service_amount_after_discount'] . '元';
            }
            if ($value['deduct_amount'] > 0 && $value['deduct_points'] == 0) {
                $value['amount_detail'] =
                    ($value['amount_detail'] == ''
                        ? '原价' . $value['service_amount']
                        : $value['amount_detail']) .
                    ' - 优惠券抵扣' .
                    $value['deduct_amount'] .
                    '元';
            }
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
