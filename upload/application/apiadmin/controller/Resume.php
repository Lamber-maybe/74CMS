<?php

namespace app\apiadmin\controller;

class Resume extends \app\common\controller\Backend
{
    public function _initialize()
    {
        parent::_initialize();
    }
    /**
     * 简历列表
     */
    public function index()
    {
        $where = [];
        $list_type = input('param.list_type/s', '', 'trim');
        $key_type = input('param.key_type/d', 0, 'intval');
        $keyword = input('param.keyword/s', '', 'trim');
        $current_page = input('param.page/d', 1, 'intval');
        $pagesize = input('param.pagesize/d', 15, 'intval');
        $audit = input('param.audit/s', '', 'trim');
        $sort = input('param.sort/s', '', 'trim');
        $level = input('param.level/s', '', 'trim');
        if ($keyword && $key_type) {
            switch ($key_type) {
                case 1:
                    $where['r.fullname'] = ['like', '%' . $keyword . '%'];
                    break;
                case 2:
                    $where['r.id'] = ['eq', intval($keyword)];
                    break;
                case 3:
                    $where['r.uid'] = ['eq', intval($keyword)];
                    break;
                case 4:
                    $where['m.mobile'] = ['eq', $keyword];
                    $where['m.utype'] = ['eq', 2];
                    break;
                default:
                    break;
            }
        }
        if($audit!=''){
            $where['r.audit'] = intval($audit);
        }
        if($sort!=''){
            $order = 'r.addtime desc,r.refreshtime desc';
        }else{
            $order = 'r.refreshtime desc';
        }
        if($level!=''){
            $where['r.high_quality'] = intval($level);
        }

        // $force_index_name = 'index_uid';
        if ($list_type == 'noaudit') {
            $where['r.audit'] = 0;
            // $force_index_name = 'index_audit';
        }
        $total = model('Resume')
            ->alias('r')
            ->join(config('database.prefix').'member m','r.uid=m.uid','LEFT')
            // ->force($force_index_name)
            ->where($where)
            ->count();
        $list = model('Resume')
            ->alias('r')
            ->join(config('database.prefix').'member m','r.uid=m.uid','LEFT')
            ->field('r.id,r.uid,r.is_display,r.high_quality,r.display_name,r.audit,r.stick,r.service_tag,r.fullname,r.sex,r.birthday,r.residence,r.height,r.marriage,r.education,r.enter_job_time,r.householdaddress,r.major1,r.major2,r.major,r.tag,r.idcard,r.specialty,r.photo_img,r.addtime,r.refreshtime,r.current,r.click,r.tpl,r.custom_field_1,r.custom_field_2,r.custom_field_3,r.platform,r.remark,r.comment,m.mobile')
            ->where($where)
            ->order($order)
            ->page($current_page . ',' . $pagesize)
            ->select();
            
        $ridarr = [];
        $uidarr = [];
        $complete_list = [];
        $photo_arr = $photo_id_arr = [];
        foreach ($list as $key => $value) {
            $ridarr[] = $value['id'];
            $uidarr[] = $value['uid'];
            $value['photo_img'] > 0 && ($photo_id_arr[] = $value['photo_img']);
        }
        if (!empty($photo_id_arr)) {
            $photo_arr = model('Uploadfile')->getFileUrlBatch(
                $photo_id_arr
            );
        }
        if (!empty($ridarr)) {
            $complete_list = model('Resume')->countCompletePercentBatch(
                $ridarr
            );
        }

        $bindarr = [];
        if(!empty($uidarr)){
            $bindarr = model('MemberBind')->whereIn('uid',$uidarr)->where('type','weixin')->where('is_subscribe',1)->column('uid,id');
        }

        foreach ($list as $key => $value) {
            $value['photo_img_src'] = isset($photo_arr[$value['photo_img']])
                ? $photo_arr[$value['photo_img']]
                : default_empty('photo');
            $value['age'] =
                intval($value['birthday']) == 0
                    ? '年龄未知'
                    : date('Y') - intval($value['birthday']) . '岁';
            $value['sex_cn'] = isset(model('Resume')->map_sex[$value['sex']])
                ? model('Resume')->map_sex[$value['sex']]
                : '性别未知';
            $value['education_cn'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '学历未知';
            $value['experience_cn'] =
                $value['enter_job_time'] == 0
                    ? '无经验'
                    : format_date($value['enter_job_time']);

            $value['complete_percent'] = isset($complete_list[$value['id']])
                ? $complete_list[$value['id']]
                : 0;
            $value['link'] = config('global_config.sitedomain').url('index/resume/show', ['id' => $value['id']]);
            $value['bind_weixin'] = isset($bindarr[$value['uid']])?1:0;     
            $value['platform_cn'] = isset(model('BaseModel')->map_platform[$value['platform']])?model('BaseModel')->map_platform[$value['platform']]:'未知平台';
        
            $list[$key] = $value;
        }
        $return['items'] = $list;
        $return['total'] = $total;
        $return['current_page'] = $current_page;
        $return['pagesize'] = $pagesize;
        $return['total_page'] = ceil($total / $pagesize);
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /**
     * 获取简历可显示模块
     */
    public function moduleList()
    {
        $return = [];
        $moduleList = model('ResumeModule')->getCache();
        foreach ($moduleList as $key => $value) {
            if ($value['is_display'] == 0) {
                continue;
            }
            $return[$key] = $value['module_cn'];
        }
        $this->ajaxReturn(200, '获取数据成功', ['items' => $return]);
    }
    /**
     * 创建简历
     */
    public function add()
    {
        $input_data = [
            'fullname' => input('post.fullname/s', '', 'trim'),
            'sex' => input('post.sex/d', 0, 'intval'),
            'birthday' => input('post.birthday/s', '', 'trim'),
            'residence' => input('post.residence/s', '', 'trim'),
            'height' => input('post.height/s', '', 'trim'),
            'marriage' => input('post.marriage/d', 0, 'intval'),
            'education' => input('post.education/d', 0, 'intval'),
            'enter_job_time' => input('post.enter_job_time/s', '', 'trim'),
            'current' => input('post.current/d', 0, 'intval'),
            'householdaddress' => input('post.householdaddress/s', '', 'trim'),
            'major1' => input('post.major1/d', 0, 'intval'),
            'major2' => input('post.major2/d', 0, 'intval'),
            'idcard' => input('post.idcard/s', '', 'trim'),
            'district1' => input('post.district1/d', 0, 'intval'),
            'district2' => input('post.district2/d', 0, 'intval'),
            'district3' => input('post.district3/d', 0, 'intval'),
            'photo_img' => input('post.photo_img/d', 0, 'intval'),
            'custom_field_1' => input('post.custom_field_1/s', '', 'trim'),
            'custom_field_2' => input('post.custom_field_2/s', '', 'trim'),
            'custom_field_3' => input('post.custom_field_3/s', '', 'trim'),
            'member' => [
                'username' => input('post.member.username/s', '', 'trim'),
                'password' => input('post.member.password/s', '', 'trim'),
                'mobile' => input('post.member.mobile/s', '', 'trim'),
                'utype' => 2
            ],
            'contact' => [
                'mobile' => input('post.contact.mobile/s', '', 'trim'),
                'weixin' => input('post.contact.weixin/s', '', 'trim'),
                'qq' => input('post.contact.qq/s', '', 'trim'),
                'email' => input('post.contact.email/s', '', 'trim')
            ],
            'intention' => [
                'nature' => input('post.intention.nature/d', 0, 'intval'),
                'category1' => input('post.intention.category1/d', 0, 'intval'),
                'category2' => input('post.intention.category2/d', 0, 'intval'),
                'category3' => input('post.intention.category3/d', 0, 'intval'),
                'district1' => input('post.intention.district1/d', 0, 'intval'),
                'district2' => input('post.intention.district2/d', 0, 'intval'),
                'district3' => input('post.intention.district3/d', 0, 'intval'),
                'minwage' => input('post.intention.minwage/d', 0, 'intval'),
                'maxwage' => input('post.intention.maxwage/d', 0, 'intval'),
                'trade' => input('post.intention.trade/d', 0, 'intval')
            ]
        ];
        $input_data['enter_job_time'] =
            $input_data['enter_job_time'] == ''
                ? 0
                : strtotime($input_data['enter_job_time']);

        $r = model('Resume')->backendAdd($input_data);
        if ($r === false) {
            $this->ajaxReturn(500, model('Resume')->getError());
        }
        model('AdminLog')->record(
            '添加简历。简历ID【' .
                model('Resume')->id .
                '】；姓名【' .
                $input_data['fullname'] .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '保存成功', ['resumeid' => $r]);
    }
    /**
     * 简历基本资料
     */
    public function basic()
    {
        if (request()->isGet()) {
            $id = input('get.id/d', 0, 'intval');
            $info = model('Resume')
                ->where('id', $id)
                ->find();
            if ($info !== null) {
                $info = $info->toArray();

                $value['major_'] = isset($category_major_data[$info['major']])
                    ? $category_major_data[$info['major']]
                    : '';
                $resume_sex_map = model('Resume')->map_sex;
                $info['sex_'] = isset($resume_sex_map[$info['sex']])
                    ? $resume_sex_map[$info['sex']]
                    : '未选择';
                $category_data = model('Category')->getCache();
                $info['education_'] = isset(
                    model('BaseModel')->map_education[$info['education']]
                )
                    ? model('BaseModel')->map_education[$info['education']]
                    : '';
                $info['experience_'] =
                    $info['enter_job_time'] == 0
                        ? '无经验'
                        : format_date($info['enter_job_time']);
                $info['enter_job_time'] =
                    $info['enter_job_time'] == 0
                        ? ''
                        : date('Y-m', $info['enter_job_time']);
                $info['current_'] = isset(
                    $category_data['QS_current'][$info['current']]
                )
                    ? $category_data['QS_current'][$info['current']]
                    : '';
                $resume_marriage_map = model('Resume')->map_marriage;
                $info['marriage_'] = isset(
                    $resume_marriage_map[$info['marriage']]
                )
                    ? $resume_marriage_map[$info['marriage']]
                    : '未选择';

                $info['contact'] = model('ResumeContact')
                    ->where('rid', $id)
                    ->find();
                $photoUrl = model('Uploadfile')->getFileUrl($info['photo_img']);
            } else {
                $info['contact'] = [];
                $photoUrl = '';
            }

            $extra_validate_rule = model('FieldRule')->getCache('Resume');
            $extra_validate_rule['contact'] = model('FieldRule')->getCache(
                'ResumeContact'
            );

            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info,
                'photoUrl' => $photoUrl,
                'extra_validate_rule' => $extra_validate_rule
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'fullname' => input('post.fullname/s', '', 'trim'),
                'sex' => input('post.sex/d', 0, 'intval'),
                'birthday' => input('post.birthday/s', '', 'trim'),
                'residence' => input('post.residence/s', '', 'trim'),
                'height' => input('post.height/d', 0, 'intval'),
                'marriage' => input('post.marriage/d', 0, 'intval'),
                'education' => input('post.education/d', 0, 'intval'),
                'enter_job_time' => input('post.enter_job_time/s', '', 'trim'),
                'householdaddress' => input(
                    'post.householdaddress/s',
                    '',
                    'trim'
                ),
                'major1' => input('post.major1/d', 0, 'intval'),
                'major2' => input('post.major2/d', 0, 'intval'),
                'idcard' => input('post.idcard/s', '', 'trim'),
                'district1' => input('post.district1/d', 0, 'intval'),
                'district2' => input('post.district2/d', 0, 'intval'),
                'district3' => input('post.district3/d', 0, 'intval'),
                'photo_img' => input('post.photo_img/d', 0, 'intval'),
                'current' => input('post.current/d', 0, 'intval'),
                'custom_field_1' => input('post.custom_field_1/s', '', 'trim'),
                'custom_field_2' => input('post.custom_field_2/s', '', 'trim'),
                'custom_field_3' => input('post.custom_field_3/s', '', 'trim'),
                'contact' => [
                    'mobile' => input('post.contact.mobile/s', '', 'trim'),
                    'email' => input('post.contact.email/s', '', 'trim'),
                    'qq' => input('post.contact.qq/s', '', 'trim'),
                    'weixin' => input('post.contact.weixin/s', '', 'trim')
                ]
            ];

            $input_data['enter_job_time'] =
                $input_data['enter_job_time'] == ''
                    ? 0
                    : strtotime($input_data['enter_job_time']);
            $input_data['major'] =
                $input_data['major2'] != 0
                    ? $input_data['major2']
                    : ($input_data['major1'] != 0
                        ? $input_data['major1']
                        : 0);

            $data_contact = $input_data['contact'];
            unset($input_data['contact']);
            $data_basic = $input_data;
            $resume_id = $data_basic['id'];
            \think\Db::startTrans();
            try {
                if (
                    false ===
                    model('Resume')
                        ->validate(true)
                        ->allowField(true)
                        ->save($data_basic, ['id' => $resume_id])
                ) {
                    throw new \Exception(model('Resume')->getError());
                }

                if (
                    false ===
                    model('ResumeContact')
                        ->validate(true)
                        ->allowField(true)
                        ->save($data_contact, ['rid' => $resume_id])
                ) {
                    throw new \Exception(model('ResumeContact')->getError());
                }
                //提交事务
                \think\Db::commit();
            } catch (\Exception $e) {
                \think\Db::rollBack();
                $this->ajaxReturn(500, $e->getMessage());
            }
            model('Resume')->refreshSearch($resume_id);

            model('AdminLog')->record(
                '编辑简历基本资料。简历ID【' . $resume_id . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    /**
     * 求职意向列表
     */
    public function intentionList()
    {
        $rid = input('get.rid/d', 0, 'intval');

        $list = model('ResumeIntention')
            ->where('rid', $rid)
            ->select();
        $category_job_data = model('CategoryJob')->getCache('all');
        $category_district_data = model('CategoryDistrict')->getCache('all');
        $category_data = model('Category')->getCache('QS_trade');
        foreach ($list as $key => $value) {
            $value['nature_cn'] = model('Resume')->map_nature[$value['nature']];
            $category_index =
                $value['category3'] != 0
                    ? $value['category3']
                    : ($value['category2'] != 0
                        ? $value['category2']
                        : $value['category1']);
            $value['category_cn'] = isset($category_job_data[$category_index])
                ? $category_job_data[$category_index]
                : '';
            $district_index =
                $value['district3'] != 0
                    ? $value['district3']
                    : ($value['district2'] != 0
                        ? $value['district2']
                        : $value['district1']);
            $value['district_cn'] = isset(
                $category_district_data[$district_index]
            )
                ? $category_district_data[$district_index]
                : '';
            $value['trade_cn'] = isset($category_data[$value['trade']])
                ? $category_data[$value['trade']]
                : '';
            $value['wage_cn'] =
                $value['minwage'] . '-' . $value['maxwage'] . '元/月';
            $list[$key] = $value;
        }

        $this->ajaxReturn(200, '获取数据成功', [
            'items' => $list
        ]);
    }
    /**
     * 求职意向添加修改
     */
    public function intentionAddAndEdit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d', 0, 'intval');

            $info = model('ResumeIntention')
                ->where('id', $id)
                ->find();

            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'rid' => input('post.rid/d', 0, 'intval'),
                'nature' => input('post.nature/d', 0, 'intval'),
                'category1' => input('post.category1/d', 0, 'intval'),
                'category2' => input('post.category2/d', 0, 'intval'),
                'category3' => input('post.category3/d', 0, 'intval'),
                'district1' => input('post.district1/d', 0, 'intval'),
                'district2' => input('post.district2/d', 0, 'intval'),
                'district3' => input('post.district3/d', 0, 'intval'),
                'minwage' => input('post.minwage/d', 0, 'intval'),
                'maxwage' => input('post.maxwage/d', 0, 'intval'),
                'trade' => input('post.trade/d', 0, 'intval')
            ];

            $id = intval($input_data['id']);
            $input_data['category'] =
                $input_data['category3'] != 0
                    ? $input_data['category3']
                    : ($input_data['category2'] != 0
                        ? $input_data['category2']
                        : $input_data['category1']);
            $input_data['district'] =
                $input_data['district3'] != 0
                    ? $input_data['district3']
                    : ($input_data['district2'] != 0
                        ? $input_data['district2']
                        : $input_data['district1']);
            $basic = model('Resume')
                ->where('id', $input_data['rid'])
                ->find();

            if ($id > 0) {
                $result = model('ResumeIntention')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
            } else {
                unset($input_data['id']);
                $input_data['uid'] = $basic['uid'];

                $result = model('ResumeIntention')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data);
            }

            if (false === $result) {
                $this->ajaxReturn(500, model('ResumeIntention')->getError());
            }
            model('Resume')->updateComplete(
                ['intention' => 1],
                $basic['id'],
                $basic['uid']
            );
            model('Resume')->refreshSearch($basic['id']);

            model('AdminLog')->record(
                '编辑简历求职意向。简历ID【' . $basic['id'] . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    /**
     * 求职意向删除
     */
    public function intentionDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        $rid = input('post.rid/d', 0, 'intval');

        if (!$id || !$rid) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeIntention')->destroy([$id]);
        $intention_total = model('ResumeIntention')
            ->where(['rid' => $rid])
            ->count();
        if ($intention_total == 0) {
            $basic = model('Resume')
                ->where('id', $rid)
                ->find();
            model('Resume')->updateComplete(
                ['intention' => 0],
                $basic['id'],
                $basic['uid']
            );
        }
        model('Resume')->refreshSearch($rid);

        model('AdminLog')->record(
            '删除简历求职意向。简历意向ID【' .
                $id .
                '】；简历ID【' .
                $rid .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 自我描述
     */
    public function specialty()
    {
        if (request()->isGet()) {
            $id = input('get.id/d', 0, 'intval');

            $info = model('Resume')
                ->where('id', $id)
                ->field('id,specialty')
                ->find();

            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'specialty' => input('post.specialty/s', '', 'trim')
            ];

            $id = intval($input_data['id']);
            $basic = model('Resume')
                ->where('id', $id)
                ->find();

            if (
                false ===
                model('Resume')
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('Resume')->getError());
            }
            model('Resume')->updateComplete(
                ['specialty' => 1],
                $basic['id'],
                $basic['uid']
            );
            model('Resume')->refreshSearch($basic['id']);

            model('AdminLog')->record(
                '编辑简历自我描述。简历ID【' . $id . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    /**
     * 教育经历列表
     */
    public function educationList()
    {
        $rid = input('get.rid/d', 0, 'intval');

        $list = model('ResumeEducation')
            ->where('rid', $rid)
            ->select();
        foreach ($list as $key => $value) {
            $value['education_cn'] = isset(
                model('BaseModel')->map_education[$value['education']]
            )
                ? model('BaseModel')->map_education[$value['education']]
                : '';
            $value['timerange'] =
                date('Y年m月', $value['starttime']) .
                ' ~ ' .
                ($value['todate'] == 1
                    ? '至今'
                    : date('Y年m月', $value['endtime']));
            $list[$key] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', [
            'items' => $list
        ]);
    }
    /**
     * 教育经历添加修改
     */
    public function educationAddAndEdit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d', 0, 'intval');

            $info = model('ResumeEducation')
                ->where('id', $id)
                ->find();
            if ($info !== null) {
                $info = $info->toArray();
                $info['starttime'] = date('Y-m', $info['starttime']);
                $info['endtime'] =
                    $info['todate'] == 1 ? '' : date('Y-m', $info['endtime']);
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'rid' => input('post.rid/d', 0, 'intval'),
                'school' => input('post.school/s', '', 'trim'),
                'major' => input('post.major/s', '', 'trim'),
                'education' => input('post.education/d', 0, 'intval'),
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
            $basic = model('Resume')
                ->where('id', $input_data['rid'])
                ->find();

            if ($id > 0) {
                $result = model('ResumeEducation')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
            } else {
                unset($input_data['id']);
                $input_data['uid'] = $basic['uid'];
                $result = model('ResumeEducation')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data);
            }

            if (false === $result) {
                $this->ajaxReturn(500, model('ResumeEducation')->getError());
            }
            model('Resume')->updateComplete(
                ['education' => 1],
                $basic['id'],
                $basic['uid']
            );

            model('AdminLog')->record(
                '编辑简历教育经历。简历ID【' . $basic['id'] . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    /**
     * 教育经历删除
     */
    public function educationDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        $rid = input('post.rid/d', 0, 'intval');

        if (!$id || !$rid) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeEducation')->destroy($id);
        $education_total = model('ResumeEducation')
            ->where(['rid' => $rid])
            ->count();
        if ($education_total == 0) {
            $basic = model('Resume')
                ->where('id', $rid)
                ->find();
            model('Resume')->updateComplete(
                ['education' => 0],
                $basic['id'],
                $basic['uid']
            );
        }

        model('AdminLog')->record(
            '删除简历教育经历。教育经历ID【' .
                $id .
                '】；简历ID【' .
                $rid .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 工作经历列表
     */
    public function workList()
    {
        $rid = input('get.rid/d', 0, 'intval');

        $list = model('ResumeWork')
            ->where('rid', $rid)
            ->select();
        foreach ($list as $key => $value) {
            $value['timerange'] =
                date('Y年m月', $value['starttime']) .
                ' ~ ' .
                ($value['todate'] == 1
                    ? '至今'
                    : date('Y年m月', $value['endtime']));
            $list[$key] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', [
            'items' => $list
        ]);
    }
    /**
     * 工作经历添加修改
     */
    public function workAddAndEdit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d', 0, 'intval');

            $info = model('ResumeWork')
                ->where('id', $id)
                ->find();
            if ($info !== null) {
                $info = $info->toArray();
                $info['starttime'] = date('Y-m', $info['starttime']);
                $info['endtime'] =
                    $info['todate'] == 1 ? '' : date('Y-m', $info['endtime']);
            }

            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'rid' => input('post.rid/d', 0, 'intval'),
                'companyname' => input('post.companyname/s', '', 'trim'),
                'jobname' => input('post.jobname/s', '', 'trim'),
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
            $basic = model('Resume')
                ->where('id', $input_data['rid'])
                ->find();
            if ($id > 0) {
                $result = model('ResumeWork')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
            } else {
                unset($input_data['id']);
                $input_data['uid'] = $basic['uid'];
                $result = model('ResumeWork')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data);
            }

            if (false === $result) {
                $this->ajaxReturn(500, model('ResumeWork')->getError());
            }
            model('Resume')->updateComplete(
                ['work' => 1],
                $basic['id'],
                $basic['uid']
            );
            model('Resume')->refreshSearch($basic['id']);

            model('AdminLog')->record(
                '编辑简历工作经历。简历ID【' . $basic['id'] . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    /**
     * 工作经历删除
     */
    public function workDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        $rid = input('post.rid/d', 0, 'intval');

        if (!$id || !$rid) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeWork')->destroy($id);
        $work_total = model('ResumeWork')
            ->where(['rid' => $rid])
            ->count();
        if ($work_total == 0) {
            $basic = model('Resume')
                ->where('id', $rid)
                ->find();
            model('Resume')->updateComplete(
                ['work' => 0],
                $basic['id'],
                $basic['uid']
            );
        }
        model('Resume')->refreshSearch($rid);

        model('AdminLog')->record(
            '删除简历工作经历。工作经历ID【' .
                $id .
                '】；简历ID【' .
                $rid .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 培训经历列表
     */
    public function trainingList()
    {
        $rid = input('get.rid/d', 0, 'intval');

        $list = model('ResumeTraining')
            ->where('rid', $rid)
            ->select();
        foreach ($list as $key => $value) {
            $value['timerange'] =
                date('Y年m月', $value['starttime']) .
                ' ~ ' .
                ($value['todate'] == 1
                    ? '至今'
                    : date('Y年m月', $value['endtime']));
            $list[$key] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', [
            'items' => $list
        ]);
    }
    /**
     * 培训经历添加修改
     */
    public function trainingAddAndEdit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d', 0, 'intval');

            $info = model('ResumeTraining')
                ->where('id', $id)
                ->find();
            if ($info !== null) {
                $info = $info->toArray();
                $info['starttime'] = date('Y-m', $info['starttime']);
                $info['endtime'] =
                    $info['todate'] == 1 ? '' : date('Y-m', $info['endtime']);
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'rid' => input('post.rid/d', 0, 'intval'),
                'agency' => input('post.agency/s', '', 'trim'),
                'course' => input('post.course/s', '', 'trim'),
                'description' => input('post.description/s', '', 'trim'),
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
            $basic = model('Resume')
                ->where('id', $input_data['rid'])
                ->find();

            if ($id > 0) {
                $result = model('ResumeTraining')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
            } else {
                unset($input_data['id']);
                $input_data['uid'] = $basic['uid'];
                $result = model('ResumeTraining')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data);
            }

            if (false === $result) {
                $this->ajaxReturn(500, model('ResumeTraining')->getError());
            }
            model('Resume')->updateComplete(
                ['training' => 1],
                $basic['id'],
                $basic['uid']
            );

            model('AdminLog')->record(
                '编辑简历培训经历。简历ID【' . $basic['id'] . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    /**
     * 培训经历删除
     */
    public function trainingDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        $rid = input('post.rid/d', 0, 'intval');

        if (!$id || !$rid) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeTraining')->destroy($id);
        $training_total = model('ResumeTraining')
            ->where(['rid' => $rid])
            ->count();
        if ($training_total == 0) {
            $basic = model('Resume')
                ->where('id', $rid)
                ->find();
            model('Resume')->updateComplete(
                ['training' => 0],
                $basic['id'],
                $basic['uid']
            );
        }
        model('AdminLog')->record(
            '删除简历培训经历。培训经历ID【' .
                $id .
                '】；简历ID【' .
                $rid .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 项目经历列表
     */
    public function projectList()
    {
        $rid = input('get.rid/d', 0, 'intval');

        $list = model('ResumeProject')
            ->where('rid', $rid)
            ->select();
        foreach ($list as $key => $value) {
            $value['timerange'] =
                date('Y年m月', $value['starttime']) .
                ' ~ ' .
                ($value['todate'] == 1
                    ? '至今'
                    : date('Y年m月', $value['endtime']));
            $list[$key] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', [
            'items' => $list
        ]);
    }
    /**
     * 项目经历添加修改
     */
    public function projectAddAndEdit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d', 0, 'intval');

            $info = model('ResumeProject')
                ->where('id', $id)
                ->find();
            if ($info !== null) {
                $info = $info->toArray();
                $info['starttime'] = date('Y-m', $info['starttime']);
                $info['endtime'] =
                    $info['todate'] == 1 ? '' : date('Y-m', $info['endtime']);
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'rid' => input('post.rid/d', 0, 'intval'),
                'projectname' => input('post.projectname/s', '', 'trim'),
                'role' => input('post.role/s', '', 'trim'),
                'description' => input('post.description/s', '', 'trim'),
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
            $basic = model('Resume')
                ->where('id', $input_data['rid'])
                ->find();

            if ($id > 0) {
                $result = model('ResumeProject')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
            } else {
                unset($input_data['id']);
                $input_data['uid'] = $basic['uid'];
                $result = model('ResumeProject')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data);
            }

            if (false === $result) {
                $this->ajaxReturn(500, model('ResumeProject')->getError());
            }
            model('Resume')->updateComplete(
                ['project' => 1],
                $basic['id'],
                $basic['uid']
            );
            model('Resume')->refreshSearch($basic['id']);

            model('AdminLog')->record(
                '编辑简历项目经历。简历ID【' . $basic['id'] . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    /**
     * 项目经历删除
     */
    public function projectDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        $rid = input('post.rid/d', 0, 'intval');

        if (!$id || !$rid) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeProject')->destroy($id);
        $project_total = model('ResumeProject')
            ->where(['rid' => $rid])
            ->count();
        if ($project_total == 0) {
            $basic = model('Resume')
                ->where('id', $rid)
                ->find();
            model('Resume')->updateComplete(
                ['project' => 0],
                $basic['id'],
                $basic['uid']
            );
        }
        model('Resume')->refreshSearch($rid);
        model('AdminLog')->record(
            '删除简历项目经历。项目经历ID【' .
                $id .
                '】；简历ID【' .
                $rid .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 证书列表
     */
    public function certificateList()
    {
        $rid = input('get.rid/d', 0, 'intval');

        $list = model('ResumeCertificate')
            ->where('rid', $rid)
            ->select();
        foreach ($list as $key => $value) {
            $value['time'] = date('Y年m月', $value['obtaintime']);
            $list[$key] = $value;
        }

        $this->ajaxReturn(200, '获取数据成功', [
            'items' => $list
        ]);
    }
    /**
     * 证书添加修改
     */
    public function certificateAddAndEdit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d', 0, 'intval');

            $info = model('ResumeCertificate')
                ->where('id', $id)
                ->find();
            if ($info !== null) {
                $info = $info->toArray();
                $info['obtaintime'] = date('Y-m', $info['obtaintime']);
            }

            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'rid' => input('post.rid/d', 0, 'intval'),
                'name' => input('post.name/s', '', 'trim'),
                'obtaintime' => input('post.obtaintime/s', '', 'trim')
            ];

            $id = intval($input_data['id']);
            $input_data['obtaintime'] = strtotime($input_data['obtaintime']);

            $basic = model('Resume')
                ->where('id', $input_data['rid'])
                ->find();

            if ($id > 0) {
                $result = model('ResumeCertificate')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
            } else {
                unset($input_data['id']);
                $input_data['uid'] = $basic['uid'];
                $result = model('ResumeCertificate')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data);
            }

            if (false === $result) {
                $this->ajaxReturn(500, model('ResumeCertificate')->getError());
            }
            model('Resume')->updateComplete(
                ['certificate' => 1],
                $basic['id'],
                $basic['uid']
            );
            model('AdminLog')->record(
                '编辑简历证书。简历ID【' . $basic['id'] . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    /**
     * 证书删除
     */
    public function certificateDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        $rid = input('post.rid/d', 0, 'intval');

        if (!$id || !$rid) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeCertificate')->destroy($id);
        $certificate_total = model('ResumeCertificate')
            ->where(['rid' => $rid])
            ->count();
        if ($certificate_total == 0) {
            $basic = model('Resume')
                ->where('id', $rid)
                ->find();
            model('Resume')->updateComplete(
                ['certificate' => 0],
                $basic['id'],
                $basic['uid']
            );
        }
        model('AdminLog')->record(
            '删除简历证书。证书ID【' . $id . '】；简历ID【' . $rid . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 语言能力列表
     */
    public function languageList()
    {
        $rid = input('get.rid/d', 0, 'intval');

        $list = model('ResumeLanguage')
            ->where('rid', $rid)
            ->select();

        $category_data = model('Category')->getCache('QS_language');
        $level_data = model('Category')->getCache('QS_language_level');
        foreach ($list as $key => $value) {
            $value['language_cn'] = isset($category_data[$value['language']])
                ? $category_data[$value['language']]
                : '';
            $value['level_cn'] = isset($level_data[$value['level']])
                ? $level_data[$value['level']]
                : '';
            $list[$key] = $value;
        }

        $this->ajaxReturn(200, '获取数据成功', [
            'items' => $list
        ]);
    }
    /**
     * 语言能力添加修改
     */
    public function languageAddAndEdit()
    {
        if (request()->isGet()) {
            $id = input('get.id/d', 0, 'intval');

            $info = model('ResumeLanguage')
                ->where('id', $id)
                ->find();
            if ($info !== null) {
                $info = $info->toArray();
                $info['obtaintime'] = date('Y-m', $info['obtaintime']);
            }
            $this->ajaxReturn(200, '获取数据成功', [
                'info' => $info
            ]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'rid' => input('post.rid/d', 0, 'intval'),
                'language' => input('post.language/d', 0, 'intval'),
                'level' => input('post.level/d', 0, 'intval')
            ];

            $id = intval($input_data['id']);

            $basic = model('Resume')
                ->where('id', $input_data['rid'])
                ->find();

            if ($id > 0) {
                $result = model('ResumeLanguage')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data, ['id' => $id]);
            } else {
                unset($input_data['id']);
                $input_data['uid'] = $basic['uid'];
                $result = model('ResumeLanguage')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data);
            }

            if (false === $result) {
                $this->ajaxReturn(500, model('ResumeLanguage')->getError());
            }
            model('Resume')->updateComplete(
                ['language' => 1],
                $basic['id'],
                $basic['uid']
            );
            model('AdminLog')->record(
                '编辑简历语言能力。简历ID【' . $basic['id'] . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    /**
     * 语言能力删除
     */
    public function languageDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        $rid = input('post.rid/d', 0, 'intval');

        if (!$id || !$rid) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeLanguage')->destroy($id);
        $language_total = model('ResumeLanguage')
            ->where(['rid' => $rid])
            ->count();
        if ($language_total == 0) {
            $basic = model('Resume')
                ->where('id', $rid)
                ->find();
            model('Resume')->updateComplete(
                ['language' => 0],
                $basic['id'],
                $basic['uid']
            );
        }
        model('AdminLog')->record(
            '删除简历语言能力。语言能力ID【' .
                $id .
                '】；简历ID【' .
                $rid .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 特长标签
     */
    public function tag()
    {
        if (request()->isGet()) {
            $id = input('get.id/d', 0, 'intval');

            $info = model('Resume')
                ->where('id', $id)
                ->field('id,tag')
                ->find();
            $info['tag_'] = '';
            if ($info['tag']) {
                $category_tag_data = model('Category')->getCache(
                    'QS_resumetag'
                );
                $tag_id_arr = explode(',', $info['tag']);
                foreach ($tag_id_arr as $key => $value) {
                    if (
                        is_numeric($value) &&
                        isset($category_tag_data[$value])
                    ) {
                        $info['tag_'] .= ',' . $category_tag_data[$value];
                    } else {
                        $info['tag_'] .= ',' . $value;
                    }
                }
                $info['tag_'] = trim($info['tag_'], ',');
            }

            $this->ajaxReturn(200, '获取数据成功', ['info' => $info]);
        } else {
            $input_data = [
                'id' => input('post.id/d', 0, 'intval'),
                'tag' => input('post.tag/a')
            ];

            $id = intval($input_data['id']);
            $input_data['tag'] =
                isset($input_data['tag']) && !empty($input_data['tag'])
                    ? implode(',', $input_data['tag'])
                    : '';
            $basic = model('Resume')
                ->where('id', $id)
                ->find();
            if (
                false ===
                model('Resume')
                    ->allowField(true)
                    ->save($input_data, ['id' => $id])
            ) {
                $this->ajaxReturn(500, model('Resume')->getError());
            }

            model('Resume')->updateComplete(
                ['tag' => 1],
                $basic['id'],
                $basic['uid']
            );
            model('Resume')->refreshSearch($basic['id']);
            model('AdminLog')->record(
                '编辑简历特长标签。简历ID【' . $basic['id'] . '】',
                $this->admininfo
            );
            $this->ajaxReturn(200, '保存成功');
        }
    }
    /**
     * 照片作品列表
     */
    public function img()
    {
        $rid = input('get.rid/d', 0, 'intval');

        $list = model('ResumeImg')
            ->where('rid', $rid)
            ->select();
        $img_id_arr = $img_src_data = [];
        foreach ($list as $key => $value) {
            $img_id_arr[] = $value['img'];
        }
        if (!empty($img_id_arr)) {
            $img_src_data = model('Uploadfile')->getFileUrlBatch($img_id_arr);
        }
        foreach ($list as $key => $value) {
            $value['img_src'] = isset($img_src_data[$value['img']])
                ? $img_src_data[$value['img']]
                : '';
            $list[$key] = $value;
        }
        $this->ajaxReturn(200, '获取数据成功', [
            'items' => $list
        ]);
    }
    /**
     * 照片作品添加
     */
    public function imgAdd()
    {
        $rid = input('post.rid/d', 0, 'intval');

        if (!$rid) {
            $this->ajaxReturn(500, '请选择');
        }
        $imgid = input('post.imgid/d', 0, 'intval');
        if (!$imgid) {
            $this->ajaxReturn(500, '请选择');
        }
        $basic = model('Resume')
            ->where('id', $rid)
            ->find();
        $data['uid'] = $basic['uid'];
        $data['rid'] = $rid;
        $data['img'] = $imgid;
        $data['title'] = '';
        $data['addtime'] = time();
        $data['audit'] = 0;
        if (
            false ===
            model('ResumeImg')
                ->validate(true)
                ->allowField(true)
                ->save($data)
        ) {
            $this->ajaxReturn(500, model('ResumeImg')->getError());
        }
        model('Resume')->updateComplete(
            ['img' => 1],
            $basic['id'],
            $basic['uid']
        );
        model('AdminLog')->record(
            '添加简历照片作品。简历ID【' . $basic['id'] . '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '上传成功', model('ResumeImg')->id);
    }
    /**
     * 照片作品删除
     */
    public function imgDelete()
    {
        $id = input('post.id/d', 0, 'intval');
        $rid = input('post.rid/d', 0, 'intval');

        if (!$id || !$rid) {
            $this->ajaxReturn(500, '参数错误');
        }
        model('ResumeImg')->destroy($id);

        $img_total = model('ResumeImg')
            ->where(['rid' => $rid])
            ->count();
        if ($img_total == 0) {
            model('Resume')->updateComplete(['img' => 0], $rid, 0);
        }
        model('AdminLog')->record(
            '删除简历照片作品。照片作品ID【' .
                $id .
                '】；简历ID【' .
                $rid .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 审核简历
     */
    public function setAudit()
    {
        $id = input('post.id/a');
        $audit = input('post.audit/d', 0, 'intval');
        $reason = input('post.reason/s', '', 'trim');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择');
        }
        model('Resume')->setAudit($id, $audit, $reason);
        model('AdminLog')->record(
            '将简历审核状态变更为【' .
                model('Resume')->map_audit[$audit] .
                '】。简历ID【' .
                implode(',', $id) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '审核成功');
    }
    /**
     * 简历等级
     */
    public function setLevel()
    {
        $id = input('post.id/a');
        $level = input('post.level/d', 0, 'intval');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择');
        }
        model('Resume')->setLevel($id, $level);
        model('AdminLog')->record(
            '将简历等级变更为【' .
                ($level==1?'优质简历':'普通简历') .
                '】。简历ID【' .
                implode(',', $id) .
                '】',
            $this->admininfo
        );
        $this->ajaxReturn(200, '操作成功');
    }
    /**
     * 简历点评
     */
    public function setComment()
    {
        $id = input('post.id/d',0,'intval');
        $comment = input('post.comment/s','', 'trim');
        if ($id==0) {
            $this->ajaxReturn(500, '请选择');
        }
        model('Resume')->save(['comment'=>$comment],['id'=>$id]);
        model('AdminLog')->record('将简历点评内容变更为“' .$comment.'”。简历ID【'.$id.'】',$this->admininfo);
        $this->ajaxReturn(200, '操作成功');
    }
    /**
     * 删除简历
     */
    public function delete()
    {
        $uid = input('post.uid/a');
        if (empty($uid)) {
            $this->ajaxReturn(500, '请选择');
        }
        model('Member')->deleteMemberByUids($uid);
        model('AdminLog')->record('删除简历。简历UID【'.implode(",",$uid).'】',$this->admininfo);
        $this->ajaxReturn(200, '删除成功');
    }
    /**
     * 刷新简历
     */
    public function refresh(){
        $id = input('post.id/a');
        if (empty($id)) {
            $this->ajaxReturn(500, '请选择');
        }
        model('Resume')->backendRefreshResume($id);
        model('AdminLog')->record('刷新简历。简历ID【'.implode(",",$id).'】',$this->admininfo);
        $this->ajaxReturn(200, '刷新成功');
    }
}
