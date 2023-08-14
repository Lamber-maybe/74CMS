<?php
namespace app\common\lib\cron;

class RefreshJob
{
    public function execute()
    {
        $timestamp = time();
        $where['execute_time'] = ['lt', $timestamp];
        $list = model('RefreshjobQueue')
            ->where($where)
            ->field('uid,jobid')
            ->select();
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $uid_arr[] = $value['uid'];
                $jobid_arr[] = $value['jobid'];
            }
            // 刷新职位信息 chenyang 2022年3月21日15:17:43
            $refreshParams = [
                'id'          => $jobid_arr,
                'refresh_log' => true,
            ];
            model('Job')->refreshJobData($refreshParams);
        }
        model('RefreshjobQueue')
            ->where($where)
            ->delete();
    }
}
