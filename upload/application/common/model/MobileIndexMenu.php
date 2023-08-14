<?php
namespace app\common\model;

class MobileIndexMenu extends \app\common\model\BaseModel
{
    protected static function init()
    {
        self::event('after_write', function () {
            cache('cache_mobile_index_menu', null);
        });
        self::event('after_delete', function () {
            cache('cache_mobile_index_menu', null);
        });
    }
    public function getCache()
    {
        if (false === ($data = cache('cache_mobile_index_menu'))) {
            $list = $this->where('is_display', 1)
                ->order('sort_id desc,id asc')
                ->column('alias,title,custom_title,icon,link_url');
            $icon_id_arr = $icon_url_arr = [];
            foreach ($list as $key => $value) {
                $value['icon'] > 0 && ($icon_id_arr[] = $value['icon']);
            }
            if (!empty($icon_id_arr)) {
                $icon_url_arr = model('Uploadfile')->getFileUrlBatch(
                    $icon_id_arr
                );
            }
            $data = [];
            foreach ($list as $key => $value) {
                $arr['alias'] = $value['alias'];
                /**
                 * 【ID1000432】
                 * 【优化】链接带参数跳转后没有筛选状态
                 * yx - 2022.11.21
                 * [旧]:
                 * $arr['url'] = $value['link_url'];
                 * [新]:
                 * $arr['url'] = htmlspecialchars_decode($value['link_url']);
                 */
                $arr['url'] = htmlspecialchars_decode($value['link_url']);
                $arr['title'] =
                    $value['custom_title'] == ''
                        ? $value['title']
                        : $value['custom_title'];
                $arr['icon'] = isset($icon_url_arr[$value['icon']])
                    ? $icon_url_arr[$value['icon']]
                    : '';
                $data[] = $arr;
            }
            cache('cache_mobile_index_menu', $data);
        }
        return $data;
    }
}
