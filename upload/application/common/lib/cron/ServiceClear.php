<?php
namespace app\common\lib\cron;


class ServiceClear
{
    public function execute()
    {
        $model = new \app\common\model\ServiceQueue();
        $where['deadline'] = ['lt', time()];
        $list = $model
            ->where($where)
            ->limit(10)
            ->select();
        if (empty($list)) {
            // 处理错误数据
            $this->_handleErrorData($model);
            return true;
        }

        foreach ($list as $key => $value) {
            if ($value['utype'] == 2) {
                //取消简历置顶
                if ($value['type'] == 'stick') {
                    \app\common\model\Resume::where('id',$value['pid'])->setField('stick', 0);
                    \app\common\model\ResumeSearchKey::where('id',$value['pid'])->setField('stick', 0);
                    \app\common\model\ResumeSearchRtime::where('id',$value['pid'])->setField('stick', 0);
                }
                //取消简历醒目标签
                if ($value['type'] == 'tag') {
                    \app\common\model\Resume::where('id',$value['pid'])->setField('service_tag', '');
                }
            }
            if ($value['utype'] == 1) {
                //取消职位置顶
                if ($value['type'] == 'jobstick') {
                    \app\common\model\Job::where('id', $value['pid'])->setField('stick',0);
                    \app\common\model\JobSearchKey::where('id',$value['pid'])->setField('stick', 0);
                    \app\common\model\JobSearchRtime::where('id',$value['pid'])->setField('stick', 0);
                }
                //取消职位紧急
                if ($value['type'] == 'emergency') {
                    \app\common\model\Job::where('id', $value['pid'])->setField('emergency',0);
                    \app\common\model\JobSearchKey::where('id',$value['pid'])->setField('emergency', 0);
                    \app\common\model\JobSearchRtime::where('id',$value['pid'])->setField('emergency', 0);
                }
            }
        }
        $model->where($where)->delete();
    }

    /**
     * 处理错误数据 当service_queue表中没有数据
     * 但是职位或简历中还有置顶相关的数据未清理的情况
     * @access private
     * @author chenyang
     * @param  object $model
     * @return bool
     * Date Time：2022年4月8日12:07:22
     */
    private function _handleErrorData($model){
        // 当服务队列表中未查询到数据时，去掉查询条件查询是否表内有数据
        $typeWhere = [
            'type' => ['in', ['stick','tag','jobstick','emergency']]
        ];
        $info = $model->field('id')->where($typeWhere)->find();
        if (!empty($info)) {
            return true;
        }
        // 查询职位中是否有 紧急 或 置顶 的简历
        $jobModel = new \app\common\model\Job();
        $jobIdArr = $jobModel->where(['emergency' => 1])->whereOr(['stick' => 1])->column('id');
        if (!empty($jobIdArr)) {
            $updateWhere = [
                'id' => ['in', $jobIdArr]
            ];
            $updateData = [
                'emergency' => 0,
                'stick'     => 0,
            ];
            $jobModel->where($updateWhere)->update($updateData);
            \app\common\model\JobSearchKey::where($updateWhere)->update($updateData);
            \app\common\model\JobSearchRtime::where($updateWhere)->update($updateData);
        }

        // 查询简历中是否有 置顶 或 醒目标签 的简历
        $resumeModel = new \app\common\model\Resume();
        $resumeIdArr = $resumeModel->where(['stick' => 1])->whereOr(['service_tag' => ['neq', '']])->column('id');
        if (!empty($resumeIdArr)) {
            $updateWhere = [
                'id' => ['in', $resumeIdArr]
            ];
            $updateData = [
                'stick'       => 0,
                'service_tag' => '',
            ];
            $resumeModel->where($updateWhere)->update($updateData);
            unset($updateData['service_tag']);
            \app\common\model\ResumeSearchKey::where($updateWhere)->update($updateData);
            \app\common\model\ResumeSearchRtime::where($updateWhere)->update($updateData);
        }
        return true;
    }

}
