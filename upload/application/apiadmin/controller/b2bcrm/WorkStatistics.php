<?php

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;
use think\Db;

class WorkStatistics  extends Backend
{
    /**
     * 工作看板
     * @return void
     */
    public function work(){
        $timestampToday = strtotime('today');
        $total_company =  model('Company')
            ->count();//累计总客户
        $today_company = model('Company')
            ->where('addtime','>',$timestampToday)
            ->count();//今日新增
        $international_company = model('Company')
            ->where('admin_id','=',0)
            ->count();//客户公海
        $follow_up_company = model('Company')
            ->where('last_visit_time','>',$timestampToday)
            ->count();//今日跟进
        $total_clue = model('b2bcrm.CrmClue')
            ->count();//累计总线索统计
        $today_clue = model('b2bcrm.CrmClue')
            ->where('is_customer','=',0)
            ->where('createtime','>',$timestampToday)
            ->count();//今日新增线索
        $international_clue = model('b2bcrm.CrmClue')
            ->where('is_customer','=',0)
            ->where('admin_id','=',0)
            ->count();//线索公海
        $total_turn_clue = model('b2bcrm.CrmClue')
            ->where('is_customer','=',1)
            ->count();//累计线索转客户
        $expire_company = model('Company')
            ->alias('c')
            ->join('member_setmeal m','m.uid = c.uid')
            ->where('m.deadline','lt',time())
            ->where('m.deadline','gt',0)
            ->count();//到期为续费客户
        $expiring_soon = model('Company')
            ->alias('c')
            ->join('member_setmeal m','m.uid = c.uid')
            ->where('m.deadline','>=',time())
            ->where('m.deadline','<',strtotime('+'.config('global_config.meal_min_remind').'day'))
            ->count();//即将到期客户
        $never_follow_up_company = model('Company')
            ->where('last_visit_time','=',0)
            ->count();//从未跟进客户
        $never_follow_up_clue = model('b2bcrm.CrmClue')
            ->where('is_customer','=',0)
            ->where('last_visit_time','=',0)
            ->count();//从未跟进线索
        $return = [
            'statistics'=>[
                'total_company'=>$total_company,
                'today_company'=>$today_company,
                'international_company'=>$international_company,
                'follow_up_company'=>$follow_up_company,
                'total_clue'=>$total_clue,
                'today_clue'=>$today_clue,
                'international_clue'=>$international_clue,
                'total_turn_clue'=>$total_turn_clue,
            ],
            'information_reminder'=>[
                'expire_company'=>$expire_company,
                'expiring_soon'=>$expiring_soon,
                'never_follow_up_company'=>$never_follow_up_company,
                'never_follow_up_clue'=>$never_follow_up_clue,
            ]
        ];
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
    /**
     * 销售统计
     */
    public function salesStatistics(){
        $page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $order_type = input('get.order_type/s', '', 'trim');
        $order = input('get.order/s', '', 'trim');
        if($order_type != '' && $order!= ''){
            $order_str = $order_type.' '.$order;
        }else{
            $order_str = 'company_total desc';
        }
        $timestampToday = strtotime('today');
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $seven_days_time = strtotime(date("Y-m-d", strtotime("-7 day")));
        $fifteen_days_time = strtotime(date("Y-m-d", strtotime("-15 day")));
        $total = Db::name('admin')->count();
        $list = Db::name('admin')
            ->alias('a')
            ->join('Company c','a.id = c.admin_id','LEFT')
            ->join('member_setmeal s','s.uid = c.uid','LEFT')
            ->field('
                a.username,
                count(c.id) as company_total,
                count(if(c.addtime > '.$timestampToday.',true,null)) as today_num,
                count(if(s.deadline > '.time().' && deadline < '.strtotime('+'.config('global_config.meal_min_remind').'day').',true,null)) as expiring_soon,
                count(if(s.deadline > 0 && deadline < '.time().',true,null))  as no_renewal,
                count(if(life_cycle_id=1,true,null)) as new_customers,
                count(if(life_cycle_id=3,true,null)) as mature_num,
                count(if(life_cycle_id=4,true,null)) as maintain_num,
                count(if(life_cycle_id=5,true,null)) as stable_num,
                count(if(life_cycle_id=6,true,null)) as loss_num,
                count(if(life_cycle_id=7,true,null)) as deal_num,
                count(if(c.last_visit_time > '.$beginToday.',true,null)) as follow_up_today,
                count(if(c.last_visit_time > '.$seven_days_time.',true,null)) as seven_days_no_follow_up,
                count(if(c.last_visit_time > '.$fifteen_days_time.',true,null)) as fifteen_days_no_follow_up,
                count(if(c.last_visit_time = 0,true,null)) as no_follow_up
            ')
            ->group('a.id')
            ->order($order_str)
            ->page($page,$pagesize)
            ->select();
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 新增客户统计
     */
    public function newCompanyStatistics(){
        $admin_id = input('post.admin_id/s', '', 'trim');
        $start_time = input('post.start_time/s', '', 'trim');
        $end_time = input('post.end_time/s', '', 'trim');
        $this->getDataList('newCompanyStatistics',$start_time,$end_time,$admin_id);
    }
    /**
     * 客户跟进统计
     */
    public function newCompanyFollowStatistics(){
        $admin_id = input('post.admin_id/s', '', 'trim');
        $start_time = input('post.start_time/s', '', 'trim');
        $end_time = input('post.end_time/s', '', 'trim');
        $this->getDataList('newCompanyFollowStatistics',$start_time,$end_time,$admin_id);
    }
    /**
     * 新增线索
     */
    public function newClueStatistics(){
        $admin_id = input('post.admin_id/s', '', 'trim');
        $start_time = input('post.start_time/s', '', 'trim');
        $end_time = input('post.end_time/s', '', 'trim');
        $this->getDataList('newClueStatistics',$start_time,$end_time,$admin_id);
    }
    /**
     * 线索跟进统计
     */
    public function newClueFollowStatistics(){
        $admin_id = input('post.admin_id/s', '', 'trim');
        $start_time = input('post.start_time/s', '', 'trim');
        $end_time = input('post.end_time/s', '', 'trim');
        $this->getDataList('newClueFollowStatistics',$start_time,$end_time,$admin_id);
    }
    /**
     * 生命周期客户统计
     */
    public function lifeCycle(){
        $where = [];
        $admin_id = input('post.admin_id/s', '', 'trim');
        $start_time = input('post.start_time/s', '', 'trim');
        $end_time = input('post.end_time/s', '', 'trim');
        $life_cycle_list = model('b2bcrm.CrmLifeCycle')
            ->field('id,name')
            ->select()
            ->toArray();
        $life_cycle_name_arr = array_column($life_cycle_list,'name');
        if($admin_id != ''){
            $admin_id_arr = explode(',',$admin_id);
            $where['admin_id'] = ['in',$admin_id_arr];
        }
        $return = [
            'legend' => $life_cycle_name_arr,
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime($end_time);
        $starttime = strtotime($start_time);
        $daterange = [$starttime, $endtime + 86400 - 1];
        foreach ($life_cycle_list as $k=>$v){
            $list = Db::name('Company')
                ->where('life_cycle_id',$v['id'])
                ->where($where)
                ->where('updatetime', 'between time', $daterange)
                ->group('time')
                ->column(
                    'UNIX_TIMESTAMP(FROM_UNIXTIME(`updatetime`, "%Y%m%d")) as time,count(*) as num'
                );
            $lists = [];
            $date = [];
            for ($i = $starttime; $i <= $endtime; $i += 86400) {
                $date[] = date('m/d', $i);
                $lists[] = isset($list[$i])
                    ? $list[$i]
                    : 0;
            }
            $return['series'][$k] = $lists;
            if(empty($return['xAxis'])){
                $return['xAxis'] = $date;
            }
        }
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
    public function getDataList($type,$start_time,$end_time,$admin_id){
        $admin_list = model('admin');
        if($admin_id != ''){
            $group_arr =  explode(',',$admin_id);
            $admin_list = $admin_list->where('id','in',$group_arr);
        }
        $admin_list = $admin_list->field('id,username')->select();
        $return = [
            'legend' => array_column($admin_list,'username'),
            'xAxis' => [],
            'series' => []
        ];
        $endtime = strtotime($end_time);
        $starttime = strtotime($start_time);
        $daterange = [$starttime, $endtime + 86400 - 1];
        foreach ($admin_list as $k=>$v){
            switch ($type){
                case 'newClueFollowStatistics':
                    $list = Db::name('CrmClue')
                        ->where('admin_id',$v['id'])
                        ->where('last_visit_time', 'between time', $daterange)
                        ->group('time')
                        ->column(
                            'UNIX_TIMESTAMP(FROM_UNIXTIME(`last_visit_time`, "%Y%m%d")) as time,count(*) as num'
                        );
                    break;
                case 'newClueStatistics':
                    $list = Db::name('CrmClue')
                        ->where('admin_id',$v['id'])
                        ->where('collection_time', 'between time', $daterange)
                        ->group('time')
                        ->column(
                            'UNIX_TIMESTAMP(FROM_UNIXTIME(`collection_time`, "%Y%m%d")) as time,count(*) as num'
                        );
                    break;
                case 'newCompanyFollowStatistics':
                    $list = Db::name('Company')
                        ->where('admin_id',$v['id'])
                        ->where('last_visit_time', 'between time', $daterange)
                        ->group('time')
                        ->column(
                            'UNIX_TIMESTAMP(FROM_UNIXTIME(`last_visit_time`, "%Y%m%d")) as time,count(*) as num'
                        );
                    break;
                case 'newCompanyStatistics':
                    $list = Db::name('Company')
                        ->where('admin_id',$v['id'])
                        ->where('collection_time', 'between time', $daterange)
                        ->group('time')
                        ->column(
                            'UNIX_TIMESTAMP(FROM_UNIXTIME(`collection_time`, "%Y%m%d")) as time,count(*) as num'
                        );
                    break;
            }
            $lists = [];
            $date = [];
            for ($i = $starttime; $i <= $endtime; $i += 86400) {
                $date[] = date('m/d', $i);
                $lists[] = isset($list[$i])
                    ? $list[$i]
                    : 0;
            }
            $return['series'][$k] = $lists;
            if(empty($return['xAxis'])){
                $return['xAxis'] = $date;
            }
        }
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
}
