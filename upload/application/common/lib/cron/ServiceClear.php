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
        foreach ($list as $key => $value) {
            if ($value['utype'] == 2) {
                //取消简历置顶
                if ($value['type'] == 'stick') {
                    \app\common\model\Resume::where(
                        'id',
                        $value['pid']
                    )->setField('stick', 0);
                    \app\common\model\ResumeSearchKey::where(
                        'id',
                        $value['pid']
                    )->setField('stick', 0);
                    \app\common\model\ResumeSearchRtime::where(
                        'id',
                        $value['pid']
                    )->setField('stick', 0);
                }
                //取消简历醒目标签
                if ($value['type'] == 'tag') {
                    \app\common\model\Resume::where(
                        'id',
                        $value['pid']
                    )->setField('service_tag', '');
                }
            }
            if ($value['utype'] == 1) {
                //取消职位置顶
                if ($value['type'] == 'stick') {
                    \app\common\model\Job::where('id', $value['pid'])->setField(
                        'stick',
                        0
                    );
                    \app\common\model\JobSearchKey::where(
                        'id',
                        $value['pid']
                    )->setField('stick', 0);
                    \app\common\model\JobSearchRtime::where(
                        'id',
                        $value['pid']
                    )->setField('stick', 0);
                }
                //取消职位紧急
                if ($value['type'] == 'emergency') {
                    \app\common\model\Job::where('id', $value['pid'])->setField(
                        'emergency',
                        0
                    );
                    \app\common\model\JobSearchKey::where(
                        'id',
                        $value['pid']
                    )->setField('emergency', 0);
                    \app\common\model\JobSearchRtime::where(
                        'id',
                        $value['pid']
                    )->setField('emergency', 0);
                }
            }
        }
        $model->where($where)->delete();
    }
}
