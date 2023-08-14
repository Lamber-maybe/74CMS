<?php
namespace app\common\lib\cron;

class NologinNotice
{
    public function execute()
    {
        $timestamp = time();
        $config_time_range = config('global_config.nologin_notice_timerange');
        $config_time_counter = config('global_config.nologin_notice_counter');
        if(false === stripos($config_time_range,',')){
            $starttime = strtotime('-'.intval($config_time_range).'day');
            $starttime = strtotime(date('Y-m-d',$starttime));
            $endtime = $starttime + 3600*24;
        }else{
            $config_time_range_arr = explode(",",$config_time_range);
            $starttime = strtotime('-'.intval($config_time_range_arr[0]).'day');
            $starttime = strtotime(date('Y-m-d',$starttime));
            $endtime = strtotime('-'.intval($config_time_range_arr[1]).'day');
            $endtime = strtotime(date('Y-m-d',$endtime));
            $endtime = $endtime + 3600*24;
        }
        $where = 'nologin_notice_counter < '.intval($config_time_counter).' and (last_login_time = 0 or (last_login_time >= '.$starttime.' and last_login_time < '.$endtime.'))';
        $model = new \app\common\model\Member();
        $list = $model
            ->where($where)
            ->select();
        $uidarr_company = $uidarr_personal = $uid_arr = [];
        foreach ($list as $key => $value) {
            $uid_arr[] = $value['uid'];
            if($value['utype']==1){
                $uidarr_company[] = $value['uid'];
            }else{
                $uidarr_personal[] = $value['uid'];
            }
        }
        if (!empty($uidarr_company)) {
            model('NotifyRule')->notify($uidarr_company, 1, 'cron_recommend');
        }
        if (!empty($uidarr_personal)) {
            model('NotifyRule')->notify($uidarr_personal, 2, 'cron_recommend');
        }
        if (!empty($uid_arr)) {
            $model->whereIn('uid',$uid_arr)->setInc('nologin_notice_counter');
        }
    }
}
