<?php
namespace app\common\model;

class Explain extends \app\common\model\BaseModel
{
    protected $readonly = ['id', 'addtime'];
    protected $type     = [
        'id'        => 'integer',
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
            cache('cache_explain_nav', null);
        });
        self::event('after_delete', function () {
            cache('cache_explain_nav', null);
        });
    }
    public function getCache()
    {
        if (false === ($list = cache('cache_explain_nav'))) {
            $list = model('Explain')->where('is_display',1)->order('sort_id desc,id asc')->column('id,title,link_url','id');
            foreach ($list as $key => $value) {
                $list[$key]['link_url'] = $value['link_url']==''?url('index/explain/show',['id'=>$value['id']]):$value['link_url'];
            }
            cache('cache_explain_nav', $list);
        }
        return $list;
    }
}
