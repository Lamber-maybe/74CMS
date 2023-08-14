<?php

namespace app\common\model\b2bcrm;

use app\common\model\BaseModel;
use think\Cache;

class CrmLifeCycle extends BaseModel
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
    protected $cacheKey = 'crm_life_cycle';

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
     * 获取所有生命周期
     * @return CrmLifeCycle[]|array|bool|mixed|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    protected function getAll()
    {
        $cache = cache($this->cacheKey);
        if (!isset($cache) || empty($cache)) {
            $cache = $this->select();
            if ($cache->isEmpty()) {
                return false;
            } else {
	    	$cache = $cache->toArray();
                cache($this->cacheKey, $cache);
            }
        }
        return $cache;
    }

    public function getDate($id = 0)
    {
        $allCache = $this->getAll();
        if (false === $allCache) {
            return false;
        }
        if ($id) {
            $arr = [];
            foreach ($allCache as $v) {
                if ($v['id'] == $id) {
                    $arr[] = $v;
                }
            }
            return $arr;
        }
        return $allCache;
    }

    /**
     * 开启/关闭指定客户等级（生命周期）阶段
     * @param $id
     * @param $is_close
     * @return bool
     */
    public function switch($id, $is_open)
    {
        $result = $this->allowField(true)
            ->isUpdate(true)
            ->save(
                [
                    'is_open' => $is_open
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

    /**
     * 编辑客户等级（生命周期）阶段名
     * @param $id
     * @param $name
     * @return bool
     */
    public function rename($id, $name)
    {
        $result = $this->allowField(true)
            ->isUpdate(true)
            ->save(
                [
                    'name' => $name
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


    /**
     * 通过ID获取客户等级（生命周期）阶段名
     * @param $id
     * @return void
     */
    public function getNameById($id)
    {
        $name = $this->where('id', $id)
            ->value('name');
        if (isset($name) && !empty($name)) {
            return $name;
        } else {
            return '不详';
        }

    }


}