<?php
namespace app\common\lib\cron;

class FewViewNotice
{
    public function execute()
    {
        $cuttime1 = strtotime(date('Y-m-d',strtotime('-4day')));//时间>4天前
        $cuttime2 = strtotime(date('Y-m-d',strtotime('-3day')));//时间>3天前
        $cuttime3 = strtotime(date('Y-m-d',strtotime('-2day')));//时间>2天前
        $cuttime4 = strtotime(date('Y-m-d',strtotime('-1day')));//时间>1天前
        $where['addtime'] = ['egt',$cuttime1];
        $list = model('Job')
            ->field('id,uid,addtime,click')
            ->where($where)
            ->select();
        $jobidarr = [];
        $uidarr = [];
        foreach ($list as $key => $value) {
            $jobidarr[] = $value['id'];
            if($value['addtime']>$cuttime4 && $value['click']<50){//发布时间>1天前并且点击量小于50
                $uidarr[] = $value['uid'];
                continue;
            }
            if($value['addtime']>$cuttime3 && $value['click']<100){//发布时间>2天前并且点击量小于100
                $uidarr[] = $value['uid'];
                continue;
            }
            if($value['addtime']>$cuttime2 && $value['click']<200){//发布时间>3天前并且点击量小于200
                $uidarr[] = $value['uid'];
                continue;
            }
            if($value['addtime']>$cuttime1 && $value['click']<300){//发布时间>4天前并且点击量小于300
                $uidarr[] = $value['uid'];
                continue;
            }
        }
        $where['addtime'] = ['lt',$cuttime1];//发布时间<4天前
        $where['refreshtime'] = [['egt',$cuttime2],['lt',$cuttime4],'and'];//刷新时间大于3天前小于1天前
        $where['click'] = ['lt',800];//点击量小于800
        $list2 = model('Job')
            ->where($where)
            ->field('uid')
            ->select();
        foreach ($list2 as $key => $value) {
            $uidarr[] = $value['uid'];
        }
        if(!empty($jobidarr)){
            $apply_data = model('JobApply')->whereIn('jobid',$jobidarr)->group('jobid')->column('jobid,count(id) as num');
            foreach ($list as $key => $value) {
                if($value['addtime']>$cuttime4 && (!isset($apply_data[$value['id']]) || $apply_data[$value['id']]<2)){//发布时间>1天前并且投递量小于2
                    $uidarr[] = $value['uid'];
                    continue;
                }
                if($value['addtime']>$cuttime3 && (!isset($apply_data[$value['id']]) || $apply_data[$value['id']]<4)){//发布时间>2天前并且投递量小于4
                    $uidarr[] = $value['uid'];
                    continue;
                }
                if($value['addtime']>$cuttime2 && (!isset($apply_data[$value['id']]) || $apply_data[$value['id']]<8)){//发布时间>3天前并且投递量小于8
                    $uidarr[] = $value['uid'];
                    continue;
                }
                if($value['addtime']>$cuttime1 && (!isset($apply_data[$value['id']]) || $apply_data[$value['id']]<10)){//发布时间>4天前并且投递量小于10
                    $uidarr[] = $value['uid'];
                    continue;
                }
            }
        }
        unset($where);
        $where['a.addtime'] = ['lt',$cuttime1];//发布时间<4天前
        $where['a.refreshtime'] = [['egt',$cuttime2],['lt',$cuttime4],'and'];//刷新时间大于3天前小于1天前
        $list3 = model('Job')->alias('a')
            ->join(config('database.prefix').'job_apply b','a.id=b.jobid','LEFT')
            ->where($where)
            ->group('b.jobid')
            ->having('num<10')
            ->column('a.uid,count(b.id) as num');//投递数小于10
        foreach ($list3 as $key => $value) {
            $uidarr[] = $key;
        }
        if (!empty($uidarr)) {
            $uidarr = array_unique($uidarr);
            model('NotifyRule')->notify($uidarr, 1, 'cron_job_few_view');
        }
    }
}
