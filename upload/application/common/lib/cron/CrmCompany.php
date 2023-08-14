<?php
/**
 * CRM企业未跟进自动释放
 * yx - 2022.11.10
 */

namespace app\common\lib\cron;

use app\common\model\b2bcrm\CrmClueRelease;
use Think\Db;

class CrmCompany
{
    /**
     * @var int 当前时间
     */
    protected $timestamp;

    /**
     * @var null 错误信息
     */
    protected $error = null;

    public function __construct()
    {
        $this->timestamp = time();
    }

    public function execute()
    {
        /**
         * 1.未跟进掉入公海
         */
        $customer_fall_seas = model('b2bcrm.CrmSysConfig')
            ->where([
                'category' => 'customer',
                'is_open' => 1,
                'key' => 'customer_fall_seas',
                'value' => ['gt', 0]
            ])
            ->value('value');

        $customer_fall_seas = intval($customer_fall_seas);

        /**
         * 1.1  表示销售未跟进自己的客户达到设置的天数后，客户会自动掉入公共客户中，0为不掉入。
         */
        if ($customer_fall_seas > 0) {
            // 掉入公海最后跟进日期时间节点
            $time = $this->timestamp - ($customer_fall_seas * 86400);

            try {
                // 掉入公海企业查询条件
                $company_where = [
                    'admin_id' => ['gt', 0],
                    'last_visit_time' => [['lt', $time], ['gt', 0]],
                ];

                $companies = model('Company')
                    ->where($company_where)
                    ->field('id,uid,admin_id')
                    ->select();

                if (isset($companies) && !empty($companies)) {

                    Db::startTrans();

                    foreach ($companies as $company) {
                        $crmClueRelease = new CrmClueRelease();
                        $crmClueRelease->releaseAdd(
                            $company['uid'],
                            $company['admin_id'],
                            2,
                            1,
                            1
                        );
                    }

                    $ids = array_column($companies, 'id');
                    $release = model('Company')
                        ->where(['id' => ['in', $ids]])
                        ->update(['admin_id' => 0]);
                    if (false === $release) {
                        throw new \Exception(model('b2bcrm.CrmClue')->getError());
                    }

                } else {
                    $this->error = '没有要掉入公海的企业客户';
                    return false;
                }

                Db::commit();
                return true;
            } catch (\Exception $e) {
                Db::rollback();
                $this->error = $e->getMessage();
                return false;
            }
        } else {
            $this->error = '系统未开启【未跟进掉入公海】配置';
            return false;
        }
    }

    public function getError()
    {
        return $this->error;
    }
}