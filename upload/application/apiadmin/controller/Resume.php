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
            ->join(config('database.prefix').'resume_contact c','r.id=c.rid','LEFT')
            ->field('r.id,r.uid,r.is_display,r.high_quality,r.display_name,r.audit,r.stick,r.service_tag,r.fullname,r.sex,r.birthday,r.residence,r.height,r.marriage,r.education,r.enter_job_time,r.householdaddress,r.major1,r.major2,r.major,r.tag,r.idcard,r.specialty,r.photo_img,r.addtime,r.refreshtime,r.current,r.click,r.tpl,r.custom_field_1,r.custom_field_2,r.custom_field_3,r.platform,r.remark,r.comment,m.mobile,c.mobile as contact_mobile')
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
            $value['fullname'] = htmlspecialchars_decode($value['fullname'],ENT_QUOTES);
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
            $value['link'] = url('index/resume/show', ['id' => $value['id']]);
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
                $info['fullname'] = htmlspecialchars_decode($info['fullname'],ENT_QUOTES);

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
                $photoUrl = $info['photo_img'] > 0
                    ? model('Uploadfile')->getFileUrl(
                        $info['photo_img']
                    )
                    : default_empty('photo');
            } else {
                $info['contact'] = [];
                $photoUrl = '';
            }

            $extra_validate_rule = model('FieldRule')->getCache('Resume');
            $extra_validate_rule['contact'] = model('FieldRule')->getCache(
                'ResumeContact'
            );
            /**
             * 【bug】前台有专业，后台不显示
             *  zdq 2022-11-03
             *  新增：$category_major_data = model('CategoryMajor')->getCache();
             *  $info['major_text'] = isset($category_major_data[$info['major']])
             *  ? $category_major_data[$info['major']]
             *  : '';
             */
            $category_major_data = model('CategoryMajor')->getCache();
            $info['major_text'] = isset($category_major_data[$info['major']])
                ? $category_major_data[$info['major']]
                : '';
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
            $info['specialty'] = htmlspecialchars_decode($info['specialty'],ENT_QUOTES);

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
            $value['school'] = htmlspecialchars_decode($value['school'],ENT_QUOTES);
            $value['major'] = htmlspecialchars_decode($value['major'],ENT_QUOTES);
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
                $info['school'] = htmlspecialchars_decode($info['school'],ENT_QUOTES);
                $info['major'] = htmlspecialchars_decode($info['major'],ENT_QUOTES);
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
            $value['companyname'] = htmlspecialchars_decode($value['companyname'],ENT_QUOTES);
            $value['jobname'] = htmlspecialchars_decode($value['jobname'],ENT_QUOTES);
            $value['duty'] = htmlspecialchars_decode($value['duty'],ENT_QUOTES);
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
                $info['companyname'] = htmlspecialchars_decode($info['companyname'],ENT_QUOTES);
                $info['jobname'] = htmlspecialchars_decode($info['jobname'],ENT_QUOTES);
                $info['duty'] = htmlspecialchars_decode($info['duty'],ENT_QUOTES);
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
            $value['agency'] = htmlspecialchars_decode($value['agency'],ENT_QUOTES);
            $value['course'] = htmlspecialchars_decode($value['course'],ENT_QUOTES);
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
                $info['agency'] = htmlspecialchars_decode($info['agency'],ENT_QUOTES);
                $info['course'] = htmlspecialchars_decode($info['course'],ENT_QUOTES);
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
            $value['projectname'] = htmlspecialchars_decode($value['projectname'],ENT_QUOTES);
            $value['role'] = htmlspecialchars_decode($value['role'],ENT_QUOTES);
            $value['description'] = htmlspecialchars_decode($value['description'],ENT_QUOTES);
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
                $info['projectname'] = htmlspecialchars_decode($info['projectname'],ENT_QUOTES);
                $info['role'] = htmlspecialchars_decode($info['role'],ENT_QUOTES);
                $info['description'] = htmlspecialchars_decode($info['description'],ENT_QUOTES);
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
            $value['name'] = htmlspecialchars_decode($value['name'],ENT_QUOTES);
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
                $info['name'] = htmlspecialchars_decode($info['name'],ENT_QUOTES);
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
                /**
                 * 2022-10-23
                 * zdq 编辑时拨错 obtaintime 未定义，排查前端没有渲染次字段数据也无此字段。
                 * 暂时注释
                 */
