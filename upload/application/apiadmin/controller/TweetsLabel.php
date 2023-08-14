<?php
namespace app\apiadmin\controller;
class TweetsLabel extends \app\common\controller\Backend
{
    public function index()
    {
        $list = model('TweetsLabel')
            ->select();
		$item=[];
		foreach($list as $key=>$val){
			if($val['type']==1){
				$item['footer'][] = $val;
			}elseif($val['type']==2){
				$item['content'][] = $val;
			}else{
				$item['other'][] = $val;
			}
		}
        $return['items'] = $item;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
