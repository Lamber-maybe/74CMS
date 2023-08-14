<?php
namespace app\apiadmin\controller;
class Tpl extends \app\common\controller\Backend
{
    public function index()
    {
        $list = model('Tpl')
            ->where('type','index')
            ->order('id asc')
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['thumb'] = config('global_config.sitedomain').config('global_config.sitedir').'assets/images/tpl/index/'.$value['alias'].'.jpg';
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }

    //职位企业简历详情模板 zch 2022/8/4
    public function showTpl()
    {
        $type = input('get.type/s','','trim');
        $list = model('Tpl')
            ->where('type',$type)
            ->order('id asc')
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['thumb'] = config('global_config.sitedomain').config('global_config.sitedir').'assets/images/tpl/show/'.$value['type'].'/'.$value['alias'].'.jpg';
        }
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
}
