<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/7/28
 * Time: 19:32
 */

namespace app\v1_0\controller\home;

use Think\Exception;

class ShortUrl extends \app\v1_0\controller\common\Base
{
    public function index($code){
        $m = new \app\common\model\ShortUrl();
        $row = $m->getValidByCode($code);
        if(!$row){
            $this->error('链接已失效或不存在', '/');
        }
        $m->where(['id'=>$row['id']])->setInc('pv');
        $this->redirect($row['url']);
    }

    public function genJobShow(){
        $jobId = input('get.jobId/d', 0, 'intval');
        $m = new \app\common\model\ShortUrl();
        $url = $this->sub_site_domain_m . '/job/' . $jobId;
        try{
            $s = $m->gen($url, '系统生成触屏版职位详情短链');
            $this->ajaxReturn(200, 'ok', $s);
        }catch (Exception $e){
            $this->ajaxReturn(500, $e->getMessage());
        }
    }

}
