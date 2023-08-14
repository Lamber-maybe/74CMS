<?php
namespace app\v1_0\controller\home;

class Hotword extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $limit = input('get.limit/d', 20, 'intval');
        $list = model('Hotword')
            ->field('word,hot')
            ->order('hot desc')
            ->limit($limit)
            ->select();
        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
