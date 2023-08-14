<?php
namespace app\common\model;

class Badword extends \app\common\model\BaseModel
{
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_badword', null);
        });
        self::event('after_delete', function () {
            cache('cache_badword', null);
        });
    }
    public function getCache()
    {
        if (false === ($data = cache('cache_badword'))) {
            $data = $this->column('id,name,replace_text');
            cache('cache_badword', $data);
        }
        return $data;
    }
}
