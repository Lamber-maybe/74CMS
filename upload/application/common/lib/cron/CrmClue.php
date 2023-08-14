<?php
/**
 * CRM线索未跟进自动释放
 * yx - 2022.11.10
 */

namespace app\common\lib\cron;

use app\common\model\b2bcrm\CrmClueRelease;
use Think\Db;

class CrmClue
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
        $thread_fall_seas = model('b2bcrm.CrmSysConfig')
            ->where([
                'category' => 'thread',
                'is_open' => 1,
                'key' => 'thread_fall_seas',
                'value' => ['gt', 0]
            ])
            ->value('value');

        $thread_fall_seas = intval($thread_fall_seas);

        /**
         * 1.1 表示销售未跟进自己的客户达到设置的天数后，客户会自动掉入线索池中，0为不掉入。
         */
        if ($thread_fall_seas > 0) {
            $time = $this->timestamp - ($thread_fall_seas * 86400);

            try {
                $clue_where = [
                    'admin_id' => ['gt', 0],
                    'last_visit_time' => [['lt', $time], ['gt', 0]],
                ];
                $crmClue = model('b2bcrm.CrmClue')
                    ->where($clue_where)
                    ->field('id,admin_id')
                    ->select();

                if (!$crmClue->isEmpty()) {
                    $clues = $crmClue->toArray();

                    Db::startTrans();

                    foreach ($clues as $clue) {
                        $CrmClue = new CrmClueRelease();
                        $CrmClue->releaseAdd($clue['id'], $clue['admin_id'], 2, 1, 0);
                    }

                    $ids = array_column($clues, 'id');
                    $release = model('b2bcrm.CrmClue')
                        ->where(['id' => ['in', $ids]])
                        ->update(['admin_id' => 0]);
                    if (false === $release) {
                        throw new \Exception(model('b2bcrm.CrmClue')->getError());
                    }

                } else {
                    $this->error = '没有要掉入线索池的线索';
                    return false;
                }

                Db::commit();
                return true;
            } catch (\Exception $e) {
                Db::rollback();
                $this->getError = $e->getMessage();
                return false;
            }
        } else {
            $this->error = '系统未开启【未跟进掉入线索池】配置';
            return false;
        }
    }

    public function getError()
    {
        return $this->error;
    }
}