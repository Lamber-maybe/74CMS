<?php

namespace app\common\model;

class ImRule extends BaseModel
{
    /**
     * 开关状态
     * @var string[] 性别 0:关闭|1:开启
     */
    public $map_is_open = [
        '0' => '关闭',
        '1' => '开启'
    ];

    /**
     * 审核状态要求
     * @var string[] 性别 0:不限|1:审核通过
     */
    public $map_audit_status = [
        '1' => '审核通过',
        '3' => '不限'
    ];

    /**
     * 企业显示要求
     * @var string[] 性别 0:不限|1:显示中
     */
    public $map_display_status = [
        '1' => '显示中',
        '3' => '不限'
    ];

    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_im_rule', null);
        });
        self::event('after_delete', function () {
            cache('cache_im_rule', null);
        });
    }

    public function getCache($utype = 1)
    {
        if (false === ($data = cache('cache_im_rule'))) {
            $data = [];
            $datalist = $this->field('name,utype,value')->select();
            foreach ($datalist as $key => $value) {
                $data[$value['utype']][$value['name']] = $value['value'];
            }
            cache('cache_im_rule', $data);
        }
        $data = $data[$utype];
        return $data;
    }
}
