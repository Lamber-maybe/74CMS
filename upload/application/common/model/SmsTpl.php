<?php
namespace app\common\model;

class SmsTpl extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'code', 'title', 'params', 'content'];
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_article_category', null);
        });
        self::event('after_delete', function () {
            cache('cache_article_category', null);
        });
    }
    public function getCache($tpl_code = '')
    {
        if (false === ($data = cache('cache_sms_tpl'))) {
            $data = $this->column('code,alisms_tplcode,params,content');
            cache('cache_sms_tpl', $data);
        }
        if ($tpl_code != '') {
            return isset($data[$tpl_code]) ? $data[$tpl_code] : false;
        }
        return $data;
    }
}
