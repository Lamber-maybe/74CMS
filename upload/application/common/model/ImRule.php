<?php
namespace app\common\model;

class ImRule extends \app\common\model\BaseModel
{
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
