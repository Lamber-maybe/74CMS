<?php
namespace app\common\model;

class HrtoolCategory extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'is_sys'];
    protected $type = [
        'id' => 'integer',
        'sort_id' => 'integer',
        'is_sys' => 'integer'
    ];
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_hrtool_category', null);
        });
        self::event('after_delete', function () {
            cache('cache_hrtool_category', null);
        });
    }
    public function getCache($id = 0)
    {
        if (false === ($data = cache('cache_hrtool_category'))) {
            $data = $this->order('sort_id desc,id asc')->column('id,name');
            cache('cache_hrtool_category', $data);
        }
        if ($id != 0) {
            return $data[$id];
        }
        return $data;
    }
}
