<?php
namespace app\apiadmin\controller;

class SysTool extends \app\common\controller\Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }
    /**
     * 同步企业显示状态
     */
    public function syncCompanyDisplay(){
        if($this->admininfo->access != 'all'){
            $this->ajaxReturn(500, '只有超级管理员才有权限执行此操作');
        }
        
        $pwd = input('post.pwd/s', '', 'trim');
        $validate = validate('Login');
        $validate->processRule();
        if (!$validate->check(['username'=>$this->admininfo->username, 'password'=>$pwd])) {
            $this->ajaxReturn(500, '密码有误');
        }


        $waiting = input('post.waiting/d',1, 'intval');
        $pass = input('post.pass/d',1, 'intval');
        $nopass = input('post.nopass/d',1, 'intval');
        $no_audit = input('post.no_audit/d',1, 'intval');

        //定义需要显示的企业uid数组
        $display_comuid_arr = [];
        //定义需要隐藏的企业uid数组
        $not_display_comuid_arr = [];

        $waiting_comuid_arr = model('Company')
                        ->alias('c')
                        ->join(config('database.prefix').'company_auth a','a.uid=c.uid','LEFT')
                        ->where('c.audit',0)
                        ->where('a.id','not null')
                        ->column('c.uid');
        if(!empty($waiting_comuid_arr)){
            if($waiting==1){
                $display_comuid_arr = array_merge($display_comuid_arr,$waiting_comuid_arr);
            }else{
                $not_display_comuid_arr = array_merge($not_display_comuid_arr,$waiting_comuid_arr);
            }
        }


        $pass_comuid_arr = model('Company')->where('audit',1)->column('uid');
        if(!empty($pass_comuid_arr)){
            if($pass==1){
                $display_comuid_arr = array_merge($display_comuid_arr,$pass_comuid_arr);
            }else{
                $not_display_comuid_arr = array_merge($not_display_comuid_arr,$pass_comuid_arr);
            }
        }

        $nopass_comuid_arr = model('Company')->where('audit',2)->column('uid');
        if(!empty($nopass_comuid_arr)){
            if($nopass==1){
                $display_comuid_arr = array_merge($display_comuid_arr,$nopass_comuid_arr);
            }else{
                $not_display_comuid_arr = array_merge($not_display_comuid_arr,$nopass_comuid_arr);
            }
        }

        
        $no_audit_comuid_arr = model('Company')
                        ->alias('c')
                        ->join(config('database.prefix').'company_auth a','a.uid=c.uid','LEFT')
                        ->where('c.audit',0)
                        ->where('a.id','null')
                        ->column('c.uid');
        if(!empty($no_audit_comuid_arr)){
            if($no_audit==1){
                $display_comuid_arr = array_merge($display_comuid_arr,$no_audit_comuid_arr);
            }else{
                $not_display_comuid_arr = array_merge($not_display_comuid_arr,$no_audit_comuid_arr);
            }
        }


        if(!empty($display_comuid_arr)){
            $display_comuid_arr = array_unique($display_comuid_arr);
            model('Company')->where('uid','in',$display_comuid_arr)->setField('is_display',1);
            //查找出职位索引表中不存在的职位，处理这部分职位为显示状态
            $exits_uid_arr = model('JobSearchRtime')->where('uid','in',$display_comuid_arr)->column('uid');
            if(!empty($exits_uid_arr)){
                $display_comuid_arr = array_diff($display_comuid_arr,$exits_uid_arr);
            }
            $job_id_arr = model('Job')->where('uid','in',$display_comuid_arr)->column('id');
            model('Job')->refreshSearchBatch($job_id_arr);
        }
        if(!empty($not_display_comuid_arr)){
            $not_display_comuid_arr = array_unique($not_display_comuid_arr);
            model('Company')->where('uid','in',$not_display_comuid_arr)->setField('is_display',0);
            model('JobSearchRtime')->where('uid','in',$not_display_comuid_arr)->delete();
            model('JobSearchKey')->where('uid','in',$not_display_comuid_arr)->delete();
        }
        $this->ajaxReturn(200, '同步成功');
    }
    /**
     * 同步企业套餐
     */
    public function syncSetmeal(){
        if($this->admininfo->access != 'all'){
            $this->ajaxReturn(500, '只有超级管理员才有权限执行此操作');
        }
        $setmealId = input('post.setmeal_id/d', 0, 'intval');
        $pwd = input('post.pwd/s', '', 'trim');
        $validate = validate('Login');
        $validate->processRule();
        if (!$validate->check(['username'=>$this->admininfo->username, 'password'=>$pwd])) {
            $this->ajaxReturn(500, '密码有误');
        }
        $n = model('MemberSetmeal')->syncSet($setmealId, $this->admininfo);
        $this->ajaxReturn(200, '同步成功', $n);
    }
}
