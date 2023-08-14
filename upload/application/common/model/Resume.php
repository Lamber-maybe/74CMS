<?php

namespace app\common\model;

class Resume extends \app\common\model\BaseModel
{
    public $map_audit = [
        0 => '待审核',
        1 => '已通过',
        2 => '未通过'
    ];
    public  $needle = [
        'password' => [
            'password' => [
                'type' => '/s',
                'default' => '',
                'filter' => 'trim',
                'validate' => [
                    'rule' => 'require',
                    'message' => '请输入修改的密码'
                ]
            ],
        ],
        'member_mobile' => [
            'member_mobile' => [
                'type' => '/d',
                'default' => 0,
                'filter' => 'intval',
                'validate' => [
                    'rule' => 'require',
                    'message' => '请输入会员手机号'
                ]
            ],
        ],
        'email' => [
            'email' => [
                'type' => '/s',
                'default' => '',
                'filter' => 'trim',
                'validate' => []
            ],
        ],
        'resume_mobile' => [
            'resume_mobile' => [
                'type' => '/d',
                'default' => 0,
                'filter' => 'intval',
                'validate' => [
                    'rule' => 'require',
                    'message' => '请输入会员手机号'
                ]
            ],
        ],
        'high_quality' => [
            'high_quality' => [
                'type' => '/s',
                'default' => '',
                'filter' => 'trim',
                'validate' => [
                    'rule' => 'in:0,1',
                    'message' => '简历等级参数错误'
                ]
            ],
        ],
        'remark' => [
            'remark' => [
                'type' => '/s',
                'default' => '',
                'filter' => 'trim',
                'validate' => [
                    'rule' => 'max:100',
                    'message' => '简历备注内容长度有误'
                ]
            ],
        ],
        'comment' => [
            'comment' => [
                'type' => '/s',
                'default' => '',
                'filter' => 'trim',
                'validate' => [
                    'rule' => 'max:100',
                    'message' => '客服评价内容长度有误'
                ]
            ],
        ],
        'audit' => [
            'audit' => [
                'type' => '/d',
                'default' => 0,
                'filter' => 'intval',
                'validate' => [
                    'rule' => 'in:0,1,2',
                    'message' => '简历审核参数错误'
                ]
            ],
            'reason' => [
                'type' => '/s',
                'default' => '',
                'filter' => 'trim',
                'validate' => []
            ],
        ],
        'resume_img' => [
            'examine' => [
                'type' => '/d',
                'default' => 0,
                'filter' => 'intval',
                'validate' => [
                    'rule' => 'in:0,1,2',
                    'message' => '简历作品审核参数错误'
                ]
            ],
            'resume_img' => [
                'type' => '/a',
                'default' => [],
                'filter' => '',
                'validate' => []
            ],
        ],
        'is_display' => [
            'is_display' => [
                'type' => '/d',
                'default' => 0,
                'filter' => 'intval',
                'validate' => [
                    'rule' => 'in:0,1',
                    'message' => '简历公开状态参数错误'
                ]
            ],
        ],
    ];
    /**
     * 【优化】简历未定义数组下标问题
     *  zch 2022.9.21
     * 【旧】
     *  public $map_sex = [1 => '男', 2 => '女'];
     * 【新】
     *  public $map_sex = [0 => '保密',1 => '男', 2 => '女'];
     */
    public $map_sex = [0 => '保密',1 => '男', 2 => '女'];
    public $map_marriage = [0 => '保密', 1 => '未婚', 2 => '已婚'];
    public $map_nature = [1 => '全职', 2 => '兼职', 3 => '实习'];
    protected $readonly = ['id', 'uid', 'addtime'];
    protected $insert = [
        'audit',
        'is_display' => 1,
        'high_quality' => 0,
        'display_name',
        'stick' => 0,
        'height' => '',
        'marriage' => 0,
        'tag' => '',
        'idcard' => '',
        'specialty' => '',
        'photo_img' => 0,
        'addtime',
        'refreshtime',
        'updatetime',
        'tpl' => ''
    ];
    protected $update = ['updatetime'];
    protected function setAuditAttr($value = null)
    {
        return $value === null ? config('global_config.audit_add_resume') : $value;
    }
    protected function setAddtimeAttr($value = null)
    {
        return $value === null ? time() : $value;
    }
    protected function setRefreshtimeAttr($value = null)
    {
        return $value === null ? $this->addtime : $value;
    }
    protected function setUpdatetimeAttr($value = null)
    {
        return $value === null ? time() : $value;
    }
    protected function setDisplayNameAttr($value = null)
    {
        return $value === null
            ? config('global_config.resume_display_name')
            : $value;
    }

    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'audit' => 'integer',
        'stick' => 'integer',
        'sex' => 'integer',
        'nature' => 'integer',
        'marriage' => 'integer',
        'education' => 'integer',
        'enter_job_time' => 'integer',
        'major1' => 'integer',
        'major2' => 'integer',
        'major' => 'integer',
        'addtime' => 'integer',
        'refreshtime' => 'integer',
        'current' => 'integer',
        'click' => 'integer',
        'is_display' => 'integer'
    ];

    /**
     * 更新简历的完整记录表
     * 示例：
     * $update_arr = ['basic'=>1,'intention'=>1,'education'=>0];
     */
    public function updateComplete($update_arr, $rid = 0, $uid = 0)
    {
        $rid = intval($rid);
        $uid = intval($uid);
        if ($rid > 0) {
            $where['rid'] = $rid;
        }
        if ($uid > 0) {
            $where['uid'] = $uid;
        }
        $model = model('ResumeComplete')
            ->where($where)
            ->find();
        if (null === $model) {
            $model = new \app\common\model\ResumeComplete();
            $model->rid = $rid;
            $model->uid = $uid;
        }
        $column = $model->getColumn();
        foreach ($update_arr as $key => $value) {
            if (!in_array($key, $column)) {
                unset($update_arr[$key]);
            }
        }
        if (empty($update_arr)) {
            return false;
        }
        foreach ($update_arr as $key => $value) {
            $model->$key = $value;
        }
        $model->save();
        //完成任务
        $complete_total = $this->countCompletePercent($rid, $uid);
        if ($complete_total >= 90) {
            model('Task')->doTask($model->uid, 2, ['percent60', 'percent90']);
        } elseif ($complete_total >= 60) {
            model('Task')->doTask($model->uid, 2, 'percent60');
        }
        return true;
    }
    /**
     * 计算简历的完整度
     */
    public function countCompletePercent($rid = 0, $uid = 0)
    {
        $rid = intval($rid);
        $uid = intval($uid);
        if ($rid > 0) {
            $where['rid'] = $rid;
        }
        if ($uid > 0) {
            $where['uid'] = $uid;
        }
        $score = 0;
        $model = model('ResumeComplete')
            ->where($where)
            ->find();
        if (null === $model) {
            return $score;
        }
        $module_data = model('ResumeModule')->getCache();
        foreach ($module_data as $key => $value) {
            $_tmp_module_name = $value['module_name'];
            if ($value['is_display'] == 1 && $model->$_tmp_module_name == 1) {
                $score = $score + $value['score'];
            }
        }
        return $score;
    }
    /**
     * 计算简历的完整度(批量)
     */
    public function countCompletePercentBatch($ridarr)
    {
        $where['rid'] = ['in', $ridarr];
        $list = model('ResumeComplete')
            ->where($where)
            ->select();
        if (!$list) {
            return false;
        }
        $returnlist = [];
        $module_data = model('ResumeModule')->getCache();
        foreach ($list as $key => $value) {
            $score = 0;
            foreach ($module_data as $k => $v) {
                $_tmp_module_name = $v['module_name'];
                if ($v['is_display'] == 1 && $value->$_tmp_module_name == 1) {
                    $score = $score + $v['score'];
                }
            }
            $returnlist[$value['rid']] = $score;
        }
        return $returnlist;
    }
    /**
     * 更新索引表
     */
    public function refreshSearch($resume_id = 0, $uid = 0, $userinfo = [])
    {
        $resume_id = intval($resume_id);
        $uid = intval($uid);
        if ($resume_id > 0) {
            $condition['id'] = $resume_id;
        }
        if ($uid > 0) {
            $condition['uid'] = $uid;
        }
        $resume = $this->where($condition)->find();

        if (!$resume) {
            return;
        }
        $resume_status = true; //标记信息是否有效
        if (empty($userinfo)) {
            $userinfo = model('Member')->find($resume['uid']);
        }
        if (
            !$userinfo ||
            $userinfo['status'] == 0 ||
            $resume['audit'] != 1 ||
            $resume['is_display'] == 0
        ) {
            $resume_status = false; //无效信息，不进索引表
        }
        if ($resume_status) {
            $resume_work_all = model('ResumeWork')
                ->field('rid,jobname,duty')
                ->where(array('rid' => array('eq', $resume['id'])))
                ->select();
            $resume_intention_all = model('ResumeIntention')
                ->where(array(
                    'rid' => array('eq', $resume['id'])
                ))
                ->select();
            $resume_project_all = model('ResumeProject')
                ->field('rid,role,description')
                ->where(array('rid' => array('eq', $resume['id'])))
                ->select();

            $category_data = model('Category')->getCache();
            $category_job_data = model('CategoryJob')->getCache();
            $fulltext_key = array();
            $fulltext_key[] = isset(
                model('BaseModel')->map_education[$resume['education']]
            )
                ? model('BaseModel')->map_education[$resume['education']]
                : '';
            $fulltext_key[] = $resume['specialty'];
            if ($resume_work_all) {
                foreach ($resume_work_all as $li) {
                    $fulltext_key[] = $li['jobname'];
                    $fulltext_key[] = $li['duty'];
                }
            }
            if ($resume_project_all) {
                foreach ($resume_project_all as $li) {
                    $fulltext_key[] = $li['role'];
                    $fulltext_key[] = $li['description'];
                }
            }
            $intention_jobs_arr = [];
            if ($resume_intention_all) {
                foreach ($resume_intention_all as $li) {
                    if ($li['trade']) {
                        $trade_cn = $category_data['QS_trade'][$li['trade']];
                        $fulltext_key[] = $trade_cn;
                    }
                    $category_cn_1 = $li['category1']
                        ? $category_job_data[$li['category1']]
                        : '';
                    $category_cn_2 = $li['category2']
                        ? $category_job_data[$li['category2']]
                        : '';
                    $category_cn_3 = $li['category3']
                        ? $category_job_data[$li['category3']]
                        : '';
                    if ($category_cn_1) {
                        $fulltext_key[] = $category_cn_1;
                        $intention_jobs_arr[] = $category_cn_1;
                    }
                    if ($category_cn_2) {
                        $fulltext_key[] = $category_cn_2;
                        $intention_jobs_arr[] = $category_cn_2;
                    }
                    if ($category_cn_3) {
                        $fulltext_key[] = $category_cn_3;
                        $intention_jobs_arr[] = $category_cn_3;
                    }
                }
            }
            $fulltext_key = array_unique($fulltext_key);
            $intention_jobs_arr = array_unique($intention_jobs_arr);

            if ($resume['photo_img'] > 0) {
                $photo = 1;
            } else {
                $photo = 0;
            }

            $search_rtime_data['id'] = $resume['id'];
            $search_rtime_data['uid'] = $resume['uid'];
            $search_rtime_data['high_quality'] = $resume['high_quality'];
            $search_rtime_data['photo'] = $photo;
            $search_rtime_data['stick'] = $resume['stick'];
            $search_rtime_data['sex'] = $resume['sex'];
            $search_rtime_data['birthyear'] = intval($resume['birthday']);
            $search_rtime_data['education'] = $resume['education'];
            $search_rtime_data['enter_job_time'] = $resume['enter_job_time'];
            $search_rtime_data['major1'] = $resume['major1'];
            $search_rtime_data['major2'] = $resume['major2'];
            $search_rtime_data['major'] = $resume['major'];
            $search_rtime_data['tag'] = $resume['tag'];
            $search_rtime_data['refreshtime'] = $resume['refreshtime'];
            $search_key_data = $search_rtime_data;
            $search_key_data['intention_jobs'] = implode(
                ' ',
                $intention_jobs_arr
            );
            $search_key_data['fulltext_key'] = implode(' ', $fulltext_key);
        }

        \think\Db::startTrans();
        try {
            model('ResumeSearchRtime')->destroy($resume['id']);
            model('ResumeSearchKey')->destroy($resume['id']);
            if ($resume_status) {
                if (
                    false ===
                    model('ResumeSearchRtime')
                        ->allowField(true)
                        ->save($search_rtime_data)
                ) {
                    throw new \Exception(
                        model('ResumeSearchRtime')->getError()
                    );
                }
                if (
                    false ===
                    model('ResumeSearchKey')
                        ->allowField(true)
                        ->save($search_key_data)
                ) {
                    throw new \Exception(model('ResumeSearchKey')->getError());
                }
            }
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }

        return;
    }
    /**
     * 更新索引表（批量）
     */
    public function refreshSearchBatch($idarr)
    {
        if (!is_array($idarr) || empty($idarr)) {
            $this->error = '参数错误';
            return false;
        }
        //查出所有的简历信息
        $resume_list = $this->where('id', 'in', $idarr)->select();
        //查出所有的uid
        $uid_arr = [];
        foreach ($resume_list as $key => $resume) {
            $uid_arr[] = $resume['uid'];
        }
        //查出所有的member信息，用于判断用户是否是暂停状态
        $membeninfo_list = [];
        if (!empty($uid_arr)) {
            $membeninfo_list = model('Member')
                ->where('uid', 'in', $uid_arr)
                ->column('uid,status,mobile', 'uid');
        }
        //整理出需要整理索引的简历信息(所有的有效简历)
        $valid_resume_list = [];
        foreach ($resume_list as $key => $resume) {
            if (
                isset($membeninfo_list[$resume['uid']]) &&
                $membeninfo_list[$resume['uid']]['status'] == 1 &&
                $resume['audit'] == 1 &&
                $resume['is_display'] == 1
            ) {
                $valid_resume_list[] = $resume->toArray();
            }
        }
        //如果没有有效简历，就只删除索引表中数据即可
        if (empty($valid_resume_list)) {
            model('ResumeSearchRtime')->destroy($idarr);
            model('ResumeSearchKey')->destroy($idarr);
            return;
        }
        //------------------开始整理索引更新数据-----------------------
        //查出所有的工作经历
        $resume_work_data = model('ResumeWork')
            ->field('rid,jobname,duty')
            ->where(['rid' => ['in', $idarr]])
            ->select();
        $resume_work_list = [];
        foreach ($resume_work_data as $key => $value) {
            $resume_work_list[$value['rid']][] = $value;
        }
        //查出所有的求职意向
        $resume_intention_data = model('ResumeIntention')
            ->where(['rid' => ['in', $idarr]])
            ->select();
        $resume_intention_list = [];
        foreach ($resume_intention_data as $key => $value) {
            $resume_intention_list[$value['rid']][] = $value;
        }
        //查出所有的项目经历
        $resume_project_data = model('ResumeProject')
            ->field('rid,role,description')
            ->where(['rid' => ['in', $idarr]])
            ->select();
        $resume_project_list = [];
        foreach ($resume_project_data as $key => $value) {
            $resume_project_list[$value['rid']][] = $value;
        }
        //所有的分类数据
        $category_data = model('Category')->getCache();
        $category_job_data = model('CategoryJob')->getCache();

        $insert_rtime_data = [];
        $insert_key_data = [];
        foreach ($valid_resume_list as $key => $resume) {
            $resume_work_all = isset($resume_work_list[$resume['id']])
                ? $resume_work_list[$resume['id']]
                : [];
            $resume_intention_all = isset($resume_intention_list[$resume['id']])
                ? $resume_intention_list[$resume['id']]
                : [];
            $resume_project_all = isset($resume_project_list[$resume['id']])
                ? $resume_project_list[$resume['id']]
                : [];

            $fulltext_key = [];
            $fulltext_key[] = isset(
                model('BaseModel')->map_education[$resume['education']]
            )
                ? model('BaseModel')->map_education[$resume['education']]
                : '';
            $fulltext_key[] = $resume['specialty'];
            if ($resume_work_all) {
                foreach ($resume_work_all as $li) {
                    $fulltext_key[] = $li['jobname'];
                    $fulltext_key[] = $li['duty'];
                }
            }
            if ($resume_project_all) {
                foreach ($resume_project_all as $li) {
                    $fulltext_key[] = $li['role'];
                    $fulltext_key[] = $li['description'];
                }
            }
            $intention_jobs_arr = [];
            if ($resume_intention_all) {
                foreach ($resume_intention_all as $li) {
                    if ($li['trade']) {
                        $trade_cn = $category_data['QS_trade'][$li['trade']];
                        $fulltext_key[] = $trade_cn;
                    }
                    $category_cn_1 = $li['category1']
                        ? $category_job_data[$li['category1']]
                        : '';
                    $category_cn_2 = $li['category2']
                        ? $category_job_data[$li['category2']]
                        : '';
                    $category_cn_3 = $li['category3']
                        ? $category_job_data[$li['category3']]
                        : '';
                    if ($category_cn_1) {
                        $fulltext_key[] = $category_cn_1;
                        $intention_jobs_arr[] = $category_cn_1;
                    }
                    if ($category_cn_2) {
                        $fulltext_key[] = $category_cn_2;
                        $intention_jobs_arr[] = $category_cn_2;
                    }
                    if ($category_cn_3) {
                        $fulltext_key[] = $category_cn_3;
                        $intention_jobs_arr[] = $category_cn_3;
                    }
                }
            }
            $fulltext_key = array_unique($fulltext_key);
            $intention_jobs_arr = array_unique($intention_jobs_arr);

            if ($resume['photo_img'] > 0) {
                $photo = 1;
            } else {
                $photo = 0;
            }

            $search_rtime_data['id'] = $resume['id'];
            $search_rtime_data['uid'] = $resume['uid'];
            $search_rtime_data['high_quality'] = $resume['high_quality'];
            $search_rtime_data['photo'] = $photo;
            $search_rtime_data['stick'] = $resume['stick'];
            $search_rtime_data['sex'] = $resume['sex'];
            $search_rtime_data['birthyear'] = intval($resume['birthday']);
            $search_rtime_data['education'] = $resume['education'];
            $search_rtime_data['enter_job_time'] = $resume['enter_job_time'];
            $search_rtime_data['major1'] = $resume['major1'];
            $search_rtime_data['major2'] = $resume['major2'];
            $search_rtime_data['major'] = $resume['major'];
            $search_rtime_data['tag'] = $resume['tag'];
            $search_rtime_data['refreshtime'] = $resume['refreshtime'];
            $search_key_data = $search_rtime_data;
            $search_key_data['intention_jobs'] = implode(
                ' ',
                $intention_jobs_arr
            );
            $search_key_data['fulltext_key'] = implode(' ', $fulltext_key);

            $insert_rtime_data[] = $search_rtime_data;
            $insert_key_data[] = $search_key_data;
        }
        if (!empty($insert_rtime_data) && !empty($insert_key_data)) {
            \think\Db::startTrans();
            try {
                model('ResumeSearchRtime')->destroy($idarr);
                model('ResumeSearchKey')->destroy($idarr);
                if (
                    false ===
                    model('ResumeSearchRtime')->saveAll(
                        $insert_rtime_data,
                        false
                    )
                ) {
                    throw new \Exception(
                        model('ResumeSearchRtime')->getError()
                    );
                }
                if (
                    false ===
                    model('ResumeSearchKey')->saveAll($insert_key_data, false)
                ) {
                    throw new \Exception(model('ResumeSearchKey')->getError());
                }
                \think\Db::commit();
            } catch (\Exception $e) {
                \think\Db::rollBack();
                $this->error = $e->getMessage();
                return false;
            }
        }
        return;
    }
    public function backendAdd($data)
    {
        $data_member = $data['member'];
        $data_contact = $data['contact'];
        $data_intention = $data['intention'];
        unset($data['member'], $data['contact'], $data['intention']);
        $data_basic = $data;

        //开启事务
        \think\Db::startTrans();

        $data_member['pwd_hash'] = randstr();
        if ($data_member['password']) {
            $data_member['password'] = model('Member')->makePassword(
                $data_member['password'],
                $data_member['pwd_hash']
            );
        }
        $data_member['platform'] = config('platform');
        if (
            false ===
            model('Member')
                ->validate(true)
                ->allowField(true)
                ->save($data_member)
        ) {
            $this->error = model('Member')->getError();
            return false;
        }
        model('Member')->setMemberPoints([
            'uid' => model('Member')->uid,
            'points' => 0,
            'note' => '注册赠送'
        ]);
        $data_basic['uid'] = model('Member')->uid;
        $data_basic['is_display'] = 1;
        $data_basic['audit'] = 0;
        $data_basic['stick'] = 0;
        $data_basic['addtime'] = time();
        $data_basic['refreshtime'] = $data_basic['addtime'];
        $data_basic['click'] = 0;
        $data_basic['tpl'] = '';
        $data_basic['major'] =
            $data_basic['major2'] != 0
                ? $data_basic['major2']
                : ($data_basic['major1'] != 0
                    ? $data_basic['major1']
                    : 0);

        $data_basic['tag'] = '';
        $data_basic['specialty'] = '';
        $data_basic['platform'] = config('platform');
        $result = model('Resume')
            ->validate(true)
            ->allowField(true)
            ->save($data_basic);
        if (false === $result) {
            //事务回滚
            \think\Db::rollBack();
            $this->error = model('Resume')->getError();
            return false;
        }
        $data_intention['rid'] = model('Resume')->id;
        $data_intention['uid'] = model('Member')->uid;
        $data_intention['category'] =
            $data_intention['category3'] != 0
                ? $data_intention['category3']
                : ($data_intention['category2'] != 0
                    ? $data_intention['category2']
                    : $data_intention['category1']);
        $data_intention['district'] =
            $data_intention['district3'] != 0
                ? $data_intention['district3']
                : ($data_intention['district2'] != 0
                    ? $data_intention['district2']
                    : $data_intention['district1']);
        $result = model('ResumeIntention')
            ->validate(true)
            ->allowField(true)
            ->save($data_intention);
        if (false === $result) {
            //事务回滚
            \think\Db::rollBack();
            $this->error = model('ResumeIntention')->getError();
            return false;
        }

        $data_contact['rid'] = model('Resume')->id;
        $data_contact['uid'] = model('Member')->uid;
        $result = model('ResumeContact')
            ->validate(true)
            ->allowField(true)
            ->save($data_contact);
        if (false === $result) {
            //事务回滚
            \think\Db::rollBack();
            $this->error = model('ResumeContact')->getError();
            return false;
        }

        //提交事务
        \think\Db::commit();

        $this->updateComplete(
            ['basic' => 1, 'intention' => 1],
            model('Resume')->id,
            model('Member')->uid
        );
        $this->refreshSearch(model('Resume')->id);

        return model('Resume')->id;
    }
    /**
     * 审核简历
     */
    public function setAudit($idarr, $audit, $reason = '')
    {
        $timestamp = time();
        $this->where('id', 'in', $idarr)->setField('audit', $audit);
        $uid_arr = [];
        $audit_log = [];
        $resume_list = $this->where('id', 'in', $idarr)->column('id,uid', 'id');
        foreach ($resume_list as $key => $value) {
            $uid_arr[] = $value;
            $arr['uid'] = $value;
            $arr['resumeid'] = $key;
            $arr['audit'] = $audit;
            $arr['reason'] = $reason;
            $arr['addtime'] = $timestamp;
            $audit_log[] = $arr;
        }
        model('ResumeAuditLog')->saveAll($audit_log);
        $this->refreshSearchBatch($idarr);

        //通知
        if ($audit == 1) {
            model('NotifyRule')->notify($uid_arr, 2, 'resume_audit_success');
            //微信通知
            model('WechatNotifyRule')->notify(
                $uid_arr,
                2,
                'resume_audit_success',
                [
                    '您的简历已通过审核。',
                    '通过审核',
                    date('Y年m月d日 H:i'),
                    '点击查看查看最新招聘职位'
                ],
                'joblist'
            );
        }
        if ($audit == 2) {
            model('NotifyRule')->notify($uid_arr, 2, 'resume_audit_fail', [
                'reason' => $reason
            ]);
            //微信通知
            model('WechatNotifyRule')->notify(
                $uid_arr,
                2,
                'resume_audit_fail',
                [
                    '您的简历未通过审核。',
                    '审核未通过',
                    date('Y年m月d日 H:i'),
                    $reason,
                    '请修改后再次发布，点击去修改'
                ],
                'member/personal/resume'
            );
        }
        return;
    }

    /**
     * 简历等级
     */
    public function setLevel($idarr, $level)
    {
        $timestamp = time();
        $this->where('id', 'in', $idarr)->setField('high_quality', $level);
        $this->refreshSearchBatch($idarr);
        return;
    }
    /**
     * 刷新简历
     */
    public function refreshResume($uid)
    {
        $uid = intval($uid);
        $condition['uid'] = $uid;
        $timestamp = time();
        $resume_info = model('Resume')
            ->where($condition)
            ->field('uid,refreshtime')
            ->find();
        if ($resume_info === null) {
            $this->error = '没有找到简历信息';
            return false;
        }
        $global_config = config('global_config');
        if ($global_config['refresh_resume_space'] > 0) {
            if (
                $timestamp - $resume_info['refreshtime'] <
                $global_config['refresh_resume_space'] * 60
            ) {
                $this->error =
                    '刷新间隔不能小于' .
                    $global_config['refresh_resume_space'] .
                    '分钟，请稍后再试';
                return false;
            }
        }
        if ($global_config['refresh_resume_max_perday'] > 0) {
            $total_refresh_time = model('RefreshResumeLog')
                ->whereTime('addtime', 'today')
                ->where('uid',$uid)
                ->count();
            if (
                $total_refresh_time >=
                $global_config['refresh_resume_max_perday']
            ) {
                $this->error =
                    '每天最多刷新简历' .
                    $global_config['refresh_resume_max_perday'] .
                    '次，请明天再试';
                return false;
            }
        }
        $this->where($condition)->setField('refreshtime', $timestamp);
        model('ResumeSearchRtime')
            ->where($condition)
            ->setField('refreshtime', $timestamp);
        model('ResumeSearchKey')
            ->where($condition)
            ->setField('refreshtime', $timestamp);

        model('RefreshResumeLog')->save([
            'uid' => $uid,
            'addtime' => $timestamp,
            'platform' => config('platform')
        ]);
        return;
    }
    /**
     * 后台刷新简历
     */
    public function backendRefreshResume($id)
    {
        if(empty($id)){
            return false;
        }
        $timestamp = time();
        $this->whereIn('id',$id)->setField('refreshtime', $timestamp);
        model('ResumeSearchRtime')
            ->whereIn('id',$id)
            ->setField('refreshtime', $timestamp);
        model('ResumeSearchKey')
            ->whereIn('id',$id)
            ->setField('refreshtime', $timestamp);
        return;
    }
    public function setDisplay($resume_id = 0, $uid = 0, $display)
    {
        $resume_id = intval($resume_id);
        $uid = intval($uid);
        if ($resume_id > 0) {
            $condition['id'] = $resume_id;
        }
        if ($uid > 0) {
            $condition['uid'] = $uid;
        }
        $this->where($condition)->setField('is_display', $display);
        $this->refreshSearch($resume_id, $uid);
        return;
    }
    /**
     * 增加查看数
     */
    public function addViewLog($resume_id, $company_uid = 0, $personal_uid = 0)
    {
        $rand_click = config('global_config.rand_click_resume');
        $rand_click = intval($rand_click);
        if($rand_click<=1){
            $rand_click = 1;
        }else{
            $rand_click = rand(1,$rand_click);
        }
        $this->where('id', 'eq', $resume_id)->setInc('click',$rand_click);
        if ($company_uid > 0 && $personal_uid > 0) {
            $company_info = model('Company')
                ->field('id,companyname')
                ->where('uid', $company_uid)
                ->find();
            if($company_info===null){
                return false;
            }
            $view_data['company_uid'] = $company_uid;
            $view_data['personal_uid'] = $personal_uid;
            $view_history = model('ViewResume')
                ->where($view_data)
                ->find();
            if ($view_history === null) {
                $view_data['resume_id'] = $resume_id;
                $view_data['addtime'] = time();
                model('ViewResume')->save($view_data);
            } else {
                $view_history->addtime = time();
                $view_history->save();
            }
            //通知
            model('NotifyRule')->notify(
                $personal_uid,
                2,
                'resume_view',
                [
                    'companyname' => $company_info['companyname']
                ],
                $company_info['id']
            );
        }
    }
    /**
     * 获取联系方式
     */
    public function getContact($basic,$userinfo){
        $global_config = config('global_config');
        $return['show_contact'] = 0;
        $contact_info = model('ResumeContact')
            ->field('id,rid,uid', true)
            ->where(['rid' => ['eq', $basic['id']]])
            ->find();
        if(config('platform')=='web'){
            if ($global_config['showresumecontact'] == 0) {
                //游客可见
                $return['show_contact'] = 1;
                $return['show_contact_note'] = '';
            } elseif ($global_config['showresumecontact'] == 1) {
                //已登录可见
                if ($userinfo === null) {
                    //未登录
                    $return['show_contact'] = 0;
                    $return['show_contact_note'] = 'need_login';
                } elseif ($userinfo->utype == 2) {
                    //不是企业登录
                    $return['show_contact'] = 0;
                    $return['show_contact_note'] = 'need_company_login';
                } else {
                    $return['show_contact'] = 1;
                    $return['show_contact_note'] = '';
                }
            } elseif ($global_config['showresumecontact'] == 2) {
                //下载后可见
                if ($userinfo === null) {
                    //未登录
                    $return['show_contact'] = 0;
                    $return['show_contact_note'] = 'need_login';
                } elseif ($userinfo->utype == 2) {
                    //不是企业登录
                    $return['show_contact'] = 0;
                    $return['show_contact_note'] = 'need_company_login';
                } else {
                    $member_setmeal = model('Member')->getMemberSetmeal(
                        $userinfo->uid
                    );
                    if ($member_setmeal['show_apply_contact'] == 1) {
                        if(model('JobApply')->where('company_uid', $userinfo->uid)->where('personal_uid', $basic['uid'])->field('id')->find() === null){
                            $return['show_contact'] = 0;
                            $return['show_contact_note'] = 'need_download';
                        }else{
                            $return['show_contact'] = 1;
                            $return['show_contact_note'] = '';
                        }
                    }
                    if ($return['show_contact'] === 0 && model('CompanyDownResume')->where('uid', $userinfo->uid)->where('personal_uid', $basic['uid'])->field('id')->find() === null) {
                        //没有下载简历
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_download';
                    } else {
                        $return['show_contact'] = 1;
                        $return['show_contact_note'] = '';
                    }
                }
            }
        }else{
            if ($global_config['showresumecontact_mobile'] == 0) {
                //游客可见
                $return['show_contact'] = 1;
                $return['show_contact_note'] = '';
            } elseif ($global_config['showresumecontact_mobile'] == 1) {
                //已登录可见
                if ($userinfo === null) {
                    //未登录
                    $return['show_contact'] = 0;
                    $return['show_contact_note'] = 'need_login';
                } elseif ($userinfo->utype == 2) {
                    //不是企业登录
                    $return['show_contact'] = 0;
                    $return['show_contact_note'] = 'need_company_login';
                } else {
                    $return['show_contact'] = 1;
                    $return['show_contact_note'] = '';
                }
            } elseif ($global_config['showresumecontact_mobile'] == 2) {
                //下载后可见
                if ($userinfo === null) {
                    //未登录
                    $return['show_contact'] = 0;
                    $return['show_contact_note'] = 'need_login';
                } elseif ($userinfo->utype == 2) {
                    //不是企业登录
                    $return['show_contact'] = 0;
                    $return['show_contact_note'] = 'need_company_login';
                } else {
                    $member_setmeal = model('Member')->getMemberSetmeal(
                        $userinfo->uid
                    );
                    if ($member_setmeal['show_apply_contact'] == 1) {
                        if(model('JobApply')->where('company_uid', $userinfo->uid)->where('personal_uid', $basic['uid'])->field('id')->find() === null){
                            $return['show_contact'] = 0;
                            $return['show_contact_note'] = 'need_download';
                        }else{
                            $return['show_contact'] = 1;
                            $return['show_contact_note'] = '';
                        }
                    }
                    if ($return['show_contact'] === 0 && model('CompanyDownResume')->where('uid', $userinfo->uid)->where('personal_uid', $basic['uid'])->field('id')->find() === null) {
                        //没有下载简历
                        $return['show_contact'] = 0;
                        $return['show_contact_note'] = 'need_download';
                    } else {
                        $return['show_contact'] = 1;
                        $return['show_contact_note'] = '';
                    }
                }
            }
        }

        if ($return['show_contact'] == 1) {
            $return['contact_info'] = $contact_info;
        } else {
            /**
             * 隐藏联系方式，使用*号代替
             * 【旧】：
             *  $return['contact_info'] = [];
             */
            $tmp = $contact_info->toArray();
            $contact_return = [];
            foreach ($tmp as $contact_key => $contact_value) {
                if (!empty($contact_value)) {
                    $contact_return[$contact_key] = srtAsteriskReplace($contact_value, 4);
                } else {
                    $contact_return[$contact_key] = '*';
                }
            }
            $return['contact_info'] = $contact_return;
        }
        return $return;
    }
    /**
     * 处理简历姓名显示方式
     */
    public function formatFullname($rids,$userinfo,$single=false){

        $list = $this->whereIn('id',$rids)->field(true)->select();
        $return = [];
        $userinfo = (array)$userinfo;
        if(empty($userinfo) || !$userinfo || $userinfo['utype']!=1){
            foreach ($list as $key => $value) {
                $return[$value['id']] = $value['fullname'];
                if ($value['display_name'] == 0) {
                    if ($value['sex'] == 1) {
                        $return[$value['id']] = cut_str(
                            $value['fullname'],
                            1,
                            0,
                            '先生'
                        );
                    } elseif ($value['sex'] == 2) {
                        $return[$value['id']] = cut_str(
                            $value['fullname'],
                            1,
                            0,
                            '女士'
                        );
                    } else {
                        $return[$value['id']] = cut_str(
                            $value['fullname'],
                            1,
                            0,
                            '**'
                        );
                    }
                }
            }
            if($single===true){
                $array_values = array_values($return);
                return $array_values[0];
            }
            return $return;
        }
        $resumeidarr = [];
        foreach ($list as $key => $value) {
            $resumeidarr[] = $value['id'];
        }
        if(empty($resumeidarr)){
            return [];
        }
        //下载记录
        $down_resumeidarr = [];
        $downlist = model('CompanyDownResume')->whereIn('resume_id',$resumeidarr)->where('uid',$userinfo['uid'])->select();
        foreach ($downlist as $key => $value) {
            $down_resumeidarr[] = $value['resume_id'];
        }
        //收到的简历记录
        $apply_resumeidarr = [];
        $applylist = model('JobApply')->whereIn('resume_id',$resumeidarr)->where('company_uid',$userinfo['uid'])->select();
        foreach ($applylist as $key => $value) {
            $apply_resumeidarr[] = $value['resume_id'];
        }
        //套餐信息
        $setmeal = model('Member')->getMemberSetmeal($userinfo['uid']);
        //收到的简历是否能直接查看
        foreach ($list as $key => $value) {
            do{
                $return[$value['id']] = $value['fullname'];
                //是否下载过,下载过的话直接显示姓名
                if(in_array($value['id'],$down_resumeidarr)){
                    break;
                }
                //是否是收到的简历，并且和套餐权限对比
                if(in_array($value['id'],$apply_resumeidarr) && $setmeal['show_apply_contact']==1){
                    break;
                }
                if ($value['display_name'] == 0) {
                    if ($value['sex'] == 1) {
                        $return[$value['id']] = cut_str(
                            $value['fullname'],
                            1,
                            0,
                            '先生'
                        );
                    } elseif ($value['sex'] == 2) {
                        $return[$value['id']] = cut_str(
                            $value['fullname'],
                            1,
                            0,
                            '女士'
                        );
                    } else {
                        $return[$value['id']] = cut_str(
                            $value['fullname'],
                            1,
                            0,
                            '**'
                        );
                    }
                }
            }while(0);
        }
        if($single===true){
            $array_values = array_values($return);
            return $array_values[0];
        }
        return $return;
    }

    /**
     * 刷新简历信息
     * @access public
     * @author chenyang
     * @param  array   $params [请求参数]
     * @param  integer $source [来源:1|登录自动刷新,2|系统级手动刷新,3|会员手动刷新]
     * @return array
     * Date Time：2022年3月15日09:27:45
     */
    public function refreshResumeData($params, $source = 1){
        if (!isset($params['uid']) || empty($params['uid'])) {
            return callBack(false, '缺少会员ID');
        }

        $condition = [
            'uid' => $params['uid']
        ];
        // 如果缺少会员类型则进行查询
        if (!isset($params['utype']) || empty($params['utype'])) {
            $params = model('Member')->where($condition)->field(['uid', 'utype'])->find();
        }

        // 必须为个人用户
        if ($params['utype'] == 2) {
            // 获取简历信息
            $resumeModel = model('Resume');
            $resumeInfo = $resumeModel->where($condition)->field('refreshtime')->find();
            if (empty($resumeInfo) || $resumeInfo === null) {
                return callBack(false, '没有找到简历信息');
            }

            $currentTime = time();
            $isRefresh = false;

            switch ($source) {
                // 登录自动刷新
                case 1:
                    // 判断是否开启了登录自动刷新功能 并且 当天如果未刷新过则进行自动刷新
                    if (config('global_config.resume_auto_refresh') == 1 && $resumeInfo->refreshtime < strtotime('today')) {
                        $isRefresh = true;
                    }
                    break;
                // 系统级手动刷新
                case 2:
                    $isRefresh = true;
                    break;
                // 会员手动刷新
                case 3:
                    // 校验简历刷新条件
                    $validateParams = [
                        'uid'          => $params['uid'],
                        'current_time' => $currentTime,
                    ];
                    $validateResult = $this->_validateRefreshResume($validateParams);
                    if ($validateResult['status'] === false) {
                        return callBack(false, $validateResult['msg']);
                    }
                    $isRefresh = true;
                    break;
                default:
                    return callBack(false, '刷新简历-来源参数有误');
            }

            if ($isRefresh === true) {
                // 刷新简历
                $resumeModel->where($condition)->setField('refreshtime', $currentTime);
                model('ResumeSearchRtime')->where($condition)->setField('refreshtime', $currentTime);
                model('ResumeSearchKey')->where($condition)->setField('refreshtime', $currentTime);

                ############### 系统级刷新不记录在log表中 ###############
                if ($source == 3) {
                    $saveData = [
                        'uid'      => $params['uid'],
                        'addtime'  => $currentTime,
                        'platform' => config('platform'),
                    ];
                    model('RefreshResumeLog')->save($saveData);
                }
            }
        }
        return callBack(true, 'SUCCESS');
    }

    /**
     * 校验简历刷新条件
     * @access private
     * @author chenyang
     * @param  integer $params['uid']          [会员ID]
     * @param  integer $params['current_time'] [当前时间]
     * @return array
     * Date Time：2022年3月15日11:49:46
     */
    private function _validateRefreshResume($params){
        // 判断是否设置每天刷新简历次数
        $refreshResumeMaxPerday = config('global_config.refresh_resume_max_perday');
        if ($refreshResumeMaxPerday > 0) {
            // 获取当天的简历刷新次数
            $refreshTotal = model('RefreshResumeLog')
                ->whereTime('addtime', 'today')
                ->where('uid', $params['uid'])
                ->count();
            // 校验每天可刷新简历次数
            if ($refreshTotal >= $refreshResumeMaxPerday) {
                $errorMsg = '每天最多刷新简历' . $refreshResumeMaxPerday . '次，请明天再试';
                return ['status' => false, 'msg' => $errorMsg];
            }
        }

        // 判断是否设置简历刷新间隔
        $refreshResumeSpace = config('global_config.refresh_resume_space');
        if ($refreshResumeSpace > 0) {
            // 获取简历log刷新时间
            $resumeLogInfo = model('RefreshResumeLog')
                            ->field('addtime')
                            ->where('uid', $params['uid'])
                            ->whereTime('addtime', 'today')
                            ->order(['addtime' => 'desc'])
                            ->find();
            // 校验简历刷新间隔
            if (!empty($resumeLogInfo) && $params['current_time'] - $resumeLogInfo['addtime'] < $refreshResumeSpace * 60) {
                $errorMsg = '刷新间隔不能小于' . $refreshResumeSpace . '分钟，请稍后再试';
                return ['status' => false, 'msg' => $errorMsg];
            }
        }
        return ['status' => true, 'msg' => 'SUCCESS'];
    }

}
