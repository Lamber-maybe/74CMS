<?php
namespace app\v1_0\controller\home;

class Pay extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function scanWxpay()
    {
        $data = 0;
        $oid = input('post.oid/s','','trim');
        if(cache('wxpay_'.$oid)=='ok'){
            cache('wxpay_'.$oid, NULL);
            $data = 1;
        }
        $this->ajaxReturn(200, '获取数据成功', $data);
    }
}
