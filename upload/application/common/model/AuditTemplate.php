<?php
/**
 * 审核模板 Model
 * @author edc
 * @date 2023-7-3
 */

namespace app\common\model;

class AuditTemplate extends BaseModel
{

    /**
     * 获取列表
     * @access public
     * @param array        $where [查询条件]
     * @param array|string $field [查询字段]
     * @param array|string $order [排序条件]
     * @return array
     * @author edc
     */
    public function getList($where = [], $field = '*', $order = [])
    {
        $list = $this->where($where)->field($field)->order($order)->select();
        if (is_null($list) || empty($list)) {
            return [];
        }
        return collection($list)->toArray();
    }

    /**
     * 获取详情
     * @access public
     * @param array        $where [查询条件]
     * @param array|string $field [查询字段]
     * @param array|string $order [排序条件]
     * @return array
     * @author edc
     */
    public function getInfo($where, $field = '*', $order = [])
    {
        $info = $this->where($where)->field($field)->order($order)->find();
        if (is_null($info) || empty($info)) {
            return [];
        }
        return $info->toArray();
    }

    /**
     * 获取某个字段的值
     * @access public
     * @param array        $where [查询条件]
     * @param string       $field [查询字段]
     * @param array|string $order [排序条件]
     * @return string
     * @author edc
     */
    public function getValue($where, $field, $order = [])
    {
        $value = $this->where($where)->order($order)->value($field);
        if (is_null($value) || empty($value)) {
            return '';
        }
        return $value;
    }

    /**
     * COUNT查询
     * @access public
     * @param array  $where [查询条件]
     * @param string $field [字段名]
     * @return integer
     * @author edc
     */
    public function getCount($where = [], $field = '*')
    {
        return $this->where($where)->count($field);
    }

    /**
     * 新增
     * @access public
     * @param array $data [新增数据]
     * @return integer
     * @author edc
     */
    public function addTemplate($data)
    {
        if (empty($data)) {
            return 0;
        }
        return $this->insertGetId($data);
    }

    /**
     * 编辑
     * @access public
     * @param array $where [删除条件]
     * @param array $update [修改数据]
     * @return bool|integer
     * @author edc
     */
    public function editTemplate($where, $update = [])
    {
        if (empty($where)) {
            return false;
        }
        return $this->where($where)->update($update);
    }

}
