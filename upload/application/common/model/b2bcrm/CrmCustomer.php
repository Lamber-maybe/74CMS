<?php

namespace app\common\model\b2bcrm;

use app\common\model\BaseModel;
use Think\Db;

class CrmCustomer extends BaseModel
{
    public function getConsumeLog($cid, $type, $page, $pageSize){
        $where = ['uid'=>$cid];
        if(!$type){
            $total = Db::table(config('database.prefix').'member_points_log')->where($where)->count();
            $list = Db::table(config('database.prefix').'member_points_log')->where($where)
                ->order('id desc')->limit(($page-1)*$pageSize, $pageSize)->select();
        }else{
            $where['content'] = ['like', '下载简历-%'];
            $total = Db::table(config('database.prefix').'member_setmeal_log')->where($where)->count();
            $list = Db::table(config('database.prefix').'member_setmeal_log')->where($where)
                ->order('id desc')->limit(($page-1)*$pageSize, $pageSize)->select();
        }
        $total_page = ceil($total / $pageSize);

        return ['list'=>$list, 'total'=>$total,'pageSize'=>$total_page,'current_page'=>$page];
    }
}