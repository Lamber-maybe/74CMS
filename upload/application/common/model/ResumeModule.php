<?php

namespace app\common\model;

class ResumeModule extends BaseModel
{
    /**
     * 是否显示
     * @var string[]
     */
    public $map_is_display = [
        0 => '不显示',
        1 => '显示'
    ];

    protected $readonly = [
        'id',
        'module_name',
        'module_cn',
        'enable_close'
    ];

    protected $type = [
        'id' => 'integer',
        'score' => 'integer',
        'is_display' => 'integer',
        'enable_close' => 'integer'
    ];

    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_resume_module', null);
        });
        self::event('after_delete', function () {
            cache('cache_resume_module', null);
        });
    }

    public function getCache()
    {
        if (false === ($data = cache('cache_resume_module'))) {
            $data = $this->column(
                'module_name,module_cn,score,is_display,enable_close'
            );
            cache('cache_resume_module', $data);
        }
        return $data;
    }
}
