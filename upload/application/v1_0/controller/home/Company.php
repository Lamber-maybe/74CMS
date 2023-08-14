<?php
namespace app\v1_0\controller\home;

use think\Db;

class Company extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
    }
    public function index()
    {
        $where = ['a.district1'=>['gt',0]];
        $famous = input('get.famous/d', 0, 'intval');
        $keyword = input('get.keyword/s', '', 'trim,addslashes');
        $district1 = input('get.district1/d', 0, 'intval');
        $district2 = input('get.district2/d', 0, 'intval');
        $district3 = input('get.district3/d', 0, 'intval');
        $trade = input('get.trade/d', 0, 'intval');
        $scale = input('get.scale/d', 0, 'intval');
        $nature = input('get.nature/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 10, 'intval');
        if ($keyword != '') {
            $where['a.companyname'] = ['like', '%' . $keyword . '%'];
        }
        if($famous==1){
            $famous_enterprises_setmeal = config(
                'global_config.famous_enterprises'
            );
            $famous_enterprises_setmeal =
                $famous_enterprises_setmeal == ''
                    ? []
                    : explode(',', $famous_enterprises_setmeal);
            if(!empty($famous_enterprises_setmeal)){
                $where['a.setmeal_id'] = ['in',$famous_enterprises_setmeal];
            }
        }
        
        $subsiteCondition = get_subsite_condition('a');
        if(!empty($subsiteCondition)){
            foreach ($subsiteCondition as $key => $value) {
                if($key=='a.district1'){
                    $district1 = $value;
                    break;
                }
                if($key=='a.district2'){
                    $district2 = $value;
                    break;
                }
                if($key=='a.district3'){
                    $district3 = $value;
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
        if ($trade > 0) {
            $where['a.trade'] = ['eq', $trade];
        }
        if ($scale > 0) {
            $where['a.scale'] = ['eq', $scale];
        }
        if ($nature > 0) {
            $where['a.nature'] = ['eq', $nature];
        }
        $where['a.is_display'] = 1;

        $list = model('Company')
            ->alias('a')
            ->field(
                'distinct a.id,a.companyname,a.logo,a.district,a.scale,a.nature,a.trade,a.audit,a.setmeal_id,b.deadline as setmeal_deadline,a.tag'
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
            ->where('c.id','not null')
            ->where($where)
            ->order('a.id desc')
            ->page($current_page, $pagesize)
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

            $scale = model('Category')->getCache('QS_scale');
            $job_data = model('Job')
                ->alias('j')
                ->join('company c','c.id=j.company_id','left')
                ->where('j.company_id', 'in', $comid_arr)
                ->where('j.is_display', 1)
                ->where('j.audit', 1)
                ->field('j.id,j.jobname,j.district1,j.district2,j.district3,j.minwage,j.maxwage,c.scale,j.education,j.company_id,j.negotiable')
                ->select();

            $category_district_data = model('CategoryDistrict')->getCache();

            foreach ($job_data as $key => $value) {
                $value['district1_text'] = isset($category_district_data[$value['district1']]) ? $category_district_data[$value['district1']] : '';
                $value['district2_text'] = isset($category_district_data[$value['district2']]) ? $category_district_data[$value['district2']] : '';
                $value['district3_text'] = isset($category_district_data[$value['district3']]) ? $category_district_data[$value['district3']] : '';
                $value['education_text'] = isset(
                    model('BaseModel')->map_education[$value['education']]
                )
                    ? model('BaseModel')->map_education[$value['education']]
                    : '学历不限';
                $value['wage_text'] = model('BaseModel')->handle_wage(
                    $value['minwage'],
                    $value['maxwage'],
                    $value['negotiable']
                );

                $value['scale_text'] = isset($scale[$value['scale']]) ? $scale[$value['scale']] : '';

                $job_list[$value['company_id']][] = $value->toArray();
            }
        }

        $category_data = model('Category')->getCache();
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
                ? $job_list[$value['id']][0]['jobname']
                : '';
            $tmp_arr['job_list'] = isset($job_list[$value['id']])
                ? $job_list[$value['id']] : [];
            $tmp_arr['logo_src'] = isset($logo_arr[$value['logo']])
                ? $logo_arr[$value['logo']]
                : default_empty('logo');
            if (isset($setmeal_list[$value['setmeal_id']]) && ($value['setmeal_deadline']>time() || $value['setmeal_deadline']==0)) {
                $tmp_arr['setmeal_icon'] =
                    $setmeal_list[$value['setmeal_id']]['icon'] > 0
                        ? model('Uploadfile')->getFileUrl(
                            $setmeal_list[$value['setmeal_id']]['icon']
                        )
                        : model('Setmeal')->getSysIcon($value['setmeal_id']);
            } else {
                $tmp_arr['setmeal_icon'] = '';
            }

            /**
             * 【新增】企业福利标签
             */
            $tmp_arr['tag_text_arr'] = [];
            if ($value['tag'] != '') {
                $tag_arr = explode(',', $value['tag']);
                foreach ($tag_arr as $tag_id) {
                    if (is_numeric($tag_id) && isset($category_data['QS_jobtag'][$tag_id])) {
                        $tmp_arr['tag_text_arr'][] =
                            $category_data['QS_jobtag'][$tag_id];
                    } else {
                        $tmp_arr['tag_text_arr'][] = $tag_id;
                    }
                }
            }

            $returnlist[] = $tmp_arr;
        }

        $return['items'] = $returnlist;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function show()
    {
        $id = input('get.id/d', 0, 'intval');
        $cominfo = model('Company')
            ->where('id', 'eq', $id)
            ->field(true)
            ->find();
        if ($cominfo === null) {
            $this->ajaxReturn(500, '企业信息为空');
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
            $category_district_data[$cominfo['district']]
        )
            ? $category_district_data[$cominfo['district']]
            : '';
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
        if ($setmeal !== null && ($setmeal['setmeal_deadline']>time() || $setmeal['setmeal_deadline']==0)) {
            $base_info['setmeal_icon'] =
                $setmeal['icon'] > 0
                    ? model('Uploadfile')->getFileUrl($setmeal['icon'])
                    : model('Setmeal')->getSysIcon($cominfo['setmeal_id']);
        } else {
            $base_info['setmeal_icon'] = '';
        }

        //企业风采
        $img_list = $this->getCompanyImg($base_info['id']);
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
        $apply_map['company_uid'] = $cominfo['uid'];
        $endtime = time();
        $starttime = $endtime - 3600 * 24 * 14;
        $apply_map['addtime'] = ['between', [$starttime, $endtime]];
        $apply_data = model('JobApply')
            ->field('id,is_look')
            ->where($apply_map)
            ->select();
        if (!empty($apply_data)) {
            $total = $looked = 0;
            foreach ($apply_data as $key => $value) {
                $value['is_look'] == 1 && $looked++;
                $total++;
            }
            $return['watch_percent'] = round($looked / $total, 2) * 100 . '%';
        } else {
            $return['watch_percent'] = '100%';
        }
        $return['fans'] = model('AttentionCompany')
            ->where('company_uid', 'eq', $cominfo['uid'])
            ->count();
        $report = model('CompanyReport')
            ->where('company_id', $base_info['id'])
            ->field('id')
            ->find();
        if ($report === null) {
            $return['report'] = 0;
        } else {
            $return['report'] = 1;
        }
        $coordinate = model('Config')->bd09ToWgs84($base_info['map_lng'],$base_info['map_lat']);
        $base_info['map_lng'] = $coordinate['lng'];
        $base_info['map_lat'] = $coordinate['lat'];
        $return['base_info'] = $base_info;
        $return['img_list'] = $img_list;
        $return['field_rule'] = $field_rule;
        if ($this->userinfo != null && $this->userinfo->utype == 2) {
            $attention_info = model('AttentionCompany')
                ->where('comid', $id)
                ->where('personal_uid', $this->userinfo->uid)
                ->find();
            if ($attention_info === null) {
                $return['has_attention'] = 0;
            } else {
                $return['has_attention'] = 1;
            }
        } else {
            $return['has_attention'] = 0;
        }
        $job_list = model('Job')
                ->field('id,jobname')
                ->where('company_id', 'eq', $base_info['id'])
                ->where('is_display', 1)
                ->where('audit', 1)
                ->select();
        $return['base_info']['jobnum'] = count($job_list);
        $return['share_url'] = $this->sub_site_domain_m.'company/'.$base_info['id'];
        model('Job')->addViewLog($base_info['id']);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    protected function getCompanyImg($company_id)
    {
        $list = model('CompanyImg')
            ->alias('a')
            ->join(
                config('database.prefix') . 'uploadfile b',
                'a.img=b.id',
                'LEFT'
            )
            ->field('b.save_path,b.platform,a.title')
            ->where('a.comid', $company_id)
            ->where('a.audit', 1)
            ->limit(6)
            ->select();
        $return = [];
        foreach ($list as $key => $value) {
            $arr['title'] = $value['title'];
            $arr['img_src'] = make_file_url(
                $value['save_path'],
                $value['platform']
            );
            $return[] = $arr;
        }
        return $return;
    }
    public function report()
    {
        $id = input('get.id/d', 0, 'intval');
        $info = model('CompanyReport')
            ->where('company_id', $id)
            ->field('evaluation,certifier,addtime')
            ->find();
        if ($info === null) {
            $this->ajaxReturn(500, '实地认证信息为空');
        }
        $return = [
            'evaluation' => $info['evaluation'],
            'certifier' => $info['certifier'],
            'addtime' => date('Y-m-d', $info['addtime'])
        ];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function supplementary(){
        $id = input('get.id/d', 0, 'intval');
        $cominfo = model('Company')
            ->where('id', 'eq', $id)
            ->field('id,uid,addtime')
            ->find();
        if ($cominfo === null) {
            $this->ajaxReturn(200,'获取数据成功',null);
        }
        //在招职位数
        $return['jobnum'] = model('Job')->where('company_id', 'eq', $id)->where('is_display', 1)->where('audit', 1)->count();
        //简历查看率
        $endtime = time();
        $starttime = $endtime - 3600 * 24 * 14;
        $apply_data = model('JobApply')
            ->field('id,is_look')
            ->where('comid',$id)
            ->where('addtime','between',[$starttime, $endtime])
            ->select();
        if (!empty($apply_data)) {
            $total = $looked = 0;
            foreach ($apply_data as $key => $value) {
                $value['is_look'] == 1 && $looked++;
                $total++;
            }
            $return['watch_percent'] = round($looked / $total, 2) * 100 . '%';
        } else {
            $return['watch_percent'] = '100%';
        }
        //最近登录时间
        $last_login_time = model('Member')
            ->field('last_login_time')
            ->where('uid', 'eq', $cominfo['uid'])
            ->find();
        $return['last_login_time'] = $last_login_time['last_login_time'] == 0 ? '从未登录' : format_last_login_time($last_login_time['last_login_time']);
        //入驻时长
        $return['reg_duration'] = $this->getDuration($cominfo['addtime']);
        //粉丝数
        $return['fans'] = model('AttentionCompany')
            ->where('company_uid', 'eq', $cominfo['uid'])
            ->count();
        //是否实地认证
        $report = model('CompanyReport')
            ->where('company_id', $id)
            ->field('id')
            ->find();
        if ($report === null) {
            $return['report'] = 0;
        } else {
            $return['report'] = 1;
        }
        $return['img_list'] = $this->getCompanyImg($cominfo['id']);
        if ($this->userinfo != null && $this->userinfo->utype == 2) {
            $attention_info = model('AttentionCompany')
                ->where('comid', $id)
                ->where('personal_uid', $this->userinfo->uid)
                ->find();
            if ($attention_info === null) {
                $has_attention = 0;
            } else {
                $has_attention = 1;
            }
        } else {
            $has_attention = 0;
        }
        $return['has_attention'] = $has_attention;
        $this->ajaxReturn(200,'获取数据成功',$return);
    }
    public function click(){
        $id = input('post.id/d',0,'intval');
        $cominfo = model('Company')
            ->where('id', 'eq', $id)
            ->field('id,uid,click')
            ->find();
        if ($cominfo !== null) {
            model('Company')->addViewLog($cominfo['id']);
            $click = $cominfo['click']+1;
        }else{
            $click = 0;
        }
        $this->ajaxReturn(200, '数据添加成功',$click);
    }
    /**
     * 根据行业获取企业列表
     */
    public function listByTrade(){
        $limit = input('get.limit/d',10,'intval');
        $trade = input('get.trade/d',0,'intval');
        $list = model('Company');
        if($trade==0){
            $trade_arr = model('Category')->getCache('QS_trade');
            $trade = key($trade_arr);
            $list = $list->where('trade',$trade);
        }else if($trade==-1){
            $trade_arr = model('Category')->getCache('QS_trade');
            $trade_id_arr = [];
            $counter = 0;
            foreach ($trade_arr as $key => $value) {
                $counter++;
                if($counter<16){
                    continue;
                }
                $trade_id_arr[] = $key;
            }
            if(!empty($trade_id_arr)){
                $list = $list->whereIn('trade',$trade_id_arr);
            }else{
                $list = $list->where('id',0);
            }
        }else{
            $list = $list->where('trade',$trade);
        }
        $subsiteCondition = get_subsite_condition();
        $list = $list->where('is_display',1)->where($subsiteCondition)->order('refreshtime desc')->limit($limit)->column('id,companyname');
        $return = [];
        foreach ($list as $key => $value) {
            $arr['id'] = $key;
            $arr['companyname'] = $value;
            $arr['web_link'] = url('index/company/show', ['id' => $key], true, $this->sub_site_domain);
            $arr['mobile_link'] = $this->sub_site_domain_m.'company/'.$key;
            $return[] = $arr;
        }
        $this->ajaxReturn(200, '获取数据成功',['items'=>$return,'trade'=>$trade]);
    }
}
