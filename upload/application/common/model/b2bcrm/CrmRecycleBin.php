<?php
/**
 * CRM回收站表
 */

namespace app\common\model\b2bcrm;

use app\common\model\BaseModel;

class CrmRecycleBin extends BaseModel
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


    /**
     * @Purpose:
     * 获取数据总条数
     * @Method getDataNum()
     *
     * @param array $map 查询条件
     *
     * @return false|int|string
     *
     * @throws \think\Exception
     *
     * @link XXXXXXXXXX
     *
     * @author  Administrator
     * @version 1.1
     * @since   2022/3/3
     */
    public function getDataNum($map)
    {
        if (!is_array($map)) {
            return false;
        }
        $total = $this
            ->where($map)
            ->count($this->pk);
        return $total;
    }


    /**
     * 获取用户列表数据
     * @param $map
     * @param $order
     * @param $page_num
     * @param $page_size
     * @param string $field
     * @return array|false
     */
    public function getList($map, $order, $page_num, $page_size, $field = '*')
    {
        if (!is_array($map)) {
            return false;
        }
        $total = $this->getDataNum($map);
        if (empty($total)) {
            return array();
        }
        if (empty($page_size) || $page_size > 100 || $page_size < 1) {
            $page_size = 10;
            $limit_size = 10;
        } else {
            $limit_size = (int)$page_size;
        }
        $total_page = ceil($total / $page_size);
        if ($page_num > $total_page) {
            return array();
        }
        if (empty($page_num) || $page_num < 1) {
            $page_num = 1;
            $start = 0;
        } else {
            $start = (int)$page_num - 1;
        }
        $limit_start = $start * $limit_size;

        $data = $this
            ->field($field)
            ->where($map)
            ->order($order)
            ->limit($limit_start, $limit_size)
            ->select();

        if ($data->isEmpty()) {
            return array();
        }

        return [
            'rows' => $data->toArray(),
            'pages' => [
                'now_page' => $page_num,
                'total_page' => $total_page,
                'record_num' => $total
            ]
        ];
    }


    /**
     * 移动到回收站
     * @param $data
     * @return bool
     */
    public function put($data = [])
    {
        if (!is_array($data)) {
            throw new \Exception('回收数据错误');
        }
        $result = $this->allowField(true)
            ->isUpdate(false)
            ->save($data);
        if (false === $result) {
            throw new \Exception('移动到回收站错误');
        } else {
            return true;
        }
    }


    /**
     * 通过回收站ID删除回收站
     * @param $id
     * @return bool
     */
    public function del($id)
    {
        $result = $this->allowField(true)
            ->isUpdate(true)
            ->save(
                [
                    'status' => 3
                ],
                [
                    'id' => $id
                ]
            );
        if ($result) {
            return true;
        } else {
            throw new \Exception('删除回收站错误');
        }
    }


    /**
     * 通过回收站ID恢复回收站
     * @param $id
     * @return bool
     */
    public function recover($id)
    {
        $result = $this->allowField(true)
            ->isUpdate(true)
            ->save(
                [
                    'status' => 2
                ],
                [
                    'id' => $id
                ]
            );
        if ($result) {
            return true;
        } else {
            throw new \Exception('恢复回收站错误');
        }
    }
}