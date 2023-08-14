<?php
namespace app\apiadmin\controller;
class TweetsTemplate extends \app\common\controller\Backend
{
    public function index()
    {
        $where = [];
        $list = model('TweetsTemplate')
            ->where($where)
			->order('is_sys desc,id desc')
            ->select();

		$TweetsLabel = model('TweetsLabel')
            ->order('id desc')->select();


		foreach($list as $key=>$val){
			$content = $val['content'];
			foreach($TweetsLabel as $keys=>$vals){
				$content = str_replace($vals['value'],$vals['name'],$content);
			}
			$list[$key]['content_'] = $content;
		}
        $return['items'] = $list;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }
    /**
     * 新增模板
     */
    public function add()
    {
        $input_data = [
            'temname' => input('post.temname/s', '', 'trim'),
            'title' => input('post.title/s', '', 'trim'),
            'content' => input('post.content/s', '', 'trim'),
            'footer' => input('post.footer/s', '', 'trim')
        ];
        $input_data['addtime'] = time();
        $r = model('TweetsTemplate')->save($input_data);
        if ($r === false) {
            $this->ajaxReturn(500, model('TweetsTemplate')->getError());
        }
        $this->ajaxReturn(200, '保存成功', ['tweets_template_id' => $r]);
    }
    /**
     * 删除模板
     */
    public function del()
    {
        $id = input('post.id/d', 0, 'intval');
		$where['id'] = $id;
		$r = model('TweetsTemplate')->where($where)->delete();
        if($r) {
            $this->ajaxReturn(200, '删除成功', ['tweets_template_id' => $r]);
        }else{
			$this->ajaxReturn(500, model('TweetsTemplate')->getError());
		}

    }
    public function edit()
    {
        $id = input('get.id/d', 0, 'intval');
        if ($id) {
            $info = model('TweetsTemplate')->find($id);
            //$info['title_'] = str_replace('{id}','{{}}',$info['title']);
            if (!$info) {
                $this->ajaxReturn(500, '数据获取失败');
            }
            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
			$input_data = [
				'id' => input('post.id/s', 0, 'intval'),
				'temname' => input('post.temname/s', '', 'trim'),
				'title' => input('post.title/s', '', 'trim'),
				'content' => input('post.content/s', '', 'trim'),
				'footer' => input('post.footer/s', '', 'trim')
			];
			$input_data['addtime'] = time();
			if($input_data['id']){
				$r = model('TweetsTemplate')->where('id', $input_data['id'])->update($input_data);
			}
			if ($r === false) {
				$this->ajaxReturn(500, model('TweetsTemplate')->getError());
			}
			$this->ajaxReturn(200, '保存成功', ['tweets_template_id' => $r]);
		}
    }
    public function companySearch(){
        $keyword = input('get.keyword/s', '', 'trim');
        $list = [];
        if ($keyword != '') {
            $datalist = model('Company')
                ->where('id', 'eq', $keyword)
                ->whereOr('companyname', 'like', '%' . $keyword . '%')
                ->field('id,companyname')
                ->select();
            $comid_arr = [];
            foreach ($datalist as $key => $value) {
                $comid_arr[] = $value['id'];
            }
            $jobdata = [];
            $jobid_arr = [];
            if(!empty($comid_arr)){
                $joblistdata = model('Job')->where('audit',1)->where('is_display',1)->where('company_id','in',$comid_arr)->select();
                foreach ($joblistdata as $key => $value) {
                    $jobid_arr[] = $value['id'];
                    $jobdata[$value['company_id']][] = $value;
                }
				$condata = model('CompanyContact')->where('comid','in',$comid_arr)->column('contact,mobile,telephone','comid');
            }
            if(!empty($jobid_arr)){
                $jobcontactdata = model('JobContact')->where('jid','in',$jobid_arr)->column('contact,mobile,telephone,use_company_contact','jid');
            }else{
                $jobcontactdata = [];
            }

			$allDistrict = model('CategoryDistrict')->getCache('all');
            foreach ($datalist as $key => $values) {
                $list[$key]['id'] = $values['id'];
                $list[$key]['companyname'] = $values['companyname'];
                $list[$key]['joblist']=[];
                if(isset($jobdata[$values['id']])){
                    foreach($jobdata[$values['id']] as $keys => $value){
                        $item['id'] = $value['id'];
                        $com_id = $value['company_id'];
                        $item['company'] = $values['companyname'];
                        $item['job'] = $value['jobname'];
                        $item['jobname'] = $value['jobname'];
                        if($value['nature']==1){
                            $item['nature'] = '全职';
                        }elseif($value['nature']==2){
                            $item['nature'] = '实习';
                        }else{
                            $item['nature'] = '不限';
                        }
                        $item['education'] = isset(
                            model('BaseModel')->map_education[$value['education']]
                        )
                            ? model('BaseModel')->map_education[$value['education']]
                            : '学历不限';
                        $item['experience'] = isset(
                            model('BaseModel')->map_experience[$value['experience']]
                        )
                        ? model('BaseModel')->map_experience[$value['experience']]
                        : '经验不限';
                        $item['wage'] = model('BaseModel')->handle_wage(
                            $value['minwage'],
                            $value['maxwage'],
                            $value['negotiable']
                        );
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
                        $item['jobtag'] = implode(",",$item['tag']);
                        $item['amount'] = $value['amount'];
                        if($value['district1']&&$value['district2']&&$value['district3']){
                            $item['district_cn'] = $allDistrict[$value['district1']].'/'.$allDistrict[$value['district2']].'/'.$allDistrict[$value['district3']];
                        }elseif($value['district1']&&$value['district2']){
                            $item['district_cn'] = $allDistrict[$value['district1']].'/'.$allDistrict[$value['district2']];
                        }elseif($value['district1']){
                            $item['district_cn'] = $allDistrict[$value['district1']];
                        }
                        $item['address'] = $value['address'];
                        $item['content'] = $value['content'];
                        $jid = $value['id'];
                        $cid = $value['company_id'];
                        if(!isset($jobcontactdata[$jid])){
                            $item['contact'] = $condata[$cid]['contact'];
                            $item['telephone'] = !empty($condata[$cid]['telephone'])?$condata[$cid]['telephone']:$condata[$cid]['mobile'];
                        }else{
                            if($jobcontactdata[$jid]['use_company_contact']==1){
                                $item['contact'] = $condata[$cid]['contact'];
                                $item['telephone'] = !empty($condata[$cid]['telephone'])?$condata[$cid]['telephone']:$condata[$cid]['mobile'];
                            }else{
                                $item['contact'] = $jobcontactdata[$jid]['contact'];
                                $item['telephone'] = !empty($jobcontactdata[$jid]['telephone'])?$jobcontactdata[$jid]['telephone']:$jobcontactdata[$jid]['mobile'];
                            }
                        }

                        $item['joburl'] = url('index/job/show', ['id' => $value['id']]);
                        $item['companyurl'] = url('index/company/show', ['id' => $value['company_id']]);
                        $list[$key]['joblist'][]=$item;
                    }
                }

                $list[$key]['has_job_num'] = count($list[$key]['joblist']);
                $list[$key]['has_job'] = $list[$key]['has_job_num']==0?0:1;

            }
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }
    public function jobSearch(){
        $keyword = input('get.keyword/s', '', 'trim');
        $list = [];
        if ($keyword != '') {
            $datalist = model('Job')
                ->where('id', 'eq', $keyword)
                ->whereOr('jobname', 'like', '%' . $keyword . '%')
                ->select();
            $comid_arr = $jid_arr = [];
            foreach ($datalist as $k => $val) {
                $comid_arr[] = $val['company_id'];
                $jid_arr[] = $val['id'];
            }
            $comdata = [];
            if(!empty($comid_arr)){
                $comdata = model('Company')->where('id','in',$comid_arr)->column('companyname','id');
				$condata = model('CompanyContact')->where('comid','in',$comid_arr)->column('contact,mobile,telephone','comid');
            }
            unset($comid_arr);
			$jobcontactdata = model('JobContact')->where('jid','in',$jid_arr)->column('contact,mobile,telephone,use_company_contact','jid');
			$allDistrict = model('CategoryDistrict')->getCache('all');
            foreach ($datalist as $key => $value) {
				$item['id'] = $value['id'];
				$com_id = $value['company_id'];
				$item['company'] = isset($comdata[$com_id])?$comdata[$com_id]:'未知';
				$item['companyname'] = $item['company'];
				$item['job'] = $value['jobname'];
				$item['jobname'] = $value['jobname'];
				if($value['nature']==1){
					$item['nature'] = '全职';
				}elseif($value['nature']==2){
					$item['nature'] = '实习';
				}else{
					$item['nature'] = '不限';
				}
				$item['education'] = isset(
					model('BaseModel')->map_education[$value['education']]
				)
					? model('BaseModel')->map_education[$value['education']]
					: '学历不限';
				$item['experience'] = isset(
					model('BaseModel')->map_experience[$value['experience']]
				)
				? model('BaseModel')->map_experience[$value['experience']]
				: '经验不限';
				$item['wage'] = model('BaseModel')->handle_wage(
					$value['minwage'],
					$value['maxwage'],
					$value['negotiable']
				);
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
				$item['jobtag'] = implode(",",$item['tag']);
				$item['amount'] = $value['amount'];
				if($value['district1']&&$value['district2']&&$value['district3']){
					$item['district_cn'] = $allDistrict[$value['district1']].'/'.$allDistrict[$value['district2']].'/'.$allDistrict[$value['district3']];
				}elseif($value['district1']&&$value['district2']){
					$item['district_cn'] = $allDistrict[$value['district1']].'/'.$allDistrict[$value['district2']];
				}elseif($value['district1']){
					$item['district_cn'] = $allDistrict[$value['district1']];
				}
				$item['address'] = $value['address'];
				$item['content'] = $value['content'];
				$jid = $value['id'];
				$cid = $value['company_id'];
                if(!isset($jobcontactdata[$jid])){
                    $item['contact'] = $condata[$cid]['contact'];
                    $item['telephone'] = !empty($condata[$cid]['telephone'])?$condata[$cid]['telephone']:$condata[$cid]['mobile'];
                }else{
                    if($jobcontactdata[$jid]['use_company_contact']==1){
                        $item['contact'] = $condata[$cid]['contact'];
                        $item['telephone'] = !empty($condata[$cid]['telephone'])?$condata[$cid]['telephone']:$condata[$cid]['mobile'];
                    }else{
                        $item['contact'] = $jobcontactdata[$jid]['contact'];
                        $item['telephone'] = !empty($jobcontactdata[$jid]['telephone'])?$jobcontactdata[$jid]['telephone']:$jobcontactdata[$jid]['mobile'];
                    }
                }
				$item['joburl'] = url('index/job/show', ['id' => $value['id']]);
				$item['companyurl'] = url('index/company/show', ['id' => $value['company_id']]);
				$list[] = $item;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $list]);
    }
    public function joblist($condition){
        $type = input('post.type/s', '', 'trim');
        $condition = input('post.condition/a', []);
        $jobid_arr = [];
        if($type==='joblist'){
            $model = $this->_parseConditionOfJob($condition);
            $jobid_arr = $model->column('a.id');//v3.0.2
        }else if($type==='tocompany'){
            foreach($condition as $key=>$val){
                foreach($val['joblist'] as $keys=>$vals) {
                    $jobid_arr[]=$vals['id'];
                }
            }
        }else{
            foreach($condition as $key=>$val){
                $jobid_arr[]=$val['id'];
            }
        }
        $list = [];
        if(!empty($jobid_arr)){
            $list = model('Job')->where('id','in',$jobid_arr)->select();
        }
		$comid_arr = [];
		foreach ($list as $k => $val) {
			$comid_arr[] = $val['company_id'];
		}
		$comdata = [];
		if(!empty($comid_arr)){
			$comdata = model('Company')->where('id','in',$comid_arr)->column('companyname','id');
			$condata = model('CompanyContact')->where('comid','in',$comid_arr)->column('contact,mobile,telephone','comid');
		}
		unset($comid_arr);

        $return = [];
        $category_data = model('Category')->getCache();
		$jobcontactdata = model('JobContact')->where('jid','in',$jobid_arr)->column('contact,mobile,telephone,use_company_contact','jid');
		$allDistrict = model('CategoryDistrict')->getCache('all');
		foreach ($list as $key => $value) {
			$item['id'] = $value['id'];
			$com_id = $value['company_id'];
            $item['company'] = isset($comdata[$com_id])?$comdata[$com_id]:'未知';
            $item['job'] = $value['jobname'];
            $item['jobname'] = $value['jobname'];
			if($value['nature']==1){
				$item['nature'] = '全职';
			}elseif($value['nature']==2){
				$item['nature'] = '实习';
			}else{
				$item['nature'] = '不限';
			}
            $item['education'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '学历不限';
            $item['experience'] = isset(
                model('BaseModel')->map_experience[$value['experience']]
            )
            ? model('BaseModel')->map_experience[$value['experience']]
            : '经验不限';
            $item['wage'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                $value['negotiable']
            );
			$item['tag'] = [];
            if (trim($value['tag'])) {
                $tag_arr = explode(',', $value['tag']);
                foreach ($tag_arr as $k => $v) {
                    if (is_numeric($v) && isset($category_data['QS_jobtag'][$v])) {
						$item['tag'][] = $category_data['QS_jobtag'][$v];
                    } else {
                        $item['tag'][] = $v;
                    }
                }
				$item['jobtag'] = implode(",",$item['tag']);
            }else{
				$item['jobtag'] = [];
			}

			$item['amount'] = $value['amount'];
			if($value['district1']&&$value['district2']&&$value['district3']){
				$item['district_cn'] = $allDistrict[$value['district1']].'/'.$allDistrict[$value['district2']].'/'.$allDistrict[$value['district3']];
			}elseif($value['district1']&&$value['district2']){
				$item['district_cn'] = $allDistrict[$value['district1']].'/'.$allDistrict[$value['district2']];
			}elseif($value['district1']){
				$item['district_cn'] = $allDistrict[$value['district1']];
			}
            $item['address'] = $value['address'];
			$item['content'] = $value['content'];
			$jid = $value['id'];
			$cid = $value['company_id'];
            if(!isset($jobcontactdata[$jid])){
                $item['contact'] = $condata[$cid]['contact'];
                $item['telephone'] = !empty($condata[$cid]['telephone'])?$condata[$cid]['telephone']:$condata[$cid]['mobile'];
            }else{
                if($jobcontactdata[$jid]['use_company_contact']==1){
                    $item['contact'] = $condata[$cid]['contact'];
                    $item['telephone'] = !empty($condata[$cid]['telephone'])?$condata[$cid]['telephone']:$condata[$cid]['mobile'];
                }else{
                    $item['contact'] = $jobcontactdata[$jid]['contact'];
                    $item['telephone'] = !empty($jobcontactdata[$jid]['telephone'])?$jobcontactdata[$jid]['telephone']:$jobcontactdata[$jid]['mobile'];
                }
            }

            $item['joburl_o'] = url('index/job/show', ['id' => $value['id']]);
            $item['companyurl_o'] = url('index/company/show', ['id' => $value['company_id']]);
            $return[] = $item;
        }
		$shortUrl = new \app\common\model\ShortUrl();
		$shortUrl->genCode4Array($return, 'joburl_o', 'joburl', '社群推文营销');
        $shortUrl->genCode4Array($return, 'companyurl_o', 'companyurl', '社群推文营销');
        $return['items'] = $return;
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function title_footer(){
		$item['nowtime'] = date('Y-m-d',time());
		$item['sitename'] = config('global_config.sitename');
		$item['sitedir'] = config('global_config.sitedomain');
        $shortUrl = new \app\common\model\ShortUrl();
        $item['login'] =  $shortUrl->gen( config('global_config.sitedomain').config('global_config.sitedir').config('global_config.member_dirname').'/login', '社群推文营销');
        $item['register'] = $shortUrl->gen(config('global_config.sitedomain').config('global_config.sitedir').config('global_config.member_dirname').'/reg/personal', '社群推文营销');
		$this->ajaxReturn(200, '获取数据成功', $item);
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
                $model = $model->where('a.stick',1);
                break;
            case 'emergency':
                $model = $model->where('a.emergency',1);
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
}
