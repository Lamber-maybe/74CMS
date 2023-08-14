<?php
namespace app\common\model;

class Poster extends \app\common\model\BaseModel
{
    public function getList($type){
        $list = $this->where('type',$type)->field('id,indexid,name,sort_id,is_display')->order('is_display desc,sort_id desc,id asc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['img_src'] = make_file_url('resource/poster/'.$this->getTypeEn($type).'/'.$value['indexid'].'.jpg');
        }
        return $list;
    }
    public function addOne($data){
        $last_one = $this->where('type',$data['type'])->order('indexid desc')->find();
        if($last_one===null){
            $data['indexid'] = 1;
        }else{
            $data['indexid'] = $last_one['indexid'] + 1;
        }
        if(file_exists(SYS_UPLOAD_PATH.$data['img'])){
            copy(SYS_UPLOAD_PATH.$data['img'],SYS_UPLOAD_PATH.'resource/poster/'.$this->getTypeEn($data['type']).'/'.$data['indexid'].'.jpg');
            @unlink(SYS_UPLOAD_PATH.$data['img']);
        }
        return $this->validate(true)->allowField(true)->save($data);
    }
    public function editOne($data){
        $info = $this->where('id',$data['id'])->find();
        if($data['img']!='' && file_exists(SYS_UPLOAD_PATH.$data['img'])){
            copy(SYS_UPLOAD_PATH.$data['img'],SYS_UPLOAD_PATH.'resource/poster/'.$this->getTypeEn($data['type']).'/'.$info['indexid'].'.jpg');
            @unlink(SYS_UPLOAD_PATH.$data['img']);
        }
        return $this->validate(true)->allowField(true)->save($data,['id'=>$data['id']]);
    }
    public function deleteOne($id){
        return $this->destroy($id);
    }
    protected function getTypeEn($type){
        switch($type){
            case 1:
                return 'job';
            case 2:
                return 'resume';
            case 3:
                return 'company';
            default:
                return 'job';
        }
    }
}
