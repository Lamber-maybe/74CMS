<?php
namespace app\common\model;

class MemberSetmeal extends \app\common\model\BaseModel
{
    public function syncSet($setMealId, $admin, $syncItem){
        $setmeal = Setmeal::get($setMealId);

        $where = [
            'setmeal_id' => $setMealId,
            'deadline'   => ['gt', time()]
        ];
        // 判断如果当前要同步的套餐为无限期的话，将修改条件中的过期时间改为0 chenyang 2022年3月18日15:10:30
        if ($setmeal['days'] <= 0) {
            $where['deadline'] = 0;
        }

        $updateData = $uidArr = [];
        $points = 0;
        // 根据选择项进行同步 chenyang 2022年4月6日15:26:24
        foreach ($syncItem as $item) {
            if (isset($setmeal[$item])) {
                $updateData[$item] = $setmeal[$item];
            }
            // 当勾选积分点数时，更改当前用户的积分数
            if (isset($setmeal[$item]) && $item == 'gift_point') {
                $points = $setmeal[$item];
                $uidArr = $this->where($where)->column('uid');
            }
        }
        if (empty($updateData)) {
            return callBack(false, '请选择要同步的项目');
        }

        // 重置会员积分
        if (!empty($uidArr)) {
            $memberModel = model('Member');
            $note = '同步套餐-积分重置【管理员：'.$admin->username.'】';
            foreach ($uidArr as $uid) {
                $pointsData = [
                    'uid'    => $uid,
                    'points' => $points,
                    'note'   => $note,
                ];
                $memberModel->setMemberPoints($pointsData, 3);
            }
        }

        // 删除积分点数
        unset($updateData['gift_point']);

        $n = 0;
        if (!empty($updateData)) {
            $n = $this->where($where)->update($updateData);
            if ($n === false) {
                return callBack(false, '同步失败');
            }
            model('AdminLog')->record(
                '同步企业套餐。套餐名称【' . $setmeal['name'] . '】',
                $admin
            );
        }
        return callBack(true, '同步成功', [$n]);
    }
}
