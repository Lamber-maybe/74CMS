<?php
/**
 * 审核模板 Controller
 * @author edc
 * @date 2023-7-4
 */

namespace app\apiadmin\controller;

use app\common\logic\AuditTemplateLogic;

class AuditTemplate extends \app\common\controller\Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 获取列表
     * @access public
     * @author edc
     */
    public function getList()
    {
        try {
            // 获取参数
            $params['type'] = $this->request->param('type', 0, 'intval');

            $logic = new AuditTemplateLogic();
            $list = $logic->getList($params);

            $this->ajaxReturn(200, '获取成功', $list);
        } catch (\Exception $e) {
            saveLog('获取失败-报错信息：' . json_encode(['Line' => $e->getLine(), 'File' => $e->getFile(), 'Message' => $e->getMessage()]));
            $this->ajaxReturn(500, '获取失败');
        }
    }

    /**
     * 删除模板
     * @access public
     * @author edc
     */
    public function delete()
    {
        try {
            // 获取参数
            $params['id'] = $this->request->post('id', 0, 'intval');

            $logic = new AuditTemplateLogic();
            $logic->delete($params);

            $this->ajaxReturn(200, '删除成功');
        } catch (\Exception $e) {
            saveLog('删除失败-报错信息：' . json_encode(['Line' => $e->getLine(), 'File' => $e->getFile(), 'Message' => $e->getMessage()]));
            $this->ajaxReturn(500, '删除失败');
        }
    }

}