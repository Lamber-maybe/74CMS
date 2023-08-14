<?php
/**
 * 采集设置 Model
 * @author chenyang
 * Date Time：2022年4月11日13:52:37
 */
namespace app\common\model;

class CollectionSeting extends BaseModel
{
    protected $table = 'collection_seting';

    /**
     * 修改
     * @access public
     * @author chenyang
     * @param  array $update [修改数据]
     * @param  array $where  [修改条件]
     * @return bool|integer
     * Date Time：2022年4月11日13:54:12
     */
    public function edit($where = [], $update = []) {
        if (empty($where)) {
            return false;
        }
        return db($this->table)->where($where)->update($update);
    }

    /**
     * 详情
     * @access public
     * @author chenyang
     * @param  array        $where [查询条件]
     * @param  array|string $field [查询字段]
     * @return array
     * Date Time：2022年4月12日10:15:36
     */
    public function getInfo($where, $field = '*') {
        $info = db($this->table)->where($where)->field($field)->find();
        if (is_null($info) || empty($info)) {
            return [];
        }
        return $info;
    }

}
