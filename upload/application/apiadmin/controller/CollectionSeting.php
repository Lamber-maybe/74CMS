<?php
/**
 * 采集设置 Controller
 * @author chenyang
 * Date Time：2022年4月11日11:19:23
 */
namespace app\apiadmin\controller;

use app\common\validate\CollectionSetingValidate;
use app\common\logic\CollectionSetingLogic;

class CollectionSeting extends \app\common\controller\Backend
{

    /**
     * 保存采集设置
     * @access public
     * @author chenyang
     * @return array
     * Date Time：2022年4月11日11:21:08
     */
    public function saveSeting(){
        try{
            // 获取参数
            $validate = new CollectionSetingValidate();
            $params = $validate->getParamAll();

            $logic = new CollectionSetingLogic();
            $result = $logic->saveSeting($params, 1, $this->admininfo);
            if ($result['status'] === false) {
                responseJson(400, $result['msg']);
            }

            responseJson(200, '保存成功');
        }catch(\Exception $e){
            saveLog('保存失败-报错信息：'.json_encode(['Line' => $e->getLine(),'File' => $e->getFile(),'Message' => $e->getMessage()]));
            responseJson(400, '保存失败');
        }
    }

    /**
     * 保存职位设置
     * @access public
     * @author chenyang
     * @return array
     * Date Time：2022年4月11日14:03:29
     */
    public function saveJobSeting(){
        try{
            // 获取参数
            $validate = new CollectionSetingValidate();
            $params = $validate->getParamAll();

            $logic = new CollectionSetingLogic();
            $result = $logic->saveSeting($params, 2, $this->admininfo);
            if ($result['status'] === false) {
                responseJson(400, $result['msg']);
            }

            responseJson(200, '保存成功');
        }catch(\Exception $e){
            saveLog('保存失败-报错信息：'.json_encode(['Line' => $e->getLine(),'File' => $e->getFile(),'Message' => $e->getMessage()]));
            responseJson(400, '保存失败');
        }
    }

    /**
     * 保存企业设置
     * @access public
     * @author chenyang
     * @return array
     * Date Time：2022年4月11日15:32:09
     */
    public function saveCompanySeting(){
        try{
            // 获取参数
            $validate = new CollectionSetingValidate();
            $params = $validate->getParamAll();

            $logic = new CollectionSetingLogic();
            $result = $logic->saveSeting($params, 3, $this->admininfo);
            if ($result['status'] === false) {
                responseJson(400, $result['msg']);
            }

            responseJson(200, '保存成功');
        }catch(\Exception $e){
            saveLog('保存失败-报错信息：'.json_encode(['Line' => $e->getLine(),'File' => $e->getFile(),'Message' => $e->getMessage()]));
            responseJson(400, '保存失败');
        }
    }

    /**
     * 保存账号设置
     * @access public
     * @author chenyang
     * @return array
     * Date Time：2022年4月12日09:21:55
     */
    public function saveAccountSeting(){
        try{
            // 获取参数
            $validate = new CollectionSetingValidate();
            $params = $validate->getParamAll();

            $logic = new CollectionSetingLogic();
            $result = $logic->saveSeting($params, 4, $this->admininfo);
            if ($result['status'] === false) {
                responseJson(400, $result['msg']);
            }

            responseJson(200, '保存成功');
        }catch(\Exception $e){
            saveLog('保存失败-报错信息：'.json_encode(['Line' => $e->getLine(),'File' => $e->getFile(),'Message' => $e->getMessage()]));
            responseJson(400, '保存失败');
        }
    }

    /**
     * 获取采集设置信息
     * @access public
     * @author chenyang
     * @return array
     * Date Time：2022年4月12日10:09:34
     */
    public function getSetingInfo(){
        try{
            $logic = new CollectionSetingLogic();
            $result = $logic->getSetingInfo();

            responseJson(200, '获取成功', $result);
        }catch(\Exception $e){
            saveLog('获取失败-报错信息：'.json_encode(['Line' => $e->getLine(),'File' => $e->getFile(),'Message' => $e->getMessage()]));
            responseJson(400, '获取失败');
        }
    }

    /**
     * 保存资讯采集设置
     * @access public
     * @author zhangchunhui
     * @return array
     * Date Time：2022年6月17日14:00:00
     */
    public function saveArticleSeting(){
        try{
            // 获取参数
            $validate = new CollectionSetingValidate();
            $params = $validate->getParamAll();

            $logic = new CollectionSetingLogic();
            $result = $logic->saveSeting($params, 5, $this->admininfo);
            if ($result['status'] === false) {
                responseJson(400, $result['msg']);
            }

            responseJson(200, '保存成功');
        }catch(\Exception $e){
            saveLog('保存失败-报错信息：'.json_encode(['Line' => $e->getLine(),'File' => $e->getFile(),'Message' => $e->getMessage()]));
            responseJson(400, '保存失败');
        }
    }

}