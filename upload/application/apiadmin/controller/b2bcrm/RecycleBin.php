<?php
/**
 * CRM回收站
 */

namespace app\apiadmin\controller\b2bcrm;

use app\common\controller\Backend;
use think\Db;


class RecycleBin extends Backend
{
    /**
     * 回收站列表
     * @return void
     */
    public function index()
    {
        $page_num = input('post.page_num/d', 1, 'intval');
        $page_size = input('post.page_size/d', 10, 'intval');
        $keyword = input('post.keyword/s', '', 'trim');

        $map = array();
        // 只显示移动到回收站的数据
        $map['status'] = ['=', 1];

        //  1.关键字
        if (isset($keyword) && !empty($keyword)) {
            //  关键字检索[公司名称]
            $map['companyname'] = ['like', '%' . $keyword . '%'];
        }

        //  2.排序
        $order = ['id DESC'];

        // 查询字段
        $field = 'id as recycle_bin_id,
        companyname,
        life_cycle_name,
        mobile,
        contact,
        contact_mobile,
        jobs_num,
        job_apply_count,
        refreshtime,
        reg_time,
        last_visit_time,
        create_time,
        operate_id,
        operate_admin,
        setmeal_name';

        try {
            $list = model('b2bcrm.CrmRecycleBin')
                ->getList($map, $order, $page_num, $page_size, $field);
            if (false === $list) {
                throw new \Exception(model('b2bcrm.CrmRecycleBin')->getError());
            }
        } catch (\Exception $e) {
            $this->ajaxReturn(500, $e->getMessage());
        }

        $this->ajaxReturn(200, 'SUCCESS', $list);
    }


