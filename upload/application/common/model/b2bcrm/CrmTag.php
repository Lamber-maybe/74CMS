<?php

namespace app\common\model\b2bcrm;

use app\common\model\BaseModel;
use think\Cache;

class CrmTag extends BaseModel
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
        'order' => 'integer',
        'create_time' => 'integer',
        'update_time' => 'integer'
    ];

    // 设置返回数据集的对象名
    protected $resultSetType = 'collection';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 缓存键
    protected $cacheKey = 'crm_tags';

    /**
     * 自定义模型初始化
     * @return void
     */
    protected static function init()
    {
        self::event('after_update', function () {
            // 更新操作，清空缓存（更新缓存）
            Cache::rm((new self())->cacheKey);
        });
        self::event('after_insert', function () {
            // 写入操作，清空缓存（更新缓存）
            Cache::rm((new self())->cacheKey);
        });
        self::event('after_delete', function () {
            // 删除操作，清空缓存（更新缓存）
            Cache::rm((new self())->cacheKey);
        });
    }

    /**
     * 获取所有CRM客户标签
     * @return CrmSysConfig[]|array|bool|mixed|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllTags()
    {
        $cache = cache($this->cacheKey);
        if (!isset($cache) || empty($cache)) {
            $cache = $this->order('order DESC, id DESC')
                ->select();
            if ($cache->isEmpty()) {
                return [];
            } else {
                cache($this->cacheKey, $cache->toArray());
            }
        }
        return $cache;
    }

    /**
     * 新增CRM客户标签
     * @param $tagInfo
     * @return bool
     */
    public function addTag($tagInfo)
    {
        $result = $this->allowField(true)
            ->isUpdate(false)
            ->save($tagInfo);
        if (false === $result) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 删除CRM客户标签
     * @param $tagId
     * @return bool
     */
    public function delTag($tagId)
    {
        $result = $this->destroy($tagId);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 编辑CRM客户标签
     * @param $tagInfo
     * @return bool
     */
    public function editTag($tagInfo)
    {
        $result = $this->allowField(true)
            ->isUpdate(true)
            ->save($tagInfo);
        if (false === $result) {
            return false;
        } else {
            return true;
        }
    }
}