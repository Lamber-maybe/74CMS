<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;
use think\Db;

class SaleStatistics extends Backend
{
    /**
     * 销售统计
     */
    public function salesKanban(){
        $user_id = $this->admininfo->id;
        $month = input('post.month/s', '', 'trim');
        $month_stat = strtotime(getMonthRange($month));
        $month_end = strtotime(getMonthRange($month,false));
        $new_customer = model('Company')
            ->where('admin_id',$user_id)
            ->where('collection_time','>',$month_stat)
            ->where('collection_time','<=',$month_end)
            ->count();//新增客户
        $new_crm_clue = model('b2bcrm.CrmClue')
            ->where('admin_id',$user_id)
            ->where('collection_time','>',$month_stat)
            ->where('collection_time','<=',$month_end)
            ->count();//新增线索
        $expire_company = model('Company')
            ->alias('c')
            ->join('member_setmeal s','c.uid = s.uid')
            ->where('c.admin_id',$user_id)
            ->where('s.deadline','<',time())
            ->where('s.deadline','>',0)
            ->where('collection_time','>',$month_stat)
            ->where('collection_time','<=',$month_end)
            ->group('c.id')
            ->count();//到期为续费客户
        $expiring_soon = model('Company')
            ->alias('c')
            ->join('member_setmeal s','c.uid = s.uid')
            ->where('c.admin_id',$user_id)
            ->where('deadline','>=',time())
            ->where('deadline','<',strtotime('+'.config('global_config.meal_min_remind').'day'))
            ->where('collection_time','>',$month_stat)
            ->where('collection_time','<=',$month_end)
            ->group('c.id')
            ->count();//即将到期客户
        $transaction_customers = model('Company')
            ->where('admin_id',$user_id)
            ->where('life_cycle_id',7)
            ->where('collection_time','>',$month_stat)
            ->where('collection_time','<=',$month_end)
            ->count();//成交客户
        $customer_followed_up = model('Company')
            ->where('admin_id',$user_id)
            ->where('last_visit_time','>',0)
            ->where('collection_time','>',$month_stat)
            ->where('collection_time','<=',$month_end)
            ->count();//已跟进客户
        $return = [
            'new_customer'=>$new_customer,
            'new_crm_clue'=>$new_crm_clue,
            'expire_company'=>$expire_company,
            'expiring_soon'=>$expiring_soon,
            'transaction_customers'=>$transaction_customers,
            'customer_followed_up'=>$customer_followed_up,
        ];
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
    /**
     *  我的客户和我的线索
     */
    public function companyCrmClueStatistics(){
        $user_id = $this->admininfo->id;
        $timestampToday = strtotime('today');
        $total_company =  model('Company')
            ->where('admin_id',$user_id)
            ->count();//累计总客户
        $today_company = model('Company')
            ->where('collection_time','>',$timestampToday)
            ->where('admin_id',$user_id)
            ->count();//今日新增
        $auth_company = model('Company')
            ->alias('c')
            ->join('company_auth a', 'a.uid=c.uid', 'LEFT')
            ->where('admin_id',$user_id)
            ->where('c.audit', 0)
            ->where('a.id', 'not null')
            ->count();//认证待审核客户
        $expire_company = model('Company')
            ->alias('c')
            ->join('member_setmeal s', 's.uid=c.uid', 'LEFT')
            ->where('c.admin_id',$user_id)
            ->where('s.deadline','<',time())
            ->where('s.deadline','>',0
            )->count();//到期为续费客户
        $expiring_soon = model('Company')
            ->alias('c')
            ->join('member_setmeal s', 's.uid=c.uid', 'LEFT')
            ->where('c.admin_id',$user_id)
            ->where('s.deadline','>=',time())
            ->where('s.deadline','<',strtotime('+'.config('global_config.meal_min_remind').'day'))
            ->count();//即将到期客户
        $today_follow = model('Company')
            ->where('admin_id',$user_id)
            ->where('last_visit_time','>',$timestampToday)
            ->count();//今日跟进客户
        $time = strtotime(date("Y-m-d", strtotime("-30 day")));
        $thirty_follow = model('Company')
            ->where('admin_id',$user_id)
            ->where('last_visit_time','>',$time)
            ->count();//30天内跟进过的客户
        $time = strtotime(date("Y-m-d", strtotime("-7 day")));
        $seven_not_follow = model('Company')
            ->where('admin_id',$user_id)
            ->where('last_visit_time','>',$time)
            ->count();//7天未跟进客户
        $time = strtotime(date("Y-m-d", strtotime("-15 day")));
        $fifteen_not_follow = model('Company')
            ->where('admin_id',$user_id)
            ->where('last_visit_time','>',$time)
            ->count();//15日内未跟进客户
        $customer_fall_seas = model('b2bcrm.CrmSysConfig')
            ->getConfigByKey('customer_fall_seas');
        if (isset($customer_fall_seas) && !empty($customer_fall_seas)) {
            $day = $customer_fall_seas - 7;
            $time = strtotime(date("Y-m-d", strtotime("-$day day")));
            $international_waters = model('Company')
                ->where('admin_id',$user_id)
                ->where('last_visit_time','>',$time)
                ->count();//即将转入公海客户
        } else {
            $international_waters = 0;
        }
        $total_clue = model('b2bcrm.CrmClue')
            ->where('admin_id',$user_id)
            ->count();//累计总线索统计
        $today_clue = model('b2bcrm.CrmClue')
            ->where('collection_time','>',$timestampToday)
            ->where('admin_id',$user_id)
            ->count();//今日新增线索
        $startTime = mktime(0,0,0,date('m'),date('d')-date('w')+1,date('y'));
        $overTime = mktime(23,59,59,date('m'),date('d')-date('w')+7,date('y'));
        $week_clue = model('b2bcrm.CrmClue')
            ->where('collection_time','>',$startTime)
            ->where('collection_time','<=',$overTime)
            ->where('admin_id',$user_id)
            ->count();//本周新增线索
        $startTime =mktime(0,0,0,date('m'),1,date('Y'));
        //本月结束时间时间戳
        $overTime =mktime(23,59,59,date('m'),date('t'),date('Y'));
        $month_clue = model('b2bcrm.CrmClue')
            ->where('collection_time','>',$startTime)
            ->where('collection_time','<=',$overTime)
            ->where('admin_id',$user_id)
            ->count();//本月新增线索
        $not_following_clue = model('b2bcrm.CrmClue')
            ->where('last_visit_time',0)
            ->where('is_customer',0)
            ->where('admin_id',$user_id)
            ->count();//从未跟进过
        $following_one_clue = model('b2bcrm.CrmClue')
            ->alias('a')
            ->join('CrmFollowUp b','a.id=b.clue_id and b.type=1','left')
            ->where('a.admin_id',$user_id)
            ->field('count(b.id) as num,a.*')
            ->group('a.id')
            ->having("num = 1 and is_customer = '0' and admin_id > 0")
            ->count();//跟进过1次
        $following_two_clue = model('b2bcrm.CrmClue')
            ->alias('a')
            ->join('CrmFollowUp b','a.id=b.clue_id and b.type=1','left')
            ->where('a.admin_id',$user_id)
            ->field('count(b.id) as num,a.*')
            ->group('a.id')
            ->having("num = 2 and is_customer = '0' and admin_id > 0")
            ->count();//跟进过2次
        $following_three_clue =  model('b2bcrm.CrmClue')
            ->alias('a')
            ->join('CrmFollowUp b','a.id=b.clue_id and b.type=1','left')
            ->where('a.admin_id',$user_id)
            ->field('count(b.id) as num,a.*')
            ->group('a.id')
            ->having("num >= 3 and is_customer = '0' and admin_id > 0")
            ->count();//跟进过3次以上
        $month_stat = strtotime(getMonthRange(date('Y-m-d',time())));
        $month_end = strtotime(getMonthRange(date('Y-m-d',time()),false));
        $deal_ranking_list = model('admin')->alias('a')
            ->join('Company c','a.id = c.admin_id','left')
            ->where('a.status', 1)
            ->where('c.life_cycle_id',7)
            ->where('c.collection_time','>',$month_stat)
            ->where('c.collection_time','<=',$month_end)
            ->field('count(c.id) as num,a.username')
            ->group('a.id')
            ->order('num desc')
            ->limit(6)
            ->select();
        foreach ($deal_ranking_list as $k=>$v){
            $deal_ranking_list[$k]['ranking'] = $k+1;
        }
        $new_customersdeal_ranking_list = model('admin')->alias('a')
            ->join('Company c','a.id = c.admin_id','left')
            ->where('a.status', 1)
            ->where('c.life_cycle_id',1)
            ->where('c.collection_time','>',$month_stat)
            ->where('c.collection_time','<=',$month_end)
            ->field('count(c.id) as num,a.username')
            ->group('a.id')
            ->order('num desc')
            ->limit(6)
            ->select();
        foreach ($new_customersdeal_ranking_list as $k=>$v){
            $new_customersdeal_ranking_list[$k]['ranking'] = $k+1;
        }
        $return = [
            'total_company'=>$total_company,
            'today_company'=>$today_company,
            'auth_company'=>$auth_company,
            'expire_company'=>$expire_company,
            'expiring_soon'=>$expiring_soon,
            'today_follow'=>$today_follow,
            'thirty_follow'=>$thirty_follow,
            'seven_not_follow'=>$seven_not_follow,
            'fifteen_not_follow'=>$fifteen_not_follow,
            'international_waters'=>$international_waters,
            'total_clue'=>$total_clue,
            'today_clue'=>$today_clue,
            'week_clue'=>$week_clue,
            'month_clue'=>$month_clue,
            'not_following_clue'=>$not_following_clue,
            'following_one_clue'=>$following_one_clue,
            'following_two_clue'=>$following_two_clue,
            'following_three_clue'=>$following_three_clue,
            'deal_ranking_list'=>$deal_ranking_list,
            'new_customersdeal_ranking_list'=>$new_customersdeal_ranking_list
        ];
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
}
