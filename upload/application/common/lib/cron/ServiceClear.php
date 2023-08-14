<?php

namespace app\common\lib\cron;

use app\common\model\CityinfoArticle;
use app\common\model\FreelanceSubject;
use app\common\model\Job;
use app\common\model\JobSearchKey;
use app\common\model\JobSearchRtime;
use app\common\model\Resume;
use app\common\model\ResumeSearchKey;
use app\common\model\ResumeSearchRtime;
use app\common\model\ServiceQueue;
use think\Db;

class ServiceClear
{
    protected $error = null;

    public function execute()
    {
        $serviceQueueModel = new ServiceQueue();
        $where['deadline'] = ['lt', time()];
        $queue_list = $serviceQueueModel
            ->where($where)
//            ->limit(10)
            ->select();

        try {
            Db::startTrans();

            if (!empty($queue_list)) {
                foreach ($queue_list as $queue) {
                    if ($queue['utype'] == 2) {
                        //取消简历置顶
                        if ($queue['type'] == 'stick') {
                            Resume::where('id', $queue['pid'])->setField('stick', 0);
                            ResumeSearchKey::where('id', $queue['pid'])->setField('stick', 0);
                            ResumeSearchRtime::where('id', $queue['pid'])->setField('stick', 0);
                        }
                        //取消简历醒目标签
                        if ($queue['type'] == 'tag') {
                            Resume::where('id', $queue['pid'])->setField('service_tag', '');
                        }
                    }
                    if ($queue['utype'] == 1) {
                        //取消职位置顶
                        if ($queue['type'] == 'jobstick') {
                            Job::where('id', $queue['pid'])->setField('stick', 0);
                            JobSearchKey::where('id', $queue['pid'])->setField('stick', 0);
                            JobSearchRtime::where('id', $queue['pid'])->setField('stick', 0);
                        }
                        //取消职位紧急
                        if ($queue['type'] == 'emergency') {
                            Job::where('id', $queue['pid'])->setField('emergency', 0);
                            JobSearchKey::where('id', $queue['pid'])->setField('emergency', 0);
                            JobSearchRtime::where('id', $queue['pid'])->setField('emergency', 0);
                        }
                        if ($queue['type'] == 'pt-stick-subject') {
                            (new FreelanceSubject())->save(['is_top' => 0], ['id' => $queue['pid']]);
                        }
                    }
                    if ($queue['type'] == 'cityinfo-stick') {
                        (new CityinfoArticle())->save(['is_top' => 0], ['id' => $queue['pid']]);
                    }
                }
                $serviceQueueModel->where($where)->delete();
            }

            // 处理错误数据
            $this->_handleErrorData($serviceQueueModel);

            Db::commit();
            return true;

        } catch (\Exception $e) {
            Db::rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * 处理错误数据
     * @param $model
     * @return true
     */
    private function _handleErrorData($model)
    {
        $resumeModel = new Resume();
        $jobModel = new Job();

        /**
         * 1.简历置顶业务
         * 错误数据处理，
         * [逻辑]:
         * 先查询`ServiceQueue`中 置顶 服务
         * 再查询简历中是否有 置顶 的简历
         * 取差集即为错误数据
         */
        $stick_pids = $model
            ->where('type', 'stick')
            ->column('pid');
        $resume_stick_ids = $resumeModel
            ->alias('r')
            ->join('ResumeSearchKey sk', 'sk.id = r.id', 'LEFT')
            ->join('ResumeSearchRtime sr', 'sr.id = r.id', 'LEFT')
            ->where('r.stick', 1)
            ->whereOr('sk.stick', 1)
            ->whereOr('sr.stick', 1)
            ->column('r.id');

        $error_stick_ids = array_diff($resume_stick_ids, $stick_pids);
        if (!empty($error_stick_ids)) {
            $stickWhere = [
                'id' => ['in', $error_stick_ids]
            ];
            $stickData = [
                'stick' => 0
            ];
            $resumeModel->where($stickWhere)->update($stickData);
            ResumeSearchKey::where($stickWhere)->update($stickData);
            ResumeSearchRtime::where($stickWhere)->update($stickData);
        }

        /**
         * 2.简历醒目标签业务
         * 错误数据处理，
         * [逻辑]:
         * 先查询`ServiceQueue`中 醒目标签 服务
         * 再查询简历中是否有 醒目标签 的简历
         * 取差集即为错误数据
         */
        $tag_pids = $model
            ->where('type', 'service_tag')
            ->column('pid');
        $tag_ids = $resumeModel->where(['service_tag' => ['neq', '']])->column('id');
        $error_tag_ids = array_diff($tag_ids, $tag_pids);
        if (!empty($error_tag_ids)) {
            $tagWhere = [
                'id' => ['in', $error_tag_ids]
            ];
            $tagData = [
                'service_tag' => '',
            ];
            $resumeModel->where($tagWhere)->update($tagData);
        }

        /**
         * 3.职位置顶业务
         * 错误数据处理，
         * [逻辑]:
         * 先查询`ServiceQueue`中职位 置顶 服务
         * 再查询职位中是否有 置顶 的职位
         * 取差集即为错误数据
         */
        $jobstick_pids = $model
            ->where('type', 'jobstick')
            ->column('pid');
        $jobstick_ids = $jobModel
            ->alias('j')
            ->join('JobSearchKey jk', 'jk.id = j.id', 'LEFT')
            ->join('JobSearchRtime jr', 'jr.id = j.id', 'LEFT')
            ->where('j.stick', 1)
            ->whereOr('jk.stick', 1)
            ->whereOr('jr.stick', 1)
            ->column('j.id');
        $error_jobstick_ids = array_diff($jobstick_ids, $jobstick_pids);
        if (!empty($error_jobstick_ids)) {
            $jobstickWhere = [
                'id' => ['in', $error_jobstick_ids]
            ];
            $jobstickData = [
                'stick' => 0
            ];
            $jobModel->where($jobstickWhere)->update($jobstickData);
            JobSearchKey::where($jobstickWhere)->update($jobstickData);
            JobSearchRtime::where($jobstickWhere)->update($jobstickData);
        }

        /**
         * 4.职位紧急业务
         * 错误数据处理，
         * [逻辑]:
         * 先查询`ServiceQueue`中职位 紧急 服务
         * 再查询职位中是否有 紧急 的职位
         * 取差集即为错误数据
         */
        $emergency_pids = $model
            ->where('type', 'emergency')
            ->column('pid');
        $emergency_ids = $jobModel
            ->alias('j')
            ->join('JobSearchKey jk', 'jk.id = j.id', 'LEFT')
            ->join('JobSearchRtime jr', 'jr.id = j.id', 'LEFT')
            ->where('j.emergency', 1)
            ->whereOr('jk.emergency', 1)
            ->whereOr('jr.emergency', 1)
            ->column('j.id');
        $error_emergency_ids = array_diff($emergency_ids, $emergency_pids);
        if (!empty($error_emergency_ids)) {
            $emergencyWhere = [
                'id' => ['in', $error_emergency_ids]
            ];
            $emergencyData = [
                'emergency' => 0
            ];
            $jobModel->where($emergencyWhere)->update($emergencyData);
            JobSearchKey::where($emergencyWhere)->update($emergencyData);
            JobSearchRtime::where($emergencyWhere)->update($emergencyData);
        }

        return true;
    }

    public function getError()
    {
        return $this->error;
    }

}
