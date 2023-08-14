<?php
/**
 * CRM旧系统数据转换至新系统
 */

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;
use think\Db;

class CrmTransform extends Backend
{
    public function initiate()
    {

        Db::startTrans();
        try {
            // a：转换【标签】
            $this->_configLabel();

            // b:转换【线索】
            $this->_customer();

            // c:转换【联系人】
            $this->_linkman();

            // c:转换【跟进记录】
            $this->_visitLog();

            // c:写入操作日志表
            $adminInfo = [
                'id' => 0,
                'username' => 'system'
            ];
            $adminInfo = (object)$adminInfo;
            model('AdminLog')->writeLog(
                'CRM升级，完成CRM数据转换',
                $adminInfo,
                0,
                1
            );

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }

        $this->ajaxReturn(200, 'CRM数据转换完成');

    }


    /**
     * 获取旧CRM标签数据
     * @return false|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function _configLabel()
    {
        $label_info = model('crm.CrmConfig')
            ->field("id,name,sort_id as `order`")
            ->where('category', 'label')
            ->select();
        $label_info = collection($label_info);

        if ($label_info->isEmpty()) {
            return false;
        } else {
            $label_info = $label_info->toArray();
            $this->_transformLabel($label_info);
        }
    }


    /**
     * 标签数据转换【标签-b2bcrm.CrmTag】
     * @param $label_info
     * @return void
     * @throws \Exception
     */
    private function _transformLabel($labelInfo)
    {
        if (!is_array($labelInfo)) {
            throw new \Exception('数据转换失败【标签数据格式错误】');
        }
        $label_result = model('b2bcrm.CrmTag')
            ->allowField(true)
            ->isUpdate(false)
            ->saveAll($labelInfo, false);
        if (false === $label_result) {
            throw new \Exception('数据转换失败【标签数据转换失败】');
        }
    }


    /**
     * 获取旧CRM客户数据【线索】
     * @return false|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function _customer()
    {
        $this->_customerClue();
        $this->_customerCompany();
    }


    /**
     * 获取旧CRM客户数据【线索-未绑定】
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function _customerClue()
    {
        $field =
            "cc.id,
            cc.title AS name,
            CASE 
            WHEN am.id IS NULL
                THEN 0
                ELSE am.id
            END AS admin_id,
            cc.labels,
            cl.name as contacts,
            cl.mobile as mobile,
            '' as weixin,
            0 as district1,
            0 as district2,
            0 as district3,
            0 as district,
            cc.com_addr as address,
            cc.addtime as createtime,
            cc.updatetime as updatetime,
            1 as creat_id,
            cc.remark,
            0 as uid,
            0 as utype,
            0 as trade,
            cc.last_visit_time,
            CASE 
            WHEN sc.id IS NULL
                THEN 0
                ELSE sc.id
            END AS last_visit_admin,
            0 as scale";
        $customer_info = Db('crm_customer')
            ->alias('cc')
            ->join('crm_linkman cl', 'cc.master_linkman=cl.id', 'LEFT')
            ->join('crm_visit_log vl', 'cc.last_visit_id=vl.id', 'LEFT')
            ->join('admin am', 'cc.sales_consultant=am.id', 'LEFT')
            ->join('admin sc', 'vl.sc_id=sc.id', 'LEFT')
            ->field($field)
            ->where('cc.uid', '=', 0) //没有UID
            ->where('cc.comid', '=', 0) //没有企业ID
            ->where('cc.deletetime', '=', 0) //没有软删除
            ->where('cc.realdelete', '=', 0) //没有硬删除
            ->where('cc.master_linkman', '<>', 0) //有联系人
            ->where('cl.id', 'not null') //联系人不为空
            ->select();

        if (empty($customer_info)) {
            return false;
        } else {
            $this->_transformCrmClue($customer_info);
        }
    }


    /**
     * 线索数据转换【线索-b2bcrm.CrmClue】
     * @param $customerInfo
     * @return void
     * @throws \Exception
     */
    private function _transformCrmClue($customerInfo)
    {
        if (!is_array($customerInfo)) {
            throw new \Exception('数据转换失败【线索数据格式错误】');
        }
        $clue_result = model('b2bcrm.CrmClue')
            ->allowField(true)
            ->isUpdate(false)
            ->saveAll($customerInfo, false);
        if (false === $clue_result) {
            throw new \Exception('数据转换失败【线索数据转换失败】');
        }
    }


