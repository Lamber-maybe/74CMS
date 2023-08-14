<?php
namespace app\common\lib\cron;

class InterviewVideoNotice
{
    public function execute()
    {
        $today_start = strtotime('today');
        $today_end = $today_start + 3600 * 24 - 1;
        $list = \app\common\model\CompanyInterviewVideo::where(
            'interview_time',
            'between',
            [$today_start, $today_end]
        )
            ->field('uid,personal_uid')
            ->select();
        $uid_company = $uid_personal = [];
        foreach ($list as $key => $value) {
            $uid_company[] = $value['uid'];
            $uid_personal[] = $value['personal_uid'];
        }
        if (!empty($uid_company)) {
            model('NotifyRule')->notify($uid_company, 1, 'cron_interview_video');
        }
        if (!empty($uid_personal)) {
            model('NotifyRule')->notify($uid_personal, 2, 'cron_interview_video');
        }
    }
}
