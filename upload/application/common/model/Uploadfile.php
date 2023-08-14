<?php
namespace app\common\model;

class Uploadfile extends \app\common\model\BaseModel
{
    /**
     * 获取文件显示url
     */
    public function getFileUrl($id)
    {
        $id = intval($id);
        $url = '';
        if ($id > 0) {
            $file = $this->where(['id' => ['eq', $id]])->find();
            if ($file !== null) {
                $url = make_file_url($file['save_path'], $file['platform']);
            }
        }
        return $url;
    }
    /**
     * 批量获取文件显示url
     */
    public function getFileUrlBatch($id_arr)
    {
        $id_arr = is_array($id_arr) ? $id_arr : [$id_arr];
        $url = [];
        if (!empty($id_arr)) {
            $file_arr = $this->where(['id' => ['in', $id_arr]])->column(
                'id,save_path,platform'
            );
            foreach ($file_arr as $key => $value) {
                $url[$key] = make_file_url(
                    $value['save_path'],
                    $value['platform']
                );
            }
        }
        return $url;
    }

    public function getFileUrlBatch2(&$target, $field, $newField='')
    {
        $id_arr = [];
        if(!empty($target) && is_array($target)){
            foreach($target as $v){
                if(isset($v[$field]) && $v[$field]>0){
                    $id_arr[] = intval($v[$field]);
                }
            }
        }
        if (!empty($id_arr)) {
            $file_arr = $this->where(['id' => ['in', $id_arr]])->column('id,save_path,platform');
            foreach($target as &$v){
                if(isset($v[$field]) && $v[$field]>0 && isset($file_arr[$v[$field]])){
                    $vv = $file_arr[$v[$field]];
                    $v[$newField ?: $field] = make_file_url($vv['save_path'], $vv['platform']);

                }
            }
        }
        return $target;
    }
}
