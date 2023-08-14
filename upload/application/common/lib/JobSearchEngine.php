<?php
/**
 * 职位搜索类
 */
namespace app\common\lib;
class JobSearchEngine
{
    protected $keyword;
    protected $emergency;
    protected $district1;
    protected $district2;
    protected $district3;
    protected $category1;
    protected $category2;
    protected $category3;
    protected $minwage;
    protected $maxwage;
    protected $tag;
    protected $trade;
    protected $company_id;
    protected $company_nature_id;
    protected $scale;
    protected $nature;
    protected $education;
    protected $experience;
    protected $settr;
    protected $current_page;
    protected $pagesize;
    protected $sort;
    protected $license;
    protected $search_cont;
    protected $famous;
    protected $filter_apply_uid;
    protected $range;
    protected $lat;
    protected $lng;
    protected $south_west_lat;
    protected $south_west_lng;
    protected $north_east_lat;
    protected $north_east_lng;

    protected $join;
    protected $where;
    protected $field;
    protected $orderby;
    protected $against = '';

    protected $count_total = 1; //是否需要计算总数
    protected $list_max;

    protected $global_config;

    protected $tablename;
    protected $tableprefix;
    protected $fulltext_mode = 'NATURAL LANGUAGE';
    protected $userinfo;

    protected $engine;  // 职位搜索方式 1全文搜索 2模糊搜索

