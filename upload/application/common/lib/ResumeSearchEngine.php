<?php

/**
 * 简历搜索类
 */

namespace app\common\lib;

class ResumeSearchEngine
{
    protected $match_mode;
    protected $keyword;
    protected $district1;
    protected $district2;
    protected $district3;
    protected $category1;
    protected $category2;
    protected $category3;
    protected $minage;
    protected $maxage;
    protected $tag;
    protected $minwage;
    protected $maxwage;
    protected $sex;
    protected $trade;
    protected $major;
    protected $nature;
    protected $education;
    protected $experience;
    protected $high_quality;
    protected $settr;
    protected $photo;
    protected $img;
    protected $shield_company_uid;
    protected $current_page;
    protected $pagesize;
    protected $sort;

    protected $join;
    protected $where;
    protected $field;
    protected $orderby;
    protected $against = '';
    protected $intention_where = '';

    protected $count_total = 1; //是否需要计算总数
    protected $list_max;

    protected $global_config;

    protected $tablename;
    protected $tableprefix;
    protected $fulltext_mode = 'NATURAL LANGUAGE';

    public function __construct($getdata = array())
    {
        $this->global_config = config('global_config');
        $this->tableprefix = config('database.prefix');
        $this->tablename = $this->tableprefix . 'resume_search_rtime';
        $this->join = [];
        $this->where = '';
        $this->field = 'a.id,stick,refreshtime';
        //排序：什么关键词都没有的时候按置顶和时间排序,有关键词的时候按相关度，按刷新时间时按刷新时间排序
        $this->orderby = 'stick desc,refreshtime desc';
        $this->list_max = $this->global_config['resume_list_max'];
        $this->init_searchdata($getdata);
    }
    protected function init_searchdata($getdata)
    {
        $this->keyword = isset($getdata['keyword'])
            ? $getdata['keyword']
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
        $this->category1 = isset($getdata['category1'])
            ? $getdata['category1']
            : null;
        $this->category2 = isset($getdata['category2'])
            ? $getdata['category2']
            : null;
        $this->category3 = isset($getdata['category3'])
            ? $getdata['category3']
            : null;
        $this->minage = isset($getdata['minage']) ? $getdata['minage'] : null;
        $this->maxage = isset($getdata['maxage']) ? $getdata['maxage'] : null;
        $this->tag = isset($getdata['tag']) ? $getdata['tag'] : null;
        $this->minwage = isset($getdata['minwage'])
            ? $getdata['minwage']
            : null;
        $this->maxwage = isset($getdata['maxwage'])
            ? $getdata['maxwage']
            : null;
        $this->sex = isset($getdata['sex']) ? $getdata['sex'] : null;
        $this->trade = isset($getdata['trade']) ? $getdata['trade'] : null;
        $this->major = isset($getdata['major']) ? $getdata['major'] : null;
        $this->nature = isset($getdata['nature']) ? $getdata['nature'] : null;
        $this->education = isset($getdata['education'])
            ? $getdata['education']
            : null;
        $this->experience = isset($getdata['experience'])
            ? $getdata['experience']
            : null;
        $this->high_quality = isset($getdata['high_quality'])
            ? $getdata['high_quality']
            : null;
        $this->settr = isset($getdata['settr']) ? $getdata['settr'] : null;
        $this->photo = isset($getdata['photo']) ? $getdata['photo'] : null;
        $this->img = isset($getdata['img']) ? $getdata['img'] : null;
        $this->shield_company_uid = isset($getdata['shield_company_uid'])
            ? $getdata['shield_company_uid']
            : null;
        $this->current_page = isset($getdata['current_page'])
            ? $getdata['current_page']
            : 1;
        $this->pagesize = isset($getdata['pagesize'])
            ? $getdata['pagesize']
            : 10;
        $this->count_total = isset($getdata['count_total'])
            ? $getdata['count_total']
            : 1;
        $this->sort = isset($getdata['sort']) ? $getdata['sort'] : '';
        $this->match_mode = isset($getdata['match_mode'])
            ? $getdata['match_mode']
            : 'default';
        $this->_init_setter();
    }
    protected function _init_setter()
    {
        $this->_set_keyword();
        $this->_set_current_page();
        $this->_set_pagesize();
        $this->_set_minage();
        $this->_set_maxage();
        $this->_set_photo();
        $this->_set_img();
        $this->_set_shield_company_uid();
        $this->_set_tag();
        $this->_set_sex();
        $this->_set_minwage();
        $this->_set_maxwage();
        $this->_set_trade();
        $this->_set_major();
        $this->_set_nature();
        $this->_set_education();
        $this->_set_experience();
        $this->_set_high_quality();
        $this->_set_settr();
        $this->_set_sort();
        $this->_set_citycategory();
        $this->_set_jobcategory();
    }
    public function run()
    {
        if ($this->against) {
            $fulltext_str =
                " MATCH (`intention_jobs`,`fulltext_key`) AGAINST ('" .
                $this->against .
                "' IN " .
                $this->fulltext_mode .
                ' MODE)';
            $this->where .=
                $this->where == '' ? $fulltext_str : ' AND ' . $fulltext_str;
        }

        if ($this->intention_where) {
            $this->intention_where = ltrim(trim($this->intention_where), 'AND');
            $intention_idarr = \think\Db::table(
                $this->tableprefix . 'resume_intention'
            )
                ->where($this->intention_where)
                ->column('rid');
            if (!empty($intention_idarr)) {
                $intention_idarr = array_unique($intention_idarr);
                $this->where .=
                    ' AND a.id in (' . implode(',', $intention_idarr) . ') ';
            } else {
                $this->where = 'a.id=0';
            }
        }

        if ($this->where) {
            $this->where = ltrim(trim($this->where), 'AND');
        }
        if ($this->count_total == 1) {
            if ($this->list_max == 0) {
                $total = \think\Db::table($this->tablename)->alias('a');
                if (!empty($this->join)) {
                    $total = $total->join(
                        $this->join[0],
                        $this->join[1],
                        $this->join[2]
                    );
                }
                $total = $total->where($this->where)->count('distinct a.id');
            } else {
                $total_list = \think\Db::table($this->tablename)->alias('a');
                if (!empty($this->join)) {
                    $total_list = $total_list->join(
                        $this->join[0],
                        $this->join[1],
                        $this->join[2]
                    );
                }
                $total_list = $total_list
                    ->where($this->where)
                    ->field('distinct a.id')
                    ->limit($this->list_max)
                    ->select();
                $total = count($total_list);
            }
            //当前页码超出总量限制时，重置为最大值
            $total_page = $total == 0 ? 0 : ceil($total / $this->pagesize);
            if ($this->current_page > $total_page) {
                $this->current_page = $total_page;
            }
        } else {
            $total = 0;
        }

        if ($this->current_page == 0) {
            $this->current_page = 1;
        }
        $list = \think\Db::table($this->tablename)
            ->alias('a')
            ->field($this->field);
        if (!empty($this->join)) {
            $list = $list->join($this->join[0], $this->join[1], $this->join[2]);
        }
        $list = $list
            ->where($this->where)
            ->order($this->orderby)
            ->distinct('a.id')
            // ->page($this->current_page, $this->pagesize)
            // ->select();
            ->paginate(['list_rows'=>$this->pagesize,'page'=>$this->current_page,'type'=>'\\app\\common\\lib\\Pager'],$total);

        $return['items'] = $list;
        $return['total'] = $total;
        $return['total_page'] =
            $total == 0 ? 0 : ceil($total / $this->pagesize);
        return $return;
    }
    // public function __set($name, $value) {
    //     $this->$name = $value;
    //     $method_name = '_set_'.$name;
    //     if(method_exists($this, $method_name)){
    //         $this->$method_name();
    //     }
    // }
    // public function __get($name) {
    //     return $this->$name;
    // }
    /**
     * 设置关键词
     */
    protected function _set_keyword()
    {
        if (!$this->keyword) {
            return false;
        }
        $this->tablename = $this->tableprefix . 'resume_search_key';
        $this->keyword = urldecode(urldecode($this->keyword));
        $keyword = trim($this->keyword);
        if (false !== stripos($keyword, ' ')) {
            $keyword = merge_spaces($keyword);
            $this->fulltext_mode = 'BOOLEAN';
            $tmp_keyword_arr = explode(' ', $keyword);
            foreach ($tmp_keyword_arr as $key => $value) {
                $this->against .= '+' . $value . ' ';
            }
            $this->against = trim($this->against);
            $keyword = $this->against;
        } else {
            $this->against = $keyword;
        }

        $this->field =
            "a.id,refreshtime,stick,FROM_UNIXTIME(`refreshtime`, '%Y') AS t_year,FROM_UNIXTIME(`refreshtime`, '%m') AS t_month,MATCH (`intention_jobs`) AGAINST ('" .
            $keyword .
            "' IN " .
            $this->fulltext_mode .
            ' MODE) AS score1';
        $this->orderby =
            't_year desc,t_month desc,score1 desc,refreshtime desc';
    }
    /**
     * 设置分页
     */
    protected function _set_current_page()
    {
        // if(!$this->current_page){
        //     return false;
        // }
        // $this->limit = ( ($this->current_page - 1) * $this->pagesize ) . ',' . $this->pagesize;
    }
    /**
     * 设置分页
     */
    protected function _set_pagesize()
    {
        // $this->limit = ( ($this->current_page - 1) * $this->pagesize ) . ',' . $this->pagesize;
    }
    /**
     * 设置年龄
     */
    protected function _set_minage()
    {
        if (!$this->minage) {
            return false;
        }
        $this->where .= ' AND `birthyear`<=' . (date('Y') - $this->minage);
    }
    protected function _set_maxage()
    {
        if (!$this->maxage) {
            return false;
        }
        $this->where .= ' AND `birthyear`>=' . (date('Y') - $this->maxage);
    }
    /**
     * 设置是否有照片
     */
    protected function _set_photo()
    {
        $this->photo && ($this->where .= ' AND `photo`=' . $this->photo);
    }
    /**
     * 设置是否有作品
     */
    protected function _set_img()
    {
        if (intval($this->img) > 0) {
            $this->join = [
                config('database.prefix') . 'resume_img c',
                'a.uid=c.uid',
                'LEFT'
            ];
            $this->where .=
                ' AND c.id is NOT NULL';
        }
    }
    /**
     * 设置屏蔽企业
     */
    protected function _set_shield_company_uid()
    {
        if (intval($this->shield_company_uid) > 0) {
            $this->join = [
                config('database.prefix') . 'shield b',
                'a.uid=b.personal_uid',
                'LEFT'
            ];
            $this->where .=
                ' AND (b.company_uid<>' .
                $this->shield_company_uid .
                ' OR b.id is NULL)';
        }
    }
    /**
     * 设置标签
     */
    protected function _set_tag()
    {
        if ($this->tag) {
            $tag_arr = is_array($this->tag)
                ? $this->tag
                : explode('_', $this->tag);
            foreach ($tag_arr as $key => $value) {
                $this->where .= " AND FIND_IN_SET('" . $value . "',`tag`)";
            }
        }
    }
    /**
     * 设置性别
     */
    protected function _set_sex()
    {
        $this->sex && ($this->where .= ' AND `sex`=' . $this->sex);
    }
    /**
     * 设置薪资
     */
    protected function _set_minwage()
    {
        if (!$this->minwage) {
            return false;
        }
        $this->intention_where .= ' AND `maxwage`>=' . $this->minwage;
    }
    protected function _set_maxwage()
    {
        if (!$this->maxwage) {
            return false;
        }
        $this->intention_where .= ' AND `minwage`<=' . $this->maxwage;
    }
    /**
     * 设置行业
     */
    protected function _set_trade()
    {
        if ($this->trade) {
            $this->intention_where .= ' AND `trade`=' . $this->trade;
        }
    }
    /**
     * 设置专业
     */
    protected function _set_major()
    {
        $this->major && ($this->where .= ' AND `major`=' . $this->major);
    }
    /**
     * 设置性质
     */
    protected function _set_nature()
    {
        if ($this->nature) {
            $this->intention_where .= ' AND `nature`=' . $this->nature;
        }
    }
    /**
     * 设置学历
     */
    protected function _set_education()
    {
        $this->education &&
            ($this->where .= ' AND `education`=' . $this->education);
    }
    /**
     * 设置高质量
     */
    protected function _set_high_quality()
    {
        $this->high_quality &&
            ($this->where .= ' AND `high_quality`=' . $this->high_quality);
    }
    /**
     * 设置经验
     */
    protected function _set_experience()
    {
        switch ($this->experience) {
            case 1: //无经验/应届生
                $this->where .= ' AND `enter_job_time`=0';
                break;
            case 2:
                $this->where .=
                    ' AND `enter_job_time` > ' . strtotime('-2 year');
                break;
            case 3:
                $this->where .=
                    ' AND `enter_job_time` <= ' .
                    strtotime('-2 year') .
                    ' AND `enter_job_time` > ' .
                    strtotime('-3 year');
                break;
            case 4:
                $this->where .=
                    ' AND `enter_job_time` <= ' .
                    strtotime('-3 year') .
                    ' AND `enter_job_time` > ' .
                    strtotime('-4 year');
                break;
            case 5:
                $this->where .=
                    ' AND `enter_job_time` <= ' .
                    strtotime('-3 year') .
                    ' AND `enter_job_time` > ' .
                    strtotime('-5 year');
                break;
            case 6:
                $this->where .=
                    ' AND `enter_job_time` <= ' .
                    strtotime('-5 year') .
                    ' AND `enter_job_time` > ' .
                    strtotime('-10 year');
                break;
            case 7:
                $this->where .=
                    ' AND `enter_job_time` <= ' . strtotime('-10 year');
                break;
            default:
                break;
        }
    }
    /**
     * 设置时间
     */
    protected function _set_settr()
    {
        $this->settr &&
            ($this->where .=
                ' AND `refreshtime` >= ' .
                strtotime('-' . $this->settr . 'day'));
    }
    /**
     * 设置排序
     */
    protected function _set_sort()
    {
        if ($this->sort == 'rtime') {
            $this->field = 'a.id,stick,refreshtime';
            $this->orderby = 'refreshtime desc';
        }
    }
    /**
     * 设置地区
     */
    protected function _set_citycategory()
    {
        $in_district_arr = [];
        $this->district1 = intval($this->district1);
        $this->district2 = intval($this->district2);
        $this->district3 = intval($this->district3);
        $district_cache = model('CategoryDistrict')->getCache('');
        if ($this->district3) {
            //搜榆次，出山西不限，晋中不限
            $in_district_arr = [
                $this->district3,
                $this->district2,
                $this->district1
            ];
        } elseif ($this->district2) {
            //搜晋中，出山西不限，晋中不限，晋中所有地区
            $in_district_arr = [$this->district2, $this->district1];
            if (isset($district_cache[$this->district2])) {
                $sub_arr = $district_cache[$this->district2];
                $sub_arr = array_keys($sub_arr);
                $in_district_arr = array_merge($in_district_arr, $sub_arr);
            }
        } elseif ($this->district1) {
            //搜山西，出现山西不限，山西所有地区，晋中所有地区，太原所有地区
            $in_district_arr = [$this->district1];
            if (isset($district_cache[$this->district1])) {
                $sub_arr = $district_cache[$this->district1];
                $sub_arr = array_keys($sub_arr);
                $in_district_arr = array_merge($in_district_arr, $sub_arr);
                foreach ($sub_arr as $key => $value) {
                    if (!isset($district_cache[$value])) {
                        continue;
                    }
                    $tmp_arr = $district_cache[$value];
                    $tmp_arr = array_keys($tmp_arr);
                    $in_district_arr = array_merge($in_district_arr, $tmp_arr);
                }
            }
        }

        if (!empty($in_district_arr)) {
            $this->intention_where .=
                ' AND `district` in (' . implode(',', $in_district_arr) . ')';
        }
    }
    /**
     * 设置意向职位
     */
    protected function _set_jobcategory()
    {
        $in_category_arr = [];
        $this->category1 = intval($this->category1);
        $this->category2 = intval($this->category2);
        $this->category3 = intval($this->category3);
        $category_cache = model('CategoryJob')->getCache('');
        if ($this->category3) {
            $in_category_arr = [
                $this->category3,
                $this->category2,
                $this->category1
            ];
        } elseif ($this->category2) {
            $in_category_arr = [$this->category2, $this->category1];
            $sub_arr = isset($category_cache[$this->category2])?$category_cache[$this->category2]:[];
            $sub_arr = array_keys($sub_arr);
            $in_category_arr = array_merge($in_category_arr, $sub_arr);
        } elseif ($this->category1) {
            $in_category_arr = [$this->category1];
            $sub_arr = isset($category_cache[$this->category1])?$category_cache[$this->category1]:[];
            $sub_arr = array_keys($sub_arr);
            $in_category_arr = array_merge($in_category_arr, $sub_arr);
            foreach ($sub_arr as $key => $value) {
                $tmp_arr = isset($category_cache[$value])?$category_cache[$value]:[];
                $tmp_arr = array_keys($tmp_arr);
                $in_category_arr = array_merge($in_category_arr, $tmp_arr);
            }
        }

        if (!empty($in_category_arr)) {
            $this->intention_where .=
                ' AND `category` in (' . implode(',', $in_category_arr) . ')';
        }
    }
}
