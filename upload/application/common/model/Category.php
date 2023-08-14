<?php
namespace app\common\model;

class Category extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'alias'];
    protected $type = [
        'id' => 'integer',
        'sort_id' => 'integer'
    ];
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_category', null);
        });
        self::event('after_delete', function () {
            cache('cache_category', null);
        });
    }
    public function getCache($alias = '')
    {
        if (false === ($data = cache('cache_category'))) {
            $list = $this->order('sort_id desc,id asc')->column(
                'alias,id,name',
                'id'
            );
            $data = [];
            foreach ($list as $key => $value) {
                $data[$value['alias']][$value['id']] = $value['name'];
            }
            cache('cache_category', $data);
        }
        if ($alias != '') {
            $data = $data[$alias];
        }
        return $data;
    }
}
