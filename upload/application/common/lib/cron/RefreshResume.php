<?php
/**
 * 自动刷新简历
 * zch - 2023.06.12
 */

namespace app\common\lib\cron;

use think\Db;

class RefreshResume
{
    /**
     * @var null 错误信息
     */
    protected $error = null;

    public function execute()
    {
        $timestamp = time() - (15*86400);
        $where = [
            'addtime' => ['gt', $timestamp],
            'is_display' => 1,
            'audit' => 1
        ];
        $limit = model('Cron')->where('action','RefreshResume')->value('refresh_quantity');
        if (empty($limit)){
            $this->error = '未设置简历自动刷新条数';
            return false;
        }
        $list = model('Resume')
            ->where($where)
            ->orderRaw('rand()')
            ->limit($limit)
            ->column('uid');

        if (empty($list)){
            $this->error = '没有要自动刷新的简历';
            return false;
        }

        try {
            Db::startTrans();
            foreach ($list as $uid) {
                $refreshParams = [
                    'uid' => $uid,
                    'utype' => 2
                ];
                model('Resume')->refreshResumeData($refreshParams, 2);
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
