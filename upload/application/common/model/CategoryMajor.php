<?php

namespace app\common\model;

class CategoryMajor extends \app\common\model\BaseModel
{
    protected $readonly = ['id'];
    protected $type = [
        'id' => 'integer',
        'pid' => 'integer',
        'sort_id' => 'integer'
    ];
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_category_major', null);
        });
        self::event('after_delete', function () {
            cache('cache_category_major', null);
        });
    }
    public function getCache($pid = 'all')
    {
        if (false === ($data = cache('cache_category_major'))) {
            $list = $this->order('sort_id desc,id asc')->column(
                'id,pid,name',
                'id'
            );
            $data = [];
            foreach ($list as $key => $value) {
                $data[$value['pid']][$value['id']] = $value['name'];
                $data['all'][$value['id']] = $value['name'];
            }
            cache('cache_category_major', $data);
        }
        if ($pid != '') {
            $data = isset($data[$pid]) ? $data[$pid] : [];
        }
        return $data;
    }
}