//              $info['obtaintime'] = date('Y-m', $info['obtaintime']);
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
        $uidArr = input('post.uid/a');
        if (empty($uidArr)) {
            $this->ajaxReturn(500, '请选择');
        }
        // 刷新简历信息 chenyang 2022年3月15日15:32:33
        foreach ($uidArr as $uid) {
            $refreshParams = [
                'uid'   => $uid,
                'utype' => 2
            ];
            $result = model('Resume')->refreshResumeData($refreshParams, 2);
            if ($result['status'] === false) {
                $this->ajaxReturn(500, $result['msg']);
            }
        }

        model('AdminLog')->record('刷新简历。简历ID【'.implode(",",$uidArr).'】',$this->admininfo);
        $this->ajaxReturn(200, '刷新成功');
    }
    /**
     * 简历导入
     */
    public function import()
    {
        $timestamp = time();
        $post_data = input('post.');

        $compelete_list = [];
        $contact_list = [];
        $intention_list = [];
        $certificate_list = [];
        $education_list = [];
        $language_list = [];
        $work_list = [];
        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();
        $category_district_data = model('CategoryDistrict')->getCache();
        $memberModel = model('Member');
        $resumeModel = model('Resume');
        $resumeid_arr = [];
        \think\Db::startTrans();
        try{
            foreach ($post_data as $key => $value) {
                $_member_model = clone $memberModel;
                $_resume_model = clone $resumeModel;
                if($_member_model->where('mobile',$value['basic']['contact']['mobile'])->find()!==null){
                    continue;
                }
                $insert_data_member = [
                    'username' => config('global_config.reg_prefix') . $value['basic']['contact']['mobile'],
                    'password' => '',
                    'mobile' => $value['basic']['contact']['mobile'],
                    'utype' => 2,
                    'platform' => $value['basic']['platform']
                ];
                $insert_data_member['pwd_hash'] = randstr();
                if ($insert_data_member['password'] != '') {
                    $insert_data_member['password'] = $this->makePassword(
                        $insert_data_member['password'],
                        $insert_data_member['pwd_hash']
                    );
                } else {
                    $insert_data_member['password'] = '';
                }
                //插入member表
                $_member_model->validate(false)->allowField(true)->save($insert_data_member);
                $insert_uid = $_member_model->uid;
                $insert_data_resume = [
                    'uid'=>$insert_uid,
                    'service_tag'=>'',
                    'fullname'=>$value['basic']['fullname'],
                    'sex'=>$value['basic']['sex'],
                    'birthday'=>$value['basic']['birthday'],
                    'residence'=>$value['basic']['residence'],
                    'height'=>$value['basic']['height'],
                    'marriage'=>array_search($value['basic']['marriage'],$_resume_model->map_marriage),
                    'education'=>array_search($value['basic']['education'],model('BaseModel')->map_education),
                    'enter_job_time'=>$value['basic']['enter_job_time']?strtotime($value['basic']['enter_job_time']):0,
                    'householdaddress'=>$value['basic']['householdaddress'],
                    'major1'=>0,
                    'major2'=>0,
                    'major'=>0,
                    'current'=>0,
                    'click'=>0,
                    'custom_field_1'=>'',
                    'custom_field_2'=>'',
                    'custom_field_3'=>'',
                    'platform'=>$value['basic']['platform'],
                    'remark'=>'',
                    'comment'=>''
                ];
                //插入resume表
                $_resume_model->validate(false)->save($insert_data_resume);
                $insert_resumeid = $_resume_model->id;
                if($value['basic']['specialty']!=''){
                    model('Resume')->where('id',$insert_resumeid)->setField('specialty',$value['basic']['specialty']);
                }
                
                $resumeid_arr[] = $insert_resumeid;
                
                $contact_list[] = [
                    'rid'=>$insert_resumeid,
                    'uid'=>$insert_uid,
                    'mobile'=>$value['basic']['contact']['mobile'],
                    'email'=>$value['basic']['contact']['email'],
                    'qq'=>'',
                    'weixin'=>''
                ];
                foreach ($value['intention_list'] as $k => $v) {
                    $arr = [
                        'rid'=>$insert_resumeid,
                        'uid'=>$insert_uid,
                        'nature'=>array_search($v['nature'],$_resume_model->map_nature),
                        'category1'=>0,
                        'category2'=>0,
                        'category3'=>0,
                        'category'=>0,
                        'district1'=>0,
                        'district2'=>0,
                        'district3'=>0,
                        'district'=>0,
                        'minwage'=>$v['minwage'],
                        'maxwage'=>$v['maxwage'],
                        'trade'=>array_search($v['trade'],$category_data['QS_trade'])===false?0:array_search($v['trade'],$category_data['QS_trade'])
                    ];
                    $district_arr = explode("/",$v['district']);
                    $v['district'] = $district_arr[count($district_arr)-1];
                    $district_id = array_search($v['district'],$category_district_data);
                    if($district_id!==false){
                        $district_info = model('CategoryDistrict')->where('id',$district_id)->find();
                        if($district_info!==null){
                            if($district_info['pid']==0){
                                $arr['district1'] = $district_id;
                                $arr['district'] = $district_id;
                            }else{
                                $parent_district_info = model('CategoryDistrict')->where('id',$district_info['pid'])->find();
                                if($parent_district_info['pid']==0){
                                    $arr['district1'] = $parent_district_info['id'];
                                    $arr['district2'] = $district_id;
                                    $arr['district'] = $district_id;
                                }else{
                                    $arr['district1'] = $parent_district_info['pid'];
                                    $arr['district2'] = $parent_district_info['id'];
                                    $arr['district3'] = $district_id;
                                    $arr['district'] = $district_id;
                                }
                            }
                            
                        }
                    }
                    $category_arr = explode("/",$v['category']);
                    $v['category'] = $category_arr[count($category_arr)-1];
                    $category_id = array_search($v['category'],$category_job_data);
                    if($category_id!==false){
                        $category_info = model('CategoryJob')->where('id',$category_id)->find();
                        if($category_info!==null){
                            if($category_info['pid']==0){
                                $arr['category1'] = $category_id;
                                $arr['category'] = $category_id;
                            }else{
                                $parent_category_info = model('CategoryJob')->where('id',$category_info['pid'])->find();
                                if($parent_category_info['pid']==0){
                                    $arr['category1'] = $parent_category_info['id'];
                                    $arr['category2'] = $category_id;
                                    $arr['category'] = $category_id;
                                }else{
                                    $arr['category1'] = $parent_category_info['pid'];
                                    $arr['category2'] = $parent_category_info['id'];
                                    $arr['category3'] = $category_id;
                                    $arr['category'] = $category_id;
                                }
                            }
                            
                        }
                    }
                    $intention_list[] = $arr;
                }
                foreach ($value['certificate_list'] as $k => $v) {
                    $arr = [
                        'rid'=>$insert_resumeid,
                        'uid'=>$insert_uid,
                        'name'=>$v['name'],
                        'obtaintime'=>strtotime($v['obtaintime'])
                    ];
                    $certificate_list[] = $arr;
                }
                foreach ($value['education_list'] as $k => $v) {
                    $arr = [
                        'rid'=>$insert_resumeid,
                        'uid'=>$insert_uid,
                        'starttime'=>strtotime($v['starttime']),
                        'endtime'=>strtotime($v['endtime']),
                        'todate'=>0,
                        'school'=>$v['school'],
                        'major'=>$v['major'],
                        'education'=>array_search($v['education'],model('BaseModel')->map_education)
                    ];
                    $education_list[] = $arr;
                }
                foreach ($value['language_list'] as $k => $v) {
                    $arr = [
                        'rid'=>$insert_resumeid,
                        'uid'=>$insert_uid,
                        'language'=>array_search($v['language'],$category_data['QS_language'])===false?0:array_search($v['language'],$category_data['QS_language']),
                        'level'=>array_search($v['level'],$category_data['QS_language_level'])===false?0:array_search($v['level'],$category_data['QS_language_level'])
                    ];
                    $language_list[] = $arr;
                }
                foreach ($value['work_list'] as $k => $v) {
                    $arr = [
                        'rid'=>$insert_resumeid,
                        'uid'=>$insert_uid,
                        'starttime'=>strtotime($v['starttime']),
                        'endtime'=>strtotime($v['endtime']),
                        'todate'=>0,
                        'companyname'=>$v['companyname'],
                        'jobname'=>$v['jobname'],
                        'duty'=>$v['duty']
                    ];
                    $work_list[] = $arr;
                }
                $compelete_list[] = [
                    'rid'=>$insert_resumeid,
                    'uid'=>$insert_uid,
                    'basic'=>1,
                    'intention'=>!empty($intention_list)?1:0,
                    'specialty'=>$value['basic']['specialty']==''?0:1,
                    'education'=>!empty($education_list)?1:0,
                    'work'=>!empty($work_list)?1:0,
                    'training'=>0,
                    'project'=>0,
                    'certificate'=>!empty($certificate_list)?1:0,
                    'language'=>!empty($language_list)?1:0,
                    'tag'=>0,
                    'img'=>0
                ];
            }
            unset($_member_model,$_resume_model);
            if(!empty($compelete_list)){
                model('ResumeComplete')->saveAll($compelete_list);
            }
            if(!empty($contact_list)){
                model('ResumeContact')->saveAll($contact_list);
            }
            if(!empty($intention_list)){
                model('ResumeIntention')->saveAll($intention_list);
            }
            if(!empty($certificate_list)){
                model('ResumeCertificate')->saveAll($certificate_list);
            }
            if(!empty($education_list)){
                model('ResumeEducation')->saveAll($education_list);
            }
            if(!empty($language_list)){
                model('ResumeLanguage')->saveAll($language_list);
            }
            if(!empty($work_list)){
                model('ResumeWork')->saveAll($work_list);
            }
            if(!empty($resumeid_arr)){
                model('Resume')->where('id','in',$resumeid_arr)->setField('audit',1);
            }
            \think\Db::commit();
        }catch(\Exception $e){
            \think\Db::rollBack();
            $this->ajaxReturn(500,$e->getMessage());
        }
        if(!empty($resumeid_arr)){
            model('Resume')->refreshSearchBatch($resumeid_arr);
        }
        $this->ajaxReturn(200, '导入成功');
    }
    public function downloadImportResumeTpl(){
        $file = SYS_UPLOAD_PATH.'resource/import_resume.xls';
        $result = file_get_contents($file);
        ob_start(); 
        echo "$result"; 
        header("Cache-Control: public"); 
        Header("Content-type: application/octet-stream"); 
        Header("Accept-Ranges: bytes"); 
        header('Content-Disposition: attachment; filename=简历导入模板.xls'); 
        header("Pragma:no-cache"); 
        header("Expires:0"); 
        ob_end_flush(); 
    }
}
