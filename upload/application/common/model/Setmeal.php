<?php

namespace app\common\model;

class Setmeal extends BaseModel
{
    /**
     * 开启限时特惠
     * @var string[] 开启限时特惠 0:关闭|1:开启
     */
    public $map_preferential_open = [
        0 => '关闭',
        1 => '开启'
    ];

    /**
     * 使用视频面试
     * @var string[] 使用视频面试 0:不允许|1:允许
     */
    public $map_enable_video_interview = [
        0 => '不允许',
        1 => '允许'
    ];

    /**
     * 收到简历免费查看
     * @var string[] 收到简历免费查看 0:不允许|1:允许
     */
    public $map_show_apply_contact = [
        0 => '不允许',
        1 => '允许'
    ];

    /**
     * 是否推荐
     * @var string[] 推荐状态 0:不推荐|1:推荐
     */
    public $map_recommend = [
        0 => '不推荐',
        1 => '推荐'
    ];

    /**
     * 允许申请
     * @var string[] 允许申请 0:不允许|1:允许
     */
    public $map_is_apply = [
        0 => '不允许',
        1 => '允许'
    ];

    /**
     * 是否显示
     * @var string[] 是否显示 0:隐藏|1:显示
     */
    public $map_is_display = [
        0 => '隐藏',
        1 => '显示'
    ];

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
                SYS_UPLOAD_DIR_NAME . '/resource/setmeal' .
                $setmeal_id .
                '.png';
        } else {
            return '';
        }
    }
}
