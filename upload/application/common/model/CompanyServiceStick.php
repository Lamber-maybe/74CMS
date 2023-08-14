<?php
namespace app\common\model;

class CompanyServiceStick extends \app\common\model\BaseModel
{
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
