<?php
namespace app\common\lib\cron;

class PushSubscribeJob
{
    public function execute()
    {
        $timestamp = time();
        $model = new \app\common\model\SubscribeJob();
        $where['pushtime'] = [['lt', strtotime('-7day')],['eq', 0],'or'];
        $list = $model
            ->where($where)
            ->limit(10)
            ->select();
        $idarr = [];
        foreach ($list as $key => $value) {
            $idarr[] = $value['id'];
            $urlParams = [
                'c1'=>$value['category1'],
                'c2'=>$value['category2'],
                'c3'=>$value['category3'],
                'd1'=>$value['district1'],
                'd2'=>$value['district2'],
                'd3'=>$value['district3'],
                'w1'=>$value['minwage'],
                'w2'=>$value['maxwage']
            ];
            model('NotifyRule')->notify($value['uid'], 2, 'cron_subscribe',[],0,http_build_query($urlParams));
        }
        if (!empty($idarr)) {
            $model->whereIn('id',$idarr)->setField('pushtime',$timestamp);
        }
    }
}
