<?php

namespace app\common\model\b2bcrm;

use app\common\model\BaseModel;

class CrmFollowUp extends BaseModel
{
    public $method = [
        0 => '其他拜访',
        1 => '当面拜访',
        2 => '电话拜访',
        3 => '网络拜访'
    ];

    protected $pk = 'id';
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
    public function getList($map = [], $order = ['id DESC'], $field = '*', $page_num = 1, $page_size = 10)
    {
        if (!is_array($map)) {
            return false;
        }
        $total = $this->getDataNum($map);
        if (empty($total)) {
            return array();
        }
        $data = $this->field($field)
            ->alias('a')
            ->join('Admin b','b.id=a.admin_id','left')
            ->join('CrmClue c','c.id=a.clue_id','left')
            ->join('company d','d.uid=a.uid','left')
            ->where($map)
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
            $data = $data->group('a.id')->limit($limit_start, $limit_size)->select();
            $return_data['pages'] = array(
                'now_page' => $page_num,
                'total_page' => $total_page,
                'record_num' => $total,
                'count' => $this->getDataNums($map)
            );
        }
        else
        {
            $data = $data->select();
        }
        if ($data->isEmpty()) {
            return array();
        }
        $return_data['rows'] = collection($data)->toArray();
        return $return_data;
    }

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
     * @author  Administrator
     * @version 1.1
     * @since   2022/8/22
     */
    public function getDataNum($map)
    {
        if (!is_array($map)) {
            return false;
        }
        $total = $this->field($this->pk)
            ->alias('a')
            ->where($map)
            ->count($this->pk);
        return $total;
    }

    public function getDataNums($map)
    {
        if (!is_array($map) || !isset($map['next_time'])) {
            return false;
        }
        $total = $this->field($this->pk)
            ->alias('a')
            ->where($map)
            ->count($this->pk);
        return $total;
    }

    public function saveData($data){
        $add_visit = $this->allowField(true)
            ->isUpdate(false)
            ->save($data);
        if ($add_visit === false)
        {
            throw new \think\Exception('跟进记录添加失败');
        }
        model('b2bcrm.CrmClue')->where(['id'=>$data['clue_id']])->update(['last_visit_time'=>time()]);
        return true;
    }
}