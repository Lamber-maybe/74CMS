<?php
namespace app\common\model;

class JobfairOnline extends \app\common\model\BaseModel
{

    public $map_source = [
        0 => '取消置顶',
        1 => '置顶'
    ];
    // 添加网络招聘会
    public function jobfairOnlineAdd($data,$admin){
        $data['addtime'] = time();
        try {
            if(false === $reg = $this->allowField(true)->validate(true)->isUpdate(false)->save($data)) {
                throw new \Exception($this->getError());
            }
            if(!$reg || !$this->id) {
                throw new \Exception('发布失败，请重新操作');
            }
            $jobfairol['id'] = $this->id;
            $jobfairol['title'] = $data['title'];
        } catch (\Exception $e) {
            return ['state'=>false,'msg'=>$e->getMessage()];
        }
        model('AdminLog')->record(
            '发布网络招聘会。招聘会ID【' . $jobfairol['id'] . '】;招聘会标题【' . $jobfairol['title'] . '】',
            $admin
        );
        return ['state'=>true,'msg'=>'添加成功','data'=>$jobfairol];
    }
    // 编辑网络招聘会
    public function jobfairOnlineEdit($data,$admin){
        if(false === $reg = $this->allowField(true)->validate(true)->isUpdate(true)->save($data)) {
            return ['state'=>false,'msg'=>$this->getError()];
        }
        $jobfairol = $this->find($data['id']);
        model('AdminLog')->record(
            '编辑网络招聘会。招聘会ID【' . $jobfairol['id'] . '】;招聘会标题【' . $jobfairol['title'] . '】',
            $admin
        );
        return ['state'=>true,'msg'=>'保存成功'];
    }
    // 删除网络招聘会
	public function jobfairOnlineDelete($id,$admin){
        !is_array($id) && $id=array($id);
		$sqlin=implode(",",$id);
        if (fieldRegex($sqlin,'in')){
            $list = $this->where('id','in',$sqlin)->column('id,title');
            $id = array_keys($list);
            try {
                $this->where('id', 'in', $id)->delete();
                model('JobfairOnlineParticipate')->where('jobfair_id', 'in', $id)->delete();
            } catch (\Exception $e) {
                return array('state'=>false,'msg'=>$e->getMessage());
            }
            model('AdminLog')->record(
                '删除网络招聘会。招聘会ID【' . $sqlin . '】;网络招聘会标题【' . implode(',', array_values($list)) . '】',
                $admin
            );
            return array('state'=>true,'msg'=>'删除成功！');
        }else{
            return array('state'=>false,'msg'=>'删除失败,请正确选择网络招聘会！');
        }
    }
    // 置顶
    public function setSticky($jobfair_id,$uid,$stick,$admin){
        model('JobfairOnlineParticipate')
            ->where('jobfair_id', $jobfair_id)
            ->where(['uid' => ['in', $uid]])
            ->setField('stick', $stick);
        return ['state'=>true,'msg'=>'保存成功'];
    }
    // 微信直面
    public function setQrcode($id,$uid,$qrcode,$note,$admin){
        $data = [];
        $data['qrcode'] = $qrcode;
        $data['note'] = $note;
        model('JobfairOnlineParticipate')
            ->where(['jobfair_id' => ['in', $id]])
            ->where(['uid' => ['in', $uid]])
            ->setField($data);
        model('AdminLog')->record(
            '编辑网络招聘会。招聘会ID【' . $id . '】',
            $admin
        );
        return ['state'=>true,'msg'=>'保存成功'];
    }
    // 添加参会企业
    public function participateAdd($data){
        $exhibitors = model('JobfairOnlineParticipate')->where(['jobfair_id'=>$data['jobfair_id'],'uid'=>$data['uid']])->find();
        if($exhibitors!==null){
            return ['state'=>false,'msg'=>'已经报名过此招聘会'];
        } 
        try {
            model('JobfairOnlineParticipate')->insert($data);
        } catch (\Exception $e) {
            return ['state'=>false,'msg'=>$e->getMessage()];
        }
        return ['state'=>true,'msg'=>'添加成功！'];
    }
    // 参会状态
    public function setStatus($jobfair_id,$uid,$audit){
        try {
            model('JobfairOnlineParticipate')
                ->where('jobfair_id',$jobfair_id)
                ->where(['uid' => ['in', $uid]])
                ->setField('audit', $audit);
        } catch (\Exception $e) {
            return ['state'=>false,'msg'=>$e->getMessage()];
        }
        return ['state'=>true,'msg'=>'保存成功'];
    }
    // 删除参会
    public function participateDelete($jobfair_id,$uid) {
        try {
            model('JobfairOnlineParticipate')
                ->where('jobfair_id',$jobfair_id)
                ->where('uid', 'in', $uid)->delete();
        } catch (\Exception $e) {
            return ['state'=>false,'msg'=>$e->getMessage()];
        }
        return ['state'=>true,'msg'=>'保存成功'];
    }
    // 设置微信直面为客服
    public function qrService($jobfair_id,$uid) {
        try {
            model('JobfairOnlineParticipate')
                ->where('jobfair_id',$jobfair_id)
                ->where('uid', 'in', $uid)
                ->setField('qrcode',0);
        } catch (\Exception $e) {
            return ['state'=>false,'msg'=>$e->getMessage()];
        }
        return ['state'=>true,'msg'=>'保存成功'];
    }
}