    /**
     * 移动到回收站
     * @return void
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function put()
    {
        $company_id = input('post.company_id/d', 0, 'intval');
        if ($company_id == 0) {
            $this->ajaxReturn(500, '请选择企业');
        }

        if ($this->admininfo->access_delete == 0)
        {
            $this->ajaxReturn(500, '当前管理员没有删除数据权限');
        }

        // 企业基础信息
        $company_info = model('Company')->alias('c')
            ->join('CrmLifeCycle clc', 'clc.id=c.life_cycle_id', 'LEFT')
            ->join('Member m', 'c.uid=m.uid', 'LEFT')
            ->join('CompanyContact cc', 'cc.comid=c.id', 'LEFT')
            ->join('MemberSetmeal ms', 'ms.uid=c.uid', 'LEFT')
            ->join('setmeal s', 's.id=ms.setmeal_id', 'LEFT')
            ->join('job_apply ja', 'ja.company_uid=c.uid', 'LEFT')
            ->join('job_search_rtime jsr', 'jsr.uid=c.uid', 'LEFT')
            ->where('c.id', $company_id)
            ->field("c.id as cid,c.uid,c.companyname,c.life_cycle_id,c.admin_id,c.last_visit_time,c.refreshtime,c.addtime,
            clc.name as life_cycle_name,        
            m.mobile,m.reg_time,m.last_login_time,
            ifnull(cc.contact,'') as contact,ifnull(cc.mobile,'') as contact_mobile,
            ms.deadline as setmeal_deadline,ms.setmeal_id,
            s.name as setmeal_name,
            count(DISTINCT ja.id) as job_apply_count,
            count(DISTINCT jsr.id) as jobs_num")
            ->find();
        if (empty($company_info)) {
            $this->ajaxReturn(500, '企业不存在');
        }

        if (empty($company_info['uid']) || empty($company_info['cid'])) {
            $this->ajaxReturn(500, '企业信息错误');
        }

        if (!isset($company_info) || empty($company_info)) {
            $this->ajaxReturn(500, '企业信息异常');
        } else {
            $company_info = $company_info->toArray();
            $company_info['operate_id'] = $this->admininfo->id;
            $company_info['operate_admin'] = $this->admininfo->username;
        }

        Db::startTrans();
        try {
            // a：写入回收站表
            $put_result = model('b2bcrm.CrmRecycleBin')->put($company_info);
            if (false === $put_result) {
                throw new \Exception(model('b2bcrm.CrmRecycleBin')->getError());
            } else {
                $recycle_bin_id = model('b2bcrm.CrmRecycleBin')->getLastInsID();
            }

            // b:将所有表数据写入临时表
            $backup_result = model('b2bcrm.CrmBackUp')->backUpByUid($company_info['uid'], $recycle_bin_id);
            if (false === $backup_result) {
                throw new \Exception(model('CrmBackUp')->getError());
            }


            // c:删除所有表数据
            $del_result = model('Member')->deleteMemberByUids([$company_info['uid']]);
            if (false === $del_result) {
                throw new \Exception(model('Member')->getError());
            }

            // 写入操作日志表
            $log_result = model('AdminLog')->writeLog(
                '删除{' . $company_info['companyname'] . '}(企业ID:' . $company_info['cid'] . ')，企业会员无法登录，相关信息隐藏，可在CRM回收站恢复',
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }

        $this->ajaxReturn(200, '移动到回收站成功');

    }


    /**
     * 删除回收站
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function del()
    {
        $recycle_bin_id = input('post.recycle_bin_id/d', 0, 'intval');
        if ($recycle_bin_id == 0) {
            $this->ajaxReturn(500, '请选择要删除的回收站企业');
        }

        $recycle_bin_info = model('b2bcrm.CrmRecycleBin')
            ->where('id', $recycle_bin_id)
            ->find();

        if (!isset($recycle_bin_info) || empty($recycle_bin_info)) {
            $this->ajaxReturn(500, '要删除的回收站企业不存在');
        }

        // 回收站状态【1:放入回收站;2:已恢复;3:已删除;】
        switch ($recycle_bin_info['status']) {
            case 1:
                break;

            case 2:
                $this->ajaxReturn(500, '要删除的企业已从回收站恢复');

            case 3:
                $this->ajaxReturn(500, '要删除的企业已从回收站删除');

            default:
                $this->ajaxReturn(500, '要删除的企业已从回收站状态异常');

        }

        Db::startTrans();
        try {
            // a：更新回收站表
            $del_result = model('b2bcrm.CrmRecycleBin')->del($recycle_bin_id);
            if (false === $del_result) {
                throw new \Exception(model('b2bcrm.CrmRecycleBin')->getError());
            }

            // b:将临时表所有数据清空
            $clear_result = model('b2bcrm.CrmBackUp')->clear($recycle_bin_id);
            if (false === $clear_result) {
                throw new \Exception(model('b2bcrm.CrmBackUp')->getError());
            }

            // c:写入操作日志表
            $log_result = model('AdminLog')->writeLog(
                'CRM回收站删除{' . $recycle_bin_info['companyname'] . '}(企业ID:' . $recycle_bin_info['cid'] . ')，数据已彻底删除，无法恢复',
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }

        $this->ajaxReturn(200, '回收站删除成功');
    }

    /**
     * 恢复回收站
     * @return void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function recover()
    {
        $recycle_bin_id = input('post.recycle_bin_id/d', 0, 'intval');
        if ($recycle_bin_id == 0) {
            $this->ajaxReturn(500, '请选择要恢复的回收站企业');
        }

        $recycle_bin_info = model('b2bcrm.CrmRecycleBin')
            ->where('id', $recycle_bin_id)
            ->find();

        if (!isset($recycle_bin_info) || empty($recycle_bin_info)) {
            $this->ajaxReturn(500, '要恢复的回收站企业不存在');
        }

        // 回收站状态【1:放入回收站;2:已恢复;3:已删除;】
        switch ($recycle_bin_info['status']) {
            case 1:
                break;

            case 2:
                $this->ajaxReturn(500, '要恢复的企业已从回收站恢复');

            case 3:
                $this->ajaxReturn(500, '要恢复的企业已从回收站删除');

            default:
                $this->ajaxReturn(500, '要恢复的企业已从回收站状态异常');

        }

        Db::startTrans();
        try {
            // a：更新回收站表【恢复】
            $recover_result = model('b2bcrm.CrmRecycleBin')->recover($recycle_bin_id);
            if (false === $recover_result) {
                throw new \Exception(model('b2bcrm.CrmRecycleBin')->getError());
            }

            // b:将临时表所有数据还原
            $restore_result = model('b2bcrm.CrmBackUp')->restore($recycle_bin_id);
            if (false === $restore_result) {
                throw new \Exception(model('b2bcrm.CrmBackUp')->getError());
            }

            // e:将临时表中的数据清空
            $clear_result = model('b2bcrm.CrmBackUp')->clear($recycle_bin_id);
            if (false === $clear_result) {
                throw new \Exception(model('b2bcrm.CrmBackUp')->getError());
            }

            // c:写入操作日志表
            $log_result = model('AdminLog')->writeLog(
                'CRM回收站对{' . $recycle_bin_info['companyname'] . '}(企业ID:' . $recycle_bin_info['cid'] . ')还原，企业恢复成功',
                $this->admininfo,
                0,
                1
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            //提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }

        $this->ajaxReturn(200, '回收站恢复成功');
    }
}