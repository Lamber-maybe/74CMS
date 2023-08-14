<?php
namespace app\apiadmin\controller;

class LinkSubmit extends \app\common\controller\Backend
{
    public function index()
    {
        $type = input('get.type/s', '', 'trim');
        $range = input('get.range/s', 'today', 'trim');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 100, 'intval');
        if (!in_array($range, ['today', 'all'])) {
            $this->ajaxReturn(500, 'range参数错误');
        }
        if (!in_array($type, ['job', 'company', 'article'])) {
            $this->ajaxReturn(500, '请选择提交数据类型');
        }
        $where = [];
        if ($range == 'today') {
            $where['addtime'] = ['EGT', time('today')];
        }
        if ($type == 'job') {
            $total = model('Job')
                ->where($where)
                ->count();
            $list = model('Job')
                ->field('id')
                ->where($where)
                ->order('id asc')
                ->page($current_page . ',' . $pagesize)
                ->select();
        }
        if ($type == 'company') {
            $total = model('Company')
                ->where($where)
                ->count();
            $list = model('Company')
                ->field('id')
                ->where($where)
                ->order('id asc')
                ->page($current_page . ',' . $pagesize)
                ->select();
        }
        if ($type == 'article') {
            $total = model('Article')
                ->where($where)
                ->count();
            $list = model('Article')
                ->field('id')
                ->where($where)
                ->order('id asc')
                ->page($current_page . ',' . $pagesize)
                ->select();
        }
        if (empty($list)) {
            $this->ajaxReturn(500, '没有可提交的链接');
        }
        $urls = [];
        $sitedomain = config('global_config.sitedomain');
        foreach ($list as $key => $value) {
            $urls[] = $sitedomain.url('index/'.$type.'/show',['id' => $value['id']]);
        }
        $site = str_replace('http://', '', $sitedomain);
        $site = str_replace('https://', '', $site);
        $api =
            'http://data.zz.baidu.com/urls?site=' .
            $site .
            '&token=' .
            config('global_config.linksubmit_token');
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain')
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $result = json_decode($result, true);
        if (isset($result['success'])) {
            model('AdminLog')->record(
                '成功推送链接至百度【' . $result['success'] . '条】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '提交成功', [
                'status' => 1,
                'data' => [
                    'max' => 10000,
                    'total' => $total,
                    'success' => $result['success'],
                    'remain' => $result['remain']
                ]
            ]);
        } else {
            if ('over quota' == $result['message']) {
                $this->ajaxReturn(200, '已超过今日配额，明天再试吧', [
                    'status' => 0
                ]);
            }
            $this->ajaxReturn(500, $result['message']);
        }
    }
}
