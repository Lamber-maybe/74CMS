<?php

namespace app\common\model\b2bcrm;

use app\common\model\BaseModel;

class CrmClueRelease extends BaseModel
{

    protected $pk = 'a.id';
    // 设置返回数据集的对象名
    protected $resultSetType = 'collection';
    /*
     * 添加领取释放记录
     * clue_id 线索id
     * admin_id 所属销售id
     * operation_type 操作类型 1-领取 2-释放
     * operator 操作人员  1-系统 2-销售
     * */
    public function releaseAdd($clue_id,$admin_id,$operation_type,$operator,$utype = 0)
    {
        $field = 'clue_id';
        if (intval($utype) > 0)
        {
            $field = 'uid';
        }
        if (is_array($clue_id))
        {
            $arr = [];
            foreach($clue_id as $k=>$v)
            {
                $arr[] = [
                    $field => $v,
                    'create_time' => time(),
                    'operation_type' => $operation_type,
                    'admin_id' => $admin_id,
                    'operator' => $operator,
                    'utype' => $utype
                ];
            }
            return $this->insertAll($arr);

        }
        else
        {
            $arr = [
                $field => $clue_id,
                'create_time' => time(),
                'operation_type' => $operation_type,
                'admin_id' => $admin_id,
                'operator' => $operator,
                'utype' => $utype
            ];
            return $this->insert($arr);
        }

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
     * @since   2022/3/3
     */
    public function getDataNum($map)
    {
        if (!is_array($map)) {
            return false;
        }
        $total = $this->field($this->pk)
            ->alias('a')
            ->join('company c','c.uid=a.uid','left')
            ->where($map)
            ->count($this->pk);

        return $total;
    }


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
     * @since   2022/3/14
     */
    public function getList($map = [], $order = ['id DESC'], $page_num = 1, $page_size = 10, $field = '*')
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
        $data = $this->field($field)
            ->alias('a')
            ->join('admin b','b.id=a.admin_id','left')
            ->where($map)
            ->order($order)
            ->limit($limit_start, $limit_size)
            ->select();
        if ($data->isEmpty()) {
            return array();
        }
        $return_data['rows'] = $data->toArray();
        $return_data['pages'] = array(
            'now_page' => $page_num,
            'total_page' => $total_page,
            'record_num' => $total);
        return $return_data;
    }


    /**
     * 写企业释放/领取记录
     * @param $ids array 企业ID
     * @param $operationType integer 操作类型 1-领取 2-释放
     * @param $operator integer 操作人员 1-系统 2-销售
     * @return bool|int|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function releaseCompany($ids = [], $operationType = 1, $operator = 1)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        $all_dates = [];
        foreach ($ids as $id) {
            $company_info = model('Company')
                ->field('id,uid,admin_id,clue_id')
                ->find($id);

            $all_dates[] = [
                'clue_id' => $company_info['clue_id'],
                'create_time' => time(),
                'operation_type' => $operationType,
                'admin_id' => $company_info['admin_id'],
                'operator' => $operator,
                'utype' => 1,
                'uid' => $company_info['uid']
            ];

        }

        return $this->insertAll($all_dates);
    }
}