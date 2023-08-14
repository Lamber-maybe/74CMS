<?php

namespace app\common\model;

class MemberSetmeal extends BaseModel
{
    public function syncSet($setMealId, $admin, $syncItem)
    {
        $setmeal = Setmeal::get($setMealId);

        $where = [
            'setmeal_id' => $setMealId,
            'deadline' => ['gt', time()]
        ];
        // 判断如果当前要同步的套餐为无限期的话，将修改条件中的过期时间改为0 chenyang 2022年3月18日15:10:30
        if ($setmeal['days'] <= 0) {
            $where['deadline'] = 0;
        }

        $updateData = $uidArr = [];
        $points = 0;

        $log_field = '套餐权限同步，所选套餐:月度会员；同步的项目-';

        // 根据选择项进行同步 chenyang 2022年4月6日15:26:24
        foreach ($syncItem as $item) {
            if (isset($setmeal[$item])) {
                $updateData[$item] = $setmeal[$item];

                switch ($item) {
                    case 'download_resume_point':
                        $log_field .= '简历下载点数:' . $setmeal[$item] . '；';
                        break;
                    case 'im_total':
                        $log_field .= '职聊次数:' . $setmeal[$item] . '；';
                        break;
                    case 'gift_point':
                        $log_field .= '积分点数:' . $setmeal[$item] . '；';
                        // 当勾选积分点数时，更改当前用户的积分数
                        $points = $setmeal[$item];
                        $uidArr = $this->where($where)->column('uid');
                        break;
                    case 'refresh_jobs_free_perday':
                        $log_field .= '每日免费刷新职位数:' . $setmeal[$item] . '次/天；';
                        break;
                    case 'jobs_meanwhile':
                        $log_field .= '同时在招职位数:' . $setmeal[$item] . '；';
                        break;
                    case 'im_max_perday':
                        $log_field .= '允许发起聊天数:' . $setmeal[$item] . '次/天；';
                        break;
                    case 'enable_video_interview':
                        $log_field .= '使用视频面试:' . ($setmeal[$item] === 1 ? '允许' : '不允许') . '；';
                        break;
                    case 'show_apply_contact':
                        $log_field .= '收到简历免费查看:' . ($setmeal[$item] === 1 ? '允许' : '不允许') . '；';
                        break;
                    case 'service_added_discount':
                        $log_field .= '套餐增值包折扣:' . $setmeal[$item] . '折；';
                        break;
                    case 'download_resume_max_perday':
                        $log_field .= '下载简历数上限:' . $setmeal[$item] . '；';
                        break;
                    default:
                        break;
                }
            }
        }

        if (empty($updateData)) {
            return callBack(false, '请选择要同步的项目');
        }

        // 重置会员积分
        if (!empty($uidArr)) {
            $memberModel = model('Member');
            $note = '同步套餐-积分重置【管理员：' . $admin->username . '】';
            foreach ($uidArr as $uid) {
                $pointsData = [
                    'uid' => $uid,
                    'points' => $points,
                    'note' => $note,
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
                rtrim($log_field, '；'),
                $admin,
                0,
                5
            );
        }
        return callBack(true, '同步成功', [$n]);
    }
}
