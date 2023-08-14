<?php
namespace app\common\lib;

set_time_limit(1000);
ignore_user_abort(true);

class Cron
{
    const CRON_MAX_TIME = 60; // 单个任务最大执行时间
    protected $timestamp;
    protected $error = null;
    public function __construct()
    {
        $this->timestamp = time();
    }
    /**
     * 外部执行专用方法
     */
    public function runOuter($id){
        $cron = model('Cron')
            ->where('id', 'eq', $id)
            ->find();
        if ($cron === null) {
            $this->error = '没有找到计划任务';
            return false;
        }
        $class_name = '\\app\\common\\lib\\cron\\' . $cron['action'];
        if (!class_exists($class_name)) {
            $this->error = '任务脚本类文件不存在';
            return false;
        }
        $instance = new $class_name();
        $result = $instance->execute();
        if ($result===false) {
            $this->error = $instance->getError();
            return false;
        }
        return true;
    }
    /**
     * 单条执行
     */
    public function runOne($id)
    {
        $cron = model('Cron')
            ->where('id', 'eq', $id)
            ->find();
        if ($cron === null) {
            $this->error = '没有找到计划任务';
            return false;
        }
        debug('begin');
        $class_name = '\\app\\common\\lib\\cron\\' . $cron['action'];
        if (!class_exists($class_name)) {
            $this->error = '任务脚本类文件不存在';
            return false;
        }
        $instance = new $class_name();
        $result = $instance->execute();
        if ($result===false) {
            $this->error = $instance->getError();
            return false;
        }
        debug('end');
        $timerange = debug('begin', 'end');
        $next_execute_time = $this->getNextExecuteTime($cron);
        model('Cron')
            ->where(['id' => $cron['id']])
            ->update([
                'last_execute_time' => $this->timestamp,
                'next_execute_time' => $next_execute_time
            ]);
        $log_arr['cron_id'] = $cron['id'];
        $log_arr['cron_name'] = $cron['name'];
        $log_arr['addtime'] = $this->timestamp;
        $log_arr['seconds'] = $timerange;
        $log_arr['is_auto'] = 0;
        model('CronLog')->save($log_arr);
        return [
            'last_execute_time' => $this->timestamp,
            'next_execute_time' => $next_execute_time
        ];
    }
    /**
     * 多条执行
     */
    public function run()
    {
        $lockfile = $this->lock();
        $cronlist = $this->getCronList();
        $log = [];
        foreach ($cronlist as $key => $value) {
            debug('begin');
            $class_name = '\\app\\common\\lib\\cron\\' . $value['action'];
            if (!class_exists($class_name)) {
                continue;
            }
            $instance = new $class_name();
            $instance->execute();
            debug('end');
            $timerange = debug('begin', 'end');
            $next_execute_time = $this->getNextExecuteTime($value);
            model('Cron')
                ->where(['id' => $value['id']])
                ->update([
                    'last_execute_time' => $this->timestamp,
                    'next_execute_time' => $next_execute_time
                ]);
            $log_arr['cron_id'] = $value['id'];
            $log_arr['cron_name'] = $value['name'];
            $log_arr['addtime'] = $this->timestamp;
            $log_arr['seconds'] = $timerange;
            $log_arr['is_auto'] = 1;
            $log[] = $log_arr;
        }
        if (!empty($log)) {
            model('CronLog')->saveAll($log);
        }
        $this->unlock($lockfile);
    }
    /**
     * 获取需要执行的计划任务列表
     */
    protected function getCronList()
    {
        $where['next_execute_time'] = ['lt', $this->timestamp];
        $where['status'] = 1;
        $cronlist = model('Cron')
            ->where($where)
            ->select();
        return $cronlist;
    }
    /**
     * 锁定
     */
    protected function lock()
    {
        $lockfile = RUNTIME_PATH . 'cron.lock';
        if (
            is_writable($lockfile) &&
            filemtime($lockfile) >
                input('server.REQUEST_TIME') - self::CRON_MAX_TIME
        ) {
            clearstatcache();
            return;
        } else {
            touch($lockfile); //设置文件访问和修改时间,文件不存在则会被创建
        }
        return $lockfile;
    }

    /**
     * 解除锁定
     */
    protected function unlock($lockfile)
    {
        @unlink($lockfile);
    }
    /**
     * 计算下次执行时间
     */
    public function getNextExecuteTime($croninfo)
    {
        if ($croninfo['weekday'] >= 0) {
            $weekday = array(
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'
            );
            $nextrun = strtotime('Next ' . $weekday[$croninfo['weekday']]);
        } elseif ($croninfo['day'] > 0) {
            $nextrun = strtotime('+1 months');
            $nextrun = mktime(
                0,
                0,
                0,
                date('m', $nextrun),
                $croninfo['day'],
                date('Y', $nextrun)
            );
        } else {
            $nextrun = time();
        }
        if ($croninfo['hour'] >= 0) {
            $nextrun = mktime(
                $croninfo['hour'],
                0,
                0,
                date('m', $nextrun),
                date('d', $nextrun),
                date('Y', $nextrun)
            );
            if ($nextrun < time()) {
                $nextrun += 3600 * 24;
            }
        }
        if (stripos($croninfo['minute'], '/') !== false) {
            $minute_arr = explode('/', $croninfo['minute']);
            $nextrun = $nextrun + 60 * intval($minute_arr[1]);
            $nextrun = mktime(
                date('H', $nextrun),
                date('i', $nextrun),
                0,
                date('m', $nextrun),
                date('d', $nextrun),
                date('Y', $nextrun)
            );
        } elseif (intval($croninfo['minute']) > 0) {
            $nextrun = mktime(
                date('H', $nextrun),
                $croninfo['minute'],
                0,
                date('m', $nextrun),
                date('d', $nextrun),
                date('Y', $nextrun)
            );
            if ($nextrun < time()) {
                $nextrun += 3600;
            }
        }
        return $nextrun;
    }
    public function getError()
    {
        return $this->error;
    }
}
