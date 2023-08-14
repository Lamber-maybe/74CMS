<?php
namespace app\common\model;

class CategoryDistrict extends \app\common\model\BaseModel
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
            cache('cache_category_district', null);
        });
        self::event('after_delete', function () {
            cache('cache_category_district', null);
        });
    }
    public function getCache($pid = 'all')
    {
        if (false === ($data = cache('cache_category_district'))) {
            $list = $this->order('sort_id desc,id asc')->column(
                'id,pid,name',
                'id'
            );
            $data = [];
            foreach ($list as $key => $value) {
                $data[$value['pid']][$value['id']] = $value['name'];
                $data['all'][$value['id']] = $value['name'];
            }
            cache('cache_category_district', $data);
        }
        if ($pid !== '') {
            $data = isset($data[$pid]) ? $data[$pid] : [];
        }
        return $data;
    }
    public function getTreeCache()
    {
        if (false === ($list = cache('cache_category_district_tree'))) {
            $list = [];
            $top = $this->getCache('0');
            foreach ($top as $key => $value) {
                $first = [];
                $first['id'] = $key;
                $first['label'] = $value;
                $first_children = $this->getCache($key);
                if ($first_children) {
                    $i = 0;
                    foreach ($first_children as $k => $v) {
                        $second['id'] = $k;
                        $second['label'] = $v;
                        $second_children = $this->getCache($k);
                        if ($second_children) {
                            $j = 0;
                            foreach ($second_children as $k1 => $v1) {
                                $third['id'] = $k1;
                                $third['label'] = $v1;
                                $second['children'][$j] = $third;
                                $third = [];
                                $j++;
                            }
                        } else {
                            $second['children'] = [];
                        }
                        $first['children'][$i] = $second;
                        $second = [];
                        $i++;
                    }
                } else {
                    $first['children'] = [];
                }
                $list[] = $first;
            }
            cache('cache_category_district_tree', $list);
        }
        return $list;
    }
    protected $auto = ['alias', 'spell'];
    protected function setAliasAttr()
    {
        if (isset($this->data['name'])) {
            return strtolower(getfirstchar($this->data['name']));
        }
    }
    protected function setSpellAttr()
    {
        $id = isset($this->data['id']) ? intval($this->data['id']) : 0;
        if (isset($this->data['name'])) {
            $py = new \app\common\lib\Pinyin();
            $spell = $py->getAllPY($this->data['name']);
            return $this->check_spell_repeat($spell, 0, $id);
        }
    }
}
