<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;

class Recycle extends Backend
{
    /**
     * 已删除客户列表
     */
    public function deleted_customers(){
        $search_type = input('get.search_type/d',0,'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $life_cycle_id = input('get.life_cycle_id/d', 0, 'intval'); // 客户等级
        $admin_id = input('get.admin_id/a', []); // 所属销售
        $job_count = input('get.job_count/d', 0, 'intval'); // 再招职位
        $visit_count = input('get.visit_count/d', 0, 'intval'); // 跟进次数
        $audit = input('get.audit/a',[]); // 认证状态
        $setmeal_id = input('get.setmeal_id/a', []);// 企业套餐
        $is_display = input('get.is_display/a', []);// 显示状态
        $sort = input('get.sort/s', 'addtime', 'trim'); // 排序方式 asc dec
        $sort_type = input('get.sort_type/s','desc','trim');
        $customer_type = input('get.customer_type/d', 0, 'intval'); // 客户类型 0-全部企业 1-我的客户 2-企业公海
        $where = ['deletetime'=>['gt', 0]];

        if ($search_type > 0)
        {
            switch ($search_type)
            {
                case 1: // 今日转为客户
                    $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
                    $where['c.addtime'] = ['gt',$beginToday];
                    break;
                case 2: // 30天内跟进过
                    $time = strtotime(date("Y-m-d",strtotime("-30 day")));
                    $where['last_visit_time'] = ['gt',$time];
                    break;
                case 3: // 7天未跟进
                    $time = strtotime(date("Y-m-d",strtotime("-7 day")));
                    $where['last_visit_time'] = ['lt',$time];
                    break;
                case 4: // 15天未跟进
                    $time = strtotime(date("Y-m-d",strtotime("-15 day")));
                    $where['last_visit_time'] = ['lt',$time];
                    break;
                case 5: // 30天未跟进
                    $time = strtotime(date("Y-m-d",strtotime("-30 day")));
                    $where['last_visit_time'] = ['lt',$time];
                    break;
                case 6: // 从未跟进
                    $where['c.last_visit_time'] = 0;
                    break;
                case 7: // 今日登录客户
                    $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
                    $where['me.last_login_time'] = ['gt',$beginToday];
                    break;
            }
        }
        switch ($customer_type) {
            case 1:
                if (empty($admin_id)) {
                    $this->ajaxReturn(500, '缺少所属销售id', []);
                }
                break;
            case 2:
                $where['c.admin_id'] = 0;
                break;
            default:
                // 全部
                break;
        }

        if (count($audit)>0 && is_numeric($audit[0])) {
            $where['c.audit'] = ['in',$audit];
        }
        if (count($is_display)>0 && is_numeric($is_display[0])) {
            $where['c.is_display'] = ['in',$is_display];
        }
        if (count($setmeal_id)>0 && is_numeric($setmeal_id[0])) {
            $where['c.setmeal_id'] = ['in',$setmeal_id];
        }
        if ($life_cycle_id > 0) {
            $where['c.life_cycle_id'] = $life_cycle_id;
        }
        if (count($admin_id)>0 && is_numeric($admin_id[0])) {
            $where['c.admin_id'] = ['in',$admin_id];
        }
        $having_where = '';
        if ($job_count > 0) {
            switch ($job_count)
            {
                case 1: // 无职位
                    $having_where .= 'jobs_num = 0';
                    break;
                case 2: // 有职位
                    $having_where .= 'jobs_num >= '.$job_count;
                    break;

            }
        }
        if ($visit_count > 0) {
            $having = 'count(uid) = ' . $visit_count;
            if ($visit_count >= 3) {
                $having = 'count(uid) >= ' . $visit_count;
            }
            $time = strtotime(date('Y-m'));
            $job_count = model('b2bcrm.CrmFollowUp')
                ->where(['utype' => 1, 'create_time' => ['gt', $time]])
                ->group('uid')
                ->having($having)
                ->column('uid,count(uid) as num', 'uid');
            $job_uid = array_keys($job_count);
            if (empty($job_uid)) {
                $this->ajaxReturn(200, '获取数据成功', []);
            }
            $where['c.uid'] = ['in', $job_uid];
        }
        switch ($sort_type) {
            case 'refreshtime':
                $order = 'refreshtime '.$sort;
                break;
            case 'last_visit_time':
                $order = 'last_visit_time'.$sort;
                break;
            default:
                $order = 'addtime '.$sort;
        }
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['c.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['c.id'] = ['eq', intval($keyword)];
                    break;
                case 3:
                    $map_userinfo = model('Member')
                        ->where(['mobile' => ['eq', $keyword]])
                        ->where(['utype' => ['eq', 1]])
                        ->find();
                    if ($map_userinfo === null) {
                        $where['c.id'] = 0;
                    } else {
                        $where['c.uid'] = ['eq', $map_userinfo['uid']];
                    }
                    break;
                case 4:
                    $where['c.uid'] = ['eq', intval($keyword)];
                    break;
                default:
                    break;
            }
        }

        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['c.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['c.id'] = ['eq', intval($keyword)];
                    break;
                case 3:
                    $map_userinfo = model('Member')
                        ->where(['mobile' => ['eq', $keyword]])
                        ->where(['utype' => ['eq', 1]])
                        ->find();
                    if ($map_userinfo === null) {
                        $where['c.id'] = 0;
                    } else {
                        $where['c.uid'] = ['eq', $map_userinfo['uid']];
                    }
                    break;
                case 4:
                    $where['c.uid'] = ['eq', intval($keyword)];
                    break;
                default:
                    break;
            }
        }
        $cc = new Company();
        $res = $cc->getList($where,[],$order,$current_page,$pagesize);
        $this->ajaxReturn(200, '', $res);
    }

    /*
     * 还原
     * */
    public function reduction()
    {
        $company_id = input('post.company_id/a',[]);
        if (empty($company_id))
        {
            $this->ajaxReturn(500,'请选择要还原的数据');
        }

        try {
            $company = model('Company')->where(['id'=>['in',$company_id]])->column('companyname');
            if (empty($company))
            {
                $this->ajaxReturn(500,'选择的企业不存在');
            }
            $reduction = model('Company')
                ->isUpdate(true)
                ->save([
                    'deletetime'=>0,
                    'delete_admin'=>0],
                    ['id'=>['in',$company_id]]);
            if (false === $reduction)
            {
                $this->ajaxReturn(500,'还原数据失败');
            }
            $log_result = model('AdminLog')->record(
                '还原回收站企业【ID:' . implode(',',$company_id) .'】,企业名称【'.implode(',',$company).'】',
                $this->admininfo
            );
            if (false === $log_result) {
                $this->ajaxReturn(500,model('AdminLog')->getError());
            }
            $this->ajaxReturn(200,'还原数据成功');
        }catch (\Exception $e)
        {
            $this->ajaxReturn(500,$e->getMessage());
        }
    }

    /**
     * 已删除客户-完全删除
     */
    public function delete(){
        $uid = input('post.uid/a');
        if (empty($uid)) {
            $this->ajaxReturn(500, '请选择企业');
        }
        model('Member')->deleteMemberByUids($uid);
        model('AdminLog')->record('删除企业。企业UID【'.implode(",",$uid).'】',$this->admininfo);
        $this->ajaxReturn(200, '删除成功');
    }

    /*
     * 加入回收站
     * */
    public function del(){
        $company_id = input('get.company_id/d',0,'intval');
        if (empty($company_id))
        {
            $this->ajaxReturn(500,'请选择要删除的数据');
        }
        $company = model('Company')
            ->where(['id'=>$company_id])
            ->find();
        if (empty($company))
        {
            $this->ajaxReturn(500,'企业id错误');
        }
        $del = model('Company')
            ->isUpdate(true)
            ->save(['delete_admin'=>$this->admininfo->id,'deletetime'=>time()],['id'=>$company_id]);

        if (false === $del)
        {
            $this->ajaxReturn(500,'删除失败');
        }

        $log_result = model('AdminLog')->record(
            '删除企业到回收站【ID:' . $company_id .'】,企业名称【'.$company['companyname'].'】',
            $this->admininfo
        );
        if (false === $log_result) {
            $this->ajaxReturn(500,model('AdminLog')->getError());
        }
        $this->ajaxReturn(200,'删除成功');
    }
}