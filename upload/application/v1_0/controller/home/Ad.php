<?php
namespace app\v1_0\controller\home;

class Ad extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $alias_arr = input('post.alias/a', []);
        if (empty($alias_arr)) {
            $this->ajaxReturn(500, '请选择广告位');
        }
        $category_arr = model('AdCategory')->whereIn('alias', $alias_arr)->column('id,alias,ad_num', 'id');
        if (!$category_arr) {
            $this->ajaxReturn(500, '没有找到对应的广告位');
        }

        $cid_arr = [];
        foreach ($category_arr as $key => $value) {
            $cid_arr[] = $value['id'];
        }
        if (empty($cid_arr)) {
            $this->ajaxReturn(500, '没有找到对应的广告位');
        }

        $timestamp = time();
        $dataset = model('Ad')
            ->field('id,cid,imageid,imageurl,target,link_url,inner_link,inner_link_params,company_id')
            ->where('is_display', 1)
            ->whereIn('cid', $cid_arr)
            ->where('starttime', '<=', $timestamp)
            ->where(function ($query) use ($timestamp) {
                $query->where('deadline', '>=', $timestamp)->whereOr('deadline', 0);
            })
            ->order('sort_id desc,id desc')
            ->select();
        $image_id_arr = $image_arr = [];
        $list = [];
        foreach ($dataset as $key => $value) {
            $arr = $value->toArray();
            $arr['imageid'] > 0 && ($image_id_arr[] = $arr['imageid']);
            $list[] = $arr;
        }
        if (!empty($image_id_arr)) {
            $image_arr = model('Uploadfile')->getFileUrlBatch($image_id_arr);
        }

        $return['items'] = [];
        foreach ($list as $key => $value) {
            $value['image_src'] = isset($image_arr[$value['imageid']])
            ? $image_arr[$value['imageid']]
            : $value['imageurl'];
            if (isset($return['items'][$category_arr[$value['cid']]['alias']]) && count($return['items'][$category_arr[$value['cid']]['alias']]) >= $category_arr[$value['cid']]['ad_num']) {
                continue;
            }
            $arr = [];
            $arr['image_src'] = $value['image_src'];
            $arr['link_url'] = $value['link_url'];
            $arr['inner_link'] = $value['inner_link'];
            $arr['inner_link_params'] = $value['inner_link_params'];
            $arr['company_id'] = $value['company_id'];
            $arr['web_link_url'] = model('Ad')->handlerWebLink($value,config('global_config.sitedomain'));
            $return['items'][$category_arr[$value['cid']]['alias']][] = $arr;
        }
        foreach ($category_arr as $key => $value) {
            if (!isset($return['items'][$value['alias']])) {
                $return['items'][$value['alias']] = [];
            }
        }
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
}
