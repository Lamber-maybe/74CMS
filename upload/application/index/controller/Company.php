<?php
namespace app\index\controller;

class Company extends \app\index\controller\Base
{
    public function _initialize(){
        parent::_initialize();
        $this->assign('navSelTag','company');
    }
    public function index()
    {
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'companylist',302);
            exit;
        }
        $where = ['district1'=>['gt',0]];
        $keyword = request()->route('keyword/s','','trim');
        $trade = request()->route('trade/d',0,'intval');
        $nature = request()->route('nature/d',0,'intval');
        $district1 = request()->route('d1/d',0,'intval');
        $district2 = request()->route('d2/d',0,'intval');
        $district3 = request()->route('d3/d',0,'intval');
        $current_page = request()->get('page/d',1,'intval');
        $pagesize = request()->get('pagesize/d',10,'intval');
        if ($keyword != '') {
            $where['companyname'] = ['like', '%' . $keyword . '%'];
        }
        if ($trade > 0) {
            $where['trade'] = ['eq', $trade];
        }
        if ($nature > 0) {
            $where['nature'] = ['eq', $nature];
        }
        if ($district3 > 0) {
            $where['district3'] = ['eq', $district3];
        } elseif ($district2 > 0) {
            $where['district2'] = ['eq', $district2];
        } elseif ($district1 > 0) {
            $where['district1'] = ['eq', $district1];
        }
        if($district2>0){
            $district_level = 3;
            $category_district = model('CategoryDistrict')->getCache($district2);
        }else if($district1>0){
            $district_level = 2;
            $category_district = model('CategoryDistrict')->getCache($district1);
        }else {
            $district_level = 1;
            $category_district = model('CategoryDistrict')->getCache('0');
        }
        $options_district = [];
        foreach ($category_district as $key => $value) {
            if($district_level==1){
                $params = ['d1'=>$key,'d2'=>null,'d3'=>null];
            }else if($district_level==2){
                $params = ['d1'=>$district1,'d2'=>$key,'d3'=>null];
            }else if($district_level==3){
                $params = ['d1'=>$district1,'d2'=>$district2,'d3'=>$key];
            }
            
            $arr['id'] = $key;
            $arr['url'] = P($params);
            $arr['text'] = $value;
            $options_district[] = $arr;
        }

        $total = model('Company')->where($where)->count();
        $list = model('Company')
            ->field(
                'id,companyname,logo,district,scale,nature,trade,audit,setmeal_id'
            )
            ->where($where)
            ->order('id desc')
            ->paginate(['list_rows'=>$pagesize,'page'=>$current_page,'type'=>'\\app\\common\\lib\\Pager'],$total);
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
            if (isset($setmeal_list[$value['setmeal_id']])) {
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
        if($district3>0){
            $seoData['citycategory'] = isset($category_district_data[$district3]) ? $category_district_data[$district3] : '';
        }else if($district2>0){
            $seoData['citycategory'] = isset($category_district_data[$district2]) ? $category_district_data[$district2] : '';
        }else if($district1>0){
            $seoData['citycategory'] = isset($category_district_data[$district1]) ? $category_district_data[$district1] : '';
        }else{
            $seoData['citycategory'] = '';
        }
        $seoData['nature'] = isset($category_data['QS_company_type'][$nature]) ? $category_data['QS_company_type'][$nature] : '';

        $this->initPageSeo('companylist',$seoData);

        $this->assign('dataset',$return);
        $this->assign('pagerHtml',$pagerHtml);
        $this->assign('district_level',$district_level);
        $this->assign('options_district',$options_district);
        $this->assign('options_trade',$options_trade);
        $this->assign('options_nature',$options_nature);
        $this->assign('hot_company_list',$hot_company_list);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('index');
    }
    public function show()
    {
        $id = request()->route('id/d',0,'intval');
        if(is_mobile_request()===true){
            $this->redirect(config('global_config.mobile_domain').'company/'.$id,302);
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
                    'is_require' => $field_attr['is_require'],
                    'is_display' => $field_attr['is_display'],
                    'field_cn' => $field_attr['field_cn']
                ];
                $field_rule[$key][$field] = $_arr;
            }
        }
        //读取页面缓存配置
        $pageCache = model('Page')->getCache('companyshow');
        //如果缓存有效期为0，则不使用缓存
        if($pageCache['expire']>0){
            $return = model('Page')->getCacheByAlias('companyshow',$id);
        }else{
            $return = false;
        }
        if (!$return) {
            $return = $this->writeShowCache($id,$pageCache);
            if($return===false){
                abort(404,'页面不存在');
            }
        }
        $return['field_rule'] = $field_rule;
        $return['share_url'] = config('global_config.mobile_domain').'company/'.$return['base_info']['id'];
        $seoData['companyname'] = $return['base_info']['companyname'];
        $seoData['content'] = $return['base_info']['short_desc']==''?cut_str(strip_tags($return['base_info']['content']),100):$return['base_info']['short_desc'];
        $this->initPageSeo('companyshow',$seoData);
        $this->assign('return',$return);
        $this->assign('pageHeader',$this->pageHeader);
        return $this->fetch('show');
    }
    protected function writeShowCache($id,$pageCache){
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
        $setmeal = model('Setmeal')
            ->where('id', $cominfo['setmeal_id'])
            ->find();
        if ($setmeal !== null) {
            $base_info['setmeal_icon'] =
                $setmeal['icon'] > 0
                    ? model('Uploadfile')->getFileUrl($setmeal['icon'])
                    : model('Setmeal')->getSysIcon($cominfo['setmeal_id']);
        } else {
            $base_info['setmeal_icon'] = '';
        }
        $return['base_info'] = $base_info;
        $return['near_district_list'] = $this->getNearDistrict($cominfo['district1'],$cominfo['district2'],$cominfo['district3']);
        $return['hotword_list'] = $this->getHotword();
        $return['similar_company_list'] = $this->getSimilarList($cominfo['id'],$cominfo['trade']);
        $return['hot_company_list'] = $this->getHotlist($cominfo['id']);
        if($pageCache['expire']>0){
            model('Page')->writeCacheByAlias('companyshow',$return,$pageCache['expire'],$id);
        }
        return $return;
    }
    /**
     * 热门企业
     */
    protected function getHotlist($id=0){
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
        $list = model('Company')
            ->alias('c')
            ->join(
                config('database.prefix') . 'member_setmeal s',
                's.uid=c.uid',
                'LEFT'
            );
        if($id>0){
            $list = $list->where('c.id','neq',$id);
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
    protected function getSimilarList($id,$trade){
        $list = model('Company')
            ->field(
                'id,companyname,logo,district,scale,nature,trade,audit,setmeal_id'
            )
            ->where('id','neq',$id)
            ->where('trade',$trade)
            ->order('id desc')
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
            if (isset($setmeal_list[$value['setmeal_id']])) {
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
    protected function getNearDistrict($district1,$district2,$district3){
        if($district2==0){
            $level = 1;
            $parentDistrict = 0;
        }else if($district3=0){
            $level = 2;
            $parentDistrict = $district1;
        }else{
            $level = 3;
            $parentDistrict = $district2;
        }
        $district_list = model('CategoryDistrict')->getCache($parentDistrict);
        $return = [];
        foreach ($district_list as $key => $value) {
            if($level==1){
                $params = ['d1'=>$key,'d2'=>0,'d3'=>0];
            }else if($level==2){
                $params = ['d1'=>$district1,'d2'=>$key,'d3'=>0];
            }else if($level==3){
                $params = ['d1'=>$district1,'d2'=>$district2,'d3'=>$key];
            }
            $return[] = ['id'=>$key,'text'=>$value,'params'=>$params];
        }
        return $return;
    }
    /**
     * 热门关键词
     */
    protected function getHotword()
    {
        $list = cache('comshow_hotword_list');
        if (!$list) {
            $list = model('Hotword')
                ->field('word,hot')
                ->order('hot desc')
                ->limit(49)
                ->select();
            cache('comshow_hotword_list', $list, 3600);
        }
        return $list;
    }
    /**
     * 实地认证报告
     */
    public function report(){
        $id = request()->route('id/d',0,'intval');
        $info = model('CompanyReport')->alias('a')
            ->join(config('database.prefix').'company b','a.uid=b.uid','LEFT')
            ->where('a.company_id', $id)
            ->field('a.*,b.companyname')
            ->find();
        if ($info === null) {
            abort(404,'实地认证信息为空');
        }
        $img_arr = model('CompanyImg')->getList($id);
        $this->pageHeader['title'] = '实地认证报告 - '.$info['companyname'].' - '.$this->pageHeader['title'];
        $this->assign('pageHeader',$this->pageHeader);
        $this->assign('info',$info);
        $this->assign('img_arr',$img_arr);
        return $this->fetch('report');
    }
}
