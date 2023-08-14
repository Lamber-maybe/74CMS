<?php

namespace app\index\controller;

class Company extends \app\index\controller\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('navSelTag', 'company');
    }

    public function index()
    {
        if (is_mobile_request() === true) {
            $this->redirect(config('global_config.mobile_domain') . 'companylist', 302);
            exit;
        }
        $where = ['a.district1' => ['gt', 0]];
        $keyword = request()->route('keyword/s', '', 'trim');
        $trade = request()->route('trade/d', 0, 'intval');
        $nature = request()->route('nature/d', 0, 'intval');
        $district1 = request()->route('d1/d', 0, 'intval');
        $district2 = request()->route('d2/d', 0, 'intval');
        $district3 = request()->route('d3/d', 0, 'intval');
        $current_page = request()->get('page/d', 1, 'intval');
        $pagesize = request()->get('pagesize/d', 10, 'intval');
        if ($keyword != '') {
            $where['a.companyname'] = ['like', '%' . $keyword . '%'];
        }
        if ($trade > 0) {
            $where['a.trade'] = ['eq', $trade];
        }
        if ($nature > 0) {
            $where['a.nature'] = ['eq', $nature];
        }


        $subsiteCondition = get_subsite_condition('a');
        $subsite_district_level = 0;
        if (!empty($subsiteCondition)) {
            foreach ($subsiteCondition as $key => $value) {
                if ($key == 'a.district1') {
                    $district1 = $value;
                    $subsite_district_level = 1;
                    break;
                }
                if ($key == 'a.district2') {
                    $district2 = $value;
                    $subsite_district_level = 2;
                    break;
                }
                if ($key == 'a.district3') {
                    $district3 = $value;
                    $subsite_district_level = 3;
                    break;
                }
            }
        }


        if ($district3 > 0) {
            $where['a.district3'] = ['eq', $district3];
        } elseif ($district2 > 0) {
            $where['a.district2'] = ['eq', $district2];
        } elseif ($district1 > 0) {
            $where['a.district1'] = ['eq', $district1];
        }
        if ($this->subsite !== null && $this->subsite->district3 > 0) {
            $district_level = 0;
            $category_district = [];
        } else if ($district2 > 0) {
            $district_level = 3;
            $category_district = model('CategoryDistrict')->getCache($district2);
        } else if ($district1 > 0) {
            $district_level = 2;
            $category_district = model('CategoryDistrict')->getCache($district1);
        } else {
            $district_level = 1;
            $category_district = model('CategoryDistrict')->getCache('0');
        }
        $options_district = [];
        foreach ($category_district as $key => $value) {
            if ($district_level == 1) {
                $params = ['d1' => $key, 'd2' => null, 'd3' => null];
            } else if ($district_level == 2) {
                $params = ['d1' => $district1, 'd2' => $key, 'd3' => null];
            } else if ($district_level == 3) {
                $params = ['d1' => $district1, 'd2' => $district2, 'd3' => $key];
            }

            $arr['id'] = $key;
            $arr['url'] = P($params);
            $arr['text'] = $value;
            $options_district[] = $arr;
        }

        //根据显示状态筛选数据
        $where['a.is_display'] = 1;

        // $total = model('Company')->alias('a')->where($where)->count();
        $list = model('Company')
            ->alias('a')
            ->field(
                'a.id,a.companyname,a.logo,a.district1,a.district2,a.district3,a.district,a.scale,a.nature,a.trade,a.audit,a.setmeal_id,b.deadline as setmeal_deadline'
            )
            ->join(
                config('database.prefix') . 'member_setmeal b',
                'a.uid=b.uid',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'job_search_rtime c',
                'a.uid=c.uid',
                'LEFT'
            )
            ->where('c.id', 'not null')
            ->where($where)
            ->order('a.id desc')
            ->group('a.id')
            // ->paginate(['list_rows'=>$pagesize,'page'=>$current_page,'type'=>'\\app\\common\\lib\\Pager'],$total);
            ->paginate(['list_rows' => $pagesize, 'page' => $current_page, 'type' => '\\app\\common\\lib\\Pager']);
        $pagerHtml = $list->render();
        $job_list = $comid_arr = $logo_arr = $logo_id_arr = $setmeal_id_arr = $setmeal_list = [];
        foreach ($list as $key => $value) {
            $comid_arr[] = $value['id'];
            $value['logo'] > 0 && ($logo_id_arr[] = $value['logo']);
            $setmeal_id_arr[] = $value['setmeal_id'];
        }
        if (!empty($logo_id_arr)) {
            $logo_arr = model('Uploadfile')->getFileUrlBatch($logo_id_arr);
        }
        if (!empty($setmeal_id_arr)) {
            $setmeal_list = model('Setmeal')
                ->where('id', 'in', $setmeal_id_arr)
                ->column('id,icon,name', 'id');
        }
        if (!empty($comid_arr)) {
            $job_data = model('Job')
                ->where('company_id', 'in', $comid_arr)
                ->where('is_display', 1)
                ->where('audit', 1)
                ->column('id,company_id,jobname', 'id');
            foreach ($job_data as $key => $value) {
                $job_list[$value['company_id']][] = $value['jobname'];
            }
        }

        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $returnlist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['companyname'] = $value['companyname'];
            $tmp_arr['company_audit'] = $value['audit'];
            $tmp_arr['district_text'] = isset(
                $category_district_data[$value['district1']]
            )
                ? $category_district_data[$value['district1']]
                : '';
            if ($tmp_arr['district_text'] != '' && $value['district2'] > 0) {
                $tmp_arr['district_text'] .= isset(
                    $category_district_data[$value['district2']]
                )
                    ? ' / ' . $category_district_data[$value['district2']]
                    : '';
            }
            if ($tmp_arr['district_text'] != '' && $value['district3'] > 0) {
                $tmp_arr['district_text'] .= isset(
                    $category_district_data[$value['district3']]
                )
                    ? ' / ' . $category_district_data[$value['district3']]
                    : '';
            }
            $tmp_arr['trade_text'] = isset(
                $category_data['QS_trade'][$value['trade']]
            )
                ? $category_data['QS_trade'][$value['trade']]
                : '';
            $tmp_arr['scale_text'] = isset(
                $category_data['QS_scale'][$value['scale']]
            )
                ? $category_data['QS_scale'][$value['scale']]
                : '';
            $tmp_arr['nature_text'] = isset(
                $category_data['QS_company_type'][$value['nature']]
            )
                ? $category_data['QS_company_type'][$value['nature']]
                : '';
            $tmp_arr['jobnum'] = isset($job_list[$value['id']])
                ? count($job_list[$value['id']])
                : 0;
            $tmp_arr['first_jobname'] = isset($job_list[$value['id']])
                ? $job_list[$value['id']][0]
                : '';
            $tmp_arr['logo_src'] = isset($logo_arr[$value['logo']])
                ? $logo_arr[$value['logo']]
                : default_empty('logo');
            if (isset($setmeal_list[$value['setmeal_id']]) && ($value['setmeal_deadline'] > time() || $value['setmeal_deadline'] == 0)) {
                $tmp_arr['setmeal_icon'] =
                    $setmeal_list[$value['setmeal_id']]['icon'] > 0
                        ? model('Uploadfile')->getFileUrl(
                        $setmeal_list[$value['setmeal_id']]['icon']
                    )
                        : model('Setmeal')->getSysIcon($value['setmeal_id']);
            } else {
                $tmp_arr['setmeal_icon'] = '';
            }

            $returnlist[] = $tmp_arr;
        }

        $return['items'] = $returnlist;

        $category_all = model('Category')->getCache('');
        $options_trade = $category_all['QS_trade'];
        $options_scale = $category_all['QS_scale'];
        $options_nature = $category_all['QS_company_type'];
        $hot_company_list = $this->getHotlist(0);

        $seoData['keyword'] = $keyword;
        $seoData['trade'] = isset($category_data['QS_trade'][$trade]) ? $category_data['QS_trade'][$trade] : '';
        if ($district3 > 0) {
            $seoData['citycategory'] = isset($category_district_data[$district3]) ? $category_district_data[$district3] : '';
        } else if ($district2 > 0) {
            $seoData['citycategory'] = isset($category_district_data[$district2]) ? $category_district_data[$district2] : '';
        } else if ($district1 > 0) {
            $seoData['citycategory'] = isset($category_district_data[$district1]) ? $category_district_data[$district1] : '';
        } else {
            $seoData['citycategory'] = '';
        }
        $seoData['nature'] = isset($category_data['QS_company_type'][$nature]) ? $category_data['QS_company_type'][$nature] : '';

        $this->initPageSeo('companylist', $seoData);
        $this->assign('subsite_district_level', $subsite_district_level);
        $this->assign('dataset', $return);
        $this->assign('pagerHtml', $pagerHtml);
        $this->assign('district_level', $district_level);
        $this->assign('options_district', $options_district);
        $this->assign('options_trade', $options_trade);
        $this->assign('options_nature', $options_nature);
        $this->assign('hot_company_list', $hot_company_list);
        $this->assign('pageHeader', $this->pageHeader);
        return $this->fetch('index');
    }

    public function show()
    {
        $id = request()->route('id/d', 0, 'intval');
        if (is_mobile_request() === true) {
            $this->redirect(config('global_config.mobile_domain') . 'company/' . $id, 302);
            exit;
        }
        $field_rule_data = model('FieldRule')->getCache();
        $field_rule = [
            'basic' => $field_rule_data['Company'],
            'contact' => $field_rule_data['CompanyContact'],
            'info' => $field_rule_data['CompanyInfo']
        ];
        foreach ($field_rule as $key => $rule) {
            foreach ($rule as $field => $field_attr) {
                $_arr = [
                    'field_name' => $field_attr['field_name'],
                    'is_require' => intval($field_attr['is_require']),
                    'is_display' => intval($field_attr['is_display']),
                    'field_cn' => $field_attr['field_cn']
                ];
                $field_rule[$key][$field] = $_arr;
            }
        }
        //读取页面缓存配置
        $pageCache = model('Page')->getCache('companyshow');
        //如果缓存有效期为0，则不使用缓存
        if ($pageCache['expire'] > 0) {
            $return = model('Page')->getCacheByAlias('companyshow', $id);
        } else {
            $return = false;
        }
        if (!$return) {
            $return = $this->writeShowCache($id, $pageCache);
            if ($return === false) {
                abort(404, '页面不存在');
            }
        }

        //获取企业登录信息
        $company = model('Company')->where(['id' => $id])->field('uid,audit')->find();
        $uid = !empty($company) ? $company['uid'] : 0;
        $logo = model('member')->where(['uid' => $uid])->field('last_login_ip,last_login_time,last_login_address')->find();
        $logo['audit'] = $company['audit'];

        /**
         * 【优化】PC风险提示显示形式
         * IP最后一位以*加密
         * zch 2022/7/22
         */
        if (!empty($logo['last_login_ip'])) {
            $dot = strripos($logo['last_login_ip'], "."); //查找“.”最后出现的位置
            $logo['last_login_ip'] = substr($logo['last_login_ip'], 0, $dot) . ".*"; //输出“.”最后出现位置之前的字符串并加上*号
        } else {
            $logo['last_login_ip'] = '';
        }

        /**
         * 【优化】
         * 【ID1000339】 - 旧数据没有登录信息导致的报错
         * yx - 2022.09.29
         */
        $logo['last_login_address'] = !empty($logo['last_login_address']) ? $logo['last_login_address'] : '';
        $logo['last_login_time'] = !empty($logo['last_login_time']) ? $logo['last_login_time'] : 0;

        $last_login_time = '';
        if (!empty($logo) && $logo['last_login_time'] > 0) {
            $last_login_time = date('Y/m/d H:i:s', $logo['last_login_time']);
        }
        $logo['last_time'] = $last_login_time;
        $this->assign('logo', $logo);
	
        $return['field_rule'] = $field_rule;
        $return['share_url'] = config('global_config.mobile_domain') . 'company/' . $return['base_info']['id'];
        $seoData['companyname'] = $return['base_info']['companyname'];
        $seoData['content'] = $return['base_info']['short_desc'] == '' ? cut_str(strip_tags($return['base_info']['content']), 100) : $return['base_info']['short_desc'];
        $this->initPageSeo('companyshow', $seoData);
        $this->assign('return', $return);
        $this->assign('pageHeader', $this->pageHeader);

        $company_show_tpl = !empty(config('global_config.company_show_tpl')) ? config('global_config.company_show_tpl') : 'def';
        return $this->fetch('company/showTpl/' . $company_show_tpl . '/show');
    }

    protected function writeShowCache($id, $pageCache)
    {
        $cominfo = model('Company')
            ->where('id', 'eq', $id)
            ->field(true)
            ->find();
        if ($cominfo === null) {
            return false;
        }
        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $base_info['id'] = $cominfo['id'];
        $base_info['logo_src'] =
            $cominfo['logo'] > 0
                ? model('Uploadfile')->getFileUrl($cominfo['logo'])
                : default_empty('logo');
        $base_info['companyname'] = $cominfo['companyname'];
        $base_info['audit'] = $cominfo['audit'];
        $base_info['map_lat'] = $cominfo['map_lat'];
        $base_info['map_lng'] = $cominfo['map_lng'];
        $base_info['map_zoom'] = $cominfo['map_zoom'];
        $base_info['nature_text'] = isset(
            $category_data['QS_company_type'][$cominfo['nature']]
        )
            ? $category_data['QS_company_type'][$cominfo['nature']]
            : '';
        $base_info['trade_text'] = isset(
            $category_data['QS_trade'][$cominfo['trade']]
        )
            ? $category_data['QS_trade'][$cominfo['trade']]
            : '';
        $base_info['district_text'] = isset(
            $category_district_data[$cominfo['district1']]
        )
            ? $category_district_data[$cominfo['district1']]
            : '';
        if ($base_info['district_text'] != '' && $cominfo['district2'] > 0) {
            $base_info['district_text'] .= isset(
                $category_district_data[$cominfo['district2']]
            )
                ? ' / ' . $category_district_data[$cominfo['district2']]
                : '';
        }
        if ($base_info['district_text'] != '' && $cominfo['district3'] > 0) {
            $base_info['district_text'] .= isset(
                $category_district_data[$cominfo['district3']]
            )
                ? ' / ' . $category_district_data[$cominfo['district3']]
                : '';
        }
        $base_info['scale_text'] = isset(
            $category_data['QS_scale'][$cominfo['scale']]
        )
            ? $category_data['QS_scale'][$cominfo['scale']]
            : '';
        $base_info['tag_text_arr'] = [];
        if ($cominfo['tag'] != '') {
            $tag_arr = explode(',', $cominfo['tag']);
            foreach ($tag_arr as $k => $v) {
                if (is_numeric($v) && isset($category_data['QS_jobtag'][$v])) {
                    $base_info['tag_text_arr'][] =
                        $category_data['QS_jobtag'][$v];
                } else {
                    $base_info['tag_text_arr'][] = $v;
                }
            }
        }
        //详细信息
        $detail_info = model('CompanyInfo')
            ->field('id,comid,uid', true)
            ->where('comid', $base_info['id'])
            ->find();
        $base_info['address'] = $detail_info['address'];
        $base_info['website'] = $detail_info['website'];
        $base_info['short_desc'] = $detail_info['short_desc'];
        $base_info['content'] = $detail_info['content'];

        //套餐
        $setmeal = model('MemberSetmeal')
            ->alias('a')
            ->field('b.*,a.deadline as setmeal_deadline')
            ->join(
                config('database.prefix') . 'setmeal b',
                'a.setmeal_id=b.id',
                'LEFT'
            )
            ->where('a.uid', $cominfo['uid'])
            ->find();
        if ($setmeal !== null && ($setmeal['setmeal_deadline'] > time() || $setmeal['setmeal_deadline'] == 0)) {
            $base_info['setmeal_icon'] =
                $setmeal['icon'] > 0
                    ? model('Uploadfile')->getFileUrl($setmeal['icon'])
                    : model('Setmeal')->getSysIcon($cominfo['setmeal_id']);
        } else {
            $base_info['setmeal_icon'] = '';
        }
        $return['base_info'] = $base_info;
        $return['near_district_list'] = $this->getNearDistrict($cominfo['district1'], $cominfo['district2'], $cominfo['district3']);
        $return['hotword_list'] = $this->getHotword();
        $return['similar_company_list'] = $this->getSimilarList($cominfo['id'], $cominfo['trade']);
        $return['hot_company_list'] = $this->getHotlist($cominfo['id']);
        if ($pageCache['expire'] > 0) {
            model('Page')->writeCacheByAlias('companyshow', $return, $pageCache['expire'], $id);
        }
        return $return;
    }

    /**
     * 热门企业
     */
    protected function getHotlist($id = 0)
    {
        $returnlist = [];
        $famous_enterprises_setmeal = config(
            'global_config.famous_enterprises'
        );
        $famous_enterprises_setmeal =
            $famous_enterprises_setmeal == ''
                ? []
                : explode(',', $famous_enterprises_setmeal);
        if (empty($famous_enterprises_setmeal)) {
            return $returnlist;
        }
        $subsiteCondition = get_subsite_condition('c');
        $list = model('Company')
            ->alias('c')
            ->join(
                config('database.prefix') . 'member_setmeal s',
                's.uid=c.uid',
                'LEFT'
            )
            ->join(
                config('database.prefix') . 'job_search_rtime d',
                'c.uid=d.uid',
                'LEFT'
            )
            ->where('d.id', 'not null')
            ->where('c.is_display', 1)
            ->where($subsiteCondition)
            ->group('c.id');
        if ($id > 0) {
            $list = $list->where('c.id', 'neq', $id);
        }
        $list = $list->where('s.setmeal_id', 'in', $famous_enterprises_setmeal)
            ->field('c.id,c.logo,c.companyname')
            ->order('c.click desc,c.refreshtime desc')
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
        foreach ($list as $key => $value) {
            $arr = $value->toArray();
            $arr['logo_src'] = isset($logo_arr[$value['logo']])
                ? $logo_arr[$arr['logo']]
                : default_empty('logo');
            $arr['jobnum'] = isset($job_list[$value['id']])
                ? count($job_list[$arr['id']])
                : 0;
            $returnlist[] = $arr;
        }
        return $returnlist;
    }

    protected function getSimilarList($id, $trade)
    {
        $subsiteCondition = get_subsite_condition('a');
        $list = model('Company')
            ->alias('a')
            ->field(
                'distinct a.id,a.companyname,a.logo,a.district,a.scale,a.nature,a.trade,a.audit,a.setmeal_id,b.deadline as setmeal_deadline'
            )
            ->join(
                config('database.prefix') . 'member_setmeal b',
                'a.uid=b.uid',
                'LEFT'
            )->join(
                config('database.prefix') . 'job_search_rtime c',
                'a.uid=c.uid',
                'LEFT'
            )
            ->where('c.id', 'not null')
            ->where('a.is_display', 1)
            ->where($subsiteCondition)
            ->where('a.id', 'neq', $id)
            ->where('a.trade', $trade)
            ->order('a.id desc')
            ->page(1, 10)
            ->select();
        $job_list = $comid_arr = $logo_arr = $logo_id_arr = $setmeal_id_arr = $setmeal_list = [];
        foreach ($list as $key => $value) {
            $comid_arr[] = $value['id'];
            $value['logo'] > 0 && ($logo_id_arr[] = $value['logo']);
            $setmeal_id_arr[] = $value['setmeal_id'];
        }
        if (!empty($logo_id_arr)) {
            $logo_arr = model('Uploadfile')->getFileUrlBatch($logo_id_arr);
        }
        if (!empty($setmeal_id_arr)) {
            $setmeal_list = model('Setmeal')
                ->where('id', 'in', $setmeal_id_arr)
                ->column('id,icon,name', 'id');
        }
        if (!empty($comid_arr)) {
            $job_data = model('Job')
                ->where('company_id', 'in', $comid_arr)
                ->where('is_display', 1)
                ->where('audit', 1)
                ->column('id,company_id,jobname', 'id');
            foreach ($job_data as $key => $value) {
                $job_list[$value['company_id']][] = $value['jobname'];
            }
        }

        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $returnlist = [];
        foreach ($list as $key => $value) {
            $tmp_arr = [];
            $tmp_arr['id'] = $value['id'];
            $tmp_arr['companyname'] = $value['companyname'];
            $tmp_arr['company_audit'] = $value['audit'];
            $tmp_arr['district_text'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $tmp_arr['trade_text'] = isset(
                $category_data['QS_trade'][$value['trade']]
            )
                ? $category_data['QS_trade'][$value['trade']]
                : '';
            $tmp_arr['scale_text'] = isset(
                $category_data['QS_scale'][$value['scale']]
            )
                ? $category_data['QS_scale'][$value['scale']]
                : '';
            $tmp_arr['nature_text'] = isset(
                $category_data['QS_company_type'][$value['nature']]
            )
                ? $category_data['QS_company_type'][$value['nature']]
                : '';
            $tmp_arr['jobnum'] = isset($job_list[$value['id']])
                ? count($job_list[$value['id']])
                : 0;
            $tmp_arr['first_jobname'] = isset($job_list[$value['id']])
                ? $job_list[$value['id']][0]
                : '';
            $tmp_arr['logo_src'] = isset($logo_arr[$value['logo']])
                ? $logo_arr[$value['logo']]
                : default_empty('logo');
            if (isset($setmeal_list[$value['setmeal_id']]) && ($value['setmeal_deadline'] > time() || $value['setmeal_deadline'] == 0)) {
                $tmp_arr['setmeal_icon'] =
                    $setmeal_list[$value['setmeal_id']]['icon'] > 0
                        ? model('Uploadfile')->getFileUrl(
                        $setmeal_list[$value['setmeal_id']]['icon']
                    )
                        : model('Setmeal')->getSysIcon($value['setmeal_id']);
            } else {
                $tmp_arr['setmeal_icon'] = '';
            }

            $returnlist[] = $tmp_arr;
        }
        return $returnlist;
    }

    protected function getNearDistrict($district1, $district2, $district3)
    {
        if ($district2 == 0) {
            $level = 1;
            $parentDistrict = 0;
        } else if ($district3 = 0) {
            $level = 2;
            $parentDistrict = $district1;
        } else {
            $level = 3;
            $parentDistrict = $district2;
        }
        $district_list = model('CategoryDistrict')->getCache($parentDistrict);
        $return = [];
        foreach ($district_list as $key => $value) {
            if ($level == 1) {
                $params = ['d1' => $key, 'd2' => 0, 'd3' => 0];
            } else if ($level == 2) {
                $params = ['d1' => $district1, 'd2' => $key, 'd3' => 0];
            } else if ($level == 3) {
                $params = ['d1' => $district1, 'd2' => $district2, 'd3' => $key];
            }
            $return[] = ['id' => $key, 'text' => $value, 'params' => $params];
        }
        return $return;
    }

    /**
     * 热门关键词
     */
    protected function getHotword()
    {
        return model('Hotword')->getList(49);
    }

    /**
     * 实地认证报告
     */
    public function report()
    {
        $id = request()->route('id/d', 0, 'intval');
        $info = model('CompanyReport')->alias('a')
            ->join(config('database.prefix') . 'company b', 'a.uid=b.uid', 'LEFT')
            ->where('a.company_id', $id)
            ->field('a.*,b.companyname')
            ->find();
        if ($info === null) {
            abort(404, '实地认证信息为空');
        }
        $img_arr = model('CompanyImg')->getList($id);
        $this->pageHeader['title'] = '实地认证报告 - ' . $info['companyname'] . ' - ' . $this->pageHeader['title'];
        $this->assign('pageHeader', $this->pageHeader);
        $this->assign('info', $info);
        $this->assign('img_arr', $img_arr);
        return $this->fetch('report');
    }
}
