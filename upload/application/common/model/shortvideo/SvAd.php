<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/4/26
 * Time: 10:55
 */

namespace app\common\model\shortvideo;

use app\common\model\BaseModel;
use app\common\model\Uploadfile;

class SvAd extends BaseModel
{
    protected $readonly = ['id', 'addtime'];
    protected $type = [
        'id' => 'integer',
        'cid' => 'integer',
        'is_display' => 'integer',
        'click' => 'integer',
        'addtime' => 'integer',
        'sort_id' => 'integer',
        'starttime' => 'integer',
        'deadline' => 'integer',
        'uid' => 'integer',
        'imageid' => 'integer'
    ];
    protected $insert = ['addtime'];
    protected function setAddtimeAttr()
    {
        return time();
    }
    public $innerLinks = [
        ['value' => 'index', 'label' => '首页'],
        ['value' => 'resumelist', 'label' => '简历列表页'],
        ['value' => 'resumeshow', 'label' => '简历详情页'],
    ];
    public function process(&$target){
        $up = new Uploadfile();
        $field = 'imageid';
        $newField = 'imgurl';

        $id_arr = [];
        if(!empty($target) && is_array($target)){
            foreach($target as &$v){
                if(isset($v[$field]) && $v[$field]>0){
                    $id_arr[] = intval($v[$field]);
                }
                $v['web_link_url'] = $this->handlerWebLink($v);
            }
        }
        if (!empty($id_arr)) {
            $file_arr = $up->where(['id' => ['in', $id_arr]])->column('id,save_path,platform');
            foreach($target as &$v){
                if(isset($v[$field]) && $v[$field]>0 && isset($file_arr[$v[$field]])){
                    $vv = $file_arr[$v[$field]];
                    $v[$newField ?: $field] = make_file_url($vv['save_path'], $vv['platform']);

                }
            }
        }
        return $target;
    }
    /**
     * 处理链接
     */
    public function handlerWebLink($item,$domain=''){
        if($item['link_url']!=''){
            return $item['link_url'];
        }else if($item['company_id']>0){
            return $domain.url('index/company/show',['id'=>$item['company_id']]);
        }else if($item['inner_link']!=''){
            $path = '';
            switch ($item['inner_link']) {
                case 'index':
                    $path = 'index/index/index';
                    break;
                case 'resumelist':
                    $path = 'index/resume/index';
                    break;
                case 'resumeshow':
                    $path = 'index/resume/show';
                    break;
                default:
                    $path = '';
                    break;
            }
            if ($path != '') {
                if ($item['inner_link_params'] > 0) {
                    $path = url($path,['id'=>$item['inner_link_params']]);
                }else{
                    $path = url($path);
                }
                return $domain.$path;
            }else{
                return '';
            }
        }
    }
}
