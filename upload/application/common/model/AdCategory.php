<?php
namespace app\common\model;

class AdCategory extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'is_sys'];
    protected $type = [
        'id' => 'integer',
        'is_sys' => 'integer',
        'ad_num' => 'integer',
    ];
    protected $insert = ['is_sys' => 0];
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_ad_category', null);
            cache('cache_ad_category_tree', null);
        });
        self::event('after_delete', function () {
            cache('cache_ad_category', null);
            cache('cache_ad_category_tree', null);
        });
    }
    public function getCache($id = 0)
    {
        if (false === ($data = cache('cache_ad_category'))) {
            $data = $this->order('id asc')->column('id,name,ad_num,platform');
            cache('cache_ad_category', $data);
        }
        if ($id != 0) {
            return $data[$id];
        }
        return $data;
    }
    public function getTreeCache()
    {
        if (false === ($data = cache('cache_ad_category_tree'))) {
            $list = $this->order('id asc')->column(
                'id,alias,platform,name,width,height',
                'id'
            );
            $data = [];
            $datalist = [];
            foreach (model('BaseModel')->map_ad_platform as $key => $value) {
                $datalist[$key] = [
                    'id' => $key,
                    'label' => $value,
                    'w' => 0,
                    'h' => 0,
                    'children' => [],
                ];
            }
            foreach ($list as $key => $value) {
                $datalist[$value['platform']]['children'][] = [
                    'id' => $value['id'],
                    'label' => $value['name'],
                    'w' => $value['width'],
                    'h' => $value['height'],
                ];
            }
            foreach ($datalist as $key => $value) {
                $data[] = $value;
            }
            cache('cache_ad_category_tree', $data);
        }
        return $data;
    }
}
