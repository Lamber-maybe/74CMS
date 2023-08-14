<?php
/**
 * 公众号营销
 */

namespace app\apiadmin\controller;

use app\common\controller\Backend;
use app\common\lib\Wechat;
use think\Db;
use think\Exception;
use think\Validate;

class Marketing extends Backend
{
    private $pageSize = 10;

    public function search()
    {
        // 参数1：信息类型
        $type = input('post.type/s', '', 'trim');
        // 搜索条件参数
        $condition = input('post.condition/a', []);

        switch ($type) {
            case 'job_list':
                // 职位列表
                $items = $this->_searchJobList($condition);
                break;

            case 'company_list':
                // 企业列表
                $items = $this->_searchCompanyList($condition);
                break;

            case 'appoint_company':
                // 指定企业
                $items = $this->_searchCompanyByIds($condition);
                break;

            default:
                $this->ajaxReturn(500, '错误的信息类型');
                break;
        }

        $miniprogram_appid = config('global_config.wechat_miniprogram_appid') ? config('global_config.wechat_miniprogram_appid') : '';

        $return = [
            'items' => $items,
            'common' => [
                'site_name' => config('global_config.sitename'),
                'site_domain' => config('global_config.sitedomain'),
                'date' => time(),
                'mini_program_appid' => $miniprogram_appid
            ]
        ];
        $this->ajaxReturn(200, 'SUCCESS', $return);

    }

    protected function _searchJobList($condition = [])
    {
        $job_list = $this->_parseJobByCondition($condition);

        if (empty($job_list) || !isset($job_list)) {
            return array();
        }

        $category_data = model('Category')->getCache();
        $wechat_class = new Wechat();

        $return = [];
        foreach ($job_list as $job_key => $job_value) {
            $job_info = $job_value->toArray();
            $item = $job_info;

            $item['job_wage'] = model('BaseModel')->handle_wage(
                $job_info['minwage'],
                $job_info['maxwage'],
                $job_info['negotiable']
            );

            if ($job_info['age_na'] == 0) {
                $item['job_age'] = $job_info['minage'] . '-' . $job_info['maxage'] . '岁';
            } else {
                $item['job_age'] = '不限';
            }

            $item['education_text'] = isset(
                model('BaseModel')->map_education[$job_info['education']]
            )
                ? model('BaseModel')->map_education[$job_info['education']]
                : '学历不限';

            $item['experience_text'] = isset(
                model('BaseModel')->map_experience[$job_info['experience']]
            )
                ? model('BaseModel')->map_experience[$job_info['experience']]
                : '经验不限';

            if (isset($job_info['tag']) && !empty($job_info['tag'])) {
                $tag_arr = explode(',', $job_info['tag']);
                foreach ($tag_arr as $tag_kay => $tag_value) {
                    if (is_numeric($tag_value) && isset($category_data['QS_jobtag'][$tag_value])) {
                        $item['job_tag'][] = $category_data['QS_jobtag'][$tag_value];
                    } else {
                        $item['job_tag'][] = $tag_value;
                    }
                }
            }

            $item['job_amount'] = ($job_value['amount'] == 0 ? '若干' : $job_value['amount']) . '人';

            $item['job_link'] = config('global_config.mobile_domain') . 'job/' . $job_value['job_id'];

            $item['job_mini_path'] = '/pages/jobs/jobs_show/jobs_show?id=' . $job_value['job_id'];

            $item['company_link'] = config('global_config.mobile_domain') . 'company/' . $job_value['company_id'];

            $item['company_mini_path'] = '/pages/jobs/company_show/company_show?id=' . $job_value['company_id'];

            $job_qrcode = $wechat_class->makeQrcode(['alias' => 'subscribe_job', 'jobid' => $job_value['job_id']]);
            $item['job_wx_qrcode'] = $job_qrcode ? $job_qrcode : '';

            $company_qrcode = $wechat_class->makeQrcode(['alias' => 'subscribe_company', 'comid' => $job_value['company_id']]);
            $item['company_wx_qrcode'] = $company_qrcode ? $company_qrcode : '';
            $return[] = $item;
        }

        return $return;
    }

