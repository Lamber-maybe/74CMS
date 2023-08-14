<?php

namespace app\apiadmin\controller\b2burm;

use app\common\controller\Backend;
use think\Db;

class RecycleBin extends Backend
{
    public function index(){
        $current_page = input('post.page/d', 1, 'intval');
        $pagesize = input('post.pagesize/d', 10, 'intval');
        $keyword = input('post.keyword/s', '', 'trim');
        $where = array();
        // 只显示移动到回收站的数据
        $where['status'] = ['=', 1];
        //  1.关键字
        if (isset($keyword) && !empty($keyword)) {
            //  关键字检索[公司名称]
            $where['fullname'] = ['like', '%' . $keyword . '%'];
        }
        $total = model('b2burm.UrmRecycleBin')
            ->where($where)
            ->order('id desc')
            ->count();
        $list = model('b2burm.UrmRecycleBin')
            ->where($where)
            ->order('id desc')
            ->page($current_page,$pagesize)
            ->select();
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
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
        $resume_id = input('post.resume_id/d', 0, 'intval');
        if ($resume_id == 0) {
            $this->ajaxReturn(500, '请选择简历');
        }

        if ($this->admininfo->access_delete == 0)
        {
            $this->ajaxReturn(500, '当前管理员没有删除数据权限');
        }
        $resume = model('Resume')
            ->alias('r')
            ->join('member m','r.uid=m.uid','LEFT')
            ->join('resume_contact c','r.id=c.rid','LEFT')
            ->join('JobApply ja','ja.resume_id=r.id','LEFT')
            ->join('CompanyDownResume cd','cd.resume_id=r.id','LEFT')
            ->join('ViewResume vr','vr.resume_id=r.id','LEFT')
            ->join('CompanyInterview ci','ci.resume_id=r.id','LEFT')
            ->join('ResumeImg ri', 'ri.rid=r.id', 'LEFT')
            ->where('r.id', $resume_id)
            ->field('r.id as rid,
            r.uid,
            r.audit,
            r.sex,
            r.last_visit_time,
            r.fullname,
            r.remark,
            r.high_quality,
            r.education,
            r.enter_job_time,
            r.comment,
            r.is_status_weixin,
            r.is_status_phone,
            r.photo_img,
            r.refreshtime,
            c.mobile as contact_mobile,
            m.mobile as member_mobile,
            m.last_login_time,
            m.reg_time
           ')
            ->find();
        if (empty($resume)) {
            $this->ajaxReturn(500, '简历不存在');
        }else{
            $resume = $resume->toArray();
            $resume['invitation'] = model('CompanyInterview')->alias('ci')
                ->join('job j','ci.jobid = j.id')
                ->where('j.jobname','not null')
                ->where('resume_id',$resume['rid'])
                ->count();
            $resume['viewed'] = model('ViewResume')
                ->where('resume_id',$resume['rid'])
                ->count();
            $resume['downloaded'] = model('CompanyDownResume')
                ->where('resume_id',$resume['rid'])
                ->count();
            $resume['delivery_num'] = model('JobApply')
                ->alias('ja')
                ->join('job j','ja.jobid = j.id')
                ->where('j.jobname','not null')
                ->where('resume_id',$resume['rid'])
                ->count();
            $member_bind = model('MemberBind')->where('uid',$resume['uid'])->find();
            if(empty($member_bind)){
                $resume['is_bind'] = 0;
            }else{
                $resume['is_bind'] = 1;
            }
            $resume['photo_img_src'] = $resume['photo_img'] != 0
                ? model('Uploadfile')->getFileUrl(
                    $resume['photo_img']
                )
                : default_empty('photo', $resume['sex']);
            unset($resume['sex']);
            unset($resume['photo_img']);
            $resume['education_cn'] = isset(
                model('BaseModel')->map_education[$resume['education']]
            )
                ? model('BaseModel')->map_education[$resume['education']]
                : '学历未知';
            $resume['experience_cn'] =
                $resume['enter_job_time'] == 0
                    ? '无经验'
                    : format_date($resume['enter_job_time']);
            //完整度
            $resume['complete_percent'] = isset($resume['rid'])
                ? model('Resume')->countCompletePercent($resume['rid'])
                : 0;
            $resume['follow_num'] = model('b2bcrm.CrmFollowUp')->where('relation_id',$resume['rid'])->count();
            if($resume['last_visit_time'] == null ){
                $resume['final_follow'] = 0;
            }else{
                $resume['final_follow'] = $resume['last_visit_time'];
            }
            $resume['operate_id'] = $this->admininfo->id;
            $resume['operate_admin'] = $this->admininfo->username;
            $resume['operate_time'] = time();
        }
        Db::startTrans();
        try {
            // a：写入回收站表
            $result = model('b2burm.UrmRecycleBin')
                ->allowField(true)
                ->isUpdate(false)
                ->save($resume);
            if (false === $result) {
                throw new \Exception(model('b2burm.UrmRecycleBin')->getError());
            } else {
                $recycle_bin_id = model('b2burm.UrmRecycleBin')->getLastInsID();
            }

            // b:将所有表数据写入临时表
            $backup_result = model('b2burm.UrmBackUp')->backUpByUid($resume['uid'], $recycle_bin_id);
            if (false === $backup_result) {
                throw new \Exception(model('UrmBackUp')->getError());
            }

            // c:删除所有表数据
            $del_result = model('Member')->deleteMemberByUids([$resume['uid']]);
            if (false === $del_result) {
                throw new \Exception(model('Member')->getError());
            }

            // 写入操作日志表
            $log_result = model('AdminLog')->writeLog(
                '全部简历列表删除{' . $resume['fullname'] . '}(简历ID:' . $resume_id . ')，用户无法登录',
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
        $recycle_bin_info = model('b2burm.UrmRecycleBin')
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
            $del_result = model('b2burm.UrmRecycleBin')->del($recycle_bin_id);
            if (false === $del_result) {
                throw new \Exception(model('b2burm.UrmRecycleBin')->getError());
            }

            // b:将临时表所有数据清空
            $clear_result = model('b2burm.UrmBackUp')->clear($recycle_bin_id);
            if (false === $clear_result) {
                throw new \Exception(model('b2burm.UrmBackUp')->getError());
            }

            // c:写入操作日志表
            $log_result = model('AdminLog')->writeLog(
                '简历回收站删除{' . $recycle_bin_info['fullname'] . '}(简历ID:' . $recycle_bin_info['rid'] . ')，数据已彻底删除，无法恢复',
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

        $recycle_bin_info = model('b2burm.UrmRecycleBin')
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
            $recover_result = model('b2burm.UrmRecycleBin')->recover($recycle_bin_id);
            if (false === $recover_result) {
                throw new \Exception(model('b2burm.UrmRecycleBin')->getError());
            }

            // b:将临时表所有数据还原
            $restore_result = model('b2burm.UrmBackUp')->restore($recycle_bin_id);
            if (false === $restore_result) {
                throw new \Exception(model('b2burm.UrmBackUp')->getError());
            }

            // e:将临时表中的数据清空
            $clear_result = model('b2burm.UrmBackUp')->clear($recycle_bin_id);
            if (false === $clear_result) {
                throw new \Exception(model('b2burm.UrmBackUp')->getError());
            }

            // c:写入操作日志表
            $log_result = model('AdminLog')->writeLog(
                '简历回收站对{' . $recycle_bin_info['fullname'] . '}(简历ID:' . $recycle_bin_info['rid'] . ')简历还原，简历恢复成功',
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