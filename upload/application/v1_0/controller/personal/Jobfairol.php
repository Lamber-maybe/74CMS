<?php
namespace app\v1_0\controller\personal;
class Jobfairol extends \app\v1_0\controller\common\Base{
    public function _initialize(){
        parent::_initialize();
        $this->checkLogin(2);
        $this->interceptPersonalResume();
    }
    /**
     * [index 已报名的招聘会]
     */
    public function index(){
    	$current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        $list = model('JobfairOnlineParticipate')
                ->alias('a')
                ->field('a.audit,a.addtime,b.id,b.title,b.starttime,b.endtime')
                ->join(config('database.prefix') . 'jobfair_online b', 'a.jobfair_id=b.id', 'left')
                ->where('uid',$this->userinfo->uid)
                ->order('a.addtime desc')
                ->page($current_page, $pagesize)
                ->select();
        $returnlist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['title'] = $value['title'];
            $tmp_arr['starttime'] = $value['starttime'];
            $tmp_arr['endtime'] = $value['endtime'];
            $tmp_arr['audit'] = $value['audit'];
            $tmp_arr['addtime'] = $value['addtime'];
            $returnlist[] = $tmp_arr;
        }
    	$return['items'] = $list;
    	$this->ajaxReturn(200, '获取数据成功', $return);
    }
}