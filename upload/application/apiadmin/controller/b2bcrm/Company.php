<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;
use app\common\model\shortvideo\SvCompanyVideo;
use app\common\model\Uploadfile;
use think\Db;

class Company extends Backend
{
    /**
     * 企业列表
     */
    public function index()
    {

        $search_type = input('get.search_type/d', 0, 'intval');
        $key_type = input('get.key_type/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $life_cycle_id = input('get.life_cycle_id/d', 0, 'intval'); // 客户等级
        $admin_id = input('get.admin_id/d', 0, 'intval'); // 所属销售
        $job_counts = input('get.job_count/d', 0, 'intval'); // 再招职位
        $audit = input('get.audit/s', '', 'trim'); // 认证状态
        $setmeal_id = input('get.setmeal_id/d', 0, 'intval');// 企业套餐
        $is_display = input('get.is_display/s', '', 'trim');// 显示状态 0-不显示；1-显示
        $sort = input('get.sort/s', 'DESC', 'trim,strtoupper'); // 排序方式 asc dec
        $sort_type = input('get.sort_type/s', '', 'trim');
        $customer_type = input('get.list_type/d', 0, 'intval'); // 客户类型 0-全部企业 1-企业公海 2-我的客户
        $setmeal_deadline = input('get.setmeal_deadline',0,'intval');// 套餐是否过期 1-未过期 2-已过期
        $weixin = input('get.weixin/d',0,'intval');// 微信绑定 1-已绑定 2-未绑定


        // 排序规则【ASC|DESC】
        if (!in_array($sort, ['ASC', 'DESC'])) {
            $sort = 'DESC';
        }
        // 排序字段
        switch ($sort_type) {
            case 'refreshtime':
                // 刷新时间
                $order = 'c.refreshtime ' . $sort;
                break;
            case 'last_visit_time':
                // 最近跟进
                $order = 'c.last_visit_time ' . $sort;
                break;
            case 'addtime':
                // 添加时间
                $order = 'c.addtime ' . $sort;
                break;
            case 'collection_time':
                // 领取时间
                $order = 'c.collection_time ' .$sort;
                break;
            default:
                if ($customer_type === 2)
                {
                    $order = 'c.collection_time DESC,c.id DESC';
                }else
                {
                    $order = 'c.id ' . $sort;
                }
                break;
        }

        // 查询条件
        $where = [];

        // 查询条件 - 显示状态
        if ($is_display === '0' || $is_display === '1') {
            $where['c.is_display'] = ['=', intval($is_display)];
        }

        // 查询条件 - 跟进状态
        if (!empty($search_type)) {
            switch ($search_type) {
                case 1: // 今日跟进
                    $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                    $where['c.last_visit_time'] = ['gt', $beginToday];
                    break;
                case 2: // 30天内跟进过
                    $time = strtotime(date("Y-m-d", strtotime("-30 day")));
                    $where['c.last_visit_time'] = ['gt', $time];
                    break;
                case 3: // 7天未跟进
                    $time = strtotime(date("Y-m-d", strtotime("-7 day")));
                    $where['c.last_visit_time'] = ['gt', $time];
                    break;
                case 4: // 15天未跟进
                    $time = strtotime(date("Y-m-d", strtotime("-15 day")));
                    $where['c.last_visit_time'] = ['gt', $time];
                    break;
                case 5: // 30天未跟进
                    $time = strtotime(date("Y-m-d", strtotime("-30 day")));
                    $where['c.last_visit_time'] = ['gt', $time];
                    break;
                case 6: // 从未跟进
                    $where['c.last_visit_time'] = 0;
                    break;
                case 7: // 即将转为公客
                    $customer_fall_seas = model('b2bcrm.CrmSysConfig')
                        ->getConfigByKey('customer_fall_seas');
                    if (isset($customer_fall_seas) && !empty($customer_fall_seas)) {
                        $day = $customer_fall_seas - 7;
                        $time = strtotime(date("Y-m-d", strtotime("-$day day")));
                        $where['c.last_visit_time'] = ['lt', $time];
                    } else {
                        $where['c.last_visit_time'] = ['lt', 0];
                    }
                    break;
            }
        }

        // 查询条件 - 客户类型
        switch ($customer_type) {
            case 1:
                // 公海
                $where['c.admin_id'] = 0;
                break;
            case 2:
                // 我的
                $where['c.admin_id'] = $this->admininfo->id;
                break;
            default:
                // 全部
                break;
        }

        // 查询条件 - 套餐ID
        if (!empty($setmeal_id)) {
            $where['c.setmeal_id'] = $setmeal_id;
        }

        // 查询条件 - 生命周期（客户等级）
        if (!empty($life_cycle_id)) {
            $where['c.life_cycle_id'] = $life_cycle_id;
        }

        // 查询条件 - 所属销售
        if (!empty($admin_id)) {
            $where['c.admin_id'] = $admin_id;
        }

        // 查询条件 套餐剩余时间【未过期、已过期】
        if ($setmeal_deadline > 0)
        {
            if ($setmeal_deadline == 1) // 未过期
            {
                $where['m.deadline'] = [['gt',time()],['lt',strtotime('+'.config('global_config.meal_min_remind').'day')],'and'];
            }else{ // 已过期
                $where['m.deadline'] = [['lt',time()],['gt',0],'and'];
            }
        }
        // 数据总条数
        $total = model('Company')
            ->alias('c')
            ->join('member_setmeal m', 'c.uid=m.uid', 'LEFT')
            ->join('setmeal s', 'm.setmeal_id=s.id', 'LEFT');

        // 查询条件 - 关键词
        if (!empty($keyword) && !empty($key_type)) {
            switch ($key_type) {
                case 1: // 企业名称
                    $where['c.companyname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2: // 会员手机号
                    $total = $total->join('member me', 'me.uid=c.uid', 'LEFT')
                        ->where('me.mobile', 'like', "%{$keyword}%");
                    $where['me.mobile'] = ['like', '%' . $keyword . '%'];
                    break;
                case 3: // 企业手机号
                    $total = $total->join('company_contact contact', 'c.id=contact.comid', 'LEFT')
                        ->where('contact.mobile', 'like', "%{$keyword}%");
                    $where['contact.mobile'] = ['like', '%' . $keyword . '%'];
                    break;
                default:
                    break;
            }
        }

        // 微信绑定搜索
        if ($weixin > 0)
        {
            if ($weixin == 1) // 已绑定
            {
                $total = $total->join('member_bind bind',"bind.uid = c.uid and bind.type='weixin'",'LEFT')
                    ->where('bind.id','not null');
                $where['bind.id'] = ['not null',''];
            }else // 未绑定
            {
                $total = $total->join('member_bind bind',"bind.uid = c.uid and bind.type='weixin'",'LEFT')
                    ->where('bind.id','null');
                $where['bind.id'] = ['null',''];
            }
        }

        // 认证状态 - 【0:待认证;1:已认证;2:未通过;3:未认证;】
        if (in_array($audit, ['0', '1', '2', '3'])) {
            $total = $total->join('company_auth a', 'a.uid=c.uid', 'LEFT');
            /**
             * 【bug】后台企业筛选已认证，标为已认证的不出来
             * 企业未上传资料标记为认证通过 筛选认证通过的企业出不来
             * 【旧】
             *  if ('3' === $audit) {
             *  $total = $total->where('c.audit', 0)->where('a.id', 'null');
             *  } else {
             *  $total = $total->where('c.audit', intval($audit))->where('a.id', 'not null');
             */
            switch ($audit)
            {
                case 0:
                    $total = $total->where('c.audit', intval($audit))->where('a.id', 'not null');
                    break;
                case 1:
                case 2:
                $total = $total->where('c.audit', intval($audit));
                break;
                case 3:
                    $total = $total->where('c.audit', 0)->where('a.id', 'null');
                    break;
            }
        }


        // 是否有职位筛选
        $having_where = '';
        if (!empty($job_counts) && in_array($job_counts, [1, 2])) {
            switch ($job_counts) {
                case 1: // 无职位
                    $having_where .= 'jobs_num = 0';
                    break;
                case 2: // 有职位
                    $having_where .= 'jobs_num >= ' . 1;
                    break;
            }

            $total = $total->join('job_search_rtime j', 'j.uid=c.uid', 'LEFT')
                ->field('count(DISTINCT j.id) as jobs_num')
                ->group('c.id')
                ->having($having_where);
        }

        // 统计数据总条数
        $total = $total->where($where)->count();
        if ($total <= 0) {
            $empty_return = [
                'items' => [],
                'total' => $total,
                'current_page' => 0,
                'pagesize' => 0,
                'total_page' => 0
            ];
            $this->ajaxReturn(200, '获取数据成功', $empty_return);
        }

        // 数据详情
        $list = model('Company')
            ->alias('c')
            ->join('member me', 'me.uid=c.uid', 'LEFT')
            ->join('member_setmeal m', 'c.uid=m.uid', 'LEFT')
            ->join('setmeal s', 'm.setmeal_id=s.id', 'LEFT')
            ->join('company_auth a', 'a.uid=c.uid', 'LEFT')
            ->join('job_search_rtime j', 'j.uid=c.uid', 'LEFT')
            ->join('company_contact contact', 'c.id=contact.comid', 'LEFT')
            ->join('member_bind bind',"bind.uid = c.uid and bind.type='weixin'",'LEFT')
            ->field('c.id,
            c.uid,
            c.companyname,
            c.life_cycle_id,
            contact.contact,
            me.mobile,
            count(DISTINCT j.id) as jobs_num,
            s.name as setmeal_name,
            c.refreshtime,
            c.addtime,
            c.admin_id,
            c.audit,
            c.is_display,
            c.last_visit_time,
            contact.mobile as contact_mobile,
            m.deadline,
            c.collection_time,
            ifnull(bind.id,0) as is_bind,
	    a.id as auth_id')
            ->group('c.id');

        // 认证状态 - 【0:待认证;1:已认证;2:未通过;3:未认证;】
        if (in_array($audit, ['0', '1', '2', '3'])) {
            /**
             * 【bug】后台企业筛选已认证，标为已认证的不出来
             * 企业未上传资料标记为认证通过 筛选认证通过的企业出不来
             * 【旧】
             *  if ('3' === $audit) {
             *  $list = $list->where('c.audit', 0)->where('a.id', 'null');
             *  } else {
             *  $list = $list->where('c.audit', intval($audit))->where('a.id', 'not null');
             */
            switch ($audit)
            {
                case 0:
                    $list = $list->where('c.audit', intval($audit))->where('a.id', 'not null');
                    break;
                case 1:
                case 2:
                    $list = $list->where('c.audit', intval($audit));
                    break;
                case 3:
                    $list = $list->where('c.audit', 0)->where('a.id', 'null');
                    break;
            }
        }

        // 是否有职位筛选
        $having_where = '';
        if (!empty($job_counts) && in_array($job_counts, [1, 2])) {
            switch ($job_counts) {
                case 1: // 无职位
                    $having_where .= 'jobs_num = 0';
                    break;
                case 2: // 有职位
                    $having_where .= 'jobs_num >= ' . 1;
                    break;
            }

            $list = $list->having($having_where);
        }

        $list = $list->where($where)
            ->field('')
            ->group('c.id')
            ->having($having_where)
            ->order($order)
            ->page($current_page . ',' . $pagesize)
            ->select();

        $list = collection($list);

        if ($list->isEmpty()) {
            $empty_return = [
                'items' => [],
                'total' => $total,
                'current_page' => 0,
                'pagesize' => 0,
                'total_page' => 0
            ];
            $this->ajaxReturn(200, '获取数据成功', $empty_return);
        } else {
            $list = $list->toArray();
        }

        // 生命周期（客户等级）
        $life_cycle = model('b2bcrm.CrmLifeCycle')->getDate();
        $life_cycle_arr = array_column($life_cycle, 'name', 'id');

        $clueObj = new Clue();
        $adminList = $clueObj->adminlist();

        foreach ($list as $comId => $comInfo) {
            // 2.公司名称
            $list[$comId]['companyname'] = htmlspecialchars_decode($comInfo['companyname'], ENT_QUOTES);

            // 3.生命周期（客户等级）
            $list[$comId]['life_cycle_txt'] = !empty($life_cycle_arr[$comInfo['life_cycle_id']])
                ? $life_cycle_arr[$comInfo['life_cycle_id']]
                : ''; // 客户等级

            // 收到简历
            $list[$comId]['job_apply_count'] = model('JobApply')
                ->where('company_uid', $comInfo['uid'])
                ->count('id');

            // 8.企业套餐
            $list[$comId]['setmeal_name'] = !empty($comInfo['setmeal_name']) ? $comInfo['setmeal_name'] : '未开通套餐';

            // 9.刷新时间
            $list[$comId]['refreshtime'] = date('Y-m-d H:i:s', $comInfo['refreshtime']);

            // 10.注册时间
            $list[$comId]['addtime'] = date('Y-m-d H:i:s', $comInfo['addtime']);

            // 11.所属销售
            $list[$comId]['admin_username'] = !empty($adminList[$comInfo['admin_id']])
                ? $adminList[$comInfo['admin_id']]
                : '';//所属销售

            // 12.认证状态
            switch ($comInfo['audit']) {
                case 0:
                    if (empty($comInfo['auth_id'])) {
                        $list[$comId]['audit'] = '未认证';
                        $list[$comId]['aduit_id'] = 1;
                    } else {
                        $list[$comId]['audit'] = '待审核';
                        $list[$comId]['aduit_id'] = 2;
                    }
                    break;
                case 1:
                    $list[$comId]['audit'] = '已认证';
                    $list[$comId]['aduit_id'] = 3;
                    break;
                case 2:
                    $list[$comId]['audit'] = '未通过';
                    $list[$comId]['aduit_id'] = 4;
                    break;
            }

            // 13.显示状态
            switch ($comInfo['is_display']) {
                case 0:
                    $list[$comId]['is_display'] = '不显示';
                    $list[$comId]['display'] = 0;
                    break;
                case 1:
                    $list[$comId]['is_display'] = '显示中';
                    $list[$comId]['display'] = 1;
                    break;
            }

            // 14~15.跟进
            if (intval($comInfo['last_visit_time']) > 0) {
                // 最后跟进
                $list[$comId]['last_visit_time'] = date('Y-m-d H:i:s', $comInfo['last_visit_time']); // 最后跟进时间
                // 未跟进时长（天）
                $list[$comId]['not_following_day'] = intval((time() - $comInfo['last_visit_time']) / 86400); // 未跟进时长
            } else {
                // 最后跟进
                $list[$comId]['last_visit_time'] = '';
                // 未跟进时长（天）
                $list[$comId]['not_following_day'] = -1;
            }

            // 套餐过期时间
            $list[$comId]['is_expire'] = 1; // 是否过期 0-无限期 1-已过期 2-即将到期 3-正常

            if ($comInfo['deadline'] == 0)
            {
                $list[$comId]['deadline'] = '';
                $list[$comId]['expire'] = 0;
            }else{
                if ($comInfo['deadline'] < time())
                {
                    $list[$comId]['deadline'] = '0天';
                    $list[$comId]['expire'] = 1;
                }else
                {
                    $surplus_seconds = $comInfo['deadline'] - time();
                    $surplus_days = ceil($surplus_seconds/3600/24);
                    $list[$comId]['deadline'] = $surplus_days.'天';
                    if($surplus_days<config('global_config.meal_min_remind')){
                        $list[$comId]['expire'] = 2;
                    }else{
                        $list[$comId]['expire'] = 3;
                    }
                }
            }


            // 领取时间
            $list[$comId]['collection_time'] = !empty($comInfo['collection_time']) ? date('Y-m-d H:i:s',$comInfo['collection_time']) : '';

            // 企业联系方式
            $list[$comId]['contact_mobile'] = $comInfo['contact_mobile'];

            // 微信绑定
            if ($comInfo['is_bind'] == 0)
            {
                $list[$comId]['is_bind'] = '未绑定';
            }else
            {
                $list[$comId]['is_bind'] = '已绑定';
            }
        }

        $list_return = [
            'items' => $list,
            'total' => $total,
            'current_page' => $current_page,
            'pagesize' => $pagesize,
            'total_page' => ceil($total / $pagesize)
        ];

        $this->ajaxReturn(200, '获取数据成功', $list_return);

    }


    /*
    * 释放(批量释放)
    * */
    public function release()
    {
        $uid = input('post.uid/a', []);
        if (!is_array($uid) || empty($uid)) {
            $this->ajaxReturn(500, '缺少uid参数');
        }
        $company_count = model('Company')->where(['uid' => ['in', $uid]])->count();
        if ($company_count == 0) {
            $this->ajaxReturn(500, '请选择正确的企业');
        }
        Db::startTrans();
        try {
            $update = model('Company')
                ->allowField(true)
                ->isUpdate(true)
                ->save(['admin_id' => 0], ['uid' => ['in', $uid]]
                );
            if (false === $update) {
                throw new \Exception(model('Company')->getError());
            }
            $release = [];

            foreach ($uid as $v) {
                $release[] = [
                    'uid' => $v,
                    'create_time' => time(),
                    'operation_type' => 1,
                    'operator' => 2,
                    'admin_id' => $this->admininfo->id,
                    'utype' => 1
                ];
            }
            model('b2bcrm.CrmClueRelease')->insertAll($release);
            $uid = implode(',', $uid);
            // 日志
            $log_result = model('AdminLog')->record(
                '释放企业所属销售【ID:' . $uid . '】',
                $this->admininfo
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }

        $this->ajaxReturn(200, '释放成功');
    }


    /*
     * 领取(批量领取)
     * */
    public function receive()
    {
        $uid = input('post.uid/a', []);
        if (!is_array($uid) || empty($uid)) {
            $this->ajaxReturn(500, '缺少uid参数');
        }
        $company_count = model('Company')->where(['uid' => ['in', $uid]])->count();
        if ($company_count == 0) {
            $this->ajaxReturn(500, '请选择企业');
        }
        try {
            $sys_configs = model('b2bcrm.CrmSysConfig')->getDateByCategory('customer');
            $sys_config = [];
            foreach ($sys_configs as $v) {
                if ($v['is_open'] == 1) {
                    $sys_config[$v['key']] = $v['value'];
                }
            }
            if ($sys_config['customer_receive_limit'] == 0) {
                $this->ajaxReturn(500, '当前不允许主动领取客户', []);
            }
            if (isset($sys_config['customer_receive_limit'])) // 领取天数限制
            {
                $today_time = strtotime("today");
                $where = [
                    'create_time' => ['gt', $today_time],
                    'admin_id' => $this->admininfo->id,
                    'operation_type' => 1,
                    'operator' => 1,
                    'utype' => 1,
                ];
                $count = model('b2bcrm.CrmClueRelease')->where($where)->count();
                if (($count + $company_count) > $sys_config['customer_receive_limit']) {
                    $can_num = $sys_config['customer_receive_limit'] - $count;
                    if ($can_num < 0) {
                        $can_num = 0;
                    }
                    $this->ajaxReturn(500, '当天领取限制' . $sys_config['customer_receive_limit'] . '个，你已领取' . $count . '个！还可领取【' . $can_num . '】个！', []);
                }
            }
            if (isset($sys_config['customer_forbidden_sale'])) // 禁止领取期限(销售)
            {
                foreach ($uid as $v) {
                    $where = [
                        'a.uid' => $v,
                        'a.admin_id' => $this->admininfo->id,
                        'a.operation_type' => 1,
                        'a.operator' => 2,
                        'a.utype' => 1,
                    ];
                    $CrmClueRelease = model('b2bcrm.CrmClueRelease')
                        ->alias('a')
                        ->join('company b', 'b.uid=a.uid', 'left')
                        ->where($where)
                        ->order('a.create_time', 'DESC')
                        ->field('a.create_time,b.companyname')
                        ->find();

                    if (!empty($CrmClueRelease)) {
                        if ($sys_config['customer_forbidden_sale'] == 0) {
                            $this->ajaxReturn(500, '主动放弃客户不允许领取', []);
                        }
                        $CrmClueRelease = $CrmClueRelease->toArray();
                        $thread_forbidden_sale = $sys_config['customer_forbidden_sale'] * 86400;

                        $timediff = strtotime($CrmClueRelease['create_time']) + $thread_forbidden_sale;

                        if (time() <= $timediff) {
                            $this->ajaxReturn(500, '【' . $CrmClueRelease['companyname'] . '】企业，释放后' . $sys_config['customer_forbidden_sale'] . '天内不能再领取该客户', []);
                        }
                    }
                }
            }
            if (isset($sys_config['customer_forbidden_sys'])) // 禁止领取期限(系统)
            {
                foreach ($uid as $v) {
                    $where = [
                        'uid' => $v,
                        'admin_id' => $this->admininfo->id,
                        'operation_type' => 2,
                        'operator' => 1,
                        'utype' => 1,
                    ];
                    $admin_time = model('b2bcrm.CrmClueRelease')
                        ->where($where)
                        ->order('create_time', 'DESC')
                        ->limit(1)
                        ->value('create_time');
                    if (!empty($admin_time)) {
                        if ($sys_config['customer_forbidden_sys'] == 0) {
                            $this->ajaxReturn(500, '系统放弃客户不允许领取', []);
                        }
                        $CrmClueRelease = $CrmClueRelease->toArray();
                        $customer_forbidden_sys = $sys_config['customer_forbidden_sys'] * 86400;

                        $timediff = strtotime($CrmClueRelease['create_time']) + $customer_forbidden_sys;

                        if (time() <= $timediff) {
                            $this->ajaxReturn(500, '系统自动释放后' . $sys_config['thread_forbidden_sys'] . '天内不能再领取该客户', []);
                        }
                    }
                }
            }

            $receive = model('b2bcrm.CrmClueRelease')->releaseAdd($uid, $this->admininfo->id, 1, 1, 1);
            if ($receive === false) {
                $this->ajaxReturn(500, '领取失败', []);
            }
            model('Company')->isUpdate('true')->save(['admin_id' => $this->admininfo->id,'collection_time'=>time()], ['uid' => ['in', $uid]]);
            $this->ajaxReturn(200, '领取成功', []);
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, $e->getMessage(), []);
        }
    }

    /*
     * 企业详情
     * */
    public function companyDetail()
    {
        $company_id = input('get.company_id/d', 0, 'intval');
        if ($company_id == 0) {
            $this->ajaxReturn(500, '请选择企业');
        }

        $data = model('Company')->alias('c')
            ->join('Member m', 'c.uid=m.uid', 'left')
            ->join('CompanyContact t', 't.uid=c.uid', 'left')
            ->join('MemberSetmeal s', 's.uid=c.uid', 'left')
            ->join('MemberPoints p', 'p.uid=c.uid', 'left')
            ->where(['c.id' => $company_id])
            ->field("c.id,c.companyname,c.uid,c.life_cycle_id,c.labels,m.username,m.mobile,t.mobile as company_mobile,c.is_display,ifnull(m.email,'')as email,c.remark,c.last_visit_time,s.deadline,s.setmeal_id,c.remark,c.audit,s.download_resume_point,s.purchase_resume_point,p.points,m.last_login_time,c.addtime,c.last_visit_admin,c.admin_id,c.clue_id,c.logo,c.user_status,c.short_name,c.nature,c.trade,c.scale")
            ->find();
        if (empty($data)) {
            $this->ajaxReturn(500, '企业id错误');
        } else {
            $data = $data->toArray();
        }
        $where = [
            'j.company_id' => ['eq', $company_id],
            'j.audit' => 1,
            'j.is_display' => 1
        ];
        $data['job_count'] = model('Job')
            ->alias('j')
            ->join('company c', 'c.id=j.company_id', 'LEFT')
            ->join('member m', 'j.uid=m.uid', 'LEFT')
            ->where($where)
            ->count();


        $crmTagModel = model('b2bcrm.CrmTag');
        $res = $crmTagModel->getAllTags();

        $data['lables_list'] = $lables_list = [];
        $labels = explode(',', $data['labels']);
        if (!empty($labels)) {
            foreach ($labels as $key => $value) {
                foreach ($res as $k => $v) {
                    if ($value == $v['id']) {
                        $lables_list[] = [
                            'id' => $v['id'],
                            'name' => $v['name']
                        ];
                    }
                }
            }
        }
        $data['lables_list'] = $lables_list;
        $data['operator'] = '';
        if (!empty($data['admin_id'])) {
            $operator = model('b2bcrm.CrmClueRelease')
                ->where([
                    'uid' => $data['uid'],
                    'operation_type' => 1,
                    'admin_id' => $data['admin_id']
                ])
                ->value('operator');
            if (!empty($operator)) {
                switch ($operator) {
                    case 1:
                        $data['operator'] = '系统分配';
                        break;
                    case 2:
                        $data['operator'] = '自动领取';
                        break;
                }
            }
        }

        $data['addtime'] = date('Y-m-d H:i:s', $data['addtime']);
        $data['last_login_time'] = empty($data['last_login_time']) ? '' : date('Y-m-d H:i:s', $data['last_login_time']);
        $clueobj = new Clue();
        $adminlist = $clueobj->adminlist();
        $data['logo_url'] = model('Uploadfile')->getFileUrl($data['logo']);
        $data['last_visit_admin_name'] = isset($adminlist[$data['last_visit_admin']]) ? $adminlist[$data['last_visit_admin']] : '';
        $data['admin_name'] = isset($adminlist[$data['admin_id']]) ? $adminlist[$data['admin_id']] : '';
        $data['follow_count'] = model('b2bcrm.CrmFollowUp')->where(['uid' => $data['uid']])->count();
        $crm_life_cycle_name = model('b2bcrm.CrmLifeCycle')->getDate($data['life_cycle_id']);
        $data['crm_life_cycle_name'] = !empty($crm_life_cycle_name) ? $crm_life_cycle_name[0]['name'] : '';
        $data['setmeal_name'] = model('Setmeal')->where(['id' => $data['setmeal_id']])->value('name');
        $overtime_setmeal_resource = config('global_config.overtime_setmeal_resource');
        $weixin_bind = model('MemberBind')
            ->where(['type' => 'weixin', 'uid' => $data['uid']])
            ->find();
        $data['weixin_bind'] = empty($weixin_bind) ? 0 : 1;
        /**
         * 【优化】套餐到期时间判断逻辑优化，新增到期时间，不能写剩余天数
         *  zch 2022.9.21
         * 【新增】
         *  $data['deadline'] = !empty($data['deadline']) ? date('Y-m-d',$data['deadline']) : '';
         */
        if ($data['deadline'] > 0) {
            if (($data['deadline'] - time()) < 0 && $overtime_setmeal_resource == 0){
                $data['download_resume_point'] = 0; // 套餐积分
            }
            $data['setmeal_deadline_day'] = intval(($data['deadline'] - time()) / 86400) . '天';
            if ($data['setmeal_deadline_day'] < 0) {
                $data['setmeal_deadline_day'] = '已过期';
            }
            $data['deadline'] =  date('Y-m-d',$data['deadline']);
        } else {
            $data['setmeal_deadline_day'] = '无限期';
            $data['deadline'] =  '无限期';
        }
        if (empty($data)) {
            $this->ajaxReturn(500, '企业id错误');
        }
        $company_auth = model('CompanyAuth')->where(['comid' => $company_id])->find();
        if (empty($company_auth) && $data['audit'] == 0) {
            $data['audit'] = 3;
        }
        $data['company_auth'] = [];
        if (!empty($company_auth)) {
            $data['company_auth'] = [
                'legal_person_idcard_front' => !empty($company_auth['legal_person_idcard_front']) ? model('Uploadfile')->getFileUrl($company_auth['legal_person_idcard_front']) : '',
                'legal_person_idcard_back' => !empty($company_auth['legal_person_idcard_back']) ? model('Uploadfile')->getFileUrl($company_auth['legal_person_idcard_back']) : '',
                'license' => !empty($company_auth['license']) ? model('Uploadfile')->getFileUrl($company_auth['license']) : '',
                'proxy' => !empty($company_auth['proxy']) ? model('Uploadfile')->getFileUrl($company_auth['proxy']) : ''
            ];
        }
        $company_img = model('CompanyImg')->where(['comid' => $company_id])->select();
        $m = new SvCompanyVideo();
        $data['company_video'] = $m->where(['uid' => $data['uid']])->field('id,is_public,audit,filesize,fid,view_count')->select();

        $up = new Uploadfile();
        $up->getFileUrlBatch2($data['company_video'], 'fid', 'video_src');
        $data['company_img'] = [];
        if (count($company_img) > 0) {
            foreach ($company_img as $value) {
                $arr[] = [
                    'id' => $value['id'],
                    'img' => !empty($value['img']) ? model('Uploadfile')->getFileUrl($value['img']) : '',
                    'audit' => $value['audit']
                ];
            }
            $data['company_img'] = $arr;
        }
        $this->ajaxReturn(200, '获取数据成功', $data);
    }

    /*
     * 企业添加
     * */
    public function add()
    {
        $input_data = [
            'companyname' => input('post.companyname/s', '', 'trim'),
            'short_name' => input('post.short_name/s', '', 'trim'),
            'nature' => input('post.nature/d', 0, 'intval'),
            'trade' => input('post.trade/d', 0, 'intval'),
            'logo' => input('post.logo/d', 0, 'intval'),
            'district1' => input('post.district1/d', 0, 'intval'),
            'district2' => input('post.district2/d', 0, 'intval'),
            'district3' => input('post.district3/d', 0, 'intval'),
            'scale' => input('post.scale/d', 0, 'intval'),
            'registered' => input('post.registered/s', '', 'trim'),
            'currency' => input('post.currency/d', 0, 'intval'),
            'tag' => input('post.tag/a'),
            'audit' => input('post.audit/d', 0, 'intval'),
            'clue_id' => input('post.clue_id/d', 0, 'intval'),
            'member' => [
                'username' => input('post.member.username/s', '', 'trim'),
                'password' => input('post.member.password/s', '', 'trim'),
                'mobile' => input('post.member.mobile/s', '', 'trim'),
                'utype' => 1
            ],
            'contact' => [
                'contact' => input('post.contact.contact/s', '', 'trim'),
                'mobile' => input('post.contact.mobile/s', '', 'trim'),
                'weixin' => input('post.contact.weixin/s', '', 'trim'),
                'telephone' => input('post.contact.telephone/s', '', 'trim'),
                'qq' => input('post.contact.qq/s', '', 'trim'),
                'email' => input('post.contact.email/s', '', 'trim')
            ],
            'info' => [
                'website' => input('post.info.website/s', '', 'trim'),
                'short_desc' => input('post.info.short_desc/s', '', 'trim'),
                'content' => input('post.info.content/s', '', 'trim'),
                'address' => input('post.info.address/s', '', 'trim')
            ],
            'setmeal' => [
                'points' => input('post.setmeal.points/d', 0, 'intval'),
                'setmeal_id' => input('post.setmeal.setmeal_id/d', 0, 'intval'),
                'charge' => input('post.setmeal.charge/d', 0, 'intval')
            ]
        ];
        if ($input_data['clue_id'] > 0) {
            $clue = model('b2bcrm.CrmClue')->where(['id' => $input_data['clue_id']])->find();
            if (empty($clue)) {
                $this->ajaxReturn(500, '线索id错误');
            }
            $input_data['last_visit_admin'] = $clue['last_visit_admin'];
            $input_data['last_visit_time'] = $clue['last_visit_time'];
        }

        $sale = input('post.member.sale/d', 0, '');
        if ($sale > 0) {
            $input_data['admin_id'] = $this->admininfo->id;
            $input_data['collection_time'] = time(); // 添加领取时间
        }
        if (
            false === model('Company')->backendAdd($input_data)
        ) {
            $this->ajaxReturn(500, model('Company')->getError());
        }

        try {
            Db::startTrans();
            if ($input_data['clue_id'] > 0) {
                $uid = model('Member')->uid;
                model('b2bcrm.CrmClue')->isUpdate('true')
                    ->save(['is_customer' => 1, 'utype' => 1, 'uid' => $uid], ['id' => $input_data['clue_id']]);
                model('b2bcrm.CrmFollowUp')
                    ->isUpdate(true)
                    ->save(['uid' => $uid], ['clue_id' => $input_data['clue_id'], 'type' => 1]);
            }
            model('AdminLog')->record(
                '添加企业。企业ID【' .
                model('Company')->id .
                '】;企业名称【' .
                $input_data['companyname'] .
                '】',
                $this->admininfo
            );
            Db::commit();
            $this->ajaxReturn(200, '保存成功');
        } catch (\Exception $e) {
            Db::rollback();
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

    /*
     * 企业编辑
     * */
    public function edit()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'uid' => input('post.uid/d', 0, 'intval'),
            'companyname' => input('post.companyname/s', '', 'trim'),
            'short_name' => input('post.short_name/s', '', 'trim'),
            'logo' => input('post.logo/d', 0, 'intval'),
            'nature' => input('post.nature/d', 0, 'intval'),
            'scale' => input('post.scale/d', 0, 'intval'),
            'trade' => input('post.trade/d', 0, 'intval')
        ];

        $backend_edit = model('b2bcrm.Company')->backendEdit($input_data);
        if (false === $backend_edit) {
            $this->ajaxReturn(500, model('Company')->getError());
        }
        model('AdminLog')->record(
            '编辑企业。企业ID【' .
            $input_data['id'] .
            '】;企业名称【' .
            $input_data['companyname'] .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功');

    }

    /*
     * 导出企业ById
     */
    public function companyById()
    {
        $this->checkExportAccess();
        $id = input('post.id/a', []);
        if (!is_array($id) || empty($id)) {
            $this->ajaxReturn(500, '请选择要导出的企业');
        }
        $list = model('Company')
            ->alias('a')
            ->join('company_contact b', 'a.id=b.comid', 'LEFT')
            ->join('member_setmeal c', 'a.uid=c.uid', 'LEFT')
            ->field(
                'a.id,a.companyname,a.short_name,a.nature,a.trade,a.district,a.scale,a.registered,a.currency,a.audit,a.addtime,a.refreshtime,b.contact,b.mobile,b.telephone,b.weixin,b.qq,b.email,a.life_cycle_id,a.admin_id,a.last_visit_time,a.uid'
            )
            ->whereIn('a.id', $id)
            ->order('a.id asc')
            ->select();
        if (empty($list)) {
            $this->ajaxReturn(500, '没有找到匹配的数据');
        }
        $life_cycle = model('b2bcrm.CrmLifeCycle')->getDate();
        $life_cycle_arr = [];
        foreach ($life_cycle as $v) {
            $life_cycle_arr[$v['id']] = $v['name'];
        }
        $uid_arr = [];
        foreach ($list as $key => $value) {
            $uid_arr[] = $value['uid'];
        }
        if (!empty($uid_arr)) {
            $job_apply_list = model('JobApply')
                ->where('company_uid', 'in', $uid_arr)
                ->group('company_uid')
                ->column('count(*) as num,company_uid', 'company_uid');
            $job_total_list = model('JobSearchRtime')
                ->where('uid', 'in', $uid_arr)
                ->group('uid')
                ->column('count(*) as num,uid', 'uid');
        } else {
            $job_apply_list = [];
            $job_total_list = [];
        }
        $return = [];
        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $clueobj = new Clue();
        $adminlist = $clueobj->adminlist();
        $count = 0;
        foreach ($list as $key => $value) {
            $arr['jobs_num'] = isset($job_total_list[$value['uid']])
                ? $job_total_list[$value['uid']]
                : 0;//在招职位数
            $arr['job_apply_count'] = isset($job_apply_list[$value['uid']])
                ? $job_apply_list[$value['uid']]
                : 0; // 收到简历数
            $arr['last_visit_time'] = $value['last_visit_time'];
            $arr['life_cycle_txt'] = isset($life_cycle_arr[$value['life_cycle_id']]) ? $life_cycle_arr[$value['life_cycle_id']] : ''; // 客户等级
            $arr['admin_name'] = isset($adminlist[$value['admin_id']]) ? $adminlist[$value['admin_id']] : '';
            $arr['not_following_day'] = '从未跟进';
            if ($value['last_visit_time'] > 0) {
                $arr['not_following_day'] = intval((time() - $value['last_visit_time']) / 86400);
            }
            $arr['number'] = ++$count;
            $arr['id'] = $value['id'];
            $arr['companyname'] = $value['companyname'];
            $arr['short_name'] = $value['short_name'];
            $arr['nature'] = isset(
                $category_data['QS_company_type'][$value['nature']]
            )
                ? $category_data['QS_company_type'][$value['nature']]
                : '';
            $arr['trade'] = isset($category_data['QS_trade'][$value['trade']])
                ? $category_data['QS_trade'][$value['trade']]
                : '';
            $arr['district'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $arr['scale'] = isset($category_data['QS_scale'][$value['scale']])
                ? $category_data['QS_scale'][$value['scale']]
                : '';
            $arr['registered'] =
                $value['registered'] .
                '万元' .
                ($value['currency'] == 0 ? '人民币' : '美元');
            $arr['audit'] = isset(model('Company')->map_audit[$value['audit']])
                ? model('Company')->map_audit[$value['audit']]
                : '认证未通过';
            $arr['addtime'] = date('Y-m-d H:i', $value['addtime']);
            $arr['refreshtime'] = date('Y-m-d H:i', $value['refreshtime']);
            $arr['contact'] = $value['contact'];
            $arr['mobile'] = $value['mobile'];
            $arr['weixin'] = $value['weixin'];
            $arr['telephone'] = $value['telephone'];
            $arr['qq'] = $value['qq'];
            $arr['email'] = $value['email'];
            $return[] = $arr;
        }

        if (!empty($return)) {
            model('AdminLog')->record(
                '导出企业信息【' . count($return) . '条】',
                $this->admininfo
            );
        }

        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }

    /*
     * 客户详情修改（侧拉）
     * */
    public function updateCompany()
    {
        $uid = input('post.uid/s', '', 'trim');
        $post = input('post.');
        $reason = isset($post['reason']) ? trim($post['reason']) : '';
        $examine = isset($post['examine']) ? intval($post['examine']) : 0;


        if (!$uid) {
            $this->ajaxReturn(500, '参数错误');
        }
        $info = model('Member')->find($uid);
        if (!$info) {
            $this->ajaxReturn(500, '数据获取失败');
        }

        $arr = [];
        if (isset($post['life_cycle_id'])) {
            $arr['life_cycle_id'] = intval($post['life_cycle_id']);
        }
        if (isset($post['labels'])) {
            $arr['labels'] = $post['labels'];
        }
        if (isset($post['is_display'])) {
            $arr['is_display'] = intval($post['is_display']);
        }
        if (isset($post['remark'])) {
            $arr['remark'] = trim($post['remark']);
        }
        if (isset($post['audit'])) {
            $arr['audit'] = intval($post['audit']);
        }
        try {
            if (isset($post['password']) || isset($post['mobile']) || isset($post['email'])) {
                $this->updateMember($uid, $post, $info); // 修改密码或手机号
            }
            if (!empty($arr)) {
                $this->editCompany($uid, $arr, $reason);
            }
            if (isset($post['company_img'])) {
                $post['company_img'] = empty($post['company_img']) ? '' : $post['company_img'];
                $this->setAudit($post['company_img'], $examine);
            }
            if (isset($post['company_mobile'])) {
                $post['company_mobile'] = empty($post['company_mobile']) ? '' : $post['company_mobile'];
                $this->updateEmail($uid, $post['company_mobile']);
            }
            $this->ajaxReturn(200, '修改成功');
        } catch (\Exception $e) {
            $this->ajaxReturn(500, $e->getMessage());
        }

    }

    // 修改企业手机号
    private function updateEmail($uid, $company_mobile)
    {
        $data = ['uid' => $uid];

        if (!empty($company_mobile)) {
            $preg_phone = '/^1[345789]\d{9}$/ims';
            if (!preg_match($preg_phone, $company_mobile)) {
                throw new \Exception('手机号格式错误');
            }
            $data['mobile'] = $company_mobile;
        }
        $company_contact = model('CompanyContact')->where(['uid' => $uid])->find();
        $isupdate = false;
        if (!empty($company_contact)) {
            $company_contact = $company_contact->toArray();
            $isupdate = true;
            $data['id'] = $company_contact['id'];
        }
        $update_email = model('CompanyContact')
            ->isUpdate($isupdate)
            ->save($data);
        if (false === $update_email) {
            throw new \Exception(model('CompanyContact')->getError());
        }
        model('AdminLog')->record(
            '编辑企业手机。企业UID【' . $uid . '】' . '修改后企业手机【' . $company_mobile . '】',
            $this->admininfo
        );

        return true;
    }

    // 修改企业风采
    private function setAudit($id, $audit)
    {
        if (!is_array($id)) {
            throw new \Exception('企业风采id请传入数组格式');
        }
        model('CompanyImg')
            ->where('id', 'in', $id)
            ->setField('audit', $audit);

        //完成上传企业风采任务
        if ($audit == 1) {
            $list = model('CompanyImg')
                ->where('id', 'in', $id)
                ->select();
            foreach ($list as $key => $value) {
                model('Task')->doTask($value['uid'], 1, 'upload_img');
            }
        }

        model('AdminLog')->record(
            '将企业风采认证状态变更为【' .
            model('CompanyImg')->map_audit[$audit] .
            '】。企业风采ID【' .
            implode(",", $id) .
            '】',
            $this->admininfo
        );
        return true;
    }

    // 修改企业
    private function editCompany($uid, $input_data, $reason = '')
    {
        $company_id = model('Company')
            ->where(['uid' => ['in', $uid]])
            ->column('id');
        if (empty($company_id)) {
            throw new \Exception('企业uid错误');
        }
        if (isset($input_data['audit']) && !empty($input_data['audit'])) {
            model('Company')->setAudit($company_id, $input_data['audit'], $reason);
            model('AdminLog')->record(
                '将企业认证状态变更为【' .
                model('Company')->map_audit[$input_data['audit']] .
                '】。企业ID【' .
                implode(',', $company_id) .
                '】',
                $this->admininfo
            );
        } else {
            $update_member = model('Company')
                ->allowField(true)
                ->save($input_data, ['uid' => $uid]);

            if (false === $update_member) {
                throw new \Exception(model('Company')->getError());
            }
            model('AdminLog')->record(
                '编辑企业。企业UID【' . $uid . '】',
                $this->admininfo
            );
        }

        return true;
    }

    // 修改密码或手机号
    private function updateMember($uid, $post, $info)
    {
        $input_data = ['username' => $info['username']];
        if (!empty($post['password'])) {
            $input_data['password'] = model('Member')->makePassword(
                $post['password'],
                $info['pwd_hash']
            );
        }

        if (isset($post['email'])) {
            if (!empty($post['email'])) {
                $preg_email = '/^[a-zA-Z0-9]+([-_.][a-zA-Z0-9]+)*@([a-zA-Z0-9]+[-.])+([a-z]{2,5})$/ims';

                if (!preg_match($preg_email, $post['email'])) {
                    throw new \Exception('请输入正确的邮箱格式');
                }
            }
            $input_data['email'] = $post['email'];
        }

        if (isset($post['mobile'])) {
            $preg_phone = '/^1[345789]\d{9}$/ims';
            if (!preg_match($preg_phone, $post['mobile'])) {
                throw new \Exception('手机号格式错误');
            }
            $member_uid = model('Member')->where(['mobile' => $post['mobile']])->value('mobile');
            if (!empty($member_uid) && $member_uid != $uid) {
                throw new \Exception('手机号已存在');
            }
            $input_data['mobile'] = $post['mobile'];
        }
        $update_member = model('Member')
            ->allowField(true)
            ->save($input_data, ['uid' => $uid]);

        if (false === $update_member) {
            throw new \Exception(model('Member')->getError());
        }
        model('AdminLog')->record(
            '编辑会员。会员UID【' . $uid . '】',
            $this->admininfo
        );
        return true;
    }

    public function lifeCycle()
    {
        $data = model('b2bcrm.CrmLifeCycle')->where(['is_open' => 1])->field('id,name,is_open,is_system,update_time')->select();
        $this->ajaxReturn(200, '获取数据成功', $data);
    }

    /**
     * 设置显示状态
     */
    public function setDisplay()
    {
        $id = input('post.id/a', []);
        if (count($id) == 0) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $is_display = input('post.is_display/d', 1, 'intval');

        $update = model('Company')->whereIn('id', $id)->setField('is_display', $is_display);
        $jobid_arr = model('Job')->whereIn('company_id', $id)->column('id');
        model('Job')->refreshSearchBatch($jobid_arr);
        model('AdminLog')->record(
            '将企业显示状态变更为【' .
            ($is_display == 1 ? '显示' : '不显示') .
            '】。企业ID【' .
            implode(',', $id) .
            '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '设置成功');
    }

    public function qrcode()
    {
        $uid = input('get.uid/d', 0, 'intval');
        if ($uid == 0) {
            $this->ajaxReturn(500, '请选择企业');
        }
        $alias = 'member_bind_weixin';
        $params = [
            'alias' => $alias,
            'uid' => $uid,
            'utype' => 1
        ];
        $class = new \app\common\lib\Wechat;
        $qrcode = $class->makeQrcode($params);
        if ($qrcode) {
            $this->ajaxReturn(200, '', $qrcode);
        } else {
            $this->ajaxReturn(501, 'server error');
        }
    }

    /*
     * 查看微信绑定状态
     * */
    public function getisWeixinBind()
    {
        $uid = input('get.uid/d', 0, 'intval');
        if ($uid === 0) {
            $this->ajaxReturn(500, '请选择企业');
        }
        $weixin = model('MemberBind')->where(['uid' => $uid, 'type' => 'weixin'])->find();
        if (empty($weixin)) {
            $this->ajaxReturn(500, '暂未绑定');
        }
        $this->ajaxReturn(200, '已绑定');
    }

}