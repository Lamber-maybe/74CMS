<?php
/**
 * 采集 Controller
 * @author chenyang
 * Date Time：2022年3月25日11:49:49
 */
namespace app\apiadmin\controller;

use app\common\validate\CollectionValidate;
use app\common\logic\CollectionLogic;

class Collection
{

    /**
     * 保存职位信息
     * @access public
     * @author chenyang
     * @return array
     * Date Time：2022年3月25日11:54:27
     */
    public function saveJob(){
        try{
            // 获取参数
            $validate = new CollectionValidate();
            $params = $validate->getParamAll();

            $logic = new CollectionLogic();
            $result = $logic->saveJob($params);
            if ($result['status'] === false) {
                responseJson(400, $result['msg']);
            }

            responseJson(200, $result);
        }catch(\Exception $e){
            saveLog('保存职位失败-报错信息：'.json_encode(['Line' => $e->getLine(),'File' => $e->getFile(),'Message' => $e->getMessage()]));
            responseJson(400, '保存职位失败');
        }
    }

    /**
     * 保存企业信息
     * @access public
     * @author chenyang
     * @return array
     * Date Time：2022年3月30日16:39:10
     */
    public function saveCompany(){
        try{
            // 获取参数
            $validate = new CollectionValidate();
            $params = $validate->getParamAll();

            $logic = new CollectionLogic();
            $result = $logic->saveCompany($params);
            if ($result['status'] === false) {
                responseJson(400, $result['msg']);
            }

            responseJson(200, $result);
        }catch(\Exception $e){
            saveLog('保存企业失败-报错信息：'.json_encode(['Line' => $e->getLine(),'File' => $e->getFile(),'Message' => $e->getMessage()]));
            responseJson(400, '保存企业失败');
        }
    }

}