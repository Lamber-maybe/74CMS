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
            model('Job')->refreshJobBatch($jobid_arr, $uid_arr);
        }
        model('RefreshjobQueue')
            ->where($where)
            ->delete();
    }
}