    /**
     * 获取旧CRM客户数据【线索-已绑定】
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function _customerCompany()
    {
        $field =
            "cc.id,
            com.companyname AS name,
            CASE 
            WHEN am.id IS NULL
                THEN 0
                ELSE am.id
            END AS admin_id,
            cc.labels,
            cl.name as contacts,
            cl.mobile as mobile,
            '' as weixin,
            com.district1 as district1,
            com.district2 as district2,
            com.district3 as district3,
            com.district as district,
            cc.com_addr as address,
            cc.addtime as createtime,
            cc.updatetime as updatetime,
            1 as creat_id,
            cc.remark,
            com.uid as uid,
            2 as utype,
            com.trade as trade,
            cc.last_visit_time,
            CASE 
            WHEN sc.id IS NULL
                THEN 0
                ELSE sc.id
            END AS last_visit_admin,
            com.scale as scale,
            1 as is_customer";
        $customer_info = Db('crm_customer')
            ->alias('cc')
            ->join('company com', 'cc.comid=com.id', 'LEFT')
            ->join('crm_linkman cl', 'cc.master_linkman=cl.id', 'LEFT')
            ->join('crm_visit_log vl', 'cc.last_visit_id=vl.id', 'LEFT')
            ->join('admin am', 'cc.sales_consultant=am.id', 'LEFT')
            ->join('admin sc', 'vl.sc_id=sc.id', 'LEFT')
            ->field($field)
            ->where('cc.comid', '>', 0) //有企业ID
            ->where('cc.deletetime', '=', 0) //没有软删除
            ->where('cc.realdelete', '=', 0) //没有硬删除
            ->where('cc.master_linkman', '<>', 0) //有联系人
            ->where('cl.id', 'not null') //联系人不为空
            ->where('com.uid', '>', 0) //有企业信息
            ->select();

        if (empty($customer_info)) {
            return false;
        } else {
            $this->_transformCompany($customer_info);
        }
    }

    /**
     * 线索数据转换【客户-b2bcrm.CrmClue】
     * @param $customerInfo
     * @return void
     * @throws \Exception
     */
    private function _transformCompany($customerInfo)
    {
        if (!is_array($customerInfo)) {
            throw new \Exception('数据转换失败【线索（客户）数据格式错误】');
        }
        $clue_result = model('b2bcrm.CrmClue')
            ->allowField(true)
            ->isUpdate(false)
            ->saveAll($customerInfo, false);

        foreach ($customerInfo as $customer) {
            $result = model('Company')
                ->allowField(true)
                ->isUpdate(true)
                ->save(
                    ['clue_id' => $customer['id'],
                        'admin_id' => $customer['admin_id']
                    ],
                    [
                        'uid' => $customer['uid']
                    ]
                );
            if (false === $result){
                throw new \Exception('数据转换失败【企业信息更新失败】');
            }
        }

        if (false === $clue_result) {
            throw new \Exception('数据转换失败【线索（客户）数据转换失败】');
        }
    }


