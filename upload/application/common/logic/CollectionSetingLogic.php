<?php
/**
 * 采集设置 Logic
 * @author chenyang
 * Date Time：2022年4月11日13:25:32
 */
namespace app\common\logic;

use app\common\model\CollectionSeting;

class CollectionSetingLogic
{

    /**
     * 保存采集设置
     * @access public
     * @author chenyang
     * @param  array $params     [请求参数]
     * @param  array $ruleSource [规则来源:1|采集设置,2|职位设置,3|企业设置,4|账号设置]
     * @param  array $adminInfo  [登录信息]
     * @return array
     * Date Time：2022年4月11日13:46:18
     */
    public function saveSeting($params, $ruleSource, $adminInfo){
        $model = new CollectionSeting();
        $log_field = '';
        switch ($ruleSource) {
            // 采集设置
            case 1:
                $updateData = $params;
                $select_model = clone $model;
                $seting_info = $select_model->getInfo(['id' => 1]);
                $log_field = '数据采集，修改采集设置，';
                if ($seting_info['status'] != $params['status']) {
                    $log_field .= '功能状态:' . $select_model->map_status[$seting_info['status']] . '->' . $select_model->map_status[$params['status']] . '；';
                }
                if ($seting_info['matching_accuracy'] != $params['matching_accuracy']) {
                    $log_field .= '匹配精准度:' . $seting_info['matching_accuracy'] . '->' . $params['matching_accuracy'];
                }
                break;
            // 职位设置
            case 2:
                if ($params['minwage'] > $params['maxwage']) {
                    return callBack(false, '最低工资不可大于最高工资');
                }
                if ($params['minage'] > $params['maxage']) {
                    return callBack(false, '最低年龄不可大于最高年龄');
                }
                // 当不限制年龄时，年龄为0
                if ($params['age_na'] == 1) {
                    $params['minage'] = $params['maxage'] = 0;
                }
                $updateData = [
                    'job_seting' => json_encode($params),
                ];
                $log_field = '数据采集，修改职位设置';
                break;
            // 企业设置
            case 3:
                $updateData = [
                    'company_seting' => json_encode($params),
                ];
                $log_field = '数据采集，修改企业设置';
                break;
            // 账号设置
            case 4:
                if ($params['pwd_rule'] == 1) $params['password'] = '';
                if ($params['pwd_rule'] == 2 && empty($params['password'])) {
                    return callBack(false, '请输入指定密码');
                }
                if (!empty($params['password']) && strlen($params['password']) < 6) {
                    return callBack(false, '密码长度不少于6位');
                }
                $updateData = [
                    'account_seting' => json_encode($params),
                ];
                $log_field = '数据采集，修改账号设置';
                break;
            case 5://添加资讯才行参数验证
                if (!isset($params['cid']) || intval($params['cid']) <=0)
                {
                    return callBack(false, '请选择资讯分类');
                }
                $updateData = [
                    'article_seting' => json_encode($params),
                ];
                $log_field = '数据采集，修改资讯设置';
                break;
            default:
                return callBack(false, '规则来源有误');
        }

        $updateWhere = [
            'id' => 1
        ];
        $updateResult = $model->edit($updateWhere, $updateData);
        if ($updateResult === false) {
            saveLog('保存设置失败-请求SQL为：'.$model->getLastSql());
            return callBack(false, '保存设置失败');
        }

        model('AdminLog')->writeLog(
            $log_field,
            $adminInfo,
            0,
            3
        );

        return callBack(true, 'success');
    }

    /**
     * 获取采集设置信息
     * @access public
     * @author chenyang
     * @return array
     * Date Time：2022年4月12日10:10:15
     */
    public function getSetingInfo(){
        $model = new CollectionSeting();
        $info = $model->getInfo(['id' => 1]);
        if (!empty($info)) {
            $info['job_seting']     = !empty($info['job_seting']) ? json_decode($info['job_seting'], true) : [];
            $info['company_seting'] = !empty($info['company_seting']) ? json_decode($info['company_seting'], true) : [];
            $info['account_seting'] = !empty($info['account_seting']) ? json_decode($info['account_seting'], true) : [];
            $info['article_seting'] = !empty($info['article_seting']) ? json_decode($info['article_seting'],true) : []; //添加资讯采集
        }
        return $info;
    }

}