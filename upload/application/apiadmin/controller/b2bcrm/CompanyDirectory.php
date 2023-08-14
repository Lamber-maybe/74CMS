<?php
/**
 * 企业名录 Controller
 * @author edc
 * Date：2022-11-17
 */

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;
use app\common\validate\CompanyDirectoryValidate;
use app\common\logic\CompanyDirectoryLogic;

class CompanyDirectory extends Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 基础配置
     * @access public
     * @author edc
     * Date：2022-11-17
     */
    public function baseConfig()
    {
        try {
            if (request()->isGet()) {
                $configInfo = config('global_config.account_qy_directory');
                responseJson(200, '获取成功', $configInfo);
            }
            // 获取参数
            $validate = new CompanyDirectoryValidate();
            $params = $validate->getParamAll();

            $logic = new CompanyDirectoryLogic();
            $logic->saveConfig($params, $this->admininfo);

            responseJson(200, '保存成功');
        } catch (\Exception $e) {
            saveLog('保存失败-报错信息：' . json_encode(['Line' => $e->getLine(), 'File' => $e->getFile(), 'Message' => $e->getMessage()]));
            responseJson(400, '保存失败');
        }
    }

    /**
     * 查询缓存是否存在
     * @access public
     * @author edc
     * Date：2022-11-30
     */
    public function queryCacheIsExists()
    {
        try {
            // 获取参数
            $validate = new CompanyDirectoryValidate();
            $params = $validate->getParamAll();

            $logic = new CompanyDirectoryLogic();
            $result = $logic->queryCacheIsExists($params);

            responseJson(200, '获取成功', $result);
        } catch (\Exception $e) {
            saveLog('获取失败-报错信息：' . json_encode(['Line' => $e->getLine(), 'File' => $e->getFile(), 'Message' => $e->getMessage()]));
            responseJson(400, '获取失败');
        }
    }

    /**
     * 获取企业线索列表
     * @access public
     * @author edc
     * Date：2022-11-17
     */
    public function getCompanyClueList()
    {
        try {
            // 获取参数
            $validate = new CompanyDirectoryValidate();
            $params = $validate->getParamAll();

            $logic = new CompanyDirectoryLogic();
            $result = $logic->getCompanyClueList($params, $this->admininfo);

            responseJson(200, '获取成功', $result);
        } catch (\Exception $e) {
            saveLog('获取失败-报错信息：' . json_encode(['Line' => $e->getLine(), 'File' => $e->getFile(), 'Message' => $e->getMessage()]));
            responseJson(400, '获取失败');
        }
    }

    /**
     * 获取消耗记录列表
     * @access public
     * @author edc
     * Date：2022-11-21
     */
    public function getConsumeRecordList()
    {
        try {
            // 获取参数
            $validate = new CompanyDirectoryValidate();
            $params = $validate->getParamAll();

            $logic = new CompanyDirectoryLogic();
            $result = $logic->getConsumeRecordList($params);

            responseJson(200, '获取成功', $result);
        } catch (\Exception $e) {
            saveLog('获取失败-报错信息：' . json_encode(['Line' => $e->getLine(), 'File' => $e->getFile(), 'Message' => $e->getMessage()]));
            responseJson(400, '获取失败');
        }
    }

    /**
     * 导入线索
     * @access public
     * @author edc
     * Date：2022-11-22
     */
    public function importClue()
    {
        try {
            // 获取参数
            $validate = new CompanyDirectoryValidate();
            $params = $validate->getParamAll();

            $logic = new CompanyDirectoryLogic();
            $result = $logic->importClue($params, $this->admininfo);

            responseJson(200, '导入成功', $result);
        } catch (\Exception $e) {
            saveLog('获取失败-报错信息：' . json_encode(['Line' => $e->getLine(), 'File' => $e->getFile(), 'Message' => $e->getMessage()]));
            responseJson(400, '导入失败');
        }
    }

    /**
     * 获取账户余额
     * @access public
     * @author edc
     * Date：2022-11-23
     */
    public function getAccountBalance()
    {
        try {
            $logic = new CompanyDirectoryLogic();
            $result = $logic->getAccountBalance();

            responseJson(200, '获取成功', $result);
        } catch (\Exception $e) {
            saveLog('获取失败-报错信息：' . json_encode(['Line' => $e->getLine(), 'File' => $e->getFile(), 'Message' => $e->getMessage()]));
            responseJson(400, '获取失败');
        }
    }

}