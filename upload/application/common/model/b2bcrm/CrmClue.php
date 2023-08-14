<?php

namespace app\common\model\b2bcrm;

use app\common\model\BaseModel;

class CrmClue extends BaseModel
{
    protected $pk = 'id';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 设置返回数据集的对象名
    protected $resultSetType = 'collection';

    /**
     * @Purpose:
     * 获取数据列表
     * @Method getList()
     *
     * @param array $map
     * @param string[] $order
     * @param int $page_num
     * @param int $page_size
     * @param string $field
     *
     * @return array|false
     *
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     *
     * @author  Administrator
     * @version 1.1
     * @since   2022/8/22
     */
    public function getList($map = [], $order = ['id DESC'], $page_num = 1, $page_size = 10, $field = '*')
    {
        if (!is_array($map)) {
            return false;
        }
        $total = $this->getDataNum($map);
        $data = $this->field($field)
            ->alias('a')
            ->join('CrmFollowUp b','a.id=b.clue_id and b.type=1','left')
            ->where($map)
            ->group('a.id')
            ->order($order);
        if (is_numeric($page_size))
        {
            if (empty($total)) {
                return array();
            }

            if (empty($page_size) || $page_size < 1) {
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
            $data = $data
                ->group('a.id')
                ->limit($limit_start, $limit_size)
                ->select();
            $return_data['pages'] = array(
                'now_page' => $page_num,
                'total_page' => $total_page,
                'record_num' => $total);
        }
        else
        {
            $data = $data->select();
        }

        if ($data->isEmpty()) {
            return array();
        }
        $return_data['rows'] = $data->toArray();

        return $return_data;
    }

    /**
     * @Purpose:
     * 获取数据总条数
     * @Method getDataNum()receive()
     * {
     *
     *
     * @param array $map 查询条件
     *
     * @return false|int|string
     *
     * @throws \think\Exception
     *
     * @author  Administrator
     * @version 1.1
     * @since   2022/8/22
     */
    public function getDataNum($map)
    {
        if (!is_array($map)) {
            return false;
        }
        $total = $this->alias('a')->field($this->pk)
            ->where($map)
            ->count($this->pk);
        return $total;
    }

    /*
     * 线索删除
     * */
    public function clueDel($clue_id)
    {
        if (!is_array($clue_id)) {
            return false;
        }
        $cluelist = $this->where(['id'=>['in',$clue_id]])->field('id,admin_id,name,is_customer')->select();
        if ($cluelist->isEmpty()){
            throw new \Exception('请选择线索');
        }
        $clue_name = [];
        foreach ($cluelist as $v)
        {
            if ($v['admin_id'] > 0)
            {
                throw new \Exception('已有所属销售线索不可删除，请选择无所属销售线索！');
            }
            if ($v['is_customer'] == 1)
            {
                throw new \Exception('已转为客户不可删除！');
            }
            $clue_name[] = $v['name'];
        }
        model('b2bcrm.CrmFollowUp')->where(['clue_id'=>['in',$clue_id]])->delete();
        model('b2bcrm.CrmClueRelease')->where(['clue_id'=>['in',$clue_id]])->delete();
        $this->where(['id'=>['in',$clue_id]])->delete();
        $clue_name = implode(',',$clue_name);
        return $clue_name;
    }
}