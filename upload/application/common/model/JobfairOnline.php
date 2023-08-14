<?php
namespace app\common\model;

class JobfairOnline extends \app\common\model\BaseModel
{
    public $map_audit = [
        0 => '待审核',
        1 => '已通过',
        2 => '未通过'
    ];

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
        model('AdminLog')->writeLog(
            '添加网络招聘会，标题:' . $jobfairol['title'] . '，举办时间:' . date('Y-m-d', $data['starttime']) . '至' . date('Y-m-d', $data['endtime']),
            $admin,
            0,
            2
        );
        return ['state'=>true,'msg'=>'添加成功','data'=>$jobfairol];
    }
    // 编辑网络招聘会
    public function jobfairOnlineEdit($data,$admin){
        if(false === $reg = $this->allowField(true)->validate(true)->isUpdate(true)->save($data)) {
            return ['state'=>false,'msg'=>$this->getError()];
        }
        $jobfairol = $this->find($data['id']);
        model('AdminLog')->writeLog(
            '修改网络招聘会，标题:' . $jobfairol['title'],
            $admin,
            0,
            3
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
                model('AdminLog')->writeLog(
                    '删除网络招聘会，标题:' . implode('；', array_values($list)),
                    $admin,
                    0,
                    4
                );
            } catch (\Exception $e) {
                return array('state'=>false,'msg'=>$e->getMessage());
            }
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
        $jobfair = $this->find($jobfair_id);
        $log_field = '批量置顶网络招聘会参会企业，标题:' . $jobfair['title'] . '；';
        $company_list = model('Company')->whereIn('uid', $uid)->column('id,companyname');
        foreach ($company_list as $c_id => $c_name) {
            $log_field .= '{' . $c_name . '}(企业ID:' . $c_id . ')；';
        }
        $log_field .= '状态:' . $this->map_source[$stick];
        model('AdminLog')->writeLog(
            $log_field,
            $admin,
            0,
            1
        );
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
        $jobfair = $this->find($id);
        $log_field = '设置网络招聘会微信直面为客服，标题:' . $jobfair['title'] . '；';
        $company_list = model('Company')->whereIn('uid', $uid)->column('id,companyname');
        foreach ($company_list as $c_id => $c_name) {
            $log_field .= '{' . $c_name . '}(企业ID:' . $c_id . ')；';
        }
        model('AdminLog')->writeLog(
            $log_field,
            $admin,
            0,
            1
        );
        return ['state'=>true,'msg'=>'保存成功'];
    }
    // 添加参会企业
    public function participateAdd($data, $admin){
        $exhibitors = model('JobfairOnlineParticipate')->where(['jobfair_id'=>$data['jobfair_id'],'uid'=>$data['uid']])->find();
        if($exhibitors!==null){
            return ['state'=>false,'msg'=>'已经报名过此招聘会'];
        } 
        try {
            $jobfair = $this->find($data['jobfair_id']);
            if (empty($jobfair)) {
                return ['state' => false, 'msg' => '要报名的招聘会不存在'];
            }
            model('JobfairOnlineParticipate')->insert($data);
            model('AdminLog')->writeLog(
                '网络招聘会添加参会企业，标题:' . $jobfair['title'],
                $admin,
                0,
                1
            );
        } catch (\Exception $e) {
            return ['state'=>false,'msg'=>$e->getMessage()];
        }
        return ['state'=>true,'msg'=>'添加成功！'];
    }
    // 参会状态
    public function setStatus($jobfair_id,$uid,$audit,$admin){
        try {
            $jobfair = $this->find($jobfair_id);
            model('JobfairOnlineParticipate')
                ->where('jobfair_id',$jobfair_id)
                ->where(['uid' => ['in', $uid]])
                ->setField('audit', $audit);
            $log_field = '批量设置网络招聘会企业参会状态，标题:' . $jobfair['title'] . '；';
            $company_list = model('Company')->whereIn('uid', $uid)->column('id,companyname');
            foreach ($company_list as $c_id => $c_name) {
                $log_field .= '{' . $c_name . '}(企业ID:' . $c_id . ')；';
            }
            $log_field .= '参会状态:' . $this->map_audit[$audit];
            model('AdminLog')->writeLog(
                $log_field,
                $admin,
                0,
                6
            );
        } catch (\Exception $e) {
            return ['state'=>false,'msg'=>$e->getMessage()];
        }
        return ['state'=>true,'msg'=>'保存成功'];
    }
    // 删除参会
    public function participateDelete($jobfair_id,$uid,$admin) {
        try {
            model('JobfairOnlineParticipate')
                ->where('jobfair_id',$jobfair_id)
                ->where('uid', 'in', $uid)->delete();
            $jobfair = $this->find($jobfair_id);
            $log_field = '批量删除网络招聘会参会企业，标题:' . $jobfair['title'] . '；';
            $company_list = model('Company')->whereIn('uid', $uid)->column('id,companyname');
            foreach ($company_list as $c_id => $c_name) {
                $log_field .= '{' . $c_name . '}(企业ID:' . $c_id . ')；';
            }
            model('AdminLog')->writeLog(
                $log_field,
                $admin,
                0,
                4
            );
        } catch (\Exception $e) {
            return ['state'=>false,'msg'=>$e->getMessage()];
        }
        return ['state'=>true,'msg'=>'保存成功'];
    }
    // 设置微信直面为客服
    public function qrService($jobfair_id,$uid,$admin) {
        try {
            model('JobfairOnlineParticipate')
                ->where('jobfair_id',$jobfair_id)
                ->where('uid', 'in', $uid)
                ->setField('qrcode',0);
            $jobfair = $this->find($jobfair_id);
            $log_field = '批量设置网络招聘会微信直面为客服，标题:' . $jobfair['title'] . '；';
            $company_list = model('Company')->whereIn('uid', $uid)->column('id,companyname');
            foreach ($company_list as $c_id => $c_name) {
                $log_field .= '{' . $c_name . '}(企业ID:' . $c_id . ')；';
            }
            model('AdminLog')->writeLog(
                $log_field,
                $admin,
                0,
                1
            );
        } catch (\Exception $e) {
            return ['state'=>false,'msg'=>$e->getMessage()];
        }
        return ['state'=>true,'msg'=>'保存成功'];
    }
}
