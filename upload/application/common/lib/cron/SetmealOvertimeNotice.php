<?php
namespace app\common\lib\cron;

class SetmealOvertimeNotice
{
    public function execute()
    {
        $meal_min_remind = config('global_config.meal_min_remind');
        if (intval($meal_min_remind) == 0) {
            return;
        }
        $start_time = time();
        $end_time = strtotime('+' . $meal_min_remind . ' day');
        $memberdata = model('MemberSetmeal')->alias('a')->join(config('database.prefix').'setmeal b','a.setmeal_id=b.id')->where(
            'a.deadline',
            'between',
            [$start_time, $end_time]
        )->column('a.id,a.uid,a.deadline,b.name');
        $uid_arr = [];
        foreach ($memberdata as $key => $value) {
            $uid_arr[] = $value['uid'];
        }
        if (!empty($uid_arr)) {
            model('NotifyRule')->notify($uid_arr, 1, 'cron_setmeal_overtime');
            //微信通知
            foreach ($memberdata as $key => $value) {
                model('WechatNotifyRule')->notify(
                    $value['uid'],
                    1,
                    'cron_setmeal_overtime',
                    [
                        '您好，您的会员套餐即将到期',
                        $value['name'],
                        date('Y年m月d日 H:i',$value['deadline']),
                        '到期后将失去会员专享特权，点击查看会员特权'
                    ],
                    'member/company/mysetmeal'
                );
            }
        }
    }
}
