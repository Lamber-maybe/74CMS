<?php
namespace app\v1_0\controller\personal;

class Resume extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(2);
    }
    /**
     * 获取简历完整度信息
     */
    public function getResumeCompleteInfo(){
        $module_data = model('ResumeComplete')->field('rid,uid,id',true)->where('uid',$this->userinfo->uid)->find();
        $module_data = $module_data->toArray();
        $module = [];
        foreach ($module_data as $key => $value) {
            switch($key){
                case 'basic':
                    $name='基本信息';break;
                case 'intention':
                    $name='求职意向';break;
                case 'specialty':
                    $name='自我描述';break;
                case 'education':
                    $name='教育经历';break;
                case 'work':
                    $name='工作经历';break;
                default:
                    $name='';break;
            }
            if($name!='' && $value==1){
                $module[] = $name;
            }
        }
        $percent = model('Resume')->countCompletePercent(0,$this->userinfo->uid);
        $this->ajaxReturn(200,'获取数据成功',['module'=>$module,'percent'=>$percent]);
    }
    /**
     * 获取简历详情
     */
    protected function getDetail($uid)
    {
        $uid = intval($uid);
        $where['uid'] = $uid;
        $basic = model('Resume')
            ->where($where)
            ->field('uid,addtime', true) //排除字段
            ->find();
        if ($basic === null) {
            return false;
        }
        $basic = $basic->toArray();
        $basic['blacklist'] = model('Shield')
            ->where('personal_uid', $uid)
            ->column('company_uid');
        $basic['blacklist'] = !empty($basic['blacklist'])
            ? implode(',', $basic['blacklist'])
            : '';
        $field_rule_data = model('FieldRule')->getCache();
        $field_rule = [
            'basic' => $field_rule_data['Resume'],
            'contact' => $field_rule_data['ResumeContact'],
            'intention' => $field_rule_data['ResumeIntention'],
            'education' => $field_rule_data['ResumeEducation']
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
        $resume_module_data = model('ResumeModule')->getCache();
        $resume_module = [];
        foreach ($resume_module_data as $module_name => $module_attr) {
            $_arr = [
                'module_name' => $module_attr['module_name'],
                'module_cn' => $module_attr['module_cn'],
                'score' => intval($module_attr['score']),
                'is_display' => intval($module_attr['is_display'])
            ];
            $resume_module[$module_name] = $_arr;
        }
        $category_data = model('Category')->getCache();
        $category_major_data = model('CategoryMajor')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $global_config = config('global_config');

        $basic['sex_text'] = model('Resume')->map_sex[$basic['sex']];
        $basic['age'] = date('Y') - intval($basic['birthday']);
        $basic['education_text'] = isset(
            model('BaseModel')->map_education[$basic['education']]
        )
            ? model('BaseModel')->map_education[$basic['education']]
            : '';
        $basic['experience_text'] =
            $basic['enter_job_time'] == 0
                ? '无经验'
                : format_date($basic['enter_job_time']);
        $basic['tag'] = $basic['tag'] == '' ? [] : explode(',', $basic['tag']);
        $basic['tag_text'] = '';
        $basic['tag_text_arr'] = [];
        if (!empty($basic['tag'])) {
            $tag_text_arr = [];
            foreach ($basic['tag'] as $k => $v) {
                if (
                    is_numeric($v) &&
                    isset($category_data['QS_resumetag'][$v])
                ) {
                    $tag_text_arr[] = $category_data['QS_resumetag'][$v];
                } else {
                    $tag_text_arr[] = $v;
                }
            }
            if (!empty($tag_text_arr)) {
                $basic['tag_text_arr'] = $tag_text_arr;
                $basic['tag_text'] = implode(',', $tag_text_arr);
            }
        }
        $basic['major_text'] = isset($category_major_data[$basic['major']])
            ? $category_major_data[$basic['major']]
            : '';

        $basic['current_text'] = isset(
            $category_data['QS_current'][$basic['current']]
        )
            ? $category_data['QS_current'][$basic['current']]
            : '';
        $basic['complete_percent'] = model('Resume')->countCompletePercent(
            $basic['id']
        );
        $basic['complete_level'] =
            $basic['complete_percent'] >
            config('global_config.apply_job_min_percent')
                ? 1
                : 0;
        $basic['audit_text'] = isset(
            model('Resume')->map_audit[$basic['audit']]
        )
            ? model('Resume')->map_audit[$basic['audit']]
            : '待审核';

        $basic['marriage_text'] = isset(
            model('Resume')->map_marriage[$basic['marriage']]
        )
            ? model('Resume')->map_marriage[$basic['marriage']]
            : '保密';

        $basic['photo_img_src'] =
            $basic['photo_img'] > 0
                ? model('Uploadfile')->getFileUrl($basic['photo_img'])
                : default_empty('photo');

        //求职意向
        $intention_list = model('ResumeIntention')
            ->field('rid,uid', true)
            ->where(['rid' => ['eq', $basic['id']]])
            ->select();
        foreach ($intention_list as $key => $value) {
            $value['nature_text'] = isset(
                model('Resume')->map_nature[$value['nature']]
            )
                ? model('Resume')->map_nature[$value['nature']]
                : '全职';
            $value['category_text'] = isset(
                $category_job_data[$value['category']]
            )
                ? $category_job_data[$value['category']]
                : '';
            $value['district_text'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $value['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                0
            );
            $value['trade_text'] = isset(
                $category_data['QS_trade'][$value['trade']]
            )
                ? $category_data['QS_trade'][$value['trade']]
                : '';
            $basic['intention_jobs_text'][] = $value['category_text'];
            $basic['intention_district_text'][] = $value['district_text'];
            $intention_list[$key] = $value;
        }
        if(!empty($basic['intention_jobs_text'])){
            $basic['intention_jobs_text'] = array_unique($basic['intention_jobs_text']);
            $basic['intention_jobs_text'] = implode(",",$basic['intention_jobs_text']);
        }
        if(!empty($basic['intention_district_text'])){
            $basic['intention_district_text'] = array_unique($basic['intention_district_text']);
            $basic['intention_district_text'] = implode(",",$basic['intention_district_text']);
        }
        //联系方式
        $contact = model('ResumeContact')
            ->field('id,rid,uid', true)
            ->where(['rid' => ['eq', $basic['id']]])
            ->find();
        //工作经历
        $work_list = model('ResumeWork')
            ->field('rid,uid', true)
            ->where(['rid' => ['eq', $basic['id']]])
            ->select();
        //教育经历
        $education_list = model('ResumeEducation')
            ->field('rid,uid', true)
            ->where(['rid' => ['eq', $basic['id']]])
            ->select();
        foreach ($education_list as $key => $value) {
            $value['education_text'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '';
            $education_list[$key] = $value;
        }
        //项目经历
        if ($resume_module['project']['is_display'] == 1) {
            $project_list = model('ResumeProject')
                ->field('rid,uid', true)
                ->where(['rid' => ['eq', $basic['id']]])
                ->select();
        } else {
            $project_list = [];
        }
        //培训经历
        if ($resume_module['training']['is_display'] == 1) {
            $training_list = model('ResumeTraining')
                ->field('rid,uid', true)
                ->where(['rid' => ['eq', $basic['id']]])
                ->select();
        } else {
            $training_list = [];
        }

        //语言能力
        if ($resume_module['language']['is_display'] == 1) {
            $language_list = model('ResumeLanguage')
                ->field('rid,uid', true)
                ->where(['rid' => ['eq', $basic['id']]])
                ->select();
            foreach ($language_list as $key => $value) {
                $value['language_text'] = isset(
                    $category_data['QS_language'][$value['language']]
                )
                    ? $category_data['QS_language'][$value['language']]
                    : '';
                $value['level_text'] = isset(
                    $category_data['QS_language_level'][$value['level']]
                )
                    ? $category_data['QS_language_level'][$value['level']]
                    : '';
                $language_list[$key] = $value;
            }
        } else {
            $language_list = [];
        }
        //证书
        if ($resume_module['certificate']['is_display'] == 1) {
            $certificate_list = model('ResumeCertificate')
                ->field('rid,uid', true)
                ->where(['rid' => ['eq', $basic['id']]])
                ->select();
        } else {
            $certificate_list = [];
        }
        //照片作品
        if ($resume_module['img']['is_display'] == 1) {
            $img_list = model('ResumeImg')->getList(['uid'=>$uid]);
        } else {
            $img_list = [];
        }
        
        return [
            'module_list' => $resume_module,
            'field_rule' => $field_rule,
            'basic' => $basic,
            'contact' => $contact,
            'intention_list' => $intention_list,
            'work_list' => $work_list,
            'education_list' => $education_list,
            'project_list' => $project_list,
            'training_list' => $training_list,
            'language_list' => $language_list,
            'certificate_list' => $certificate_list,
            'img_list' => $img_list
        ];
    }
    /**
     * 获取简历详情
     */
    public function detail()
    {
        $info = $this->getDetail($this->userinfo->uid);
        if($info===false){
            $this->ajaxReturn(200, '获取数据成功', null);
        }
        
        $info['share_url'] = config('global_config.mobile_domain').'resume/'.$info['basic']['id'];
        $info['preview_url'] = config('global_config.sitedomain').url('index/resume/show',['id'=>$info['basic']['id']]);
        $this->ajaxReturn(200, '获取数据成功', $info);
    }
    /**
     * 刷新简历
     */
    public function refresh()
    {
        $this->interceptPersonalResume();
        $r = model('Resume')->refreshResume($this->userinfo->uid);
        if ($r === false) {
            $this->ajaxReturn(500, model('Resume')->getError());
        }
        model('Task')->doTask(
            $this->userinfo->uid,
            $this->userinfo->utype,
            'refresh_resume'
        );
        $this->writeMemberActionLog($this->userinfo->uid,'刷新简历');
        $this->ajaxReturn(200, '刷新成功',time());
    }

    /**
     * 保存简历基本信息
     */
    public function basicSave()
    {
        $input_data = [
            'basic' => [
                'uid' => $this->userinfo->uid,
                'fullname' => input('post.basic.fullname/s', '', 'trim,badword_filter'),
                'sex' => input('post.basic.sex/d', 0, 'intval'),
                'birthday' => input('post.basic.birthday/s', '', 'trim'),
                'education' => input('post.basic.education/d', 0, 'intval'),
                'enter_job_time' => input(
                    'post.basic.enter_job_time/s',
                    '',
                    'trim'
                )
            ],
            'contact' => [
                'uid' => $this->userinfo->uid,
                'mobile' => input('post.contact.mobile/s', '', 'trim,badword_filter')
            ]
        ];
        if (input('?post.basic.photo_img')) {
            $input_data['basic']['photo_img'] = input(
                'post.basic.photo_img/d',
                0,
                'intval'
            );
        }
        if (input('?post.basic.marriage')) {
            $input_data['basic']['marriage'] = input(
                'post.basic.marriage/d',
                0,
                'intval'
            );
        }
        if (input('?post.basic.residence')) {
            $input_data['basic']['residence'] = input(
                'post.basic.residence/s',
                '',
                'trim,badword_filter'
            );
        }
        if (input('?post.basic.major2') || input('?post.basic.major1')) {
            $input_data['basic']['major2'] = input(
                'post.basic.major2/d',
                0,
                'intval'
            );
            $input_data['basic']['major1'] = input(
                'post.basic.major1/d',
                0,
                'intval'
            );
            $input_data['basic']['major'] =
                $input_data['basic']['major2'] != 0
                    ? $input_data['basic']['major2']
                    : ($input_data['basic']['major1'] != 0
                        ? $input_data['basic']['major1']
                        : 0);
        }
        if (input('?post.basic.height')) {
            $input_data['basic']['height'] = input(
                'post.basic.height/d',
                0,
                'intval'
            );
        }
        if (input('?post.basic.householdaddress')) {
            $input_data['basic']['householdaddress'] = input(
                'post.basic.householdaddress/s',
                '',
                'trim,badword_filter'
            );
        }
        if (input('?post.basic.idcard')) {
            $input_data['basic']['idcard'] = input(
                'post.basic.idcard/s',
                '',
                'trim,badword_filter'
            );
        }
        if (input('?post.basic.custom_field_1')) {
            $input_data['basic']['custom_field_1'] = input(
                'post.basic.custom_field_1/s',
                '',
                'trim,badword_filter'
            );
        }
        if (input('?post.basic.custom_field_2')) {
            $input_data['basic']['custom_field_2'] = input(
                'post.basic.custom_field_2/s',
                '',
                'trim,badword_filter'
            );
        }
        if (input('?post.basic.custom_field_3')) {
            $input_data['basic']['custom_field_3'] = input(
                'post.basic.custom_field_3/s',
                '',
                'trim,badword_filter'
            );
        }
        $input_data['basic']['enter_job_time'] =
            $input_data['basic']['enter_job_time'] == ''
                ? 0
                : strtotime($input_data['basic']['enter_job_time']);

        if (config('global_config.audit_edit_resume') == 1) {
            $input_data['basic']['audit'] = 0;
        }

        if (input('?post.contact.email')) {
            $input_data['contact']['email'] = input(
                'post.contact.email/s',
                '',
                'trim,badword_filter'
            );
        }
        if (input('?post.contact.weixin')) {
            $input_data['contact']['weixin'] = input(
                'post.contact.weixin/s',
                '',
                'trim,badword_filter'
            );
        }
        if (input('?post.contact.qq')) {
            $input_data['contact']['qq'] = input(
                'post.contact.qq/s',
                '',
                'trim,badword_filter'
            );
        }
        \think\Db::startTrans();
        try {
            $result = model('Resume')
                ->validate(true)
                ->allowField(true)
                ->save($input_data['basic'], [
                    'uid' => $this->userinfo->uid
                ]);

            if (false === $result) {
                throw new \Exception(model('Resume')->getError());
            }
            //更新完整度
            model('Resume')->updateComplete(
                ['basic' => 1],
                0,
                $this->userinfo->uid
            );
            //完成任务
            if (
                isset($input_data['basic']['photo_img']) &&
                $input_data['basic']['photo_img'] > 0
            ) {
                model('Task')->doTask($this->userinfo->uid, 2, 'upload_photo');
            }

            $result = model('ResumeContact')
                ->validate(true)
                ->allowField(true)
                ->save($input_data['contact'], [
                    'uid' => $this->userinfo->uid
                ]);
            if (false === $result) {
                throw new \Exception(model('ResumeContact')->getError());
            }
            model('ImToken')->regToken(
                $this->userinfo->uid,
                $this->userinfo->utype
            );

            //提交事务
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        model('Resume')->refreshSearch(0, $this->userinfo->uid);
        $this->writeMemberActionLog($this->userinfo->uid,'保存简历基本信息');
        $this->ajaxReturn(200, '保存成功');
    }
    /**
     * 保存简历教育经历
     */
    public function educationSave()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'uid' => $this->userinfo->uid,
            'school' => input('post.school/s', '', 'trim,badword_filter'),
            'education' => input('post.education/d', 0, 'intval'),
            'starttime' => input('post.starttime/s', '', 'trim'),
            'endtime' => input('post.endtime/s', '', 'trim'),
            'todate' => input('post.todate/d', 0, 'intval')
        ];
        if (input('?post.major')) {
            $input_data['major'] = input('post.major/s', '', 'trim,badword_filter');
        }
        $id = intval($input_data['id']);
        $input_data['starttime'] = strtotime($input_data['starttime']);
        if ($input_data['todate'] == 1) {
            $input_data['endtime'] = 0;
        } else {
            $input_data['endtime'] = strtotime($input_data['endtime']);
        }

        if ($id > 0) {
            $result = model('ResumeEducation')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, [
                    'id' => $id,
                    'uid' => $this->userinfo->uid
                ]);
            $return_id = $id;
        } else {
            unset($input_data['id']);
            $basic_info = model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->find();
            if ($basic_info === null) {
                $this->ajaxReturn(500, '请先填写基本资料');
            }
            $input_data['rid'] = $basic_info->id;
            $result = model('ResumeEducation')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            $return_id = model('ResumeEducation')->id;
        }

        if (false === $result) {
            $this->ajaxReturn(500, model('ResumeEducation')->getError());
        }
        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
            model('Resume')->refreshSearch(0, $this->userinfo->uid);
        }
        model('Resume')->updateComplete(
            ['education' => 1],
            0,
            $this->userinfo->uid
        );

        $this->writeMemberActionLog($this->userinfo->uid,'保存简历教育经历');
        $this->ajaxReturn(200, '保存成功', ['return_id' => $return_id]);
    }
    /**
     * 删除教育经历
     */
    public function educationDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id <= 0) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeEducation')
            ->destroy(['id' => ['eq', $id], 'uid' => $this->userinfo->uid]);

        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
            model('Resume')->refreshSearch(0, $this->userinfo->uid);
        }
        $education_total = model('ResumeEducation')
            ->where(['uid' => ['eq', $this->userinfo->uid]])
            ->count();
        if ($education_total == 0) {
            model('Resume')->updateComplete(
                ['education' => 0],
                0,
                $this->userinfo->uid
            );
        }
        $this->writeMemberActionLog($this->userinfo->uid,'删除简历教育经历');
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 保存简历工作经历
     */
    public function workSave()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'uid' => $this->userinfo->uid,
            'companyname' => input('post.companyname/s', '', 'trim,badword_filter'),
            'jobname' => input('post.jobname/s', '', 'trim,badword_filter'),
            'duty' => input('post.duty/s', '', 'trim'),
            'starttime' => input('post.starttime/s', '', 'trim'),
            'endtime' => input('post.endtime/s', '', 'trim'),
            'todate' => input('post.todate/d', 0, 'intval')
        ];

        $id = intval($input_data['id']);
        $input_data['starttime'] = strtotime($input_data['starttime']);
        if ($input_data['todate'] == 1) {
            $input_data['endtime'] = 0;
        } else {
            $input_data['endtime'] = strtotime($input_data['endtime']);
        }

        if ($id > 0) {
            $result = model('ResumeWork')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, [
                    'id' => $id,
                    'uid' => $this->userinfo->uid
                ]);
            $return_id = $id;
        } else {
            unset($input_data['id']);
            $basic_info = model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->find();
            if ($basic_info === null) {
                $this->ajaxReturn(500, '请先填写基本资料');
            }
            $input_data['rid'] = $basic_info->id;
            $result = model('ResumeWork')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            $return_id = model('ResumeWork')->id;
        }

        if (false === $result) {
            $this->ajaxReturn(500, model('ResumeWork')->getError());
        }
        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
        }
        model('Resume')->updateComplete(['work' => 1], 0, $this->userinfo->uid);
        model('Resume')->refreshSearch(0, $this->userinfo->uid);
        $this->writeMemberActionLog($this->userinfo->uid,'保存简历工作经历');
        $this->ajaxReturn(200, '保存成功', ['return_id' => $return_id]);
    }
    /**
     * 删除工作经历
     */
    public function workDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id <= 0) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeWork')
            ->destroy(['id' => ['eq', $id], 'uid' => $this->userinfo->uid]);
        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
        }
        $work_total = model('ResumeWork')
            ->where(['uid' => ['eq', $this->userinfo->uid]])
            ->count();
        if ($work_total == 0) {
            model('Resume')->updateComplete(
                ['work' => 0],
                0,
                $this->userinfo->uid
            );
        }
        model('Resume')->refreshSearch(0, $this->userinfo->uid);
        $this->writeMemberActionLog($this->userinfo->uid,'删除简历工作经历');
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 获取求职意向
     */
    public function intentionList()
    {
        $basic = model('Resume')
            ->where('uid', $this->userinfo->uid)
            ->find();
        if ($basic === null) {
            $this->ajaxReturn(500, '没有找到简历');
        }
        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();

        $basic['current_text'] = isset(
            $category_data['QS_current'][$basic['current']]
        )
            ? $category_data['QS_current'][$basic['current']]
            : '';
        $list = model('ResumeIntention')
            ->field(
                'id,nature,category1,category2,category3,category,district1,district2,district3,district,minwage,maxwage,trade'
            )
            ->where(['uid' => ['eq', $this->userinfo->uid]])
            ->select();

        foreach ($list as $key => $value) {
            $value['nature_text'] = isset(
                model('Resume')->map_nature[$value['nature']]
            )
                ? model('Resume')->map_nature[$value['nature']]
                : '全职';
            $value['category_text'] = isset(
                $category_job_data[$value['category']]
            )
                ? $category_job_data[$value['category']]
                : '';
            $value['district_text'] = isset(
                $category_district_data[$value['district']]
            )
                ? $category_district_data[$value['district']]
                : '';
            $value['wage_text'] = model('BaseModel')->handle_wage(
                $value['minwage'],
                $value['maxwage'],
                0
            );
            $value['trade_text'] = isset(
                $category_data['QS_trade'][$value['trade']]
            )
                ? $category_data['QS_trade'][$value['trade']]
                : '';
            $list[$key] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', [
            'current' => $basic['current'],
            'current_text' => $basic['current_text'],
            'items' => $list
        ]);
    }
    /**
     * 当前求职状态保存
     */
    public function currentSave()
    {
        $current = input('post.current/d', 0, 'intval');
        model('Resume')
            ->allowField(true)
            ->save(['current' => $current], ['uid' => $this->userinfo->uid]);

        $this->writeMemberActionLog($this->userinfo->uid,'保存简历求职状态');
        $this->ajaxReturn(200, '保存成功');
    }
    /**
     * 求职意向保存
     */
    public function intentionSave()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'uid' => $this->userinfo->uid,
            'nature' => input('post.nature/d', 0, 'intval'),
            'district1' => input('post.district1/d', 0, 'intval'),
            'district2' => input('post.district2/d', 0, 'intval'),
            'district3' => input('post.district3/d', 0, 'intval'),
            'category1' => input('post.category1/d', 0, 'intval'),
            'category2' => input('post.category2/d', 0, 'intval'),
            'category3' => input('post.category3/d', 0, 'intval'),
            'minwage' => input('post.minwage/d', 0, 'intval'),
            'maxwage' => input('post.maxwage/d', 0, 'intval')
        ];
        $input_data['category'] =
            $input_data['category3'] > 0
                ? $input_data['category3']
                : ($input_data['category2'] > 0
                    ? $input_data['category2']
                    : $input_data['category1']);
        $input_data['district'] =
            $input_data['district3'] > 0
                ? $input_data['district3']
                : ($input_data['district2'] > 0
                    ? $input_data['district2']
                    : $input_data['district1']);
        $id = intval($input_data['id']);
        if ($id > 0) {
            if (input('?post.trade')) {
                $input_data['trade'] = input('post.trade/d', 0, 'intval');
            }
            $result = model('ResumeIntention')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, [
                    'id' => $id,
                    'uid' => $this->userinfo->uid
                ]);
            $return_id = $id;
        } else {
            if (input('?post.trade')) {
                $input_data['trade'] = input('post.trade/d', 0, 'intval');
            } else {
                $input_data['trade'] = 0;
            }
            unset($input_data['id']);
            $basic_info = model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->find();
            if ($basic_info === null) {
                $this->ajaxReturn(500, '请先填写基本资料');
            }
            $input_data['rid'] = $basic_info->id;
            $result = model('ResumeIntention')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            $return_id = model('ResumeIntention')->id;
        }

        if (false === $result) {
            $this->ajaxReturn(500, model('ResumeIntention')->getError());
        }
        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
        }
        model('Resume')->updateComplete(
            ['intention' => 1],
            0,
            $this->userinfo->uid
        );
        model('Resume')->refreshSearch(0, $this->userinfo->uid);

        $this->writeMemberActionLog($this->userinfo->uid,'保存简历求职意向');
        $this->ajaxReturn(200, '保存成功', ['return_id' => $return_id]);
    }
    /**
     * 删除求职意向
     */
    public function intentionDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id <= 0) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeIntention')
            ->destroy(['id' => ['eq', $id], 'uid' => $this->userinfo->uid]);

        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
        }
        $intention_total = model('ResumeIntention')
            ->where(['uid' => ['eq', $this->userinfo->uid]])
            ->count();
        if ($intention_total == 0) {
            model('Resume')->updateComplete(
                ['intention' => 0],
                0,
                $this->userinfo->uid
            );
        }
        model('Resume')->refreshSearch(0, $this->userinfo->uid);
        $this->writeMemberActionLog($this->userinfo->uid,'删除简历求职意向');
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 保存简历培训经历
     */
    public function trainingSave()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'uid' => $this->userinfo->uid,
            'agency' => input('post.agency/s', '', 'trim,badword_filter'),
            'course' => input('post.course/s', '', 'trim,badword_filter'),
            'description' => input('post.description/s', '', 'trim,badword_filter'),
            'starttime' => input('post.starttime/s', '', 'trim'),
            'endtime' => input('post.endtime/s', '', 'trim'),
            'todate' => input('post.todate/d', 0, 'intval')
        ];

        $id = intval($input_data['id']);
        $input_data['starttime'] = strtotime($input_data['starttime']);
        if ($input_data['todate'] == 1) {
            $input_data['endtime'] = 0;
        } else {
            $input_data['endtime'] = strtotime($input_data['endtime']);
        }
        if ($id > 0) {
            $result = model('ResumeTraining')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, [
                    'id' => $id,
                    'uid' => $this->userinfo->uid
                ]);
            $return_id = $id;
        } else {
            unset($input_data['id']);
            $basic_info = model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->find();
            if ($basic_info === null) {
                $this->ajaxReturn(500, '请先填写基本资料');
            }
            $input_data['rid'] = $basic_info->id;
            $result = model('ResumeTraining')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            $return_id = model('ResumeTraining')->id;
        }

        if (false === $result) {
            $this->ajaxReturn(500, model('ResumeTraining')->getError());
        }
        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
            model('Resume')->refreshSearch(0, $this->userinfo->uid);
        }
        model('Resume')->updateComplete(
            ['training' => 1],
            0,
            $this->userinfo->uid
        );
        $this->writeMemberActionLog($this->userinfo->uid,'保存简历培训经历');
        $this->ajaxReturn(200, '保存成功', ['return_id' => $return_id]);
    }
    /**
     * 删除培训经历
     */
    public function trainingDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id <= 0) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeTraining')
            ->destroy(['id' => ['eq', $id], 'uid' => $this->userinfo->uid]);
        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
            model('Resume')->refreshSearch(0, $this->userinfo->uid);
        }
        $training_total = model('ResumeTraining')
            ->where(['uid' => ['eq', $this->userinfo->uid]])
            ->count();
        if ($training_total == 0) {
            model('Resume')->updateComplete(
                ['training' => 0],
                0,
                $this->userinfo->uid
            );
        }
        $this->writeMemberActionLog($this->userinfo->uid,'删除简历培训经历');
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 保存简历项目经历
     */
    public function projectSave()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'uid' => $this->userinfo->uid,
            'projectname' => input('post.projectname/s', '', 'trim,badword_filter'),
            'role' => input('post.role/s', '', 'trim,badword_filter'),
            'description' => input('post.description/s', '', 'trim,badword_filter'),
            'starttime' => input('post.starttime/s', '', 'trim'),
            'endtime' => input('post.endtime/s', '', 'trim'),
            'todate' => input('post.todate/d', 0, 'intval')
        ];

        $id = intval($input_data['id']);
        $input_data['starttime'] = strtotime($input_data['starttime']);
        if ($input_data['todate'] == 1) {
            $input_data['endtime'] = 0;
        } else {
            $input_data['endtime'] = strtotime($input_data['endtime']);
        }
        if ($id > 0) {
            $result = model('ResumeProject')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, [
                    'id' => $id,
                    'uid' => $this->userinfo->uid
                ]);
            $return_id = $id;
        } else {
            unset($input_data['id']);
            $basic_info = model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->find();
            if ($basic_info === null) {
                $this->ajaxReturn(500, '请先填写基本资料');
            }
            $input_data['rid'] = $basic_info->id;
            $result = model('ResumeProject')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            $return_id = model('ResumeProject')->id;
        }

        if (false === $result) {
            $this->ajaxReturn(500, model('ResumeProject')->getError());
        }
        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
        }
        model('Resume')->updateComplete(
            ['project' => 1],
            0,
            $this->userinfo->uid
        );
        model('Resume')->refreshSearch(0, $this->userinfo->uid);
        $this->writeMemberActionLog($this->userinfo->uid,'保存简历项目经历');
        $this->ajaxReturn(200, '保存成功', ['return_id' => $return_id]);
    }
    /**
     * 删除项目经历
     */
    public function projectDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id <= 0) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeProject')
            ->destroy(['id' => ['eq', $id], 'uid' => $this->userinfo->uid]);
        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
        }
        $project_total = model('ResumeProject')
            ->where(['uid' => ['eq', $this->userinfo->uid]])
            ->count();
        if ($project_total == 0) {
            model('Resume')->updateComplete(
                ['project' => 0],
                0,
                $this->userinfo->uid
            );
        }
        model('Resume')->refreshSearch(0, $this->userinfo->uid);
        $this->writeMemberActionLog($this->userinfo->uid,'删除简历项目经历');
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 保存简历证书
     */
    public function certificateSave()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'uid' => $this->userinfo->uid,
            'name' => input('post.name/s', '', 'trim,badword_filter'),
            'obtaintime' => input('post.obtaintime/s', '', 'trim')
        ];

        $id = intval($input_data['id']);
        $input_data['obtaintime'] = strtotime($input_data['obtaintime']);
        if ($id > 0) {
            $result = model('ResumeCertificate')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, [
                    'id' => $id,
                    'uid' => $this->userinfo->uid
                ]);
            $return_id = $id;
        } else {
            $totalCheck = model('ResumeCertificate')->where('uid',$this->userinfo->uid)->count();
            if($totalCheck>=6){
                $this->ajaxReturn(500, '最多可添加6份证书');
            }
            unset($input_data['id']);
            $basic_info = model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->find();
            if ($basic_info === null) {
                $this->ajaxReturn(500, '请先填写基本资料');
            }
            $input_data['rid'] = $basic_info->id;
            $result = model('ResumeCertificate')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            $return_id = model('ResumeCertificate')->id;
        }

        if (false === $result) {
            $this->ajaxReturn(500, model('ResumeCertificate')->getError());
        }
        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
            model('Resume')->refreshSearch(0, $this->userinfo->uid);
        }
        model('Resume')->updateComplete(
            ['certificate' => 1],
            0,
            $this->userinfo->uid
        );
        $this->writeMemberActionLog($this->userinfo->uid,'保存简历证书');
        $this->ajaxReturn(200, '保存成功', ['return_id' => $return_id]);
    }
    /**
     * 删除证书
     */
    public function certificateDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id <= 0) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeCertificate')
            ->destroy(['id' => ['eq', $id], 'uid' => $this->userinfo->uid]);
        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
            model('Resume')->refreshSearch(0, $this->userinfo->uid);
        }
        $certificate_total = model('ResumeCertificate')
            ->where(['uid' => ['eq', $this->userinfo->uid]])
            ->count();
        if ($certificate_total == 0) {
            model('Resume')->updateComplete(
                ['certificate' => 0],
                0,
                $this->userinfo->uid
            );
        }
        $this->writeMemberActionLog($this->userinfo->uid,'删除简历证书');
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 保存语言能力
     */
    public function languageSave()
    {
        $input_data = [
            'id' => input('post.id/d', 0, 'intval'),
            'uid' => $this->userinfo->uid,
            'language' => input('post.language/d', 0, 'intval'),
            'level' => input('post.level/d', 0, 'intval')
        ];

        $id = intval($input_data['id']);
        if ($id > 0) {
            $result = model('ResumeLanguage')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, [
                    'id' => $id,
                    'uid' => $this->userinfo->uid
                ]);
            $return_id = $id;
        } else {
            $totalCheck = model('ResumeLanguage')->where('uid',$this->userinfo->uid)->count();
            if($totalCheck>=6){
                $this->ajaxReturn(500, '最多可添加6种语言');
            }
            unset($input_data['id']);
            $basic_info = model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->find();
            if ($basic_info === null) {
                $this->ajaxReturn(500, '请先填写基本资料');
            }
            $input_data['rid'] = $basic_info->id;
            $result = model('ResumeLanguage')
                ->validate(true)
                ->allowField(true)
                ->save($input_data);
            $return_id = model('ResumeLanguage')->id;
        }

        if (false === $result) {
            $this->ajaxReturn(500, model('ResumeLanguage')->getError());
        }
        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
            model('Resume')->refreshSearch(0, $this->userinfo->uid);
        }
        model('Resume')->updateComplete(
            ['language' => 1],
            0,
            $this->userinfo->uid
        );
        $this->writeMemberActionLog($this->userinfo->uid,'保存简历语言能力');
        $this->ajaxReturn(200, '保存成功', ['return_id' => $return_id]);
    }
    /**
     * 删除语言能力
     */
    public function languageDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id <= 0) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeLanguage')
            ->destroy(['id' => ['eq', $id], 'uid' => $this->userinfo->uid]);
        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->setField('audit', 0);
            model('Resume')->refreshSearch(0, $this->userinfo->uid);
        }
        $language_total = model('ResumeLanguage')
            ->where(['uid' => ['eq', $this->userinfo->uid]])
            ->count();
        if ($language_total == 0) {
            model('Resume')->updateComplete(
                ['language' => 0],
                0,
                $this->userinfo->uid
            );
        }
        $this->writeMemberActionLog($this->userinfo->uid,'删除简历语言能力');
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 标签
     */
    public function tag()
    {
        $tag = input('post.tag/a', []);
        if (isset($tag) && !empty($tag)) {
            $tag = array_unique($tag);
            $tag = implode(',', $tag);
        } else {
            $tag = '';
        }
        $save_data['tag'] = $tag;
        if (config('global_config.audit_edit_resume') == 1) {
            $save_data['audit'] = 0;
        }
        model('Resume')
            ->allowField(true)
            ->save($save_data, ['uid' => $this->userinfo->uid]);

        if (config('global_config.audit_edit_resume') == 1) {
            model('Resume')->refreshSearch(0, $this->userinfo->uid);
        }

        model('Resume')->updateComplete(
            ['tag' => $tag == '' ? 0 : 1],
            0,
            $this->userinfo->uid
        );
        $this->writeMemberActionLog($this->userinfo->uid,'保存简历标签');
        $this->ajaxReturn(200, '保存成功');
    }
    /**
     * 保存简历自我描述
     */
    public function specialty()
    {
        $specialty = input('post.specialty/s', '', 'trim,badword_filter');
        $save_data['specialty'] = $specialty;
        if (config('global_config.audit_edit_resume') == 1) {
            $save_data['audit'] = 0;
        }
        $result = model('Resume')
            ->allowField(true)
            ->save($save_data, [
                'uid' => $this->userinfo->uid
            ]);

        model('Resume')->updateComplete(
            ['specialty' => $specialty == '' ? 0 : 1],
            0,
            $this->userinfo->uid
        );
        model('Resume')->refreshSearch(0, $this->userinfo->uid);
        $this->writeMemberActionLog($this->userinfo->uid,'保存简历自我描述');
        $this->ajaxReturn(200, '保存成功');
    }
    //上传头像照片
    public function uploadPhoto()
    {
        $file = input('file.file');
        if (!$file) {
            $this->ajaxReturn(500, '请选择文件');
        }
        $extra = input('post.extra/s','','trim');
        $filemanager = new \app\common\lib\FileManager();
        $result = $filemanager->upload($file);
        if (false !== $result) {
            $resume = model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->find();
            $resume->photo_img = $result['file_id'];
            $resume->save();
            if($extra=='resume_photo'){
                cache('scan_upload_result_resume_photo_'.$this->userinfo->uid,json_encode($result));
            }
            model('Task')->doTask(
                $this->userinfo->uid,
                $this->userinfo->utype,
                'upload_photo'
            );
            $this->writeMemberActionLog($this->userinfo->uid,'上传简历照片');
            $this->ajaxReturn(200, '上传成功', $result);
        } else {
            $this->ajaxReturn(500, $filemanager->getError());
        }
    }
    //上传照片作品
    public function imgAdd()
    {
        $file = input('file.file');
        if (!$file) {
            $this->ajaxReturn(500, '请选择文件');
        }
        $extra = input('post.extra/s','','trim');
        $count = model('ResumeImg')
            ->where('uid', $this->userinfo->uid)
            ->count();
        if ($count >= 6) {
            $this->ajaxReturn(500, '最多上传6张作品');
        }
        $filemanager = new \app\common\lib\FileManager();
        $result = $filemanager->upload($file);
        if (false !== $result) {
            $resume = model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->find();
            $data['rid'] = $resume['id'];
            $data['uid'] = $resume['uid'];
            $data['img'] = $result['file_id'];
            $data['title'] = '';
            $data['addtime'] = time();
            $data['audit'] = 0;
            model('ResumeImg')->save($data);
            model('Resume')->updateComplete(
                ['img' => 1],
                0,
                $this->userinfo->uid
            );

            $result['audit'] = 0;
            $result['audit_text'] = model('ResumeImg')->map_audit[
                $result['audit']
            ];
            $result['id'] = model('ResumeImg')->id;
            if($extra=='resume_img'){
                $img_list = model('ResumeImg')->getList(['uid'=>$this->userinfo->uid]);
                cache('scan_upload_result_resume_img_'.$this->userinfo->uid,json_encode($img_list));
            }
            $this->writeMemberActionLog($this->userinfo->uid,'上传简历作品');
            $this->ajaxReturn(200, '上传成功', $result);
        } else {
            $this->ajaxReturn(500, $filemanager->getError());
        }
    }
    //删除照片作品
    public function imgDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        if ($id <= 0) {
            $this->ajaxReturn(500, '参数错误');
        }
        $extra = input('post.extra/s','','trim');
        model('ResumeImg')
            ->destroy(['id' => ['eq', $id], 'uid' => $this->userinfo->uid]);
        $img_total = model('ResumeImg')
            ->where(['uid' => ['eq', $this->userinfo->uid]])
            ->count();
        if ($img_total == 0) {
            model('Resume')->updateComplete(
                ['img' => 0],
                0,
                $this->userinfo->uid
            );
        }
        if($extra=='resume_img'){
            $img_list = model('ResumeImg')->getList(['uid'=>$this->userinfo->uid]);
            cache('scan_upload_result_resume_img_'.$this->userinfo->uid,json_encode($img_list));
        }
        $this->writeMemberActionLog($this->userinfo->uid,'删除简历作品');
        $this->ajaxReturn(200, '删除成功');
    }
}
