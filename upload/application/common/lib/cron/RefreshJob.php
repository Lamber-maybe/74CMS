<?php
/**
 * 自动刷新职位
 * yx - 2022.11.11
 */

namespace app\common\lib\cron;

use think\Db;

class RefreshJob
{
    /**
     * @var null 错误信息
     */
    protected $error = null;

    public function execute()
    {
        $timestamp = time();
        $where['execute_time'] = ['lt', $timestamp];

        $list = model('RefreshjobQueue')
            ->where($where)
            ->field('uid,jobid')
            ->select();

        if (isset($list) && !empty($list)) {
            $jobid_arr = [];
            foreach ($list as $job) {
                $jobid_arr[] = $job['jobid'];
            }
            $refreshParams = [
                'id' => $jobid_arr
            ];
        } else {
            $this->error = '没有要自动刷新的职位';
            return false;
        }

        try {
            Db::startTrans();

            $result = model('Job')->refreshJobMind($refreshParams, 4);
            if (false === $result['status']) {
                throw new \Exception($result['msg']);
            }

            $delete = model('RefreshjobQueue')
                ->where($where)
                ->delete();
            if (false === $delete) {
                throw new \Exception(model('RefreshjobQueue')->getError());
            }

            Db::commit();
            return true;

        } catch (\Exception $e) {
            Db::rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getError()
    {
        return $this->error;
    }
}