    /**
     * 获取旧CRM客户联系人数据【联系人】
     * @return false|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function _linkman()
    {
        $field =
            "cl.id as id,
            com.id as comid,
            com.uid as uid,
            cl.name as contact,
            cl.mobile as mobile,
            '' as weixin,
            cl.telephone as telephone,
            cl.qq as qq,
            cl.email as email,
            cl.gender as sex";
        $linkman_info = model('crm.crmLinkman')
            ->alias('cl')
            ->join('crm_customer cc', 'cl.cid=cc.id', 'LEFT')
            ->join('company com', 'cc.comid=com.id', 'LEFT')
            ->field($field)
            ->where('cc.comid', '>', 0) //线索存在
            ->where('com.uid', '>', 0) //企业存在
            ->select();
        $linkman_info = collection($linkman_info);

        if ($linkman_info->isEmpty()) {
            return false;
        } else {
            $linkman_info = $linkman_info->toArray();
            $this->_transformCompanyContact($linkman_info);
        }
    }


    /**
     * 联系人数据转换【联系人-b2bcrm.CrmCompanyContact】
     * @param $linkmanInfo
     * @return void
     * @throws \Exception
     */
    private function _transformCompanyContact($linkmanInfo)
    {
        if (!is_array($linkmanInfo)) {
            throw new \Exception('数据转换失败【企业联系人数据格式错误】');
        }
        $linkman_result = model('b2bcrm.CrmCompanyContact')
            ->allowField(true)
            ->isUpdate(false)
            ->saveAll($linkmanInfo, false);
        if (false === $linkman_result) {
            throw new \Exception('数据转换失败【企业联系人数据转换失败】');
        }
    }


    /**
     * 获取旧CRM跟进记录【跟进记录】
     * @return false|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function _visitLog()
    {
        $field =
            "vl.id as id,
            CASE WHEN com.id>0
                THEN 2
                ELSE 1
            END AS `type`,
            vl.cid as clue_id,
            cc.comid as relation_id,
            cc.uid as uid,
            vl.sc_id as admin_id,
            CASE WHEN com.id>0
                THEN 1
                ELSE 0
            END AS utype,
            vl.method as mode,
            vl.result,
            vl.addtime as create_time,
            0 as next_time,
            cl.id as linkman_id,
            cl.name as link_man,
            cl.mobile as link_mobile";
        $visit_log = model('crm.CrmVisitLog')
            ->alias('vl')
            ->join('crm_customer cc', 'vl.cid=cc.id', 'LEFT')
            ->join('company com', 'cc.comid=com.id', 'LEFT')
            ->join('crm_linkman cl', 'vl.linkman=cl.id', 'LEFT')
            ->join('admin a', 'a.id=vl.sc_id', 'LEFT')
            ->field($field)
            ->where('vl.result', '<>', '') //跟进结果不为空
            ->where('cc.deletetime', '=', 0) //线索未软删除
            ->where('cc.realdelete', '=', 0) //线索未硬删除
            ->where('cl.id', '<>', '0') //线索联系人存在
            ->where('a.id', '<>', '0') //线索跟进人admin存在
            ->where('cc.id', '<>', '0') //线索（客户）存在
            ->select();
        $visit_log = collection($visit_log);

        if ($visit_log->isEmpty()) {
            return false;
        } else {
            $visit_log = $visit_log->toArray();
            foreach ($visit_log as $id => $log) {
                $result = strip_tags(html_entity_decode($log['result']));
                $result = strtr($result, array_flip(get_html_translation_table(HTML_ENTITIES, ENT_QUOTES)));
                $result = preg_replace("/(\s|\ \;|　|\xc2\xa0)/", "", $result);
                if (empty($result)) {
                    unset($visit_log[$id]);
                } else {
                    $visit_log[$id]['result'] = $result;
                }
            }
            $this->_transformCrmFollowUp($visit_log);
        }
    }


    /**
     * 跟进记录数据转换【跟进记录-b2bcrm.CrmFollowUp】
     * @param $visitLog
     * @return void
     * @throws \Exception
     */
    private function _transformCrmFollowUp($visitLog)
    {
        if (!is_array($visitLog)) {
            throw new \Exception('数据转换失败【跟进记录数据格式错误】');
        }
        $visitlog_result = model('b2bcrm.CrmFollowUp')
            ->allowField(true)
            ->isUpdate(false)
            ->saveAll($visitLog, false);
        if (false === $visitlog_result) {
            throw new \Exception('数据转换失败【跟进记录数据转换失败】');
        }
    }
}