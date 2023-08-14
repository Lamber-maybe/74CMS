<?php
namespace app\common\model;

class Config extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'name', 'note'];
    protected $type = [
        'id' => 'integer',
    ];
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_config_frontend', null);
            cache('cache_config', null);
            cache('cache_config_all', null);
        });
        self::event('after_delete', function () {
            cache('cache_config_frontend', null);
            cache('cache_config', null);
            cache('cache_config_all', null);
        });
    }
    public function getCache($name = '')
    {
        if (false === ($data = cache('cache_config'))) {
            $data = $this->where('is_secret', 0)->column('name,value');
            foreach ($data as $key => $value) {
                if (is_json($value)) {
                    $data[$key] = json_decode($value, true);
                }
            }
            cache('cache_config', $data);
        }
        if ($name != '') {
            $data = $data[$name];
        }
        return $data;
    }
    public function getFrontendCache($name = '')
    {
        if (false === ($data = cache('cache_config_frontend'))) {
            $data = $this->where('is_frontend', 1)->column('name,value');
            foreach ($data as $key => $value) {
                if (is_json($value)) {
                    $data[$key] = json_decode($value, true);
                }
            }
            cache('cache_config_frontend', $data);
        }
        if ($name != '') {
            $data = $data[$name];
        }
        return $data;
    }
    public function getCacheAll($name = '')
    {
        if (false === ($data = cache('cache_config_all'))) {
            $data = $this->column('name,value');
            foreach ($data as $key => $value) {
                if (is_json($value)) {
                    $data[$key] = json_decode($value, true);
                }
            }
            cache('cache_config_all', $data);
        }
        if ($name != '') {
            $data = $data[$name];
        }
        return $data;
    }
}
