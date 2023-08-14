<?php
namespace app\common\lib\cron;

class FewResumePointNotice
{
    public function execute()
    {
        $cuttime = strtotime(date('Y-m-d',strtotime('-1day')));//时间>1天前
        $where['a.last_login_time'] = ['egt',$cuttime];

        $list = model('Member')->alias('a')
            ->join(config('database.prefix').'member_setmeal b','a.uid=b.uid','LEFT')
            ->field('a.uid')
            ->where('a.last_login_time','egt',$cuttime)
            ->where('b.download_resume_point','lt',100)//简历点少于100
            ->select();
        $uidarr = [];
        foreach ($list as $key => $value) {
            $uidarr[] = $value['uid'];
        }
        if (!empty($uidarr)) {
            $uidarr = array_unique($uidarr);
            model('NotifyRule')->notify($uidarr, 1, 'cron_down_point_low');
        }
    }
}
