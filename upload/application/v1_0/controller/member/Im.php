<?php
namespace app\v1_0\controller\member;

class Im extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin();
    }
    public function quickMsglist()
    {
        $utype = input('get.utype', 1, 'intval');
        $list = model('ImQuickmsg')
            ->where('utype', $utype)
            ->order('sort_id desc')
            ->select();
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }
    /**
     * 获取首页导航菜单
     */
    protected function getMenu()
    {
        $list = model('MobileIndexMenu')->getCache();
        return $list;
    }
    /**
     * 获取公告
     */
    protected function getNotice()
    {
        $list = cache('index_notice_list');
        if (!$list) {
            $list = model('Notice')
                ->field('id,title,link_url')
                ->where('is_display', 1)
                ->order('sort_id desc,id desc')
                ->select();
            cache('index_notice_list', $list, 3600);
        }
        return $list;
    }
    /**
     * 热门职位
     */
    protected function getHotword()
    {
        $list = cache('index_hotword_list');
        if (!$list) {
            $list = model('Hotword')
                ->field('word,hot')
                ->order('hot desc')
                ->limit(16)
                ->select();
            cache('index_hotword_list', $list, 3600);
        }
        return $list;
    }
    /**
     * 名企
     */
    protected function getFamous()
    {
        $return = cache('index_famous_list');
        if (!$return) {
            $famous_enterprises_setmeal = config(
                'global_config.famous_enterprises'
            );
            $famous_enterprises_setmeal =
                $famous_enterprises_setmeal == ''
                    ? []
                    : explode(',', $famous_enterprises_setmeal);
            if (empty($famous_enterprises_setmeal)) {
                $this->ajaxReturn(200, '获取数据成功', ['items' => []]);
            }
            $list = model('Company')
                ->alias('c')
                ->join(
                    config('database.prefix') . 'member_setmeal s',
                    's.uid=c.uid',
                    'LEFT'
                )
                ->where('s.id', 'in', $famous_enterprises_setmeal)
                ->field('c.id,c.logo,c.companyname')
                ->order('c.refreshtime desc')
                ->limit(9)
                ->select();
            $job_list = $comid_arr = $logo_id_arr = $logo_arr = [];
            foreach ($list as $key => $value) {
                $comid_arr[] = $value['id'];
                $value['logo'] > 0 && ($logo_id_arr[] = $value['logo']);
            }
            if (!empty($logo_id_arr)) {
                $logo_arr = model('Uploadfile')->getFileUrlBatch($logo_id_arr);
            }
            if (!empty($comid_arr)) {
                $job_data = model('Job')
                    ->where('company_id', 'in', $comid_arr)
                    ->where('is_display', 1)
                    ->column('id,company_id,jobname', 'id');
                foreach ($job_data as $key => $value) {
                    $job_list[$value['company_id']][] = $value['jobname'];
                }
            }
            $return = [];
            foreach ($list as $key => $value) {
                $arr = $value->toArray();
                $arr['logo'] = isset($logo_arr[$value['logo']])
                    ? $logo_arr[$arr['logo']]
                    : default_empty('logo');
                $arr['jobnum'] = isset($job_list[$value['id']])
                    ? count($job_list[$arr['id']])
                    : 0;
                $return[] = $arr;
            }
            cache('index_famous_list', $return, 3600);
        }

        return $return;
    }
    /**
     * 新闻资讯
     */
    protected function getArticle()
    {
        $return = cache('index_article_list');
        if (!$return) {
            $list = model('Article')
                ->field('id,title,thumb,link_url,click,addtime')
                ->where('is_display', 1)
                ->limit(5)
                ->order('sort_id desc,id asc')
                ->select();
            $thumb_id_arr = $thumb_arr = [];
            foreach ($list as $key => $value) {
                $value['thumb'] > 0 && ($thumb_id_arr[] = $value['thumb']);
            }
            if (!empty($thumb_id_arr)) {
                $thumb_arr = model('Uploadfile')->getFileUrlBatch(
                    $thumb_id_arr
                );
            }
            $return = [];
            foreach ($list as $key => $value) {
                $arr = $value->toArray();
                $arr['thumb'] = isset($thumb_arr[$value['thumb']])
                    ? $thumb_arr[$arr['thumb']]
                    : default_empty('thumb');
                $return[] = $arr;
            }
            cache('index_article_list', $return, 3600);
        }
        return $return;
    }
}
