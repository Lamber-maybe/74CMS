<?php
namespace app\apiadmin\controller;
use think\Db;

class Tipoff extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $status = input('get.status/s', '', 'trim');
        $reason = input('get.reason/s', '', 'trim');
        $type = input('get.type/d', 1, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        
        if ($status != '') {
            $where['a.status'] = ['eq', intval($status)];
        }
        if ($reason != '') {
            $where['a.reason'] = ['eq', intval($reason)];
        }
        if ($type==2) {
            $where['a.type'] = 2;
        }else{
            $where['a.type'] = 1;
        }
        $join_tablename = 'job';
        $field = 'a.*,b.jobname,c.mobile';
        if($type==2){
            $join_tablename = 'resume';
            $field = 'a.*,b.fullname,c.mobile';
        }

        $total = model('Tipoff')->alias('a')
            ->join(config('database.prefix').$join_tablename.' b','a.target_id=b.id','LEFT')
            ->join(config('database.prefix').'member c','a.uid=c.uid','LEFT')
            ->where($where)
            ->where('b.id','not null')
            ->where('c.uid','not null')
            ->count();
        $list = model('Tipoff')->alias('a')
            ->join(config('database.prefix').$join_tablename.' b','a.target_id=b.id','LEFT')
            ->join(config('database.prefix').'member c','a.uid=c.uid','LEFT')
            ->field($field)
            ->where($where)
            ->where('b.id','not null')
            ->where('c.uid','not null')
            ->order('a.status asc')
            ->page($current_page . ',' . $pagesize)
            ->select();
        foreach ($list as $key => $value) {
            if($value['type']==1){
                $value['target'] = $value['jobname'];
                $value['link'] = url('index/job/show',['id'=>$value['target_id']]);
                $value['reason_cn'] = model('Tipoff')->map_type_job[$value['reason']];
            }else{
                $value['target'] = $value['fullname'];
                $value['link'] = url('index/resume/show',['id'=>$value['target_id']]);
                $value['reason_cn'] = model('Tipoff')->map_type_resume[$value['reason']];
            }
            $list[$key] = $value;
        }

        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function handler()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }
        $status = input('post.status/d', 0, 'intval');

        Db::startTrans();
        try {
            $list = model('Tipoff')
                ->where('id', 'in', $id)
                ->column('id,target_id,type,uid,reason,content,status');

            if (empty($list)) {
                $this->ajaxReturn(500, '没有要处理的数据');
            }

            $status_set = model('Tipoff')->map_status[$status];

            $log_field = '核实举报信息，';

            foreach ($list as $tipoff) {
                if ($tipoff['type'] === 1) {
                    // 职位
                    $log_field .= '举报类型:职位；';
                    $jobname = model('Job')->where('id', $tipoff['target_id'])->value('jobname');
                    $log_field .= '被举报对象:' . $jobname . '(职位ID:' . $tipoff['target_id'] . ')；';
                    $reason = model('Tipoff')->map_type_job[$tipoff['reason']];
                } else {
                    // 简历
                    $log_field .= '举报类型:简历；';
                    $fullname = model('Resume')->where('id', $tipoff['target_id'])->value('fullname');
                    $log_field .= '被举报对象:' . $fullname . '(简历ID:' . $tipoff['target_id'] . ')；';
                    $reason = model('Tipoff')->map_type_resume[$tipoff['reason']];
                }
                if (count($list) === 1) {
                    $log_field .= '原因:' . $reason . '；';
                    $log_field .= '举报内容:' . $tipoff['content'] . '；';
                    $member_mobile = model('Member')->where('uid', $tipoff['uid'])->value('mobile');
                    $log_field .= '举报者:' . $member_mobile . '(UID:' . $tipoff['uid'] . ')，';
                    $status_original = model('Tipoff')->map_status[$tipoff['status']];
                }
            }

            if (count($list) === 1) {
                $log_field .= '核实情况:' . $status_original . '->' . $status_set;
            } else {
                $log_field .= '核实情况:' . $status_set;
            }

            $handler_result = model('Tipoff')->whereIn('id', $id)->setField('status', $status);
            if (false === $handler_result) {
                throw new \Exception(model('Tipoff')->getError());
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                1
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '处理失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '处理成功');
    }
    public function delete()
    {
        $id = input('post.id/a');
        if (!$id) {
            $this->ajaxReturn(500, '请选择数据');
        }

        Db::startTrans();
        try {
            $list = model('Tipoff')
                ->where('id', 'in', $id)
                ->column('id,target_id,type,uid,reason,content,status');

            if (empty($list)) {
                $this->ajaxReturn(500, '没有要处理的数据');
            }

            $log_field = '删除举报信息，';
            foreach ($list as $tipoff) {
                if ($tipoff['type'] === 1) {
                    // 职位
                    $log_field .= '举报类型:职位；';
                    $jobname = model('Job')->where('id', $tipoff['target_id'])->value('jobname');
                    $log_field .= '被举报对象:' . $jobname . '(职位ID:' . $tipoff['target_id'] . ')；';
                    $reason = model('Tipoff')->map_type_job[$tipoff['reason']];
                } else {
                    // 简历
                    $log_field .= '举报类型:简历；';
                    $fullname = model('Resume')->where('id', $tipoff['target_id'])->value('fullname');
                    $log_field .= '被举报对象:' . $fullname . '(简历ID:' . $tipoff['target_id'] . ')；';
                    $reason = model('Tipoff')->map_type_resume[$tipoff['reason']];
                }
                if (count($list) === 1) {
                    $log_field .= '原因:' . $reason . '；';
                    $log_field .= '举报内容:' . $tipoff['content'] . '；';
                    $member_mobile = model('Member')->where('uid', $tipoff['uid'])->value('mobile');
                    $log_field .= '举报者:' . $member_mobile . '(UID:' . $tipoff['uid'] . ')，';
                }
            }

            $del_result = model('Tipoff')->destroy($id);
            if (false === $del_result) {
                throw new \Exception(model('Tipoff')->getError());
            }

            // 日志
            $log_result = model('AdminLog')->writeLog(
                $log_field,
                $this->admininfo,
                0,
                4
            );
            if (false === $log_result) {
                throw new \Exception(model('AdminLog')->getError());
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollBack();
            $this->ajaxReturn(500, '删除失败', ['err_msg' => $e->getMessage()]);
        }

        $this->ajaxReturn(200, '删除成功');
    }
}
