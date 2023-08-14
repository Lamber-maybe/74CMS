<?php
/**
 * 委托投递
 */
namespace app\v1_0\controller\personal;

class Entrust extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(2);
        $this->interceptPersonalResume();
    }
    public function index()
    {
        $info = model('Entrust')->where('uid',$this->userinfo->uid)->find();
        if($info===null || $info['deadline']<time()){
            model('Entrust')->where('uid',$this->userinfo->uid)->delete();
            $return = null;
        }else{
            $return = $info;
            $return['days'] = ceil(($return['deadline']-time())/3600/24);
        }
        
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function submit()
    {
        $days = input('post.days/d',0,'intval');
        if($days<=0){
            $this->ajaxReturn(500,'请选择委托天数');
        }
        $info = model('Entrust')->where('uid',$this->userinfo->uid)->find();
        if($info===null){
            $result = model('Entrust')->allowField(true)->save(['uid'=>$this->userinfo->uid,'days'=>$days,'deadline'=>strtotime('+'.$days.'day')]);
        }else{
            $result = model('Entrust')->allowField(true)->save(['days'=>$days,'deadline'=>strtotime('+'.$days.'day')],['uid'=>$this->userinfo->uid]);
        }
        if($result===false){
            $this->ajaxReturn(500,model('Entrust')->getError());
        }
        $this->writeMemberActionLog($this->userinfo->uid,'设置委托投递');
        $this->ajaxReturn(200,'委托成功');
    }
    public function cancel()
    {
        model('Entrust')->where('uid',$this->userinfo->uid)->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'取消委托投递');
        $this->ajaxReturn(200, '取消委托成功');
    }
}
