<?php
namespace app\common\model;

class Help extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'addtime'];
    protected $type     = [
        'id'        => 'integer',
        'cid'        => 'integer',
        'is_display' => 'integer',
        'addtime'    => 'integer',
        'sort_id'    => 'integer',
    ];
    protected $insert = ['addtime'];
    protected function setAddtimeAttr()
    {
        return time();
    }
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_help_nav', null);
        });
        self::event('after_delete', function () {
            cache('cache_help_nav', null);
        });
    }
    public function getCache()
    {
        if (false === ($list = cache('cache_help_nav'))) {
            $list = [];
            $category_list = model('HelpCategory')->order('id asc')->column('id,name,is_sys','id');
            foreach ($category_list as $key => $value) {
                $datalist = $this->where('cid',$value['id'])->where('is_display',1)->order('sort_id desc,id asc')->select();
                $arr = [];
                $arr['id'] = $value['id'];
                $arr['title'] = $value['name'];
                /**
                 * 【ID1000244】【bug】后台内容-帮助中心分类下没内容报错
                 * 2022.08.02
                 * 【新增】
                 * $arr['items'] = [];
                 */
                $arr['items'] = [];
                foreach ($datalist as $k => $v) {
                    $arr['items'][] = ['id'=>$v['id'],'title'=>$v['title']];
                }
                $list[] = $arr;
            }
            cache('cache_help_nav', $list);
        }
        return $list;
    }
}
