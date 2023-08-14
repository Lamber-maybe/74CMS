<?php
namespace app\common\model;

class MemberSetmeal extends \app\common\model\BaseModel
{
    public function syncSet($setMealId, $admin){
        $setmeal = Setmeal::get($setMealId);

        $where = [
            'setmeal_id' => $setMealId,
            'deadline'   => ['gt', time()]
        ];
        // 判断如果当前要同步的套餐为无限期的话，将修改条件中的过期时间改为0 chenyang 2022年3月18日15:10:30
        if ($setmeal['days'] <= 0) {
            $where['deadline'] = 0;
        }
        $n = $this->where($where)->update([
            'jobs_meanwhile'=> $setmeal['jobs_meanwhile'],
            'refresh_jobs_free_perday' => $setmeal['refresh_jobs_free_perday'],
            'download_resume_max_perday' => $setmeal['download_resume_max_perday'],
            'service_added_discount' => $setmeal['service_added_discount'],
            'enable_poster' => $setmeal['enable_poster'],
            'show_apply_contact' => $setmeal['show_apply_contact'],
            'im_max_perday' => $setmeal['im_max_perday'],
        ]);
        model('AdminLog')->record(
            '同步企业套餐。套餐名称【' . $setmeal['name'] . '】',
            $admin
        );
        return $n;
    }
}
