<?php
namespace app\common\validate;

use \app\common\validate\BaseValidate;

class Setmeal extends BaseValidate
{
    protected $rule =   [
        'is_display'=>'require|in:0,1',
        'is_apply'=>'require|in:0,1',
        'show_apply_contact'=>'require|in:0,1',
        'name'   => 'require|max:30',
        'days'=>'require|number',
        'expense'=>'require|number',
        'jobs_meanwhile'=>'require|number',
        'refresh_jobs_free_perday'=>'require|number',
        'download_resume_point'=>'require|number',
        'download_resume_max_perday'=>'require|number',
        'note'=>'max:255',
        'sort_id'=>'require|number',
        'gift_point'=>'require|number',
    ];
}