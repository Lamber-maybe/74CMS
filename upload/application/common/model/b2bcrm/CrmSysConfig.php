<?php

namespace app\common\model\b2bcrm;

use app\common\model\BaseModel;
use think\Cache;

class CrmSysConfig extends BaseModel
{
    // 主键
    protected $pk = 'id';

    // 只读字段
    protected $readonly = [
        'id'
    ];

    // 数据类型
    protected $type = [
        'id' => 'integer',
        'is_open' => 'integer',
        'is_system' => 'integer',
        'update_time' => 'integer'
    ];

    // 设置返回数据集的对象名
    protected $resultSetType = 'collection';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $updateTime = 'update_time';

    // 缓存键
    protected $cacheKey = 'crm_sys_config';

    /**
     * 自定义模型初始化
     * @return void
     */
    protected static function init()
    {
        self::event('after_update', function () {
            // 清空缓存（更新缓存）
            Cache::rm((new self())->cacheKey);
        });
    }

    /**
     * 获取所有CRM配置项
     * @return CrmSysConfig[]|array|bool|mixed|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllConfig()
    {
        $cache = cache($this->cacheKey);
        if (!isset($cache) || empty($cache)) {
            $cache = $this->select();
            if ($cache->isEmpty()) {
                return false;
            } else {
                cache($this->cacheKey, $cache->toArray());
            }
        }
        return $cache;
    }


    /**
     * 获取指定分类CRM配置项
     * @param $category
     * @return CrmSysConfig[]|array|bool|mixed|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDateByCategory($category = '')
    {
        $allCache = $this->getAllConfig();
        if (false === $allCache) {
            return false;
        }

        if ($category) {
            $arr = [];
            foreach ($allCache as $config) {
                if ($config['category'] === $category) {
                    $arr[] = $config;
                }
            }
            return $arr;
        } else {
            return $allCache;
        }
    }


    /**
     * 修改CRM系统配置项
     * @param $id
     * @param $value
     * @return bool
     */
    public function toConfigure($id, $value)
    {
        $result = $this->allowField(true)
            ->isUpdate(true)
            ->save(
                [
                    'value' => $value
                ],
                [
                    'id' => $id
                ]
            );
        if ($result) {
            return true;
        } else {
            return false;
        }
    }


    public function getConfigByKey($key){
        $value = $this->where('key',$key)
            ->value('value',null);

        if (null === $value){
            return false;
        }else{
            return $value;
        }
    }
}