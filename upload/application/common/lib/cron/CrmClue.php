<?php

namespace app\common\lib\cron;

use Think\Db;

class CrmClue
{
    protected $getError;
    public function execute()
    {
        $sys_configs = model('b2bcrm.CrmSysConfig')
            ->where(['category'=>'thread','is_open'=>1,'key'=>'thread_fall_seas','value'=>['gt',0]])
            ->limit(1)
            ->value('value');
        if (!empty($sys_configs))
        {
            $time = time() - (intval($sys_configs)*86400);
            $where = [
                'admin_id' => ['gt',0],
                'last_visit_time' => [['lt',$time],['gt',0]],
            ];
            try {
                    $crmclue = model('b2bcrm.CrmClue')
                        ->where($where)
                        ->field('id')
                        ->select();

                    if (!$crmclue->isEmpty()){
                        $id = array_column($crmclue->toArray(),'id');
                        Db::startTrans();
                        foreach ($id as $v)
                        {
                            $CrmClue = new \app\common\model\b2bcrm\CrmClueRelease();
                            $CrmClue->releaseAdd($v,0,2,1,0);
                        }
                        $release = model('b2bcrm.CrmClue')->where(['id'=>['in',$id]])->update(['admin_id'=>0]);
                        if (false === $release)
                        {
                            throw new \Exception(model('b2bcrm.CrmClue')->getError());
                        }
                    }
                Db::commit();
                return true;
            }catch (\Exception $e)
            {
                Db::rollback();
                $this->getError = $e->getMessage();
                return false;
            }
        }
        return true;
    }
    public function getError()
    {
        return $this->getError;
    }
}