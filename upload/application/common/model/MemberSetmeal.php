<?php
namespace app\common\model;

class MemberSetmeal extends \app\common\model\BaseModel
{
    public function syncSet($setMealId, $admin){
        $setmeal = Setmeal::get($setMealId);
        $n = $this->where(['setmeal_id'=>$setMealId, 'deadline'=>['gt', time()]])->update([
            'jobs_meanwhile'=> $setmeal['jobs_meanwhile'],
            'refresh_jobs_free_perday' => $setmeal['refresh_jobs_free_perday'],
            'download_resume_max_perday' => $setmeal['download_resume_max_perday'],
            'service_added_discount' => $setmeal['service_added_discount'],
            'enable_poster' => $setmeal['enable_poster'],
            'show_apply_contact' => $setmeal['show_apply_contact']
        ]);
        model('AdminLog')->record(
            '同步企业套餐。套餐名称【' . $setmeal['name'] . '】',
            $admin
        );
        return $n;
    }
}
