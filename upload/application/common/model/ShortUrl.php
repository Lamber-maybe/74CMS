<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/7/22
 * Time: 9:08
 */

namespace app\common\model;

class ShortUrl extends BaseModel
{
    public function genCode(){
        $key_str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $s = str_pad($key_str, strlen($key_str)*4, $key_str);
        $s = str_shuffle($s);

        $arr = [];
        for($i=0; $i<strlen($s); $i++){
            $j = $i+1;
            if($j*2<strlen($s)) {
                $arr[] = substr($s, $i*2, 2);
            }else{
                break;
            }
            if($j*3<strlen($s)) {
                $arr[] = substr($s, $i*3, 3);
            }else{
                continue;
            }
            if($j*4<strlen($s)) {
                $arr[] = substr($s, $i*4, 4);
            }else{
                continue;
            }
            if($j*5<strlen($s)) {
                $arr[] = substr($s, $i*5, 5);
            }else{
                continue;
            }
        }

        $arr = array_unique($arr);
        $exist = $this->where(['code'=>['in', $arr], 'endtime'=>[['gt', time()], ['eq', 0], 'or']])->column('code');

        if($exist){
            $arr = array_diff($arr, $exist);
        }

        $best = false;
        foreach($arr as $v){
            if($best){
                if(strlen($v)<strlen($best)){
                    $best = $v;
                }
            }else{
                $best = $v;
            }
        }
        if(!$best)exception('生成失败,请重试');
        return $best;
    }

    public function admin(){
        return $this->hasOne(Admin::class, 'id', 'admin_id')->bind(['admin_name'=>'username']);
    }

    public function getValidUrl($code){
        $row = $this->where(['endtime'=>['gt', time()], 'code'=>trim($code)])->find();
        return $row;
    }

    public function saveOrAdd($id, $url, $remark, $endtime=0, $admin=null){
        if(!$endtime){
            $endtime = 0;
        }else{
            $endtime = strtotime($endtime);
        }
        $data = ['url'=>$url, 'endtime'=>$endtime, 'remark'=>$remark];
        if($id){
            return $this->save($data, ['id'=>$id]);
        }else{
            $code = $this->genCode();
            $data['code'] = $code;
            $data['admin_id'] = $admin ? $admin->id: 0;
            $data['addtime'] = time();
            return $this->save($data);
        }
    }

    public function gen($url, $remark='', $endtime=0){
        $data = $this->where(['endtime'=>0, 'url'=>$url])->find();
        if(!$data || !isset($data['code'])){
            $code = $this->genCode();
            $data = [
                'code'=>$code,
                'url' => $url,
                'remark' => $remark,
                'endtime' => $endtime,
                'addtime' => time()
            ];
            $this->insert($data);
        }
        return rtrim(config('global_config.sitedomain'), '/'). '/s/'. $data['code'];
    }

    public function genCode4Array(&$arr, $orgKey, $newKey, $remark=''){
        foreach($arr as &$v){
            $url = isset($v[$orgKey])?$v[$orgKey]:false ;
            if($url){
                $v[$newKey] = $this->gen($url, $remark);
            }
        }
    }

    public function getValidByCode($code){
        $row = $this->where(['code'=>$code, 'endtime'=>[['gt', time()], ['eq', 0], 'or']])->find();
        return $row;
    }

    public function getList($page, $pagesize){
        $where = [];
        $total = $this->where($where)->count();
        $list = $this->with('admin')->where($where)->order('id desc')->limit(($page-1)*$pagesize, $pagesize)->select();

        return [
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'pagesize' => $pagesize
        ];
    }

}
