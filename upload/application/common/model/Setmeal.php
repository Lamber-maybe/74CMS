<?php
namespace app\common\model;

class Setmeal extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'is_free'];
    protected $type = [
        'id' => 'integer',
        'is_display' => 'integer',
        'is_apply' => 'integer',
        'days' => 'integer',
        'expense' => 'double',
        'preferential_expense' => 'double',
        'jobs_meanwhile' => 'integer',
        'refresh_jobs_free_perday' => 'integer',
        'download_resume_point' => 'integer',
        'download_resume_max_perday' => 'integer',
        'gift_point' => 'integer',
        'show_apply_contact' => 'integer',
        'is_free' => 'integer',
        'sort_id' => 'integer'
    ];
    protected $insert = ['is_free' => 0];
    public function getSysIcon($setmeal_id)
    {
        $file_path = SYS_UPLOAD_PATH . 'resource/setmeal' . $setmeal_id . '.png';
        if (file_exists($file_path)) {
            return config('global_config.sitedomain') .
                config('global_config.sitedir') .
                SYS_UPLOAD_DIR_NAME.'/resource/setmeal' .
                $setmeal_id .
                '.png';
        } else {
            return '';
        }
    }
}
