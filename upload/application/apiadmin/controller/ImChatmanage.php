<?php
namespace app\apiadmin\controller;

class ImChatmanage extends \app\common\controller\Backend
{
    public function _initialize()
    {
        $this->baseUrl = 'https://imserv.v2.74cms.com';
        $this->config = config('global_config.account_im');
        $this->im_open = config('global_config.im_open');
        if($this->im_open!='1'){
            $this->ajaxReturn(200, '职聊功能已关闭', null);
        }
        if(empty($this->config['app_key'])){
            $this->ajaxReturn(200, 'app_key不能为空', null);
        }
        if(empty($this->config['app_secret'])){
            $this->ajaxReturn(200, 'app_secret不能为空', null);
        }
    }
    public function messageBack()
    {
        $messageid = input('post.messageid/s', '', 'trim');
        $datas = [
            'appkey'=>$this->config['app_key'],
            'appsecret'=>$this->config['app_secret'],
            'messageid'=>$messageid
        ];
        $url = $this->baseUrl.'/message/back';
        $result = https_request($url,$datas);
        $result = json_decode($result,1);
        if($result['code']==500){
            $this->ajaxReturn(500, $result['msg'], null);
        }else{
            $this->ajaxReturn(200, '撤回成功！', $datas);
        }
    }
    public function index()
    {
        $page = input('get.page', 1, 'intval');
        $pagesize = input('get.pagesize', 10, 'intval');
        $url = $this->baseUrl.'/Chatitem/manage';
        $data = [
            'appkey'=>$this->config['app_key'],
            'appsecret'=>$this->config['app_secret'],
            'page'=>$page,
            'pagesize'=>$pagesize
        ];
        $result = https_request($url,$data);
        $result = json_decode($result,1);
        if($result['code']==500){
            $this->ajaxReturn(500, $result['msg'], null);
        }
        $result_list = [];
		if(empty($result['result'])){
			$this->ajaxReturn(200, '获取数据成功！', null);
		}
        foreach($result['result']['list'] as $key=>$val){
            $company = model('company')
                ->where('uid', $val['owner_uid'])
                ->whereor('uid', $val['relate_uid'])
                ->find();
            $val['companyname'] = empty($company['companyname'])?'无效会员':$company['companyname'];
            $resume = model('resume')
                ->where('uid', $val['owner_uid'])
                ->whereor('uid', $val['relate_uid'])
                ->find();
            $val['fullname'] = empty($resume['fullname'])?'无效会员':$resume['fullname'];
            if($company['uid']===$val['owner_uid']){
                $val['com_msg_total'] = $val['owner_total'];
                $val['per_msg_total'] = $val['relate_total'];
            }else{
                $val['per_msg_total'] = $val['owner_total'];
                $val['com_msg_total'] = $val['relate_total'];
            }
            $val['companyname']=$val['companyname']."  (消息数：".$val['com_msg_total'].")";
            $val['fullname']=$val['fullname']."  (消息数：".$val['per_msg_total'].")";
            $result_list[$val['chat_id']] = $val;
        }
        $list = [];
        foreach($result_list as $keys=>$vals){
            $list[] = $vals;
        }
        $res['items'] = $list;
        $res['total'] = $result['result']['total'];
        $res['currentPage'] = $page;
        $res['pagesize'] = $pagesize;
        if($result['code']==500){
            $this->ajaxReturn(500, $result['msg'], null);
        }else{
            $this->ajaxReturn(200, $result['msg'], $res);
        }
    }

    public function messageList()
    {
        $page = input('post.page/d', 1, 'intval');
        $chat_id = input('post.chat_id/s', '', 'trim');
        $datas = [
            'appkey'=>$this->config['app_key'],
            'appsecret'=>$this->config['app_secret'],
            'chat_id'=>$chat_id,
            'page'=>$page
        ];
        $url = $this->baseUrl.'/message/adminlist';
        $result = https_request($url,$datas);
        $result = json_decode($result,1);
        if($result['code']==500){
            $this->ajaxReturn(500,$result['msg']);
        }	
        $target_uid = $result['result']['other_uid'];
        $self_uid = $result['result']['self_uid'];
        $dataset = ($result['result']['items'] && count($result['result']['items'])>0)?$result['result']['items']:[];
        $userinfo = model('Member')->where('uid',$self_uid)->find();
        $target_member = model('Member')->where('uid',$target_uid)->find();
        if(!empty($userinfo)){
            $self_disable_im = $userinfo['disable_im'];
        }else{
            $self_disable_im = 0;
        }
        if(!empty($target_member)){
            $target_disable_im = $target_member['disable_im'];
        }else{
            $target_disable_im = 0;
        }
        $return = [];
        if($userinfo['utype']==1){
            $self = model('Company')->where('uid',$userinfo['uid'])->field('logo,companyname')->find();
            $self_avatar = model('Uploadfile')->getFileUrl($self['logo']);
            $self_name = $self['companyname'];
            $self_avatar = $self_avatar?$self_avatar:default_empty('logo');

            $other = model('Resume')->where('uid',$target_uid)->field('photo_img,fullname')->find();
            $other_avatar = model('Uploadfile')->getFileUrl($other['photo_img']);
            $other_name = $other['fullname'];
            $other_avatar = $other_avatar?$other_avatar:default_empty('photo');
        }else{
            $self = model('Resume')->where('uid',$userinfo['uid'])->field('photo_img,fullname')->find();
            $self_avatar = model('Uploadfile')->getFileUrl($self['photo_img']);
            $self_name = $self['fullname'];
            $self_avatar = $self_avatar?$self_avatar:default_empty('photo');

            $other = model('Company')->where('uid',$target_uid)->field('logo,companyname')->find();
            $other_avatar = model('Uploadfile')->getFileUrl($other['logo']);
            $other_name = $other['companyname'];
            $other_avatar = $other_avatar?$other_avatar:default_empty('logo');
        }	
        foreach ($dataset as $key => $value) {
            if(1==$value['self_side']){
                $value['avatar'] = $self_avatar;
                $value['name'] = $self_name;
                $value['uid'] = $self_uid;
                $value['disable_im'] = $self_disable_im;
            }else{
                $value['avatar'] = $other_avatar;
                $value['name'] = $other_name;
                $value['uid'] = $target_uid;
                $value['disable_im'] = $target_disable_im;
            }
            $value['name'] = empty($value['name'])?'无效会员':$value['name'];
            $return[] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }
}
