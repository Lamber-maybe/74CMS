<?php
/**
 * 审核模板 Logic
 * @author edc
 * @date 2023-7-4
 */

namespace app\common\logic;

class AuditTemplateLogic
{

    /**
     * 获取列表
     * @access public
     * @param array $params [请求参数]
     * @return array
     * @author edc
     */
    public function getList($params)
    {
        // 验证参数
        $validate = validate('audit_template');
        if (!$validate->scene('get_list')->check($params)) {
            responseJson(500, $validate->getError());
        }

        $where = [
            'type' => $params['type'],
            'is_del' => 0
        ];
        $field = [
            'id',
            'content',
        ];
        $model = model('audit_template');
        return $model->getList($where, $field, ['id' => 'desc']);
    }

    /**
     * 添加模板
     * @access public
     * @param array $params [请求参数]
     * @param array $adminInfo [管理员信息]
     * @return array
     * @author edc
     */
    public function addTemplate($params, $adminInfo)
    {
        // 验证参数
        $validate = validate('audit_template');
        if (!$validate->scene('add')->check($params)) {
            return callBack(false, $validate->getError());
        }
        $where = [
            'type' => $params['type'],
            'is_del' => 0
        ];
        $model = model('audit_template');
        $count = $model->getCount($where);
        // 模板最多可添加6条
        if ($count >= 6) {
            return callBack(false, '已超出模板最大可添加数量');
        }

        $insertData = [
            'type' => $params['type'],
            'content' => $params['content'],
            'add_id' => $adminInfo['id'],
            'add_time' => time()
        ];
        $result = $model->addTemplate($insertData);
        if (empty($result)) {
            saveLog('添加模板失败-请求SQL为：' . $model->getLastSql());
            return callBack(false, '添加模板失败');
        }
        return callBack(true, 'success');
    }

    /**
     * 删除
     * @access public
     * @param array $params [请求参数]
     * @author edc
     */
    public function delete($params)
    {
        // 验证参数
        $validate = validate('audit_template');
        if (!$validate->scene('delete')->check($params)) {
            responseJson(500, $validate->getError());
        }
        $where = [
            'id' => $params['id'],
            'is_del' => 0
        ];
        $model = model('audit_template');
        $id = $model->getValue($where, 'id');
        if (empty($id)) {
            responseJson(500, '模板不存在');
        }
        // 删除模板
        $result = $model->editTemplate($where, ['is_del' => time()]);
        if (false === $result) {
            throw new \Exception('删除模板成功-请求SQL为：' . $model->getLastSql());
        }
    }

}