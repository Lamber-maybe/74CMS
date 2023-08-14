<?php
namespace app\common\model;

class MemberSetmealOpenLog extends \app\common\model\BaseModel
{
    public $open_type_arr = [1=>'注册赠送',2=>'自主开通',3=>'后台开通'];
    /**
     * 写入套餐开通日志
     * $data = [
     *     'uid'=>0,
     *     'setmeal_id'=>'',
     *     'setmeal_name'=>'',
     *     'order_id'=>0,
     *     'admin_id'=>0
     * ]
     */
    public function record($data){
        if($data['order_id']==0 && $data['admin_id']==0){
            $type = 1;
        }else if($data['order_id']>0 && $data['admin_id']==0){
            $type = 2;
        }else{
            $type = 3;
        }
        if($data['admin_id']>0){
            $admin = model('Admin')->where('id',$data['admin_id'])->find();
            $admin_username = $admin->username;
        }else{
            $admin_username = '';
        }
        
        $insertData = [
            'uid'=>$data['uid'],
            'setmeal_id'=>$data['setmeal_id'],
            'setmeal_name'=>$data['setmeal_name'],
            'type'=>$type,
            'type_cn'=>$this->open_type_arr[$type],
            'order_id'=>$data['order_id'],
            'admin_id'=>$data['admin_id'],
            'admin_username'=>$admin_username,
            'addtime'=>time()
        ];
        $this->save($insertData);
    }
}
