<?php

namespace app\common\model;

class CompanyServiceStick extends BaseModel
{
    /**
     * 积分抵扣规则
     * 0:不允许|1:全额兑换|2:部分抵扣欧
     * @var string[]
     */
    public $map_enable_points_deduct = [
        0 => '不允许',
        1 => '全额兑换',
        2 => '部分抵扣'
    ];

    /**
     * 显示
     * 0:显示中|1:不显示
     * @var string[]
     */
    public $map_is_display = [
        0 => '不显示',
        1 => '显示中'
    ];

    /**
     * 推荐
     * 0:不推荐|1:推荐
     * @var string[]
     */
    public $map_recommend = [
        0 => '不推荐',
        1 => '推荐'
    ];

    public function getList($limit = 0, $uid = 0)
    {
        $list = $this->field('is_display,sort_id', true)
            ->where('is_display', 1)
            ->order('sort_id desc');
        if ($limit > 0) {
            $list = $list->limit($limit);
        }
        $list = $list->select();
        if (!empty($list)) {
            if ($uid > 0) {
                $setmeal = model('Member')->getMemberSetmeal($uid);
            } else {
                $setmeal = [];
            }
            if (!empty($setmeal) && $setmeal['service_added_discount'] > 0) {
                foreach ($list as $key => $value) {
                    $list[$key]['expense'] =
                        ($list[$key]['expense'] / 10) *
                        $setmeal['service_added_discount'];
                }
            }
        }
        return $list;
    }
}
