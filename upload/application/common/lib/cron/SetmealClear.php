<?php
namespace app\common\lib\cron;

class SetmealClear
{
    public function execute()
    {
        $timestamp = time();
        $uid_arr = \app\common\model\MemberSetmeal::where('deadline','>',0)->where('deadline','<',$timestamp)->where('expired',0)->column('uid');
        if(empty($uid_arr)){
            return;
        }
        \app\common\model\MemberSetmeal::where('uid','in',$uid_arr)->update(['expired'=>1]);
        if(config('global_config.overtime_setmeal_resource')==0){
            \app\common\model\MemberSetmeal::where('uid','in',$uid_arr)->update([
                'download_resume_point'=>0
            ]);
        }
        if(config('global_config.overtime_setmeal_jobnum')==0){
            $overtime_config = config('global_config.setmeal_overtime_conf');
            $job_max = $overtime_config['jobs_meanwhile'];
            $handler_uid_arr = [];
            $group_list = \app\common\model\JobSearchRtime::where('uid','in',$uid_arr)->field('uid,count(*) as num')->group('uid')->select();
            foreach ($group_list as $key => $value) {
                if($value['num']>$job_max){
                    //整理出需要关闭的uid和对应的关闭职位数
                    $handler_uid_arr[$value['uid']] = $value['num'] - $job_max;
                }
            }
            if(!empty($handler_uid_arr)){
                $jobid_arr = [];
                foreach ($handler_uid_arr as $key => $value) {
                    $jobid_arr_tmp = \app\common\model\JobSearchRtime::where('uid',$key)->order('refreshtime asc,id asc')->limit($value)->column('id');
                    $jobid_arr = array_merge($jobid_arr,$jobid_arr_tmp);
                }
                if(!empty($jobid_arr)){
                    \app\common\model\Job::whereIn('id',$jobid_arr)->setField('is_display',0);
                    (new \app\common\model\Job)->refreshSearchBatch($jobid_arr);
                }
            }
        }
    }
}
