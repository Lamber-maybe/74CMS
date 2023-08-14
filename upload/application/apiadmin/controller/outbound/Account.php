<?php
/**
 * 外呼中心
 * -- 外呼配置
 */

namespace app\apiadmin\controller\outbound;

use app\common\controller\Backend;
use app\common\lib\outbound\xiaojianke\CallAccount;
use think\Db;

class Account extends Backend
{
    /**
     * @Purpose:
     * 外呼配置
     * @Method GET-获取配置信息|POST-编辑配置信息
     *
     * @param string $app_id
     * @param string $app_secret
     *
     * @return Jsonp
     *
     * @link {domain}outbound/account/index
     *
     * @author  yx
     * @version 1.1
     * @since   2022/9/28
     */
    public function index()
    {
        if (request()->isGet()) {
            $account_outbound = config('global_config.account_outbound');

            $this->ajaxReturn(200, '获取数据成功', $account_outbound);
        } else {
            $param = [
                'app_id' => input('post.app_id/s', '', 'trim'),
                'app_secret' => input('post.app_secret/s', '', 'trim')
            ];
            if (empty($param['app_id'])) {
                $this->ajaxReturn(500, '请填写AppKey');
            }
            if (empty($param['app_secret'])) {
                $this->ajaxReturn(500, '请填写AppSecret');
            }

            try {
                $configInfo = model('Config')
                    ->where('name', 'account_outbound')
                    ->find();
                if (null === $configInfo) {
                    $this->ajaxReturn(500, '在线文档预览配置项不存在');
                }
                $configValue = json_decode($configInfo['value'], true);

                Db::startTrans();

                $callSeat = new CallAccount($param);
                $result = $callSeat->verifyAccountSecret();
                if (false === $result) {
                    $this->ajaxReturn(500, 'AppKey或AppSecret错误');
                }

                $save_config = model('Config')
                    ->isUpdate(true)
                    ->allowField(true)
                    ->save(
                        ['value' => json_encode($param)],
                        ['name' => 'account_outbound']
                    );
                if (false === $save_config) {
                    throw new \Exception(model('Config')->getError());
                }


                $log_field = '外呼中心-外呼配置，修改外呼配置';
                if ($param['app_id'] != $configValue['app_id']) {
                    $log_field .= '，AppKey:' . $configValue['app_id'] . '->' . $param['app_id'];
                }
                if ($param['app_secret'] != $configValue['app_secret']) {
                    $log_field .= '，AppSecret:' . $configValue['app_secret'] . '->' . $param['app_secret'];
                }
                model('AdminLog')->writeLog(
                    $log_field,
                    $this->admininfo,
                    0,
                    3
                );

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                $this->ajaxReturn(500, $e->getMessage());
            }

            $this->ajaxReturn(200, '保存数据成功');

        }
    }


}