    public function __construct($getdata = array())
    {
        $this->global_config = config('global_config');
        $this->tableprefix = config('database.prefix');
        $this->engine = !empty($this->global_config['job_search_engine']) ? $this->global_config['job_search_engine'] : '1';
        $this->tablename = $this->tableprefix . 'job_search_rtime';
        $this->join = [];
        $this->where = '';
        $this->field = 'a.id,company_id,stick,refreshtime';
        //排序：什么关键词都没有的时候按置顶和时间排序,有关键词的时候按相关度，按刷新时间时按刷新时间排序
        $this->orderby = 'stick DESC, refreshtime DESC, a.id DESC';
        $this->list_max = $this->global_config['job_list_max'];
        $this->init_searchdata($getdata);
    }
    protected function init_searchdata($getdata)
    {
        $this->emergency = isset($getdata['emergency'])
            ? $getdata['emergency']
            : null;
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
        $this->minwage = isset($getdata['minwage'])
            ? $getdata['minwage']
            : null;
        $this->maxwage = isset($getdata['maxwage'])
            ? $getdata['maxwage']
            : null;
        $this->tag = isset($getdata['tag']) ? $getdata['tag'] : null;
        $this->trade = isset($getdata['trade']) ? $getdata['trade'] : null;
        $this->company_id = isset($getdata['company_id'])
            ? $getdata['company_id']
            : null;
        $this->company_nature_id = isset($getdata['company_nature_id'])
            ? $getdata['company_nature_id']
            : null;
        $this->scale = isset($getdata['scale']) ? $getdata['scale'] : null;
        $this->nature = isset($getdata['nature']) ? $getdata['nature'] : null;
        $this->education = isset($getdata['education'])
            ? $getdata['education']
            : null;
        $this->experience = isset($getdata['experience'])
            ? $getdata['experience']
            : null;
        $this->settr = isset($getdata['settr']) ? $getdata['settr'] : null;
        $this->license = isset($getdata['license'])
            ? $getdata['license']
            : null;
        $this->search_cont = isset($getdata['search_cont'])
            ? $getdata['search_cont']
            : null;
        $this->filter_apply_uid = isset($getdata['filter_apply_uid'])
            ? intval($getdata['filter_apply_uid'])
            : 0;
        $this->famous = isset($getdata['famous'])
            ? intval($getdata['famous'])
            : 0;
        $this->range = isset($getdata['range']) ? $getdata['range'] : null;
        $this->lat = isset($getdata['lat']) ? $getdata['lat'] : null;
        $this->lng = isset($getdata['lng']) ? $getdata['lng'] : null;
        $this->south_west_lat = isset($getdata['south_west_lat'])
            ? $getdata['south_west_lat']
            : null;
        $this->south_west_lng = isset($getdata['south_west_lng'])
            ? $getdata['south_west_lng']
            : null;
        $this->north_east_lat = isset($getdata['north_east_lat'])
            ? $getdata['north_east_lat']
            : null;
        $this->north_east_lng = isset($getdata['north_east_lng'])
            ? $getdata['north_east_lng']
            : null;
        $this->current_page =
            isset($getdata['current_page']) &&
            intval($getdata['current_page']) > 0
                ? $getdata['current_page']
                : 1;
        $this->pagesize =
            isset($getdata['pagesize']) && intval($getdata['pagesize']) > 0
                ? $getdata['pagesize']
                : 10;
        $this->count_total = isset($getdata['count_total'])
            ? $getdata['count_total']
            : 1;
        $this->sort = isset($getdata['sort']) ? $getdata['sort'] : '';
        $this->userinfo = isset($getdata['userinfo'])
            ? $getdata['userinfo']
            : null;
        $this->_init_setter();
    }
    protected function _init_setter()
    {
        $this->_set_emergency();
        $this->_set_keyword();
        $this->_set_current_page();
        $this->_set_pagesize();
        $this->_set_minwage();
        $this->_set_maxwage();
        $this->_set_range();
        $this->_set_lat();
        $this->_set_lng();
        $this->_set_view();
        $this->_set_filter_apply_uid();
        $this->_set_famous();
        $this->_set_tag();
        $this->_set_trade();
        $this->_set_company_id();
        $this->_set_company_nature_id();
        $this->_set_scale();
        $this->_set_nature();
        $this->_set_education();
        $this->_set_experience();
        $this->_set_settr();
        $this->_set_sort();
        $this->_set_license();
        // $this->_set_search_cont();
        $this->_set_citycategory();
        $this->_set_jobcategory();
    }
    public function run($outer_where = '')
    {
        if ($this->against) {
            $fulltext_str =
                " MATCH (`jobname`,`companyname`,`company_nature`) AGAINST ('" .
                $this->against .
                "' IN " .
                $this->fulltext_mode .
                ' MODE)';
            $this->where .=
                $this->where == '' ? $fulltext_str : ' AND ' . $fulltext_str;
        }
        if ($outer_where != '') {
            $this->where .= ' AND ' . $outer_where;
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
                    ->field($this->field)
                    ->where($this->where)
                    ->order($this->orderby)
                    ->limit($this->list_max) // zxr 2022.10.27  放开注释，之前是注释掉的
                    ->select();
                $tmp_idarr = [];
                foreach ($total_list as $k=>$v){
                    $tmp_idarr[] = $v['id'];
                }
                $total = 0;
                if (!empty($tmp_idarr)) {
                    // 如果有列表页显示数量限制时 提前将所有ID查询出来 chenyang 2022年4月7日18:09:23
                    $idStr = '';
                    foreach ($tmp_idarr as $id) {
                        $idStr .= $id.',';
                        $total ++;
                    }
                    $idStr = trim($idStr, ',');
                    $idWhere = 'a.id in ('.$idStr.')';
                    if ($this->where == '') {
                        $this->where = $idWhere;
                    }else{
                        $this->where .= ' and '.$idWhere;
                    }
                }
            }
            // //当前页码超出总量限制时，重置为最大值
            // $total_page = $total == 0 ? 0 : ceil($total / $this->pagesize);
            // if ($this->current_page > $total_page) {
            //     $this->current_page = $total_page;
            // }
            //zxr 2022.10.27
            //当前页码超出总量限制时，返回空数据（给个确保0条数据的where条件）
            $total_page = $total == 0 ? 0 : ceil($total / $this->pagesize);
            if ($this->current_page > $total_page) {
                $this->where = 'a.id=0';
            }
            //zxr 2022.10.27
            //当数据量很小，小到连一页都不够时，要把查询数量设置为当前的数据量
            if ($total > 0 && $total < $this->pagesize) {
                $this->pagesize = $total;
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
            // ->page($this->current_page, $this->pagesize)
            // ->select();
            ->paginate(['list_rows'=>$this->pagesize,'page'=>$this->current_page,'type'=>'\\app\\common\\lib\\Pager'],$total);
        $return['items'] = $list;
        $return['total'] = $total;
        $return['total_page'] =
            $total == 0 ? 0 : ceil($total / $this->pagesize);
        return $return;
    }
    /**
     * 设置关键词
     */
    protected function _set_keyword()
    {
        if (!$this->keyword) {
            return false;
        }

        $this->tablename = $this->tableprefix . 'job_search_key';
        $this->keyword = urldecode(urldecode($this->keyword));
        $keyword = trim($this->keyword);

        if ('1' === $this->engine) {
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
                "a.id,company_id,refreshtime,stick,MATCH (`company_nature`) AGAINST ('" .
                $keyword .
                "' IN " .
                $this->fulltext_mode .
                " MODE) AS score1,MATCH (`jobname`) AGAINST ('" .
                $keyword .
                "' IN " .
                $this->fulltext_mode .
                " MODE) AS score2,MATCH (`companyname`) AGAINST ('" .
                $keyword .
                "' IN " .
                $this->fulltext_mode .
                ' MODE) AS score3';

            /**
             * 【ID1000392】
             * 【新增】职位、简历搜索页列表展现排序
             * 职位搜索结果排序方式 1按信息关联度排序 2按信息时间线排序
             * yx - 2022.11.08
             * [旧]
             * $this->orderby = 'score1 desc,score2 desc,score3 desc,refreshtime desc';
             */
            $job_search_order = !empty(config('global_config.job_search_order')) ? config('global_config.job_search_order') : '1';
            if ('1' === $job_search_order) {
                $this->orderby = 'stick DESC, score1 desc, score2 desc, score3 desc, refreshtime desc, a.id DESC';
            } else {
                $this->orderby = 'stick DESC, refreshtime DESC, score1 desc, score2 desc, score3 desc, a.id DESC';
            }
        } else {
            /**
             * 【ID1000546】
             * 【新增】搜索关键词配置项，全文搜，模糊搜方式可选
             * yx - 2023.02.17
             * [新增]:
             */
            $like_sql = " (`jobname` like '%" . $this->keyword . "%' OR `companyname` like '%" . $this->keyword . "%' OR `company_nature` like '%" . $this->keyword . "%') ";
            $this->where .=
                $this->where == '' ? $like_sql : ' AND ' . $like_sql;
            $this->field = 'a.id,company_id,refreshtime,stick,jobname,companyname,company_nature';
            $this->orderby = 'stick DESC, refreshtime DESC, a.id DESC';
        }
    }
    /**
     * 设置分页
     */
    protected function _set_current_page()
    {
    }
    /**
     * 设置分页
     */
    protected function _set_pagesize()
    {
    }
    /**
     * 设置紧急
     */
    protected function _set_emergency()
    {
        if ($this->emergency !== null) {
            $this->where .= ' AND `emergency`=' . $this->emergency;
        }
    }
    /**
     * 设置薪资
     */
    protected function _set_minwage()
    {
        if (!$this->minwage) {
            return false;
        }
        $this->where .= ' AND `maxwage`>=' . $this->minwage;
    }
    protected function _set_maxwage()
    {
        if (!$this->maxwage) {
            return false;
        }
        $this->where .= ' AND `minwage`<=' . $this->maxwage;
    }
    protected function _set_view()
    {
        if (
            $this->south_west_lat &&
            $this->south_west_lng &&
            $this->north_east_lat &&
            $this->north_east_lng
        ) {
            $this->where .=
                ' AND `map_lat`<' .
                $this->north_east_lat .
                ' AND `map_lat`>' .
                $this->south_west_lat .
                ' AND `map_lng`>' .
                $this->south_west_lng .
                ' AND `map_lng`<' .
                $this->north_east_lng;
        }
    }
    /**
     * 设置经纬度范围
     */
    protected function _set_range()
    {
        $this->format_range();
    }
    protected function _set_lat()
    {
        $this->format_range();
    }
    protected function _set_lng()
    {
        $this->format_range();
    }
    protected function format_range()
    {
        if ($this->lat && $this->lng) {
            $this->field =
                'a.id,company_id,stick,refreshtime,ROUND(6378.138*2*ASIN(SQRT(POW(SIN((' .
                $this->lat .
                '*PI()/180-map_lat*PI()/180)/2),2)+COS(' .
                $this->lat .
                '*PI()/180)*COS(map_lat*PI()/180)*POW(SIN((' .
                $this->lng .
                '*PI()/180-map_lng*PI()/180)/2),2)))*1000) AS map_range';
            $this->orderby = 'map_range asc,refreshtime desc';
            if ($this->range) {
                $wa = intval($this->range) * 1000;
                $squares = square_point($this->lng, $this->lat, $wa / 1000);
                $this->where .=
                    ' AND (`map_lng` between ' .
                    $squares['lt']['lng'] .
                    ' AND ' .
                    $squares['rb']['lng'] .
                    ') ';
                $this->where .=
                    ' AND (`map_lat` between ' .
                    $squares['rb']['lat'] .
                    ' AND ' .
                    $squares['lt']['lat'] .
                    ') ';
            }else{
                $this->where .=
                ' AND `map_lng`>0 ';
            } 
        }
    }
    /**
     * 设置过滤已投递
     */
    protected function _set_filter_apply_uid()
    {
        if ($this->filter_apply_uid > 0) {
            $job_apply_jobid = model('JobApply')->where('personal_uid',$this->filter_apply_uid)->column('jobid');
            if(!empty($job_apply_jobid)){
                $this->where .= ' AND a.id not in('.implode(",",$job_apply_jobid).')';
            }
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
     * 设置行业
     */
    protected function _set_trade()
    {
        $this->trade && ($this->where .= ' AND `trade`=' . $this->trade);
    }
    protected function _set_company_id()
    {
        $this->company_id &&
            ($this->where .= ' AND `company_id`=' . $this->company_id);
    }
    /**
     * 设置企业性质
     */
    protected function _set_company_nature_id()
    {
        $this->company_nature_id &&
            ($this->where .=
                ' AND `company_nature_id`=' . $this->company_nature_id);
    }
    /**
     * 设置规模
     */
    protected function _set_scale()
    {
        $this->scale && ($this->where .= ' AND `scale`=' . $this->scale);
    }
    /**
     * 设置性质
     */
    protected function _set_nature()
    {
        $this->nature && ($this->where .= ' AND `nature`=' . $this->nature);
    }
    /**
     * 设置学历
     */
    protected function _set_education()
    {
        $this->education &&
            ($this->where .=
                ' AND (`education`=' . $this->education . ' OR `education`=0)');
    }
    /**
     * 设置经验
     */
    protected function _set_experience()
    {
        $this->experience &&
            ($this->where .=
                ' AND (`experience`=' .
                $this->experience .
                ' OR `experience`=0)');
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
            $this->field = 'a.id,company_id,stick,refreshtime';
            $this->orderby = 'refreshtime desc';
        }else if($this->sort == 'emergency'){
            $this->field = 'a.id,company_id,stick,emergency,refreshtime';
            $this->orderby = 'emergency desc,refreshtime desc';
        }
    }
    /**
     * 设置企业是否认证
     */
    protected function _set_license()
    {
        $this->license && ($this->where .= ' AND `license`=' . $this->license);
    }
    /**
     * 名企
     */
    protected function _set_famous()
    {
        if ($this->famous > 0) {
            $famous_enterprises_setmeal = config('global_config.famous_enterprises');
            $famous_enterprises_setmeal = $famous_enterprises_setmeal == '' ? [] : explode(',', $famous_enterprises_setmeal);
            if (empty($famous_enterprises_setmeal)) {
                $this->where .= ' AND a.id=0 ';
            }else{
                $this->where .= ' AND a.setmeal_id in ('.implode(",",$famous_enterprises_setmeal).') ';
            }
        }
    }
    /**
     * 设置搜索附加
     */
    // protected function _set_search_cont()
    // {
    //     switch ($this->search_cont) {
    //         case 'setmeal':
    //             $this->where .= ' AND `setmeal_id`>1';
    //             break;
    //         case 'famous':
    //             $this->where .= ' AND `famous`=1';
    //             break;
    //         default:
    //             break;
    //     }
    // }
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
            $this->where .=
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
            if(isset($category_cache[$this->category1])){
                $sub_arr = $category_cache[$this->category1];
                $sub_arr = array_keys($sub_arr);
                $in_category_arr = array_merge($in_category_arr, $sub_arr);
                foreach ($sub_arr as $key => $value) {
                    if (!isset($category_cache[$value])) {
                        continue;
                    }
                    $tmp_arr = $category_cache[$value];
                    $tmp_arr = array_keys($tmp_arr);
                    $in_category_arr = array_merge($in_category_arr, $tmp_arr);
                }
            }
        }

        if (!empty($in_category_arr)) {
            $this->where .=
                ' AND `category` in (' . implode(',', $in_category_arr) . ')';
        }
    }
}
