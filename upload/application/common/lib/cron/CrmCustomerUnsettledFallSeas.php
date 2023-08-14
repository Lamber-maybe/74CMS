<?php
/**
 * 客户到期未成交掉入公海
 */

namespace app\common\lib\cron;

use app\common\model\b2bcrm\CrmClueRelease;
use think\Db;

class CrmCustomerUnsettledFallSeas
{
    protected $timestamp;

    protected $error = null;

    public function __construct()
    {
        $this->timestamp = time();
    }

    public function execute()
    {
        $sys_configs = model('b2bcrm.CrmSysConfig')
            ->where([
                'category' => 'customer',
                'is_open' => 1,
                'key' => 'customer_unsettled_fall_seas'
            ])
            ->limit(1)
            ->value('value');

        if (null === $sys_configs) {
            $this->error = '未找到【CRM客户未成交自动释放】配置';
            return false;
        }

        if ($sys_configs <= 0) {
            $this->error = 'CRM客户未成交自动释放设置为不掉入';
            return false;
        }

        $limit_time = time() - (intval($sys_configs) * 86400);

        $company_ids = model('Company')
            ->where([
                'admin_id' => ['gt', 0],
                'life_cycle_id' => ['neq', 7],
                'collection_time' => [
                    ['lt', $limit_time],
                    ['gt', 0]
                ],
            ])
            ->column('id');

        if (empty($company_ids)) {
            $this->error = '没有满足掉入公海条件的客户';
            return false;
        }

        Db::startTrans();
        try {
            $crmClue = new CrmClueRelease();
            $crmClue->releaseCompany($company_ids, 2, 1);

            $release = model('Company')
                ->where([
                    'id' => ['in', $company_ids]
                ])
                ->update([
                    'admin_id' => 0
                ]);
            if (false === $release) {
                throw new \Exception(model('Company')->getError());
            }

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getError()
    {
        return $this->error;
    }
}