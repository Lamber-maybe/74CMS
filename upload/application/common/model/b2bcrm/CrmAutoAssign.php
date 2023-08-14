<?php

namespace app\common\model\b2bcrm;

use app\common\model\BaseModel;
use think\Cache;

class CrmAutoAssign extends BaseModel
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
        'type' => 'integer',
        'admin_id' => 'integer',
        'assign_num' => 'integer',
        'update_time' => 'integer'
    ];

    // 设置返回数据集的对象名
    protected $resultSetType = 'collection';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'create_time';

    protected $cacheKey = 'crm_auto_assign';

    /**
     * 自定义模型初始化
     * @return void
     */
    protected static function init()
    {
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
     * 获取自动分配管理员ID
     * @param $type
     * @return false|float|mixed|string
     */
    public function getAutoAssignAdminId($type = 2)
    {
        $adminId = $this->alias('caa')
            ->join('admin a', 'a.id = caa.admin_id', 'LEFT')
            ->where('caa.type', $type)
            ->where('a.customer_exceed', 0)
            ->where('a.status', 1)
            ->order('caa.assign_num', 'ASC')
            ->order('caa.id', 'ASC')
            ->limit(1)
            ->value('caa.admin_id');
        if ($adminId) {
            return $adminId;
        } else {
            return false;
        }
    }


    public function writeAutoAssignAdminIds($adminIds, $type = 1)
    {
        if (!is_array($adminIds) || empty($adminIds)) {
            return false;
        }

        switch ($type) {
            case 1:
            case 2:
                break;

            default:
                return false;
        }

        $del_result = $this->destroy(['type' => $type]);
        if (false === $del_result) {
            return false;
        }

        $insert_data = [];
        foreach ($adminIds as $aId) {
            $insert_data[] = [
                'type' => $type,
                'admin_id' => $aId,
                'assign_num' => 0
            ];
        }
        $insert_result = $this->allowField(true)
            ->saveAll($insert_data, false);
        if (false === $insert_result) {
            return false;
        } else {
            return true;
        }
    }
}