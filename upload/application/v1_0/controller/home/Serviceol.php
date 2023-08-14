<?php
namespace app\v1_0\controller\home;
class Serviceol extends \app\v1_0\controller\common\Base{
    public function _initialize(){
        parent::_initialize();
    }
    /**
     * 在线客服列表
     */
    public function index(){
        $return = [
            'mobile'=>[],
            'qq'=>[],
            'weixin'=>[]
        ];
        $list = model('ServiceOl')->where('display',1)->order('sort desc')->select();
        foreach ($list as $key => $value) {
            if($value['mobile']!=''){
                $return['mobile'][] = ['value'=>$value['mobile'],'name'=>$value['name']];
            }
            if($value['qq']!=''){
                $return['qq'][] = ['value'=>$value['qq'],'name'=>$value['name']];
            }
            if($value['weixin']!=''){
                $return['weixin'][] = ['value'=>model('Uploadfile')->getFileUrl($value['weixin']),'name'=>$value['name']];
            }
        }
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
}
