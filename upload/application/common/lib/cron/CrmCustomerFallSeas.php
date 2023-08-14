<?php
/**
 * 客户到期未跟进掉入公海
 */

namespace app\common\lib\cron;

class CrmCustomerFallSeas
{
    public function execute()
    {
        $timestamp = time();
        echo $timestamp;
    }
}