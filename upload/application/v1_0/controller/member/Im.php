<?php
namespace app\v1_0\controller\member;

class Im extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin();
    }
    public function quickMsglist()
    {
        $utype = input('get.utype', 1, 'intval');
        $list = model('ImQuickmsg')
            ->where('utype', $utype)
            ->order('sort_id desc')
            ->select();
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }
}
