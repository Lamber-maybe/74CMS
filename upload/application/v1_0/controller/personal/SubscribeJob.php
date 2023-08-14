<?php
/**
 * 职位订阅
 */
namespace app\v1_0\controller\personal;

class SubscribeJob extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(2);
        $this->interceptPersonalResume();
    }
    public function index(){
        $info = model('SubscribeJob')->where('uid',$this->userinfo->uid)->find();
        $info = $info===null?null:$info->toArray();
        $this->ajaxReturn(200,'获取数据成功',$info);
    }
    public function submit()
    {
        $input_data = [
            'uid' => $this->userinfo->uid,
            'district1' => input('post.district1/d', 0, 'intval'),
            'district2' => input('post.district2/d', 0, 'intval'),
            'district3' => input('post.district3/d', 0, 'intval'),
            'category1' => input('post.category1/d', 0, 'intval'),
            'category2' => input('post.category2/d', 0, 'intval'),
            'category3' => input('post.category3/d', 0, 'intval'),
            'minwage' => input('post.minwage/d', 0, 'intval'),
            'maxwage' => input('post.maxwage/d', 0, 'intval'),
            'pushtime' => input('post.pushtime/d',0,'intval')
        ];
        $input_data['category'] =
            $input_data['category3'] > 0
                ? $input_data['category3']
                : ($input_data['category2'] > 0
                    ? $input_data['category2']
                    : $input_data['category1']);
        $input_data['district'] =
            $input_data['district3'] > 0
                ? $input_data['district3']
                : ($input_data['district2'] > 0
                    ? $input_data['district2']
                    : $input_data['district1']);
        $info = model('SubscribeJob')->where('uid',$this->userinfo->uid)->find();
        if($info===null){
            $result = model('SubscribeJob')
                ->allowField(true)
                ->save($input_data);
        }else{
            $result = model('SubscribeJob')
                ->allowField(true)
                ->save($input_data,['uid'=>$this->userinfo->uid]);
        }
        
        if($result===false){
            $this->ajaxReturn(500,model('SubscribeJob')->getError());
        }
        $this->writeMemberActionLog($this->userinfo->uid,'订阅职位');
        $this->ajaxReturn(200,'订阅成功');
    }
    public function cancel(){
        model('SubscribeJob')->where('uid',$this->userinfo->uid)->delete();
        $this->writeMemberActionLog($this->userinfo->uid,'取消订阅职位');
        $this->ajaxReturn(200,'取消订阅成功');
    }
}
