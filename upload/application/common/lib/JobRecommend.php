<?php
/**
 * 职位推荐
 */
namespace app\common\lib;
class JobRecommend
{
    protected $subsiteCondition=[];
    protected $category1;
    protected $category2;
    protected $category3;
    protected $trade;
    protected $minwage;
    protected $maxwage;
    protected $district1;
    protected $district2;
    protected $district3;
    protected $nature;
    protected $timestamp;
    protected $list_max;
    protected $current_page;
    protected $pagesize;

    private $tableprefix;
    private $tablename;
    private $field;
    private $where;
    private $orderby;
    private $global_config;
    public function __construct($getdata = array())
    {
        $this->global_config = config('global_config');
        $this->tableprefix = config('database.prefix');
        $this->tablename = $this->tableprefix . 'job_search_rtime';
        $this->init_searchdata($getdata);
    }
    protected function init_searchdata($getdata)
    {
        $this->subsiteCondition = isset($getdata['subsiteCondition'])
        ? $getdata['subsiteCondition']
        : [];
        $this->category1 = isset($getdata['category1'])
            ? $getdata['category1']
            : null;
        $this->category2 = isset($getdata['category2'])
            ? $getdata['category2']
            : null;
        $this->category3 = isset($getdata['category3'])
            ? $getdata['category3']
            : null;
        $this->trade = isset($getdata['trade']) ? $getdata['trade'] : null;
        $this->minwage = isset($getdata['minwage'])
            ? $getdata['minwage']
            : null;
        $this->maxwage = isset($getdata['maxwage'])
            ? $getdata['maxwage']
            : null;
        $this->district1 = isset($getdata['district1'])
            ? $getdata['district1']
            : null;
        $this->district2 = isset($getdata['district2'])
            ? $getdata['district2']
            : null;
        $this->district3 = isset($getdata['district3'])
            ? $getdata['district3']
            : null;
        $this->nature = isset($getdata['nature']) ? $getdata['nature'] : null;
        $this->timestamp = time();
        $this->list_max = 100;
        $this->current_page = isset($getdata['current_page'])
            ? $getdata['current_page']
            : 1;
        $this->current_page = $this->current_page > 0 ? $this->current_page : 1;
        $this->pagesize = isset($getdata['pagesize'])
            ? $getdata['pagesize']
            : 10;
        $this->pagesize = $this->pagesize > 0 ? $this->pagesize : 10;
        //如果当前单页数量大于最大可显示量，重置为最大
        if ($this->pagesize > $this->list_max) {
            $this->pagesize = $this->list_max;
        }
        //如果当前页码超出最大页码，重置为最大页码
        $max_pages = ceil($this->list_max / $this->pagesize);
        if ($this->current_page > $max_pages) {
            $this->current_page = $max_pages;
        }
    }
    public function run($outer_where = '')
    {
        $this->buildField();
        $this->buildWhere($outer_where);
        $this->buildOrder();

        $list = \think\Db::table($this->tablename)
            ->field($this->field)
            ->where($this->subsiteCondition)
            ->where($this->where)
            ->orderRaw($this->orderby)
            ->page($this->current_page, $this->pagesize)
            ->select();
        $return['items'] = $list;
        return $return;
    }
    private function buildOrder()
    {
        $weight_config = $this->global_config['job_recommend_weight'];
        $this->orderby =
            'score1 * ' .
            $weight_config['category'] .
            ' + score2 * ' .
            $weight_config['trade'] .
            ' + score3 * ' .
            $weight_config['wage'] .
            ' + score4 * ' .
            $weight_config['district'] .
            ' + score5 * ' .
            $weight_config['nature'] .
            ' + score6 * ' .
            $weight_config['service_added'] .
            ' + score7 * ' .
            $weight_config['refreshtime'].' desc';
    }
    private function buildWhere($outer_where)
    {
        $this->where = 'category1=' . $this->category1;
        if ($outer_where != '') {
            $this->where .= ' AND ' . $outer_where;
        }
    }
    private function buildField()
    {
        $this->field =
            'id,company_id,CASE 
        WHEN category3=' .
            $this->category3 .
            ' THEN 1
        WHEN category2=' .
            $this->category2 .
            ' THEN 0.5
        ELSE 0
        END AS score1,CASE 
        WHEN trade=' .
            $this->trade .
            ' THEN 1
        ELSE 0
        END AS score2,CASE 
        WHEN minwage<=' .
            $this->maxwage .
            ' AND maxwage>=' .
            $this->minwage .
            ' THEN 1
        WHEN ' .
            $this->maxwage .
            '-minwage<=1000 OR maxwage-' .
            $this->minwage .
            '<=1000 THEN 0.5
        ELSE 0
        END AS score3,CASE 
        WHEN district3=' .
            $this->district3 .
            ' THEN 1
        WHEN district2=' .
            $this->district2 .
            ' THEN 0.5
        ELSE 0
        END AS score4,CASE 
        WHEN nature=' .
            $this->nature .
            ' THEN 1
        ELSE 0
        END AS score5,CASE 
        WHEN stick=1 OR emergency=1 THEN 1
        ELSE 0
        END AS score6,CASE 
        WHEN ' .
            $this->timestamp .
            '-refreshtime<=3600*24*3 THEN 1
        WHEN ' .
            $this->timestamp .
            '-refreshtime<=3600*24*7 THEN 0.9
        WHEN ' .
            $this->timestamp .
            '-refreshtime<=3600*24*15 THEN 0.5
        WHEN ' .
            $this->timestamp .
            '-refreshtime<=3600*24*30 THEN 0.3
        WHEN ' .
            $this->timestamp .
            '-refreshtime<=3600*24*90 THEN 0.1
        ELSE 0
        END AS score7';
    }
}
