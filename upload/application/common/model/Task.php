<?php
namespace app\common\model;

class Task extends \app\common\model\BaseModel
{
    /**
     * 完成任务
     */
    public function doTask($uid, $utype, $alias)
    {
        $alias_arr = is_array($alias) ? $alias : [$alias];
        $timestamp_today = strtotime('today');
        $task_data = $this->field(true)
            ->where(['utype' => $utype, 'alias' => ['in', $alias_arr]])
            ->select();
        $sqldata_arr = [];
        foreach ($task_data as $task_info) {
            $map = [];
            //如果是日常任务，需要检测是否今天已完成最大量
            if ($task_info['daily'] == 1) {
                $map['uid'] = $uid;
                $map['alias'] = $task_info['alias'];
                $map['addtime'] = $timestamp_today;
                $already_done_total = model('TaskRecord')
                    ->where($map)
                    ->count();
                if ($already_done_total >= $task_info['max_perday']) {
                    continue;
                }
            } elseif ($task_info['daily'] == 0) {
                //单次任务
                $map['uid'] = $uid;
                $map['alias'] = $task_info['alias'];
                $already_done_total = model('TaskRecord')
                    ->where($map)
                    ->count();
                if ($already_done_total > 0) {
                    continue;
                }
            }
            $data = [];
            $data['uid'] = $uid;
            $data['alias'] = $task_info['alias'];
            $data['addtime'] = $timestamp_today;
            $data['points'] = $task_info['points'];
            $data['note'] = $task_info['name'];
            $sqldata_arr[] = $data;
        }
        if (!empty($sqldata_arr)) {
            foreach ($sqldata_arr as $key => $value) {
                if ($value['points'] > 0) {
                    model('Member')->setMemberPoints([
                        'uid' => $uid,
                        'points' => $value['points'],
                        'note' => $value['note']
                    ]);
                }
            }
            model('TaskRecord')
                ->allowField(true)
                ->saveAll($sqldata_arr);
        }
    }
    /**
     * 获取任务完成情况
     */
    public function taskSituation($uid, $utype)
    {
        $timestamp_today = strtotime('today');
        $task_list = $this->field('id,utype', true)
            ->where(['utype' => $utype])
            ->select();
        foreach ($task_list as $key => $value) {
            if ($value['daily'] == -1) {
                $task_list[$key]['done_total'] = 0;
                $task_list[$key]['is_done'] = 0;
                continue;
            }
            $map = [
                'uid' => $uid,
                'alias' => $value['alias']
            ];
            if ($value['daily'] == 1) {
                $map['addtime'] = $timestamp_today;
            }
            $task_list[$key]['done_total'] = model('TaskRecord')
                ->where($map)
                ->count();
            if ($value['daily'] == 1) {
                $task_list[$key]['is_done'] =
                    $task_list[$key]['done_total'] >= $value['max_perday']
                        ? 1
                        : 0;
            } else {
                $task_list[$key]['is_done'] =
                    $task_list[$key]['done_total'] > 0 ? 1 : 0;
            }
        }
        return $task_list;
    }
    /**
     * 统计任务 - 今天获得的积分和剩余可获得积分
     */
    public function countTaskPoints($uid, $utype) {
        $count = [];
        $map['uid'] = ['eq', $uid];
        $map['addtime'] = ['eq', strtotime('today')];
        //今天已获得的积分
        $count[0] = model('TaskRecord')->where($map)->sum('points');
        //全部任务
        $all_task = $this->where('utype',$utype)->column('alias,points,daily,max_perday','alias');
        //找出所有的单次任务
        $single_task_id = $loop_task_id = [];
        //找出所有的日常任务
        $loop_task_id = [];
        foreach ($all_task as $key => $value) {
            if ($value['daily'] == 0) {
                $single_task_id[] = $key;
            } else {
                $loop_task_id[] = $key;
            }
        }
        //已完成的单次任务
        if (!empty($single_task_id)) {
            $once = model('TaskRecord')->where('uid',$uid)->whereIn('alias',$single_task_id)->column('alias');
        } else {
            $once = false;
        }

        //未完成的单次任务
        if ($once) {
            $result = array_diff($single_task_id, $once);
        } else {
            $result = $single_task_id;
        }

        $count[1] = 0;
        foreach ($result as $key => $value) {
            $count[1] += $all_task[$value]['points'];
        }

        //已完成的日常任务
        if (!empty($loop_task_id)) {
            $loop = model('TaskRecord')->where('uid',$uid)->whereIn('alias',$loop_task_id)->where('addtime',strtotime('today'))->column('alias');
        } else {
            $loop = [];
        }
        //计算已完成的日常任务分别已完成多少次
        $loop_count = [];
        foreach ($loop as $key => $value) {
            if (isset($loop_count[$value])) {
                $loop_count[$value]++;
            } else {
                $loop_count[$value] = 1;
            }
        }
        $count[2] = 0;
        foreach ($loop_task_id as $key => $value) {
            if (isset($loop_count[$value])) {
                $count[2] += ($all_task[$value]['max_perday'] - $loop_count[$value]) * $all_task[$value]['points'];
            } else {
                $count[2] += $all_task[$value]['points'] * $all_task[$value]['max_perday'];
            }
        }
        return ['obtain' => intval($count[0]), 'able' => intval($count[1] + $count[2])];
    }
}
