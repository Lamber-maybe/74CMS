<?php
namespace app\v1_0\controller\company;

class Job extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
        $this->interceptCompanyAuth();
    }
    /**
     * 检查可发布职位数
     */
    public function checkJobaddNumber()
    {
        $enable_num = model('Job')->getEnableJobaddNum($this->userinfo->uid);
        $member_setmeal_info = model('Member')->getMemberSetmeal($this->userinfo->uid);

        $this->ajaxReturn(200, '获取数据成功', [
            'enable_addjob_num_total' => $member_setmeal_info['jobs_meanwhile'],
            'enable_addjob_num' => $enable_num,
            'setmeal_name' => $member_setmeal_info['name'],
        ]);
    }
    /**
     * 添加职位预请求
     */
    public function addPre()
    {
        $field_rule_data = model('FieldRule')->getCache();
        $field_rule = [
            'basic' => $field_rule_data['Job'],
            'contact' => $field_rule_data['JobContact'],
        ];
        foreach ($field_rule as $key => $rule) {
            foreach ($rule as $field => $field_attr) {
                $_arr = [
                    'field_name' => $field_attr['field_name'],
                    'is_require' => intval($field_attr['is_require']),
                    'is_display' => intval($field_attr['is_display']),
                    'field_cn' => $field_attr['field_cn'],
                ];
                $field_rule[$key][$field] = $_arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', [
            'field_rule' => $field_rule,
        ]);
    }
    /**
     * 添加职位保存
     */
    public function addSave()
    {
        $input_data = [
            'basic' => [
                'uid' => $this->userinfo->uid,
                'jobname' => input('post.basic.jobname/s', '', 'trim,badword_filter'),
                'nature' => input('post.basic.nature/d', 1, 'intval'),
                'category1' => input('post.basic.category1/d', 0, 'intval'),
                'category2' => input('post.basic.category2/d', 0, 'intval'),
                'category3' => input('post.basic.category3/d', 0, 'intval'),
                'experience' => input('post.basic.experience/d', 0, 'intval'),
                'education' => input('post.basic.education/d', 0, 'intval'),
                'minwage' => input('post.basic.minwage/d', 0, 'intval'),
                'maxwage' => input('post.basic.maxwage/d', 0, 'intval'),
                'negotiable' => input('post.basic.negotiable/d', 0, 'intval'),
                'district1' => input('post.basic.district1/d', 0, 'intval'),
                'district2' => input('post.basic.district2/d', 0, 'intval'),
                'district3' => input('post.basic.district3/d', 0, 'intval'),
                'content' => input('post.basic.content/s', '', 'trim,badword_filter'),
                'address' => input('post.basic.address/s', '', 'trim,badword_filter'),
                'map_lat' => input('post.basic.map_lat/s', '', 'trim'),
                'map_lng' => input('post.basic.map_lng/s', '', 'trim'),
                'map_zoom' => input('post.basic.map_zoom/d', 12, 'intval'),
                'platform' => config('platform'),
            ],
            'contact' => [
                'uid' => $this->userinfo->uid,
                'use_company_contact' => input(
                    'post.contact.use_company_contact/d',
                    0,
                    'intval'
                ),
                'contact' => input('post.contact.contact/s', '', 'trim,badword_filter'),
                'mobile' => input('post.contact.mobile/s', '', 'trim,badword_filter'),
                'is_display' => input('post.contact.is_display/d', 1, 'intval'),
            ],
        ];
        if (input('?post.basic.department')) {
            $input_data['basic']['department'] = input(
                'post.basic.department/s',
                '',
                'trim,badword_filter'
            );
        } else {
            $input_data['basic']['department'] = '';
        }
        if (
            input('?post.basic.minage') &&
            input('?post.basic.maxage') &&
            input('?post.basic.age_na')
        ) {
            $input_data['basic']['minage'] = input(
                'post.basic.minage/d',
                0,
                'intval'
            );
            $input_data['basic']['maxage'] = input(
                'post.basic.maxage/d',
                0,
                'intval'
            );
            $input_data['basic']['age_na'] = input(
                'post.basic.age_na/d',
                0,
                'intval'
            );
        } else {
            $input_data['basic']['minage'] = 0;
            $input_data['basic']['maxage'] = 0;
            $input_data['basic']['age_na'] = 1;
        }
        if (input('?post.basic.tag')) {
            $input_data['basic']['tag'] = input('post.basic.tag/a', []);
        } else {
            $input_data['basic']['tag'] = [];
        }
        if (input('?post.basic.amount')) {
            $input_data['basic']['amount'] = input(
                'post.basic.amount/d',
                0,
                'intval'
            );

            // 校验招聘人数不能小于0 chenyang 2022年3月10日10:44:39
            if ($input_data['basic']['amount'] < 0) {
                $this->ajaxReturn(400, '请填写正确的招聘人数');
            }
        } else {
            $input_data['basic']['amount'] = 0;
        }
        if (input('?post.basic.custom_field_1')) {
            $input_data['basic']['custom_field_1'] = input(
                'post.basic.custom_field_1/s',
                '',
                'trim,badword_filter'
            );
        } else {
            $input_data['basic']['custom_field_1'] = '';
        }
        if (input('?post.basic.custom_field_2')) {
            $input_data['basic']['custom_field_2'] = input(
                'post.basic.custom_field_2/s',
                '',
                'trim,badword_filter'
            );
        } else {
            $input_data['basic']['custom_field_2'] = '';
        }
        if (input('?post.basic.custom_field_3')) {
            $input_data['basic']['custom_field_3'] = input(
                'post.basic.custom_field_3/s',
                '',
                'trim,badword_filter'
            );
        } else {
            $input_data['basic']['custom_field_3'] = '';
        }
        $input_data['basic']['category'] =
        $input_data['basic']['category3'] > 0
        ? $input_data['basic']['category3']
        : ($input_data['basic']['category2'] > 0
            ? $input_data['basic']['category2']
            : $input_data['basic']['category1']);
        $input_data['basic']['district'] =
        $input_data['basic']['district3'] > 0
        ? $input_data['basic']['district3']
        : ($input_data['basic']['district2'] > 0
            ? $input_data['basic']['district2']
            : $input_data['basic']['district1']);
        $input_data['basic']['tag'] = !empty($input_data['basic']['tag'])
        ? implode(',', $input_data['basic']['tag'])
        : '';
        $setmeal = model('Member')->getMemberSetmeal($this->userinfo->uid);
        $input_data['basic']['company_id'] = $this->company_profile['id'];
        $input_data['basic']['addtime'] = time();
        $input_data['basic']['refreshtime'] = $input_data['basic']['addtime'];
        $input_data['basic']['setmeal_id'] = $setmeal['setmeal_id'];
        //新添加的职位，根据配置赋值审核状态
        $input_data['basic']['audit'] =
        $this->company_profile['audit'] == 1
        ? config('global_config.audit_verifycom_addjob')
        : config('global_config.audit_unverifycom_addjob');
        $input_data['basic']['click'] = 0;
        $input_data['basic']['robot'] = 0;
        $input_data['basic']['is_display'] = 1;
        $input_data['basic']['stick'] = 0;
        $input_data['basic']['emergency'] = 0;

        if (input('?post.contact.weixin')) {
            $input_data['contact']['weixin'] = input(
                'post.contact.weixin/s',
                '',
                'trim,badword_filter'
            );
        } else {
            $input_data['contact']['weixin'] = '';
        }
        if (input('?post.contact.telephone')) {
            $input_data['contact']['telephone'] = input(
                'post.contact.telephone/s',
                '',
                'trim,badword_filter'
            );
        } else {
            $input_data['contact']['telephone'] = '';
        }
        if (input('?post.contact.qq')) {
            $input_data['contact']['qq'] = input(
                'post.contact.qq/s',
                '',
                'trim,badword_filter'
            );
        } else {
            $input_data['contact']['qq'] = '';
        }
        if (input('?post.contact.email')) {
            $input_data['contact']['email'] = input(
                'post.contact.email/s',
                '',
                'trim,badword_filter'
            );
        } else {
            $input_data['contact']['email'] = '';
        }

        \think\Db::startTrans();
        try {
            $enable_num = model('Job')->getEnableJobaddNum($this->userinfo->uid);
            if($enable_num<=0){
                throw new \Exception('当前可发布职位数为0，请先升级套餐');
            }
            $result = model('Job')
                ->validate(true)
                ->allowField(true)
                ->save($input_data['basic']);
            if (false === $result) {
                throw new \Exception(model('Job')->getError());
            }
            $jobid = model('Job')->id;
            $input_data['contact']['uid'] = $this->userinfo->uid;
            $input_data['contact']['jid'] = $jobid;
            if ($input_data['contact']['use_company_contact'] == 1) {
                $result = model('JobContact')
                    ->allowField(true)
                    ->save($input_data['contact']);
            } else {
                $result = model('JobContact')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data['contact']);
            }

            if (false === $result) {
                throw new \Exception(model('JobContact')->getError());
            }
            model('Company')->where('uid',$this->userinfo->uid)->setField('refreshtime',$input_data['basic']['refreshtime']);

            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        model('Job')->refreshSearch($jobid);
        if ($input_data['basic']['audit'] == 1) {
            $service_stick_list = model('CompanyServiceStick')->getList(
                3,
                $this->userinfo->uid
            );
        } else {
            $service_stick_list = [];
        }
        $this->writeMemberActionLog($this->userinfo->uid,'发布职位【职位id：'.$jobid.'，职位名称：'.$input_data['basic']['jobname'].'】');
        $this->ajaxReturn(200, '保存成功', [
            'id' => $jobid,
            'audit' => $input_data['basic']['audit'],
            'service_stick_list' => $service_stick_list,
        ]);
    }
    /**
     * 获取职位详情
     */
    protected function getDetail($id)
    {
        $id = intval($id);
        $where['id'] = $id;
        $basic = model('Job')
            ->where($where)
            ->field('uid', true) //排除字段
            ->find();
        if ($basic === null) {
            return false;
        }
        $basic['jobname'] = htmlspecialchars_decode($basic['jobname'],ENT_QUOTES);
        $basic['address'] = htmlspecialchars_decode($basic['address'],ENT_QUOTES);
        $basic['content'] = htmlspecialchars_decode($basic['content'],ENT_QUOTES);
        $basic['department'] = htmlspecialchars_decode($basic['department'],ENT_QUOTES);
        $basic['custom_field_1'] = htmlspecialchars_decode($basic['custom_field_1'],ENT_QUOTES);
        $basic['custom_field_2'] = htmlspecialchars_decode($basic['custom_field_2'],ENT_QUOTES);
        $basic['custom_field_3'] = htmlspecialchars_decode($basic['custom_field_3'],ENT_QUOTES);

        $category_data = model('Category')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $basic['nature_text'] = isset(
            model('Job')->map_nature[$basic['nature']]
        )
        ? model('Job')->map_nature[$basic['nature']]
        : '全职';
        $basic['sex_text'] = isset(model('Job')->map_sex[$basic['sex']])
        ? model('Job')->map_sex[$basic['nature']]
        : '不限';
        $basic['district_text'] = isset(
            $category_district_data[$basic['district']]
        )
        ? $category_district_data[$basic['district']]
        : '';
        $basic['district_text_full'] = '';
        if($basic['district1']){
            $basic['district_text_full'] = isset(
                $category_district_data[$basic['district1']]
            )
                ? $category_district_data[$basic['district1']]
                : '';
        }else{
            $basic['district_text_full'] = '';
        }
        
        if($basic['district_text_full']!='' && $basic['district2']>0){
            $basic['district_text_full'] .= isset(
                $category_district_data[$basic['district2']]
            )
                ? $category_district_data[$basic['district2']]
                : '';
        }
        if($basic['district_text_full']!='' && $basic['district3']>0){
            $basic['district_text_full'] .= isset(
                $category_district_data[$basic['district3']]
            )
                ? $category_district_data[$basic['district3']]
                : '';
        }
        $basic['category_text'] = isset($category_job_data[$basic['category']])
        ? $category_job_data[$basic['category']]
        : '';
        $basic['wage_text'] = model('BaseModel')->handle_wage(
            $basic['minwage'],
            $basic['maxwage'],
            $basic['negotiable']
        );

        $basic['education_text'] = isset(
            model('BaseModel')->map_education[$basic['education']]
        )
        ? model('BaseModel')->map_education[$basic['education']]
        : '';
        $basic['experience_text'] = isset(
            model('BaseModel')->map_experience[$basic['experience']]
        )
        ? model('BaseModel')->map_experience[$basic['experience']]
        : '';
        $basic['tag'] = $basic['tag'] == '' ? [] : explode(',', $basic['tag']);
        $basic['tag_text'] = '';
        $basic['tag_text_arr'] = [];
        if (!empty($basic['tag'])) {
            $tag_cn_arr = [];
            foreach ($basic['tag'] as $k => $v) {
                isset($category_data['QS_jobtag'][$v]) &&
                    ($tag_cn_arr[] = $category_data['QS_jobtag'][$v]);
            }
            if (!empty($tag_cn_arr)) {
                $basic['tag_text_arr'] = $tag_cn_arr;
            }
        }
        $basic['amount_text'] =
        $basic['amount'] == 0 ? '若干' : $basic['amount'] . '人';
        if ($basic['age_na'] == 1) {
            $basic['age_text'] = '不限';
        } else {
            $basic['age_text'] = $basic['minage'] . '-' . $basic['maxage'];
        }

        //联系方式
        $contact = model('JobContact')
            ->field('id,jid,uid', true)
            ->where(['jid' => ['eq', $basic['id']]])
            ->find();
        $contact['contact'] = htmlspecialchars_decode($contact['contact'],ENT_QUOTES);
        $contact['weixin'] = htmlspecialchars_decode($contact['weixin'],ENT_QUOTES);
        $contact['telephone'] = htmlspecialchars_decode($contact['telephone'],ENT_QUOTES);
        return [
            'basic' => $basic,
            'contact' => $contact,
        ];
    }
    /**
     * 修改职位预请求
     */
    public function editPre()
    {
        $id = input('get.id/d', 0, 'intval');
        $jobinfo = $this->getDetail($id);
        if ($jobinfo === false) {
            $this->ajaxReturn(500, '职位信息为空');
        }
        $field_rule_data = model('FieldRule')->getCache();
        $field_rule = [
            'basic' => $field_rule_data['Job'],
            'contact' => $field_rule_data['JobContact'],
        ];
        foreach ($field_rule as $key => $rule) {
            foreach ($rule as $field => $field_attr) {
                $_arr = [
                    'field_name' => $field_attr['field_name'],
                    'is_require' => intval($field_attr['is_require']),
                    'is_display' => intval($field_attr['is_display']),
                    'field_cn' => $field_attr['field_cn'],
                ];
                $field_rule[$key][$field] = $_arr;
            }
        }
        $this->ajaxReturn(200, '获取数据成功', [
            'basic' => $jobinfo['basic'],
            'contact' => $jobinfo['contact'],
            'field_rule' => $field_rule,
        ]);
    }
    /**
     * 修改职位保存
     */
    public function editSave()
    {
        $input_data = [
            'basic' => [
                'id' => input('post.basic.id/d', 0, 'intval'),
                'uid' => $this->userinfo->uid,
                'jobname' => input('post.basic.jobname/s', '', 'trim,badword_filter'),
                'nature' => input('post.basic.nature/d', 1, 'intval'),
                'category1' => input('post.basic.category1/d', 0, 'intval'),
                'category2' => input('post.basic.category2/d', 0, 'intval'),
                'category3' => input('post.basic.category3/d', 0, 'intval'),
                'experience' => input('post.basic.experience/d', 0, 'intval'),
                'education' => input('post.basic.education/d', 0, 'intval'),
                'minwage' => input('post.basic.minwage/d', 0, 'intval'),
                'maxwage' => input('post.basic.maxwage/d', 0, 'intval'),
                'negotiable' => input('post.basic.negotiable/d', 0, 'intval'),
                'district1' => input('post.basic.district1/d', 0, 'intval'),
                'district2' => input('post.basic.district2/d', 0, 'intval'),
                'district3' => input('post.basic.district3/d', 0, 'intval'),
                'content' => input('post.basic.content/s', '', 'trim,badword_filter'),
                'address' => input('post.basic.address/s', '', 'trim,badword_filter'),
                'map_lat' => input('post.basic.map_lat/s', '', 'trim'),
                'map_lng' => input('post.basic.map_lng/s', '', 'trim'),
                'map_zoom' => input('post.basic.map_zoom/d', 12, 'intval'),
            ],
            'contact' => [
                'uid' => $this->userinfo->uid,
                'use_company_contact' => input(
                    'post.contact.use_company_contact/d',
                    0,
                    'intval'
                ),
                'contact' => input('post.contact.contact/s', '', 'trim,badword_filter'),
                'mobile' => input('post.contact.mobile/s', '', 'trim,badword_filter'),
                'is_display' => input('post.contact.is_display/d', 1, 'intval'),
            ],
        ];
        $jobid = $input_data['basic']['id'];
        if ($jobid == 0) {
            $this->ajaxReturn(500, '请选择职位');
        }
        if (input('?post.basic.department')) {
            $input_data['basic']['department'] = input(
                'post.basic.department/s',
                '',
                'trim,badword_filter'
            );
        }
        if (
            input('?post.basic.minage') &&
            input('?post.basic.maxage') &&
            input('?post.basic.age_na')
        ) {
            $input_data['basic']['minage'] = input(
                'post.basic.minage/d',
                0,
                'intval'
            );
            $input_data['basic']['maxage'] = input(
                'post.basic.maxage/d',
                0,
                'intval'
            );
            $input_data['basic']['age_na'] = input(
                'post.basic.age_na/d',
                0,
                'intval'
            );
        }
        if (input('?post.basic.tag')) {
            $input_data['basic']['tag'] = input('post.basic.tag/a', []);
            $input_data['basic']['tag'] = !empty($input_data['basic']['tag'])
            ? implode(',', $input_data['basic']['tag'])
            : '';
        }
        if (input('?post.basic.amount')) {
            $input_data['basic']['amount'] = input(
                'post.basic.amount/d',
                0,
                'intval'
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
        $input_data['basic']['category'] =
        $input_data['basic']['category3'] > 0
        ? $input_data['basic']['category3']
        : ($input_data['basic']['category2'] > 0
            ? $input_data['basic']['category2']
            : $input_data['basic']['category1']);
        $input_data['basic']['district'] =
        $input_data['basic']['district3'] > 0
        ? $input_data['basic']['district3']
        : ($input_data['basic']['district2'] > 0
            ? $input_data['basic']['district2']
            : $input_data['basic']['district1']);

        //修改的职位，根据配置赋值审核状态
        if (
            $this->company_profile['audit'] == 1 &&
            config('global_config.audit_verifycom_editjob') == 1
        ) {
            $input_data['basic']['audit'] = 0;
        }
        if (
            $this->company_profile['audit'] == 0 &&
            config('global_config.audit_unverifycom_editjob') == 1
        ) {
            $input_data['basic']['audit'] = 0;
        }

        if (input('?post.contact.weixin')) {
            $input_data['contact']['weixin'] = input(
                'post.contact.weixin/s',
                '',
                'trim,badword_filter'
            );
        }
        if (input('?post.contact.telephone')) {
            $input_data['contact']['telephone'] = input(
                'post.contact.telephone/s',
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
        if (input('?post.contact.email')) {
            $input_data['contact']['email'] = input(
                'post.contact.email/s',
                '',
                'trim,badword_filter'
            );
        }

        \think\Db::startTrans();
        try {
            $result = model('Job')
                ->validate(true)
                ->allowField(true)
                ->save($input_data['basic'], [
                    'id' => $jobid,
                    'uid' => $this->userinfo->uid,
                ]);
            if (false === $result) {
                throw new \Exception(model('Job')->getError());
            }
            if ($input_data['contact']['use_company_contact'] == 1) {
                $result = model('JobContact')
                    ->allowField(true)
                    ->save($input_data['contact'], [
                        'jid' => $jobid,
                        'uid' => $this->userinfo->uid,
                    ]);
            } else {
                $result = model('JobContact')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data['contact'], [
                        'jid' => $jobid,
                        'uid' => $this->userinfo->uid,
                    ]);
            }

            if (false === $result) {
                throw new \Exception(model('JobContact')->getError());
            }

            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        model('Job')->refreshSearch($jobid);
        $this->writeMemberActionLog($this->userinfo->uid,'修改职位【职位id：'.$jobid.'，职位名称：'.$input_data['basic']['jobname'].'】');
        $this->ajaxReturn(200, '保存成功');
    }
    public function index()
    {
        $type = input('get.type/d', 0, 'intval');
        $current_page = input('get.page/d', 1, 'intval');
        $pagesize = input('get.pagesize/d', 5, 'intval');
        $list = model('Job')
            ->field(
                'id,jobname,emergency,stick,click,audit,is_display,refreshtime'
            )
            ->where('uid', 'eq', $this->userinfo->uid);
        switch ($type) {
            case 1: //审核中
                $list = $list->where('audit', 0);
                break;
            case 2: //已下线
                $list = $list->where(function ($query) {
                    $query->whereOr('is_display', 0)->whereOr('audit', 2);
                });
                break;
            default:
                //发布中
                $list = $list->where('audit', 1)->where('is_display', 1);
                break;
        }
        $list = $list
            ->order('refreshtime desc')
            ->page($current_page, $pagesize)
            ->select();
        $jobid_arr = $apply_total_list = $job_audit_reason_list = [];
        foreach ($list as $key => $value) {
            $jobid_arr[] = $value['id'];
        }
        if (!empty($jobid_arr)) {
            $apply_list = model('JobApply')
                ->where('jobid', 'in', $jobid_arr)
                ->column('id,jobid,is_look', 'id');
            $apply_total_list = [];
            foreach ($apply_list as $key => $value) {
                if ($value['is_look'] == 0) {
                    $apply_total_list[$value['jobid']]['nolook'] = isset(
                        $apply_total_list[$value['jobid']]['nolook']
                    )
                    ? $apply_total_list[$value['jobid']]['nolook']+1
                    : 1;
                }

                $apply_total_list[$value['jobid']]['all'] = isset($apply_total_list[$value['jobid']]['all'])? $apply_total_list[$value['jobid']]['all']+1 : 1;
            }
            foreach ($apply_total_list as $key => $value) {
                if (!isset($apply_total_list[$key]['nolook'])) {
                    $apply_total_list[$key]['nolook'] = 0;
                }
                if (!isset($apply_total_list[$key]['all'])) {
                    $apply_total_list[$key]['all'] = 0;
                }
            }
            $job_audit_log = model('JobAuditLog')
                ->field('jobid,reason')
                ->where(['jobid' => ['in', $jobid_arr], 'audit' => 2])
                ->order('id desc')
                ->select();
            foreach ($job_audit_log as $key => $value) {
                if (isset($job_audit_reason_list[$value['jobid']])) {
                    continue;
                }
                $job_audit_reason_list[$value['jobid']] = $value['reason'];
            }
        }
        if ($type == 0) {
            $service_refresh_jobid_arr = model('RefreshjobQueue')
                ->where('uid', $this->userinfo->uid)
                ->column('jobid');
        } else {
            $service_refresh_jobid_arr = [];
        }
        foreach ($list as $key => $value) {
            $value['jobname'] = htmlspecialchars_decode($value['jobname'],ENT_QUOTES);
            $value['resume_all'] = isset($apply_total_list[$value['id']])
            ? $apply_total_list[$value['id']]['all']
            : 0;
            $value['resume_nolook'] = isset($apply_total_list[$value['id']])
            ? $apply_total_list[$value['id']]['nolook']
            : 0;
            $value['audit_reason'] = isset($job_audit_reason_list[$value['id']])
            ? $job_audit_reason_list[$value['id']]
            : '';
            $value['auto_refresh'] = in_array(
                $value['id'],
                $service_refresh_jobid_arr
            )
            ? 1
            : 0;
            if ($value['audit'] == 0) {
                $value['job_status'] = 0;
                $value['job_status_cn'] = '审核中';
            } elseif ($value['is_display'] == 0) {
                $value['job_status'] = 2;
                $value['job_status_cn'] = '已关闭';
            } elseif ($value['audit'] == 2) {
                $value['job_status'] = 2;
                $value['job_status_cn'] = '未通过';
            } else {
                $value['job_status'] = 1;
                $value['job_status_cn'] = '发布中';
            }
            $value['refreshtime'] = daterange(time(), $value['refreshtime']);
            $value['job_link_url_web'] = url('index/job/show',['id'=>$value['id']]);
            $list[$key] = $value;
        }

        $return['items'] = $list;
        $member_setmeal = model('Member')->getMemberSetmeal($this->userinfo->uid);
        $return['enable_poster'] = $member_setmeal['enable_poster'];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    public function total()
    {
        $type = input('get.type/d', 0, 'intval');
        $list = model('Job')->where('uid', 'eq', $this->userinfo->uid);
        switch ($type) {
            case 1: //审核中
                $list = $list->where('audit', 0);
                break;
            case 2: //已下线
                $list = $list->where(function ($query) {
                    $query->whereOr('is_display', 0)->whereOr('audit', 2);
                });
                break;
            default:
                //发布中
                $list = $list->where('audit', 1)->where('is_display', 1);
                break;
        }
        $total = $list->count();
        $this->ajaxReturn(200, '获取数据成功', $total);
    }
    public function refresh()
    {
        $id = input('post.id/d', 0, 'intval');
        if (!$id) {
            $this->ajaxReturn(500, '请选择职位');
        }

        // 刷新职位信息 chenyang 2022年3月21日11:27:34
        $refreshParams = [
            'id'          => $id,
            'uid'         => $this->userinfo->uid,
            'refresh_log' => true
        ];
        $result = model('Job')->refreshJobData($refreshParams, 2);
        if ($result['status'] === false) {
            if (!empty($result['data'])) {
                $this->ajaxReturn(200, $result['msg'], $result['data']);
            }
            $this->ajaxReturn(500, $result['msg']);
        }

        $this->writeMemberActionLog($this->userinfo->uid,'套餐特权-免费刷新职位【' . $result['data'][0]['jobname'] . '】');

        $this->ajaxReturn(200, '刷新成功', ['done' => 1]);
    }
    public function refreshBatch()
    {
        $jobidArr = input('post.id/a',[]);
        if (empty($jobidArr)) {
            $joblist = model('Job')
                ->field('id,jobname')
                ->where([
                    'audit' => 1,
                    'uid' => $this->userinfo->uid,
                    'is_display' => 1,
                ])
                ->select();
            if (empty($joblist) || $joblist === null) {
                $this->ajaxReturn(500, '没有可刷新的职位');
            }
            $jobidArr = array_column($joblist, 'id');
        }

        // 刷新职位信息 chenyang 2022年3月21日12:58:57
        $refreshParams = [
            'id'          => $jobidArr,
            'uid'         => $this->userinfo->uid,
            'refresh_log' => true
        ];
        $result = model('Job')->refreshJobData($refreshParams, 2);
        if ($result['status'] === false) {
            if (!empty($result['data'])) {
                $this->ajaxReturn(200, $result['msg'], $result['data']);
            }
            $this->ajaxReturn(500, $result['msg']);
        }

        $jobidArr = array_column($result['data'], 'id');
        $this->writeMemberActionLog($this->userinfo->uid,'套餐特权-免费刷新职位【职位id：' . implode(",",$jobidArr) . '】');

        $this->ajaxReturn(200, '刷新成功', ['done' => 1]);
    }
    public function setDisplay()
    {
        $id = input('post.id/d', 0, 'intval');
        $is_display = input('post.is_display/d', 1, 'intval');
        $jobinfo = model('Job')
            ->field('is_display')
            ->where('id', $id)
            ->where('uid', $this->userinfo->uid)
            ->find();
        if ($jobinfo === null) {
            $this->ajaxReturn(500, '没有找到职位信息');
        }
        if ($jobinfo['is_display'] == $is_display) {
            $this->ajaxReturn(200, '设置成功');
        }
        if ($is_display == 1) {
            $enable_num = model('Job')->getEnableJobaddNum($this->userinfo->uid);
            if ($enable_num <= 0) {
                $this->ajaxReturn(500, '当前可发布职位数为0，无法恢复');
            }
        }
        $jobinfo->uid = $this->userinfo->uid;
        $jobinfo->is_display = $is_display;
        $jobinfo->save();
        model('Job')->refreshSearch($id);
        $this->writeMemberActionLog($this->userinfo->uid,'设置职位招聘状态【' . ($is_display==1?'招聘中':'暂停招聘') . '】');
        $this->ajaxReturn(200, '设置成功');
    }
    public function setCloseBatch()
    {
        $id = input('post.id/a',[]);
        if(empty($id)){
            $this->ajaxReturn(500,'请选择职位');
        }
        model('Job')->whereIn('id',$id)->where('uid',$this->userinfo->uid)->setField('is_display',0);
        model('Job')->refreshSearchBatch($id);
        $this->writeMemberActionLog($this->userinfo->uid,'批量关闭职位【职位id：' . implode(",",$id) . '】');
        $this->ajaxReturn(200, '设置成功');
    }
    public function del()
    {
        $id = input('post.id/d', 0, 'intval');
        $jobinfo = model('Job')
            ->where('id', $id)
            ->where('uid', $this->userinfo->uid)
            ->find();
        if ($jobinfo === null) {
            $this->ajaxReturn(500, '没有找到职位信息');
        }
        if (false === model('Job')->deleteJobByIds([$id])) {
            $this->ajaxReturn(500, model('Job')->getError());
        };
        $this->writeMemberActionLog($this->userinfo->uid,'删除职位【职位id：' . $jobinfo->id . '，职位名称：'.$jobinfo->jobname.'】');
        $this->ajaxReturn(200, '删除成功');
    }
    public function delBatch()
    {
        $id = input('post.id/a',[]);
        $list = model('Job')
            ->whereIn('id', $id)
            ->where('uid', $this->userinfo->uid)
            ->select();
        if (!$list) {
            $this->ajaxReturn(500, '没有找到职位信息');
        }
        if (false === model('Job')->deleteJobByIds($id)) {
            $this->ajaxReturn(500, model('Job')->getError());
        };
        $namearr = [];
        foreach ($list as $key => $value) {
            $namearr[] = $value['jobname'];
        }
        $this->writeMemberActionLog($this->userinfo->uid,'批量删除职位【职位名称：' . implode(",",$namearr) . '】');
        $this->ajaxReturn(200, '删除成功');
    }
    public function getCategoryJobTemplate()
    {
        $pid = input('get.pid/d', 0, 'intval');
        $list = model('CategoryJobTemplate')
            ->where('pid', $pid)
            ->order('id asc')
            ->select();
        $this->ajaxReturn(200, '获取数据成功', $list);
    }
}
