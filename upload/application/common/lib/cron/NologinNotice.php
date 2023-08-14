<?php
/**
 * 休眠用户提醒
 */

namespace app\common\lib\cron;

use app\common\model\Member;

class NologinNotice
{
    protected $error = null;

    public function execute()
    {
        // 配置项1-用户未登录条件区间（天）-（天）
        $config_time_range = config('global_config.nologin_notice_timerange');
        if (empty($config_time_range) || strlen($config_time_range) <= 1) {
            $this->error = '请先配置【用户未登录条件区间】';
            return false;
        }

        // 配置项2-提醒次数限制（次）
        $config_time_counter = config('global_config.nologin_notice_counter');
        if (!isset($config_time_counter) || empty($config_time_counter)) {
            $this->error = '请先配置【提醒次数限制】';
            return false;
        }

        /**
         * 1.用户登录条件区间
         */
        if (false === stripos($config_time_range, ',')) {
            // 1.1只有一个天数的情况
            $starttime = strtotime('-' . intval($config_time_range) . 'day');
            $starttime = strtotime(date('Y-m-d', $starttime));
            $endtime = $starttime + 3600 * 24;
        } else {
            // 1.2按照[较近]-[较远]天数设置
            $config_time_range_arr = explode(",", $config_time_range);
            if ($config_time_range_arr[0] >= $config_time_range_arr[1]) {
                $this->error = '用户登录条件区间指需按照[较近]-[较远]天数设置';
                return false;
            }
            $starttime = strtotime('-' . intval($config_time_range_arr[0]) . 'day');
            $starttime = strtotime(date('Y-m-d', $starttime));
            $endtime = strtotime('-' . intval($config_time_range_arr[1]) . 'day');
            $endtime = strtotime(date('Y-m-d', $endtime));
            $endtime = $endtime + 3600 * 24;
        }

        $where = 'nologin_notice_counter < ' . intval($config_time_counter) . ' and (last_login_time = 0 or (last_login_time >= ' . $starttime . ' and last_login_time < ' . $endtime . '))';

        $model = new Member();

        $company_uid = $model
            ->where($where)
            ->where('utype', 1)
            ->column('uid');
        if (isset($company_uid) && !empty($company_uid)) {
            model('NotifyRule')->notify($company_uid, 1, 'cron_recommend');
            $model->whereIn('uid', $company_uid)->setInc('nologin_notice_counter');
        }

        $personal_uid = $model
            ->where($where)
            ->where('utype', 2)
            ->column('uid');
        if (isset($personal_uid) && !empty($personal_uid)) {
            model('NotifyRule')->notify($personal_uid, 2, 'cron_recommend');
            $model->whereIn('uid', $personal_uid)->setInc('nologin_notice_counter');
        }

        return true;
    }

    public function getError()
    {
        return $this->error;
    }
}
