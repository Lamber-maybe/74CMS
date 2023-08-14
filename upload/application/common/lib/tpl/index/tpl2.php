<?php
namespace app\common\lib\tpl\index;

class tpl2 extends \app\common\lib\tpl\index\def
{
    public function getData($pageCache,$pageAlias){
        $return['notice_list'] = $this->getNoticeList(10);
        $return['hotword_list'] = $this->getHotwordList(10);
        $return['emergency_list'] = $this->getEmergencyList(12);
        $return['famous_list'] = $this->getFamousList(50);
        $return['newjob_list'] = $this->getNewjobList(100);
        $return['banner_list'] = $this->getBannerList();
        $return['flink_list'] = $this->getFlinkList();
        $return['trade_list'] = $this->getTradeList();
        // $return['company_list'] = $this->getCompanyList();
        if($pageCache['expire']>0){
            model('Page')->writeCacheByAlias($pageAlias,$return,$pageCache['expire']);
        }
        return $return;
    }
    
    /**
     * 优选职位
     */
    protected function getFamousList($limit=15){
        $famous_enterprises_setmeal = config(
            'global_config.famous_enterprises'
        );
        $famous_enterprises_setmeal =
            $famous_enterprises_setmeal == ''
                ? []
                : explode(',', $famous_enterprises_setmeal);
        $list = [];
        if (!empty($famous_enterprises_setmeal)) {
            $subsiteCondition = get_subsite_condition('a');
            $list = model('JobSearchRtime')
                ->alias('a')
                ->join(
                    config('database.prefix') . 'job b',
                    'a.id=b.id',
                    'LEFT'
                )
                ->join(
                    config('database.prefix') . 'company c',
                    'a.uid=c.uid',
                    'LEFT'
                )
                ->join(
                    config('database.prefix') . 'setmeal d',
                    'a.setmeal_id=d.id',
                    'LEFT'
                )
                ->where($subsiteCondition)
                ->where('c.id','not null')
                ->where('a.setmeal_id', 'in', $famous_enterprises_setmeal)
                ->order('a.stick desc,a.refreshtime desc')
                ->limit($limit)
                ->column('b.id,a.stick,b.addtime,b.jobname,b.refreshtime,b.district,b.district1,b.district2,b.district3,b.category,b.education,b.experience,b.negotiable,b.minwage,b.maxwage,b.tag,b.setmeal_id,b.company_id,c.companyname,c.audit as company_audit,d.icon');
            
                $comid_arr = $companyList = $icon_id_arr = $icon_arr = [];
            foreach ($list as $key => $value) {
                $comid_arr[] = $value['id'];
                $value['icon'] > 0 && ($icon_id_arr[] = $value['icon']);
            }
            if (!empty($icon_id_arr)) {
                $icon_arr = model('Uploadfile')->getFileUrlBatch(
                    $icon_id_arr
                );
            }
            $category_data = model('Category')->getCache();
            $category_district_data = model('CategoryDistrict')->getCache();
            $category_job_data = model('CategoryJob')->getCache();
            foreach ($list as $key => $value) {
                $arr = $value;
                if ($arr['district']) {
                    $arr['district_text'] = isset(
                        $category_district_data[$arr['district']]
                    )
                    ? $category_district_data[$arr['district']]
                    : '';
                } else {
                    $arr['district_text'] = '';
                }
                if($arr['district1']){
                    $arr['district_text_full'] = isset(
                        $category_district_data[$arr['district1']]
                    )
                        ? $category_district_data[$arr['district1']]
                        : '';
                }else{
                    $arr['district_text_full'] = '';
                }
                
                if($arr['district_text_full']!='' && $arr['district2']>0){
                    $arr['district_text_full'] .= isset(
                        $category_district_data[$arr['district2']]
                    )
                        ? ' - '.$category_district_data[$arr['district2']]
                        : '';
                }
                if($arr['district_text_full']!='' && $arr['district3']>0){
                    $arr['district_text_full'] .= isset(
                        $category_district_data[$arr['district3']]
                    )
                        ? ' - '.$category_district_data[$arr['district3']]
                        : '';
                }
                if ($arr['category']) {
                    $arr['category_text'] = isset(
                        $category_job_data[$arr['category']]
                    )
                    ? $category_job_data[$arr['category']]
                    : '';
                } else {
                    $arr['category_text'] = '';
                }
                
                $arr['wage_text'] = model('BaseModel')->handle_wage(
                    $arr['minwage'],
                    $arr['maxwage'],
                    $arr['negotiable']
                );

                $arr['education_text'] = isset(
                    model('BaseModel')->map_education[$arr['education']]
                )
                ? model('BaseModel')->map_education[$arr['education']]
                : '学历不限';
                $arr['experience_text'] = isset(
                    model('BaseModel')->map_experience[$arr['experience']]
                )
                ? model('BaseModel')->map_experience[$arr['experience']]
                : '经验不限';
                $arr['refreshtime'] = daterange(time(),$arr['refreshtime']);
                $arr['tag_arr'] = [];
                if ($arr['tag']) {
                    $counter = 0;
                    $tag_arr = explode(',', $arr['tag']);
                    foreach ($tag_arr as $k => $v) {
                        if($counter>=4){
                            break;
                        }
                        $counter++;
                        if (
                            is_numeric($v) &&
                            isset($category_data['QS_jobtag'][$v])
                        ) {
                            $arr['tag_arr'][] = $category_data['QS_jobtag'][$v];
                        } else {
                            $arr['tag_arr'][] = $v;
                        }
                    }
                }else{
                    $arr['tag_arr'] = [];
                }
                $arr['setmeal_icon'] = isset($icon_arr[$arr['icon']]) ? $icon_arr[$arr['icon']] : model('Setmeal')->getSysIcon($arr['setmeal_id']);
                $list[$key] = $arr;
            }
        }
        return $list;
    }
    /**
     * 最新职位
     */
    protected function getNewjobList($limit=10){
        $subsiteCondition = get_subsite_condition('a');
        $list = model('JobSearchRtime')->alias('a')
                ->join(config('database.prefix').'job b','a.id=b.id','LEFT')
                ->join(config('database.prefix').'company c','a.uid=c.uid','LEFT')
                ->where($subsiteCondition)
                ->where('c.id','not null')
                ->order('a.refreshtime desc')
                ->limit($limit)
                ->column('b.id,b.jobname,b.district,b.district1,b.district2,b.district3,b.negotiable,b.minwage,b.maxwage,b.company_id,c.companyname,a.refreshtime');
        $category_district_data = model('CategoryDistrict')->getCache();
        foreach ($list as $key => $value) {
            $arr = $value;
            $arr['jobname'] = cut_str($arr['jobname'],16,0,'...');
            $arr['wage_text'] = model('BaseModel')->handle_wage(
                $arr['minwage'],
                $arr['maxwage'],
                $arr['negotiable']
            );
            if ($arr['district']) {
                $arr['district_text'] = isset(
                    $category_district_data[$arr['district']]
                )
                ? $category_district_data[$arr['district']]
                : '';
            } else {
                $arr['district_text'] = '';
            }
            if($arr['district1']){
                $arr['district_text_full'] = isset(
                    $category_district_data[$arr['district1']]
                )
                    ? $category_district_data[$arr['district1']]
                    : '';
            }else{
                $arr['district_text_full'] = '';
            }
            
            if($arr['district_text_full']!='' && $arr['district2']>0){
                $arr['district_text_full'] .= isset(
                    $category_district_data[$arr['district2']]
                )
                    ? ' - '.$category_district_data[$arr['district2']]
                    : '';
            }
            if($arr['district_text_full']!='' && $arr['district3']>0){
                $arr['district_text_full'] .= isset(
                    $category_district_data[$arr['district3']]
                )
                    ? ' - '.$category_district_data[$arr['district3']]
                    : '';
            }
            $arr['refreshtime'] = daterange(time(),$arr['refreshtime']);
            $list[$key] = $arr;
        }
        return $list;
    }
    /**
     * 企业行业列表
     */
    protected function getTradeList(){
        $return = [];
        $list = model('Category')->getCache('QS_trade');
        $counter = 1;
        foreach ($list as $key => $value) {
            $arr = [
                'id'=>$key,
                'name'=>$value
            ];
            $return[] = $arr;
            $counter++;
            if($counter>15){
                $arr = [
                    'id'=>-1,
                    'name'=>'其他行业'
                ];
                $return[] = $arr;
                break;
            }
        }
        return $return;
    }
}