    protected function _parseJobByCondition($condition)
    {
        // 【ID1000225】【bug】公众号营销-套餐已到期信息列表中未过滤修改
        // 公众号营销搜索问题修改 zdq
        $model = model('JobSearchRtime')
            ->alias('jsr')
            ->join('Job j', 'j.id=jsr.id', 'LEFT')
            ->join('company c', 'c.id = j.company_id', 'LEFT')
            ->field('j.id as job_id,
            j.company_id,
            c.companyname as company_name,
            j.jobname as job_name,
            j.content as job_content,
            j.address as job_address,
            j.minwage,
            j.maxwage,
            j.negotiable,
            j.minage,
            j.maxage,
            j.age_na,
            j.amount,
            j.tag,
            j.education,
            j.experience')
            ->where('j.audit', 1)
            ->where('j.is_display', 1);

        if (isset($condition['refreshtime']) && intval($condition['refreshtime']) > 0) {
            $settr = intval($condition['refreshtime']);
            $model = $model->where(
                'jsr.refreshtime',
                'egt',
                strtotime('-' . $settr . 'day')
            );
        }
        if (isset($condition['jobcategory']) && count($condition['jobcategory']) > 0) {
            $tmp_str = '';
            foreach ($condition['jobcategory'] as $value) {
                $arr_lenth = count($value);
                $tmp_str .=
                    ' or jsr.category' .
                    $arr_lenth .
                    '=' .
                    $value[$arr_lenth - 1];
            }
            if ($tmp_str != '') {
                $tmp_str = trim($tmp_str, ' ');
                $tmp_str = trim($tmp_str, 'or');
                $model = $model->where($tmp_str);
            }
        }

        if (isset($condition['district']) && count($condition['district']) > 0) {
            $tmp_str = '';
            foreach ($condition['district'] as $key => $value) {
                $arr_lenth = count($value);
                $tmp_str .=
                    ' or jsr.district' .
                    $arr_lenth .
                    '=' .
                    $value[$arr_lenth - 1];
            }
            if ($tmp_str != '') {
                $tmp_str = trim($tmp_str, ' ');
                $tmp_str = trim($tmp_str, 'or');
                $model = $model->where($tmp_str);
            }
        }

        if (isset($condition['trade']) && count($condition['trade']) > 0) {
            $model = $model->where('jsr.trade', 'in', $condition['trade']);
        }

        if (isset($condition['tag']) && count($condition['tag']) > 0) {
            foreach ($condition['tag'] as $value) {
                $model = $model->where('FIND_IN_SET("' . $value . '",jsr.tag)');
            }
        }

        if (isset($condition['wage']) && count($condition['wage']) > 0) {
            $tmp_str = '';
            foreach ($condition['wage'] as $value) {
                switch ($value) {
                    case 0: //面议
                        $tmp_str .= ' or (jsr.minwage=0 and jsr.maxwage=0)';
                        break;

                    case 15000:
                        $tmp_str .= ' or jsr.maxwage>=15000';
                        break;

                    default:
                        if (false !== stripos($value, '-')) {
                            $arr = explode('-', $value);
                            $tmp_str .=
                                ' or (jsr.maxwage>=' .
                                $arr[0] .
                                ' and jsr.minwage<' .
                                $arr[1] .
                                ')';
                        }
                        break;
                }
            }
            if ($tmp_str != '') {
                $tmp_str = trim($tmp_str, ' ');
                $tmp_str = trim($tmp_str, 'or');
                $model = $model->where($tmp_str);
            }
        }

        if (isset($condition['setmeal_id']) && count($condition['setmeal_id']) > 0) {
            $model = $model->join('MemberSetmeal ms', 'ms.uid=jsr.uid', 'LEFT');
            $model = $model->where('ms.deadline = 0 OR ms.deadline >' . time());
            $model = $model->where('jsr.setmeal_id', 'in', $condition['setmeal_id']);
        }

        $filter = isset($condition['content']) ? $condition['content'] : 'all';
        switch ($filter) {
            case 'stick':
                $model = $model->where('jsr.stick', 1)->order('jsr.refreshtime', 'desc');
                break;
            case 'emergency':
                $model = $model->where('jsr.emergency', 1)->order('jsr.refreshtime', 'desc');
                break;
            case 'promotion':
                $model = $model->order('jsr.stick', 'desc')->order('jsr.emergency', 'desc');
                break;

            case 'refreshtime':
            default:
                $model = $model->order('jsr.refreshtime', 'desc');
                break;
        }

        $num = (isset($condition['num']) && intval($condition['num']) > 0) ? intval($condition['num']) : 10;

        $page_num = (isset($condition['page_num']) && intval($condition['page_num']) > 0) ? intval($condition['page_num']) : 1;

        if (empty($page_num) || $page_num < 1) {
            $start = 0;
        } else {
            $start = $page_num - 1;
        }

        $limit_start = $start * $this->pageSize;
        if ($limit_start > $num) {
            return [];
        }

        return $model->distinct('jsr.id')->limit($limit_start, $this->pageSize)->select();

    }

    protected function _searchCompanyList($condition = [])
    {
        $company_list = $this->_parseCompanyByCondition($condition);

        if (empty($company_list) || !isset($company_list)) {
            return array();
        }

        $return = array();

        $wechat_class = new Wechat();
        $category_data = model('Category')->getCache();

        foreach ($company_list as $company_key => $company) {
            // 企业数据转数组
            $company_info = $company->toarray();
            $return[$company_key] = $company_info;

            // 企业福利数据格式转换
            if (isset($company_info['tag']) && !empty($company_info['tag'])) {
                $tag_arr = explode(',', $company_info['tag']);
                foreach ($tag_arr as $tag_kay => $tag_value) {
                    if (is_numeric($tag_value) && isset($category_data['QS_jobtag'][$tag_value])) {
                        $return[$company_key]['company_tag'][] = $category_data['QS_jobtag'][$tag_value];
                    } else {
                        $return[$company_key]['company_tag'][] = $tag_value;
                    }
                }
            }

            $qrcode = $wechat_class->makeQrcode(['alias' => 'subscribe_company', 'comid' => $company_info['company_id']]);
            $return[$company_key]['company_wx_qrcode'] = $qrcode ? $qrcode : '';
            $return[$company_key]['company_link'] = config('global_config.mobile_domain') . 'company/' . $company_info['company_id'];
            $return[$company_key]['company_mini_path'] = '/pages/jobs/company_show/company_show?id=' . $company_info['company_id'];

            // 查询企业职位
            $job_list = model('Job')
                ->field('id as job_id,
                company_id,
                jobname as job_name,
                minwage,
                maxwage,
                negotiable,
                education,
                experience,
                amount,
                content as job_content')
                ->where('company_id', $company_info['company_id'])
                ->where('audit', 1)
                ->where('is_display', 1);
            if (isset($condition['job_limit']) && !empty($condition['job_limit'])) {
                $job_limit = intval($condition['job_limit']);
                $job_list = $job_list->limit($job_limit);
            }
            $job_list = $job_list->select();

            if (isset($job_list) && !empty($job_list)) {
                foreach ($job_list as $job_key => $job) {
                    $job_info = $job->toArray();
                    $job_info['job_wage'] = model('BaseModel')->handle_wage(
                        $job_info['minwage'],
                        $job_info['maxwage'],
                        $job_info['negotiable']
                    );

                    $job_info['education_text'] = isset(
                        model('BaseModel')->map_education[$job_info['education']]
                    )
                        ? model('BaseModel')->map_education[$job_info['education']]
                        : '学历不限';

                    $job_info['experience_text'] = isset(
                        model('BaseModel')->map_experience[$job_info['experience']]
                    )
                        ? model('BaseModel')->map_experience[$job_info['experience']]
                        : '经验不限';

                    $job_info['job_amount'] = ($job_info['amount'] == 0 ? '若干' : $job_info['amount']) . '人';

                    $job_info['job_link'] = config('global_config.mobile_domain') . 'job/' . $job_info['job_id'];

                    $job_qrcode = $wechat_class->makeQrcode(['alias' => 'subscribe_job', 'jobid' => $job_info['job_id']]);
                    $job_info['job_wx_qrcode'] = $job_qrcode ? $job_qrcode : '';

                    $job_info['job_mini_path'] = '/pages/jobs/jobs_show/jobs_show?id=' . $job_info['job_id'];

                    $job_names[$job_info['company_id']][] = $job_info['job_name'];

                    $return[$company_key]['job_list'][$job_key] = $job_info;

                }

            } else {
                $return[$company_key]['job_list'] = array();
            }

        }

        return $return;

    }

    protected function _parseCompanyByCondition($condition)
    {
        $model = model('Company')
            ->alias('c')
            ->join('company_contact cc', 'cc.uid=c.uid', 'LEFT')
            ->join('company_info ci', 'ci.uid=c.uid', 'LEFT')
            ->field('c.id as company_id,
            c.companyname as company_name,
            cc.contact as company_contact,
            cc.mobile as company_mobile,
            ci.address as company_address,
            ci.website as company_website,
            c.tag');

        // 企业性质
        if (isset($condition['nature']) && count($condition['nature']) > 0) {
            $model = $model->where('c.nature', 'in', $condition['nature']);
        }

        // 所属行业
        if (isset($condition['trade']) && count($condition['trade']) > 0) {
            $model = $model->where('c.trade', 'in', $condition['trade']);
        }

        if (isset($condition['district']) && count($condition['district']) > 0) {
            $tmp_str = '';
            foreach ($condition['district'] as $value) {
                $arr_length = count($value);
                $tmp_str .=
                    ' or c.district' .
                    $arr_length .
                    '=' .
                    $value[$arr_length - 1];
            }
            if ($tmp_str != '') {
                $tmp_str = trim($tmp_str, ' ');
                $tmp_str = trim($tmp_str, 'or');
                $model = $model->where($tmp_str);
            }
        }

        // 企业福利
        if (isset($condition['tag']) && count($condition['tag']) > 0) {
            foreach ($condition['tag'] as $value) {
                $model = $model->where('FIND_IN_SET("' . $value . '",c.tag)');
            }
        }

        // 会员套餐
        if (isset($condition['setmeal_id']) && count($condition['setmeal_id']) > 0) {
            /**
             * 【ID1000531】
             * 【bug】公众号营销，过期套餐的企业还会出来数据
             * yx - 2023.02.09
             * [新增]:
             * 查询加入套餐到期时间过滤
             * ->join('MemberSetmeal ms', 'ms.uid=c.uid', 'LEFT')
             * ->where('ms.deadline',['gt', time()], ['eq', 0], 'or');
             */
            $model = $model->where('c.setmeal_id', 'in', $condition['setmeal_id'])
                ->join('MemberSetmeal ms', 'ms.uid=c.uid', 'LEFT')
                ->where('ms.deadline',['gt', time()], ['eq', 0], 'or');
        }

        $filter = isset($condition['content']) ? $condition['content'] : 'all';
        switch ($filter) {
            case 'filter_nojobs':
                $model = $model
                    ->join('job_search_rtime s', 's.uid=c.uid', 'LEFT')
                    ->where('s.id', 'not null');
                break;

            case 'filter_noaudit':
                $model = $model->where('c.audit', 1);
                break;

            case 'all':
            default:
                break;
        }

        $time_order = isset($condition['time_order']) ? $condition['time_order'] : '';
        switch ($time_order) {
            case 'register_time':
                $model->order('c.addtime DESC');
                break;

            case 'refresh_time':
            default:
                $model->order('c.refreshtime DESC');
                break;
        }

        $num = (isset($condition['num']) && intval($condition['num']) > 0) ? intval($condition['num']) : 10;

        $page_num = (isset($condition['page_num']) && intval($condition['page_num']) > 0) ? intval($condition['page_num']) : 1;

        if (empty($page_num) || $page_num < 1) {
            $start = 0;
        } else {
            $start = $page_num - 1;
        }

        $limit_start = $start * $this->pageSize;
        if ($limit_start > $num) {
            return [];
        }

        return $model->distinct('c.id')->limit($limit_start, $this->pageSize)->select();
    }

    protected function _searchCompanyByIds($condition = [])
    {
        if (!isset($condition['company_ids']) || empty($condition['company_ids'])) {
            $this->ajaxReturn(500, '请选择企业');
        }

        if (is_array($condition['company_ids'])) {
            if (!isset($condition['company_ids'][0]) || empty($condition['company_ids'][0])) {
                $this->ajaxReturn(500, '请正确选择指定企业');
            }
        } else {
            $this->ajaxReturn(500, '请选择指定企业');
        }

        $comid_arr = $condition['company_ids'];

        if (empty($comid_arr) || !isset($comid_arr)) {
            return array();
        }

        $company_list = model('Company')
            ->alias('c')
            ->join('company_contact cc', 'cc.uid=c.uid', 'LEFT')
            ->join('company_info ci', 'ci.uid=c.uid', 'LEFT')
            ->field('c.id as company_id,
            c.companyname as company_name,
            cc.contact as company_contact,
            cc.mobile as company_mobile,
            ci.address as company_address,
            ci.website as company_website,
            c.tag')
            ->where('c.id', 'in', $comid_arr)
            ->orderRaw('field(c.id,' . implode(",", $comid_arr) . ')')
            ->select();

        if (empty($company_list) || !isset($company_list)) {
            return array();
        }

        $return = array();

        $wechat_class = new Wechat();
        $category_data = model('Category')->getCache();

        foreach ($company_list as $company_key => $company) {
            // 企业数据转数组
            $company_info = $company->toarray();
            $return[$company_key] = $company_info;

            // 企业福利数据格式转换
            if (isset($company_info['tag']) && !empty($company_info['tag'])) {
                $tag_arr = explode(',', $company_info['tag']);
                foreach ($tag_arr as $tag_kay => $tag_value) {
                    if (is_numeric($tag_value) && isset($category_data['QS_jobtag'][$tag_value])) {
                        $return[$company_key]['company_tag'][] = $category_data['QS_jobtag'][$tag_value];
                    } else {
                        $return[$company_key]['company_tag'][] = $tag_value;
                    }
                }
            }

            $qrcode = $wechat_class->makeQrcode(['alias' => 'subscribe_company', 'comid' => $company_info['company_id']]);
            $return[$company_key]['company_wx_qrcode'] = $qrcode ? $qrcode : '';
            $return[$company_key]['company_link'] = config('global_config.mobile_domain') . 'company/' . $company_info['company_id'];
            $return[$company_key]['company_mini_path'] = '/pages/jobs/company_show/company_show?id=' . $company_info['company_id'];

            // 查询企业职位
            $job_list = model('Job')
                ->field('id as job_id,
                company_id,
                jobname as job_name,
                minwage,
                maxwage,
                negotiable,
                education,
                experience,
                amount,
                content as job_content')
                ->where('company_id', $company_info['company_id'])
                ->where('audit', 1)
                ->where('is_display', 1);
            if (isset($condition['job_limit']) && !empty($condition['job_limit'])) {
                $job_limit = intval($condition['job_limit']);
                $job_list = $job_list->limit($job_limit);
            }
            $job_list = $job_list->select();

            if (isset($job_list) && !empty($job_list)) {
                foreach ($job_list as $job_key => $job) {
                    $job_info = $job->toArray();
                    $job_info['job_wage'] = model('BaseModel')->handle_wage(
                        $job_info['minwage'],
                        $job_info['maxwage'],
                        $job_info['negotiable']
                    );

                    $job_info['education_text'] = isset(
                        model('BaseModel')->map_education[$job_info['education']]
                    )
                        ? model('BaseModel')->map_education[$job_info['education']]
                        : '学历不限';

                    $job_info['experience_text'] = isset(
                        model('BaseModel')->map_experience[$job_info['experience']]
                    )
                        ? model('BaseModel')->map_experience[$job_info['experience']]
                        : '经验不限';

                    $job_info['job_amount'] = ($job_info['amount'] == 0 ? '若干' : $job_info['amount']) . '人';

                    $job_info['job_link'] = config('global_config.mobile_domain') . 'job/' . $job_info['job_id'];

                    $job_info['job_mini_path'] = '/pages/jobs/jobs_show/jobs_show?id=' . $job_info['job_id'];

                    $job_qrcode = $wechat_class->makeQrcode(['alias' => 'subscribe_job', 'jobid' => $job_info['job_id']]);
                    $job_info['job_wx_qrcode'] = $job_qrcode ? $job_qrcode : '';

                    $job_names[$job_info['company_id']][] = $job_info['job_name'];

                    $return[$company_key]['job_list'][$job_key] = $job_info;

                }

            } else {
                $return[$company_key]['job_list'] = array();
            }

        }

        return $return;

    }


    /**
     * @Purpose:
     * 公众号营销 - 关键词检索指定公司
     * @Method companySearchByKeyword()
     *
     * @param string $keyword 关键字检索[企业ID/企业名称]
     *
     * @return Jsonp
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     *
     * @link {domain}marketing/companySearchByKeyword
     *
     * @author  Administrator
     * @version 1.1
     * @since   2022/4/2
     */
    public function companySearchByKeyword()
    {
        $keyword = input('post.keyword/s', '', 'trim');

        if (isset($keyword) && !empty($keyword)) {
            $company_list = model('Company')
                ->alias('c')
                ->join('member m', 'm.uid=c.uid', 'LEFT')
                ->where('c.uid', 'eq', $keyword)
                ->whereOr('c.id', 'eq', $keyword)
                ->whereOr('c.companyname', 'like', '%' . $keyword . '%')
                ->whereOr('m.mobile', 'like', '%' . $keyword . '%')
                ->field('c.id as company_id, c.uid as uid, c.companyname as company_name, m.mobile')
                ->order('refreshtime desc')
                ->select();
            if (isset($company_list) && !empty($company_list)) {
                $comid_arr = [];
                foreach ($company_list as $company) {
                    $comid_arr[] = $company['company_id'];
                }

                $job_data = [];
                if (!empty($comid_arr)) {
                    $job_data = model('JobSearchRtime')
                        ->where('company_id', 'in', $comid_arr)
                        ->column('company_id,id,uid');
                }

                foreach ($company_list as $company) {
                    $arr['company_id'] = $company['company_id'];
                    $arr['company_uid'] = $company['uid'];
                    $arr['company_name'] = $company['company_name'];
                    $arr['mobile'] = $company['mobile'];
                    $arr['has_job'] = isset($job_data[$company['company_id']]) ? 1 : 0;
                    $list[] = $arr;
                }
            } else {
                $list = array();
            }

        } else {
            $list = array();
        }

        $this->ajaxReturn(200, 'SUCCESS', ['items' => $list]);

    }


    /**
     * @Purpose:
     * 营销模板 - 模板列表
     * @Method templateList()
     *
     * @param integer $page_num 当前页（默认：1）
     * @param integer $page_size 每页显示条数（默认：10）
     * @param string $keyword 关键字检索[模板名称]
     * @param integer $type 模板类型[0:全部;1:职位模板;2:企业模板;]
     *
     * @return Jsonp
     *
     * @throws null
     *
     * @link {domain}marketing/templateList
     *
     * @author  Administrator
     * @version 1.1
     * @since   2022/4/1
     */
    public function templateList()
    {
        $page_num = input('post.page_num/d', 1, 'intval');
        $page_size = input('post.page_size/d', 10, 'intval');
        $keyword = input('post.keyword/s', '', 'trim');
        $type = input('post.type/d', 0, 'intval');

        $map = array();

        //  1.关键字
        if (isset($keyword) && !empty($keyword)) {
            //  关键字检索[模板名称]
            $map['name'] = ['like', '%' . $keyword . '%'];
        }

        // 2.模板类型[0:全部;1:职位模板;2:企业模板;]
        if (isset($type) && !empty($type)) {
            $map['type'] = ['=', $type];
        }

        // 排序：ID倒序
        $order = ['id DESC'];

        // 查询字段
        $field = 'id as template_id, type, built_in, name, head, body, tail';

        try {
            $list = model('MarketingTemplate')
                ->getList($map, $order, $page_num, $page_size, $field);
            if (false === $list) {
                throw new Exception(model('MarketingTemplate')->getError());
            }

            $this->ajaxReturn(200, 'SUCCESS', $list);

        } catch (Exception $e) {
            $this->ajaxReturn(500, $e->getMessage());
        }

    }


    /**
     * @Purpose:
     * 营销模板 - 模板编辑（新增/修改）
     * @Method templateEdit()
     *
     * @param integer $template_id 营销模板ID
     * @param integer $type 模板类型[0:全部;1:职位模板;2:企业模板;]
     * @param string $name 模板名称
     * @param string $head 头部模板
     * @param string $body 内容（职位/企业）模板
     * @param string $tail 尾部模板
     *
     * @return Jsonp
     *
     * @throws null
     *
     * @link {domain}marketing/templateEdit
     *
     * @author  Administrator
     * @version 1.1
     * @since   2022/4/1
     */
    public function templateEdit()
    {
        // 接受请求参数
        $template_id = input('post.template_id/d', 0, 'intval');
        $type = input('post.type/s', '', 'trim');
        $name = input('post.name/s', '', 'trim');
        $head = input('post.head/s', '', null);
        $body = input('post.body/s', '', null);
        $tail = input('post.tail/s', '', null);

        // Validate验证数据
        $data = [
            'type' => $type,
            'name' => $name,
            'body' => $body
        ];

        // Validate验证规则
        $rule = [
            'type' => 'require|in:1,2',
            'name' => 'require|length:1,100',
            'body' => 'require'
        ];

        // Validate报错信息
        $msg = [
            'type.require' => '请选择模板类型',
            'type.in' => '请选择正确的模板类型',
            'name.require' => '请输入模板名称',
            'name.length' => '模板名称长度限制为1~100',
            'body.require' => '请填写模板正文'
        ];

        // 实例化`Validate`验证类
        $validate = new Validate($rule, $msg);
        if (!$validate->check($data)) {
            $this->ajaxReturn(500, $validate->getError());
        }

        // 判断模板名称是否重复
        $isRepeat = model('MarketingTemplate')
            ->where('name', '=', $name)
            ->where('id', '<>', $template_id)
            ->find();
        if (isset($isRepeat) && !empty($isRepeat)) {
            $this->ajaxReturn(500, '模板名称已存在');
        }

        /**
         * 根据`$template_id`参数判断写/更新操作
         * $template_id不为空，更新
         * $template_id为空，写入
         */
        if (isset($template_id) && !empty($template_id)) {
            /**
             * 修改营销模板
             */
            $isSet = model('MarketingTemplate')
                ->find($template_id);
            if (null === $isSet) {
                $this->ajaxReturn(500, '要修改的模板不存在');
            }

            if (1 === $isSet->built_in) {
                $this->ajaxReturn(500, '内置模板不可编辑');
            }

            Db::startTrans();
            try {
                // DB1：更新营销模板
                $update = model('MarketingTemplate')
                    ->allowField(true)
                    ->isUpdate(true)
                    ->save(
                        [
                            'name' => $name,
                            'head' => $head,
                            'body' => $body,
                            'tail' => $tail
                        ],
                        [
                            'id' => $template_id
                        ]
                    );
                if (false === $update) {
                    throw new Exception(model('MarketingTemplate')->getError());
                }

                // DB2：写入日志
                $log_result = model('AdminLog')->record(
                    '修改公众号营销模板。模板ID【' .
                    $template_id .
                    '】;模板名称【' .
                    $name .
                    '】',
                    $this->admininfo
                );

                if (false === $log_result) {
                    throw new Exception(model('AdminLog')->getError());
                }

                // 提交事务
                Db::commit();

                $this->ajaxReturn(200, '修改成功');

            } catch (\Exception $e) {
                // 回滚事务
                Db::rollBack();
                $this->ajaxReturn(500, $e->getMessage());
            }

        } else {
            /**
             * 新增营销模板
             */
            Db::startTrans();
            try {
                // DB1：写入新营销模板
                $insert = model('MarketingTemplate')
                    ->allowField(true)
                    ->isUpdate(false)
                    ->save([
                        'type' => $type,
                        'name' => $name,
                        'head' => $head,
                        'body' => $body,
                        'tail' => $tail
                    ]);
                if (false === $insert) {
                    throw new Exception(model('MarketingTemplate')->getError());
                }

                // DB2：写入日志
                $log_result = model('AdminLog')->record(
                    '新增公众号营销模板。模板ID【' .
                    model('MarketingTemplate')->id .
                    '】;模板名称【' .
                    $name .
                    '】',
                    $this->admininfo
                );

                if (false === $log_result) {
                    throw new Exception(model('AdminLog')->getError());
                }

                // 提交事务
                Db::commit();

                $this->ajaxReturn(200, '添加成功');

            } catch (\Exception $e) {
                // 回滚事务
                Db::rollBack();
                $this->ajaxReturn(500, $e->getMessage());
            }
        }
    }


    /**
     * @Purpose:
     * 营销模板 - 获取模板参数
     * @Method templateOption()
     *
     * @param null
     *
     * @return Jsonp
     *
     * @throws null
     *
     * @link {domain}marketing/templateOption
     *
     * @author  Administrator
     * @version 1.1
     * @since   2022/4/1
     */
    public function templateOption()
    {
        try {
            // 1.头部模板参数[head]
            $head_option = model('MarketingTemplate')->headOption;

            // 2.正文模板参数[body]
            $body_option = model('MarketingTemplate')->bodyOption;

            // 3.尾部模板参数[tail]
            $tail_option = model('MarketingTemplate')->tailOption;

            $return = [
                'head_option' => $head_option,
                'body_option' => $body_option,
                'tail_option' => $tail_option
            ];

            $this->ajaxReturn(200, 'SUCCESS', $return);

        } catch (\Exception $e) {
            $this->ajaxReturn(500, $e->getMessage());
        }
    }


    /**
     * @Purpose:
     * 营销模板 - 查看模板详情
     * @Method templateDetails()
     *
     * @param integer $template_id 营销模板ID
     *
     * @return Jsonp
     *
     * @throws null
     *
     * @link {domain}marketing/templateDetails
     *
     * @author  Administrator
     * @version 1.1
     * @since   2022/4/2
     */
    public function templateDetails()
    {
        $template_id = input('post.template_id/d', 0, 'intval');

        if (!isset($template_id) || empty($template_id)) {
            $this->ajaxReturn(500, '请选择要查看的模板');
        }

        $details = model('MarketingTemplate')
            ->field('id as template_id,
            type,
            built_in,
            name,
            head,
            body,
            tail,
            create_time,
            update_time')
            ->find($template_id);

        if (null === $details) {
            $this->ajaxReturn(500, '要查看的模板不存在');
        } else {
            $details = $details->toArray();
            $this->ajaxReturn(200, 'SUCCESS', $details);
        }

    }


    /**
     * @Purpose:
     * 营销模板 - 删除模板
     * @Method templateDelete()
     *
     * @param integer $template_id 营销模板ID
     *
     * @return Jsonp
     *
     * @throws null
     *
     * @link {domain}marketing/templateDelete
     *
     * @author  Administrator
     * @version 1.1
     * @since   2022/4/2
     */
    public function templateDelete()
    {
        $template_id = input('post.template_id/d', 0, 'intval');

        if (!isset($template_id) || empty($template_id)) {
            $this->ajaxReturn(500, '请选择要删除的模板');
        }

        $template_info = model('MarketingTemplate')
            ->field('id, built_in')
            ->find($template_id);

        if (null === $template_info) {
            $this->ajaxReturn(500, '要删除的模板不存在');
        } else {
            if (1 === $template_info->built_in) {
                $this->ajaxReturn(500, '内置模板不可删除');
            }
            $del_result = $template_info->delete();
            if (0 === $del_result) {
                $this->ajaxReturn(500, '删除失败');
            } else {
                $this->ajaxReturn(200, '删除成功');
            }
        }
    }


}
