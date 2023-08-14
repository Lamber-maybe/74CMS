<?php
/**
 * 简历推荐
 */
namespace app\common\lib;
class ResumeRecommend
{
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
    protected $education;
    protected $experience;
    protected $minage;
    protected $maxage;
    protected $shield_company_uid;
    protected $timestamp;
    protected $list_max;
    protected $current_page;
    protected $pagesize;
    protected $sort; // app增加最新、刷新时间排序 zch

    private $tableprefix;
    private $tablename;
    private $join;
    private $field;
    private $where;
    private $orderby;
    private $global_config;
    public function __construct($getdata = array())
    {
        $this->global_config = config('global_config');
        $this->tableprefix = config('database.prefix');
        $this->tablename = $this->tableprefix . 'resume_search_rtime';
        $this->join = [];
        $this->init_searchdata($getdata);
    }
    protected function init_searchdata($getdata)
    {
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
        $this->education = isset($getdata['education'])
            ? $getdata['education']
            : null;
        $this->experience = isset($getdata['experience'])
            ? $getdata['experience']
            : null;
        $this->minage = isset($getdata['minage']) ? $getdata['minage'] : null;
        $this->maxage = isset($getdata['maxage']) ? $getdata['maxage'] : null;
        $this->shield_company_uid = isset($getdata['shield_company_uid'])
            ? $getdata['shield_company_uid']
            : null;
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
        // app增加最新、刷新时间排序 zch
        $this->sort = isset($getdata['sort']) ? $getdata['sort'] : '';
    }
    public function run()
    {
        $this->buildField();
        $this->buildWhere();
        $this->buildOrder();

        $list = \think\Db::table($this->tablename)
            ->alias('a')
            ->field($this->field)
            ->join(
                $this->tableprefix . 'resume_intention b',
                'a.id=b.rid',
                'LEFT'
            );
        if (!empty($this->join)) {
            $list = $list->join($this->join[0], $this->join[1], $this->join[2]);
        }
        $list = $list
            ->where($this->where)
            ->orderRaw($this->orderby)
            ->page($this->current_page, $this->pagesize)
            ->select();
        $return['items'] = $list;
        return $return;
    }
    private function buildOrder()
    {
        $weight_config = $this->global_config['resume_recommend_weight'];
        // app增加最新、刷新时间排序 zch
        if (!empty($this->sort))
        {
            $this->orderby = $this->sort.' desc';
        }else
        {
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
                $weight_config['refreshtime'] .
                ' + score8 * ' .
                $weight_config['education'] .
                ' + score9 * ' .
                $weight_config['experience'] .
                ' + score10 * ' .
                $weight_config['birthyear'].' desc';
        }
    }
    private function buildWhere()
    {
        $this->where =
            'category1=' .
            $this->category1 .
            ' AND refreshtime>' .
            ($this->timestamp - 3600 * 24 * 360);
        if (intval($this->shield_company_uid) > 0) {
            $this->join = [
                config('database.prefix') . 'shield c',
                'a.uid=c.personal_uid',
                'LEFT'
            ];
            $this->where .=
                ' AND (c.company_uid<>' .
                $this->shield_company_uid .
                ' OR c.id is NULL)';
        }
    }
    private function buildField()
    {
        $age_min_year = date('Y') - $this->maxage;
        $age_max_year = date('Y') - $this->minage;
        $this->field =
            'a.id,CASE 
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
        WHEN minwage-' .
            $this->maxwage .
            '>=1000 OR maxwage-' .
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
        WHEN stick=1 THEN 1
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
        END AS score7,CASE 
        WHEN education>=' .
            $this->education .
            ' THEN 1
        ELSE 0
        END AS score8,';

        switch ($this->experience) {
            case 1: //无经验/应届生
                $this->field .= 'CASE 
                    WHEN 
                        enter_job_time=0 THEN 1
                    ELSE 0
                    END AS score9,';
                break;
            case 2:
                $this->field .=
                    'CASE 
                        WHEN enter_job_time >' .
                    strtotime('-2 year') .
                    ' THEN 1
                        ELSE 0
                        END AS score9,';
                break;
            case 3:
                $this->field .=
                    'CASE 
                        WHEN enter_job_time<=' .
                    strtotime('-2 year') .
                    ' AND enter_job_time >' .
                    strtotime('-3 year') .
                    ' THEN 1
                        ELSE 0
                        END AS score9,';
                break;
            case 4:
                $this->field .=
                    'CASE 
                        WHEN enter_job_time<=' .
                    strtotime('-3 year') .
                    ' AND enter_job_time >' .
                    strtotime('-4 year') .
                    ' THEN 1
                        ELSE 0
                        END AS score9,';
                break;
            case 5:
                $this->field .=
                    'CASE 
                    WHEN enter_job_time<=' .
                    strtotime('-3 year') .
                    ' AND enter_job_time >=' .
                    strtotime('-5 year') .
                    ' THEN 1
                    ELSE 0
                    END AS score9,';
                break;
            case 6:
                $this->field .=
                    'CASE 
                        WHEN enter_job_time<=' .
                    strtotime('-5 year') .
                    ' AND enter_job_time >=' .
                    strtotime('-10 year') .
                    ' THEN 1
                        ELSE 0
                        END AS score9,';
                break;
            case 7:
                $this->field .=
                    'CASE 
                        WHEN enter_job_time <=' .
                    strtotime('-10 year') .
                    ' THEN 1
                        ELSE 0
                        END AS score9,';
                break;
            default:
                $this->field .= 'CASE 
                WHEN enter_job_time<0 THEN 1
                ELSE 0
                END AS score9,';
                break;
        }

        $this->field .=
            'CASE 
        WHEN birthyear<=' .
            $age_max_year .
            ' AND birthyear>' .
            $age_min_year .
            ' THEN 1
        ELSE 0
        END AS score10';
    }
}
