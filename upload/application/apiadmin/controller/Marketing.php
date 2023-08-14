<?php
namespace app\apiadmin\controller;

class Marketing extends \app\common\controller\Backend
{
    public function index()
    {
        $type = input('post.type/s', '', 'trim');
        $condition = input('post.condition/a', []);
        if(in_array($type,['joblist','resumelist','companylist','companyshow'])){
            $list = $this->$type($condition);
        }else{
            $list = [];
        }
        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    public function companySearch(){
        $keyword = input('get.keyword/s', '', 'trim');
        $list = [];
        if ($keyword != '') {
            $datalist = model('Company')
                ->where('id', 'eq', $keyword)
                ->whereOr('companyname', 'like', '%' . $keyword . '%')
                ->field('id,companyname')
                ->order('refreshtime desc')
                ->select();
            $comid_arr = [];
            foreach ($datalist as $key => $value) {
                $comid_arr[] = $value['id'];
            }
            $jobdata = [];
            if(!empty($comid_arr)){
                $jobdata = model('JobSearchRtime')->where('company_id','in',$comid_arr)->column('company_id,id,uid');
            }
            foreach ($datalist as $key => $value) {
                $arr['id'] = $value['id'];
                $arr['companyname'] = $value['companyname'];
                $arr['has_job'] = isset($jobdata[$value['id']])?1:0;
                $list[] = $arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }
    protected function joblist($condition){
        $model = $this->_parseConditionOfJob($condition);
        $jobid_arr = $model->column('a.id');
        $list = [];
        if(!empty($jobid_arr)){
            $list = model('Job')->field('id,jobname,content,address,minwage,maxwage,negotiable,tag,education,experience')->where('id','in',$jobid_arr)->orderRaw('field(id,'.implode(",",$jobid_arr).')')->select();
        }
        $class = new \app\common\lib\Wechat;
        $return = [];
        $category_data = model('Category')->getCache();
        foreach ($list as $key => $value) {
            $item['id'] = $value['id'];
            $item['jobname'] = $value['jobname'];
            $item['content'] = $value['content'];
            $item['address'] = $value['address'];
            $item['wage'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            $item['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
            ? model('BaseModel')->map_education[$value['education']]
            : '学历不限';
            $item['experience_text'] = isset(
                model('BaseModel')->map_experience[$value['experience']]
            )
            ? model('BaseModel')->map_experience[$value['experience']]
            : '经验不限';
            $item['tag'] = [];
            if ($value['tag']) {
                $tag_arr = explode(',', $value['tag']);
                foreach ($tag_arr as $k => $v) {
                    if (
                        is_numeric($v) &&
                        isset($category_data['QS_jobtag'][$v])
                    ) {
                        $item['tag'][] = $category_data['QS_jobtag'][$v];
                    } else {
                        $item['tag'][] = $v;
                    }
                }
            }
            $item['tag'] = implode(",",$item['tag']);
            $item['jobshow_link'] = config('global_config.mobile_domain').'job/'.$value['id'];
            $qrcode = $class->makeQrcode(['alias'=>'subscribe_job','jobid'=>$value['id']]);
            $item['wxqrcode'] = $qrcode?$qrcode:'';
            $return[] = $item;
        }
        return $return;
    }
    protected function resumelist($condition){
        $model = $this->_parseConditionOfResume($condition);
        $resumeid_arr = $model->column('a.id');
        $list = [];
        if(!empty($resumeid_arr)){
            $list = model('Resume')->field('id,display_name,fullname,sex,birthday,education,enter_job_time,current,specialty')->where('id','in',$resumeid_arr)->orderRaw('field(id,'.implode(",",$resumeid_arr).')')->select();
        }
        $class = new \app\common\lib\Wechat;
        $return = [];
        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $intention_data = model('ResumeIntention')
            ->where('rid', 'in', $resumeid_arr)
            ->order('id asc')
            ->select();
        $intention_arr = [];
        foreach ($intention_data as $key => $value) {
            $intention_arr[$value['rid']][] = $value;
        }
        foreach ($list as $key => $value) {
            $item['id'] = $value['id'];
            $item['fullname'] = $value['fullname'];
            if ($value['display_name'] == 0) {
                if ($value['sex'] == 1) {
                    $item['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '先生'
                    );
                } elseif ($value['sex'] == 2) {
                    $item['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '女士'
                    );
                } else {
                    $item['fullname'] = cut_str(
                        $value['fullname'],
                        1,
                        0,
                        '**'
                    );
                }
            }
            $item['sex'] = $value['sex'];
            $item['sex_text'] = model('Resume')->map_sex[$value['sex']];
            $item['age_text'] = date('Y') - intval($value['birthday']);
            $item['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '';

            $item['experience_text'] =
                $value['enter_job_time'] == 0
                    ? '尚未工作'
                    : format_date($value['enter_job_time']);
            
            $item['current_text'] = isset(
                $category_data['QS_current'][$value['current']]
            )
                ? $category_data['QS_current'][$value['current']]
                : '';
            $item['specialty'] = $value['specialty'];
            //求职意向
            $district_arr = $category_arr = [];
            if (isset($intention_arr[$value['id']])) {
                foreach ($intention_arr[$value['id']] as $k => $v) {
                    if ($v['category']) {
                        $category_arr[] = isset(
                            $category_job_data[$v['category']]
                        )
                            ? $category_job_data[$v['category']]
                            : '';
                    }
                    if ($v['district']) {
                        $district_arr[] = isset(
                            $category_district_data[$v['district']]
                        )
                            ? $category_district_data[$v['district']]
                            : '';
                    }
                }
            }
            if (!empty($category_arr)) {
                $category_arr = array_unique($category_arr);
                $item['intention_jobs'] = implode(',', $category_arr);
            } else {
                $item['intention_jobs'] = '';
            }
            if (!empty($district_arr)) {
                $district_arr = array_unique($district_arr);
                $item['intention_district'] = implode(
                    ',',
                    $district_arr
                );
            } else {
                $item['intention_district'] = '';
            }
            $item['resumeshow_link'] = config('global_config.mobile_domain').'resume/'.$value['id'];
            $qrcode = $class->makeQrcode(['alias'=>'subscribe_resume','resumeid'=>$value['id']]);
            $item['wxqrcode'] = $qrcode?$qrcode:'';
            $return[] = $item;
        }
        return $return;
        
    }
    protected function companylist($condition){
        $model = $this->_parseConditionOfCompany($condition);
        $comid_arr = $model->order('a.refreshtime desc')->column('a.id');
        $list = $joblist = $jobname_arr = [];
        $class = new \app\common\lib\Wechat;
        if(!empty($comid_arr)){
            $list = model('Company')
                ->alias('a')
                ->join(config('dababase.prefix') . 'company_contact b','a.uid=b.uid','LEFT')
                ->join(config('dababase.prefix') . 'company_info c','a.uid=c.uid','LEFT')
                ->field('a.id,a.companyname,b.contact,c.address,a.tag')
                ->where('a.id','in',$comid_arr)
                ->orderRaw('field(a.id,'.implode(",",$comid_arr).')')
                ->select();
            $jobdata = model('Job')->field('id,company_id,jobname,minwage,maxwage,negotiable,education,experience,amount,content')->where('company_id','in',$comid_arr)->where('audit',1)->where('is_display',1)->select();
            foreach ($jobdata as $key => $value) {
                $arr['id'] = $value['id'];
                $arr['company_id'] = $value['company_id'];
                $arr['jobname'] = $value['jobname'];
                $arr['content'] = $value['content'];
                $arr['wage'] = model('BaseModel')->handle_wage(
                    $value['minwage'],
                    $value['maxwage'],
                    $value['negotiable']
                );
                $arr['education_text'] = isset(
                    model('BaseModel')->map_education[$value['education']]
                )
                ? model('BaseModel')->map_education[$value['education']]
                : '学历不限';
                $arr['experience_text'] = isset(
                    model('BaseModel')->map_experience[$value['experience']]
                )
                ? model('BaseModel')->map_experience[$value['experience']]
                : '经验不限';
                $arr['amount'] = ($value['amount'] == 0 ? '若干' : $value['amount']) . '人';
                $arr['jobshow_link'] = config('global_config.mobile_domain').'job/'.$value['id'];
                $joblist[$value['company_id']][] = $arr;
                $jobname_arr[$value['company_id']][] = $value['jobname'];
            }
        }
        $return = [];
        $category_data = model('Category')->getCache();
        foreach ($list as $key => $value) {
            $item['id'] = $value['id'];
            $item['companyname'] = $value['companyname'];
            $item['contact'] = $value['contact'];
            $item['address'] = $value['address'];
            $item['tag'] = [];
            if ($value['tag']) {
                $tag_arr = explode(',', $value['tag']);
                foreach ($tag_arr as $k => $v) {
                    if (
                        is_numeric($v) &&
                        isset($category_data['QS_jobtag'][$v])
                    ) {
                        $item['tag'][] = $category_data['QS_jobtag'][$v];
                    } else {
                        $item['tag'][] = $v;
                    }
                }
            }
            $item['tag'] = implode(",",$item['tag']);
            $item['joblist'] = isset($joblist[$value['id']])?$joblist[$value['id']]:[];
            $item['job_text'] = isset($jobname_arr[$value['id']])?implode(",",$jobname_arr[$value['id']]):'';
            $item['companyshow_link'] = config('global_config.mobile_domain').'company/'.$value['id'];
            $qrcode = $class->makeQrcode(['alias'=>'subscribe_company','comid'=>$value['id']]);
            $item['wxqrcode'] = $qrcode?$qrcode:'';
            $return[] = $item;
        }
        return $return;
        
    }
    protected function companyshow($condition){
        $company_id = intval($condition['company_id']);
        $list = model('Job')->field('id,jobname,content,address,minwage,maxwage,negotiable,tag,education,experience')->where('audit',1)->where('is_display',1)->where('company_id','eq',$company_id)->select();
        $class = new \app\common\lib\Wechat;
        $return = [];
        $category_data = model('Category')->getCache();
        foreach ($list as $key => $value) {
            $item['id'] = $value['id'];
            $item['jobname'] = $value['jobname'];
            $item['content'] = $value['content'];
            $item['address'] = $value['address'];
            $item['wage'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
            $item['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
            ? model('BaseModel')->map_education[$value['education']]
            : '学历不限';
            $item['experience_text'] = isset(
                model('BaseModel')->map_experience[$value['experience']]
            )
            ? model('BaseModel')->map_experience[$value['experience']]
            : '经验不限';
            $item['tag'] = [];
            if ($value['tag']) {
                $tag_arr = explode(',', $value['tag']);
                foreach ($tag_arr as $k => $v) {
                    if (
                        is_numeric($v) &&
                        isset($category_data['QS_jobtag'][$v])
                    ) {
                        $item['tag'][] = $category_data['QS_jobtag'][$v];
                    } else {
                        $item['tag'][] = $v;
                    }
                }
            }
            $item['tag'] = implode(",",$item['tag']);
            $item['jobshow_link'] = config('global_config.mobile_domain').'job/'.$value['id'];
            $qrcode = $class->makeQrcode(['alias'=>'subscribe_job','jobid'=>$value['id']]);
            $item['wxqrcode'] = $qrcode?$qrcode:'';
            $return[] = $item;
        }
        return $return;
        
    }
    protected function _parseConditionOfJob($condition)
    {
        $model = model('JobSearchRtime')->alias('a');

        if (
            isset($condition['refreshtime']) &&
            intval($condition['refreshtime']) > 0
        ) {
            $settr = intval($condition['refreshtime']);
            $model = $model->where(
                'a.refreshtime',
                'egt',
                strtotime('-' . $settr . 'day')
            );
        }
        if (
            isset($condition['jobcategory']) &&
            count($condition['jobcategory']) > 0
        ) {
            $tmp_str = '';
            foreach ($condition['jobcategory'] as $key => $value) {
                $arr_lenth = count($value);
                $tmp_str .=
                    ' or a.category' .
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
        if (
            isset($condition['district']) &&
            count($condition['district']) > 0
        ) {
            $tmp_str = '';
            foreach ($condition['district'] as $key => $value) {
                $arr_lenth = count($value);
                $tmp_str .=
                    ' or a.district' .
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
            $model = $model->where('a.trade', 'in', $condition['trade']);
        }

        if (isset($condition['tag']) && count($condition['tag']) > 0) {
            foreach ($condition['tag'] as $key => $value) {
                $model = $model->where('FIND_IN_SET("' . $value . '",a.tag)');
            }
        }

        if (isset($condition['wage']) && count($condition['wage']) > 0) {
            $tmp_str = '';
            foreach ($condition['wage'] as $key => $value) {
                switch ($value) {
                    case 0: //面议
                        $tmp_str .= ' or (a.minwage=0 and a.maxwage=0)';
                        break;
                    case 15000:
                        $tmp_str .= ' or a.maxwage>=15000';
                        break;
                    default:
                        if (false !== stripos($value, '-')) {
                            $arr = explode('-', $value);
                            $tmp_str .=
                                ' or (a.maxwage>=' .
                                $arr[0] .
                                ' and a.minwage<' .
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

        if (
            isset($condition['setmeal_id']) &&
            count($condition['setmeal_id']) > 0
        ) {
            $model = $model
                ->join(
                    config('dababase.prefix') . 'member_setmeal b',
                    'a.uid=b.uid',
                    'LEFT'
                )
                ->where('a.setmeal_id', 'in', $condition['setmeal_id']);
        }

        switch($condition['content']){
            case 'refreshtime':
                $model = $model->order('a.refreshtime','desc');
                break;
            case 'stick':
                $model = $model->where('a.stick',1)->order('a.refreshtime','desc');
                break;
            case 'emergency':
                $model = $model->where('a.emergency',1)->order('a.refreshtime','desc');
                break;
            case 'promotion':
                $model = $model->order('a.stick','desc')->order('a.emergency','desc');
                break;
            default:
                $model = $model->order('a.refreshtime','desc');
                break;
        }
        $num = (isset($condition['num']) && intval($condition['num'])>0) ? intval($condition['num']):10;
        $model = $model->distinct('a.id')->limit($num);

        return $model;
    }
    
    protected function _parseConditionOfResume($condition)
    {
        $model = model('ResumeSearchRtime')->alias('a');
        
        if (
            (isset($condition['jobcategory']) &&
                count($condition['jobcategory']) > 0) ||
            (isset($condition['district']) && count($condition['district']) > 0)
        ) {
            $model = $model->join(
                config('dababase.prefix') . 'resume_intention b',
                'a.uid=b.uid',
                'LEFT'
            );
            if (
                isset($condition['jobcategory']) &&
                count($condition['jobcategory']) > 0
            ) {
                $tmp_str = '';
                foreach ($condition['jobcategory'] as $key => $value) {
                    $arr_lenth = count($value);
                    $tmp_str .=
                        ' or b.category' .
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
            if (
                isset($condition['district']) &&
                count($condition['district']) > 0
            ) {
                $tmp_str = '';
                foreach ($condition['district'] as $key => $value) {
                    $arr_lenth = count($value);
                    $tmp_str .=
                        ' or b.district' .
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
        }
        if (isset($condition['education']) && count($condition['education']) > 0) {
            $model = $model->where('a.education', 'in', $condition['education']);
        }
        if (isset($condition['experience']) && count($condition['experience']) > 0) {
            $where = '';
            foreach ($condition['experience'] as $key => $value) {
                switch ($value) {
                    case 1: //无经验/应届生
                        $where .= ' OR a.`enter_job_time`=0';
                        break;
                    case 2:
                        $where .=
                            ' OR a.`enter_job_time` > ' . strtotime('-2 year');
                        break;
                    case 3:
                        $where .=
                            ' OR (a.`enter_job_time` <= ' .
                            strtotime('-2 year') .
                            ' AND a.`enter_job_time` > ' .
                            strtotime('-3 year').')';
                        break;
                    case 4:
                        $where .=
                            ' OR (a.`enter_job_time` <= ' .
                            strtotime('-3 year') .
                            ' AND a.`enter_job_time` > ' .
                            strtotime('-4 year').')';
                        break;
                    case 5:
                        $where .=
                            ' OR (a.`enter_job_time` <= ' .
                            strtotime('-3 year') .
                            ' AND a.`enter_job_time` > ' .
                            strtotime('-5 year').')';
                        break;
                    case 6:
                        $where .=
                            ' OR (a.`enter_job_time` <= ' .
                            strtotime('-5 year') .
                            ' AND a.`enter_job_time` > ' .
                            strtotime('-10 year').')';
                        break;
                    case 7:
                        $where .=
                            ' OR a.`enter_job_time` <= ' . strtotime('-10 year');
                        break;
                    default:
                        break;
                }
            }
            $where = trim($where);
            $where = ltrim($where,'OR');
            if($where!=''){
                $model = $model->where($where);
            }
        }
        switch($condition['content']){
            case 'refreshtime':
                $model = $model->order('a.refreshtime','desc');
                break;
            case 'promotion':
                $model = $model->order('a.stick','desc')->order('a.refreshtime','desc');
                break;
            default:
                $model = $model->order('a.refreshtime','desc');
                break;
        }
        $num = (isset($condition['num']) && intval($condition['num'])>0) ? intval($condition['num']):10;
        $model = $model->distinct('a.id')->limit($num);
        return $model;
    }
    protected function _parseConditionOfCompany($condition)
    {
        $model = model('Company')->alias('a');

        if (isset($condition['nature']) && count($condition['nature']) > 0) {
            $model = $model->where('a.nature', 'in', $condition['nature']);
        }

        if (isset($condition['trade']) && count($condition['trade']) > 0) {
            $model = $model->where('a.trade', 'in', $condition['trade']);
        }
        if (
            isset($condition['district']) &&
            count($condition['district']) > 0
        ) {
            $tmp_str = '';
            foreach ($condition['district'] as $key => $value) {
                $arr_lenth = count($value);
                $tmp_str .=
                    ' or a.district' .
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

        if (isset($condition['tag']) && count($condition['tag']) > 0) {
            foreach ($condition['tag'] as $key => $value) {
                $model = $model->where('FIND_IN_SET("' . $value . '",a.tag)');
            }
        }
        if (
            isset($condition['setmeal_id']) &&
            count($condition['setmeal_id']) > 0
        ) {
            $model = $model
                ->join(
                    config('dababase.prefix') . 'member_setmeal b',
                    'a.uid=b.uid',
                    'LEFT'
                )
                ->where('a.setmeal_id', 'in', $condition['setmeal_id']);
        }
        switch($condition['content']){
            case 'filter_nojobs':
                $model = $model
                    ->join(
                        config('dababase.prefix') . 'job_search_rtime c',
                        'a.uid=c.uid',
                        'LEFT'
                    )
                    ->where('c.id', 'not null');
                break;
            case 'filter_noaudit':
                $model = $model->where('a.audit',1);
                break;
            default:
                break;
        }
        $num = (isset($condition['num']) && intval($condition['num'])>0) ? intval($condition['num']):10;
        $model = $model->distinct('a.id')->limit($num);
        return $model;
    }
}
