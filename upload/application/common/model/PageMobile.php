<?php
namespace app\common\model;

class PageMobile extends \app\common\model\BaseModel
{
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_page_mobile', null);
        });
        self::event('after_delete', function () {
            cache('cache_page_mobile', null);
        });
    }
    public function getCache($alias = '')
    {
        if (false === ($data = cache('cache_page_mobile'))) {
            $data = $this->column('alias,id,expire,seo_title,seo_keywords,seo_description','alias');
            cache('cache_page_mobile', $data);
        }
        if ($alias != '') {
            $data = isset($data[$alias])?$data[$alias]:null;
        }
        return $data;
    }
    public function writeCacheByAlias($alias,$content,$expire=600,$id=0){
        if($alias){
            $cache_name = 'mobile_cache_'.$alias.'_'.$id;
            cache($cache_name,$content,$expire);
        }
    }
    public function getCacheByAlias($alias,$id=0){
        if($alias){
            $cache_name = 'mobile_cache_'.$alias.'_'.$id;
            return cache($cache_name);
        }
        return false;
    }
}
