<?php
namespace app\v1_0\controller\personal;

class ResumeRegByAppInteractive extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(2);
    }

    /**
     * 第一步：保存简历基本信息
     */
    public function step1()
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
                ),
                'current' => input('post.basic.current/d', 0, 'intval'),
                'major1' => input('post.basic.major1/d', 0, 'intval'),
                'major2' => input('post.basic.major2/d', 0, 'intval'),
                'major' => input('post.basic.major/d', 0, 'intval')
            ],
            'contact' => [
                'uid' => $this->userinfo->uid,
                'mobile' => input('post.contact.mobile/s', '', 'trim,badword_filter'),
                'weixin' => input('post.contact.weixin/s', '', 'trim,badword_filter')
            ]
        ];
        $resume_id = 0;
        $basic_info = model('Resume')
            ->where('uid', $this->userinfo->uid)
            ->find();
        if ($basic_info !== null) {
            $resume_id = $basic_info->id;
        }
        $contact_info = model('ResumeContact')
            ->where('uid', $this->userinfo->uid)
            ->find();

        \think\Db::startTrans();
        try {
            if ($basic_info !== null) {
                $result = model('Resume')
                    ->validate('Resume.reg_from_app_by_interactive')
                    ->allowField(true)
                    ->save($input_data['basic'], [
                        'uid' => $this->userinfo->uid
                    ]);
            } else {
                $input_data['basic']['platform'] = config('platform');
                $result = model('Resume')
                    ->validate('Resume.reg_from_app_by_interactive')
                    ->allowField(true)
                    ->save($input_data['basic']);
                $resume_id = model('Resume')->id;
            }
            if (false === $result) {
                throw new \Exception(model('Resume')->getError());
            }

            //联系方式
            $input_data['contact']['rid'] = $resume_id;
            if (null === $contact_info) {
                $result = model('ResumeContact')
                    ->validate('ResumeContact.reg_from_app_by_interactive')
                    ->allowField(true)
                    ->save($input_data['contact']);
            } else {
                $result = model('ResumeContact')
                    ->validate('ResumeContact.reg_from_app_by_interactive')
                    ->allowField(true)
                    ->save($input_data['contact'], [
                        'id' => $contact_info['id']
                    ]);
            }
            if (false === $result) {
                throw new \Exception(model('ResumeContact')->getError());
            }
            //更新完整度
            model('Resume')->updateComplete(
                ['basic' => 1],
                $resume_id,
                $this->userinfo->uid
            );

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

        model('Resume')->refreshSearch($resume_id);
        $this->writeMemberActionLog($this->userinfo->uid,'注册 - 保存简历基本信息');
        $this->ajaxReturn(200, '保存成功');
    }
    /**
     * 第二步：保存简历求职意向
     */
    public function step2()
    {
        $input_data = [
            'uid' => $this->userinfo->uid,
            'category1' => input('post.category1/d', 0, 'intval'),
            'category2' => input('post.category2/d', 0, 'intval'),
            'category3' => input('post.category3/d', 0, 'intval'),
            'district1' => input('post.district1/d', 0, 'intval'),
            'district2' => input('post.district2/d', 0, 'intval'),
            'district3' => input('post.district3/d', 0, 'intval'),
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
        $intention_info = model('ResumeIntention')
            ->where('uid', $this->userinfo->uid)
            ->find();

        if ($intention_info !== null) {
            $result = model('ResumeIntention')
                ->validate('ResumeIntention.reg_from_app_by_interactive')
                ->allowField(true)
                ->save($input_data, [
                    'id' => $intention_info['id']
                ]);
        } else {
            $basic_info = model('Resume')
                ->where('uid', $this->userinfo->uid)
                ->find();
            if ($basic_info === null) {
                $this->ajaxReturn(500, '请先填写基本资料');
            }
            $input_data['rid'] = $basic_info->id;
            $result = model('ResumeIntention')
                ->validate('ResumeIntention.reg_from_app_by_interactive')
                ->allowField(true)
                ->save($input_data);
        }

        if (false === $result) {
            $this->ajaxReturn(500, model('ResumeIntention')->getError());
        }
        model('Resume')->updateComplete(
            ['intention' => 1],
            0,
            $this->userinfo->uid
        );

        $this->writeMemberActionLog($this->userinfo->uid,'注册 - 保存简历求职意向');
        $this->ajaxReturn(200, '保存成功');
    }
    /**
     * 第三步：求职状态和开始工作时间保存
     */
    public function step3()
    {
        $input_data = [
            'enter_job_time' => input('post.enter_job_time/s', '', 'trim'),
            'current' => input('post.current/d', 0, 'intval')
        ];
        $input_data['enter_job_time'] =
            $input_data['enter_job_time'] == ''
                ? 0
                : strtotime($input_data['enter_job_time']);

        $result = model('Resume')
            ->allowField(true)
            ->save($input_data, [
                'uid' => $this->userinfo->uid
            ]);

        if (false === $result) {
            $this->ajaxReturn(500, model('Resume')->getError());
        }
        $this->writeMemberActionLog($this->userinfo->uid,'注册 - 保存简历求职状态和开始工作时间');

        $this->ajaxReturn(200, '保存成功');
    }
    /**
     * 第四步：工作经历+教育经历
     */
    public function step4()
    {
        $input_data = [
            'work' => [
                'uid' => $this->userinfo->uid,
                'companyname' => input('post.work.companyname/s', '', 'trim,badword_filter'),
                'jobname' => input('post.work.jobname/s', '', 'trim,badword_filter'),
                'starttime' => input('post.work.starttime/s', '', 'trim'),
                'endtime' => input('post.work.endtime/s', '', 'trim'),
                'todate' => input('post.work.todate/d', 0, 'intval'),
                'duty' => input('post.work.duty/s', '', 'trim')
            ]
        ];
        $input_data['work']['starttime'] = strtotime(
            $input_data['work']['starttime']
        );
        if ($input_data['work']['todate'] == 1) {
            $input_data['work']['endtime'] = 0;
        } else {
            $input_data['work']['endtime'] = strtotime(
                $input_data['work']['endtime']
            );
        }
        if (input('?post.education')) {
            $input_data['education'] = [
                'uid' => $this->userinfo->uid,
                'school' => input('post.education.school/s', '', 'trim,badword_filter'),
                'major' => input('post.education.major/s', '', 'trim,badword_filter'),
                'education' => input('post.education.education/d', 0, 'intval'),
                'starttime' => input('post.education.starttime/s', '', 'trim'),
                'endtime' => input('post.education.endtime/s', '', 'trim'),
                'todate' => input('post.education.todate/d', 0, 'intval')
            ];
            $input_data['education']['starttime'] = strtotime(
                $input_data['education']['starttime']
            );
            if ($input_data['education']['todate'] == 1) {
                $input_data['education']['endtime'] = 0;
            } else {
                $input_data['education']['endtime'] = strtotime(
                    $input_data['education']['endtime']
                );
            }
        }
        $basic_info = null;

        $work_info = model('ResumeWork')
            ->where('uid', $this->userinfo->uid)
            ->find();
        \think\Db::startTrans();
        try {
            if ($work_info !== null) {
                $result = model('ResumeWork')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data['work'], [
                        'id' => $work_info['id']
                    ]);
            } else {
                $basic_info = model('Resume')
                    ->where('uid', $this->userinfo->uid)
                    ->find();
                if ($basic_info === null) {
                    throw new \Exception('请先填写基本资料');
                }
                $input_data['work']['rid'] = $basic_info->id;
                $result = model('ResumeWork')
                    ->validate(true)
                    ->allowField(true)
                    ->save($input_data['work']);
            }
            if (false === $result) {
                throw new \Exception(model('ResumeWork')->getError());
            }

            //教育经历
            if (isset($input_data['education'])) {
                $education_info = model('ResumeEducation')
                    ->where('uid', $this->userinfo->uid)
                    ->find();
                if (null === $education_info) {
                    $basic_info =
                        $basic_info === null
                            ? model('Resume')
                                ->where('uid', $this->userinfo->uid)
                                ->find()
                            : $basic_info;
                    if ($basic_info === null) {
                        throw new \Exception('请先填写基本资料');
                    }
                    $input_data['education']['rid'] = $basic_info->id;
                    $result = model('ResumeEducation')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data['education']);
                } else {
                    $result = model('ResumeEducation')
                        ->validate(true)
                        ->allowField(true)
                        ->save($input_data['education'], [
                            'id' => $education_info['id']
                        ]);
                }
                if (false === $result) {
                    throw new \Exception(model('ResumeEducation')->getError());
                }
            }
            //更新完整度
            model('Resume')->updateComplete(
                [
                    'work' => 1,
                    'education' => isset($input_data['education']) ? 1 : 0
                ],
                0,
                $this->userinfo->uid
            );
            //提交事务
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }
        model('Resume')->refreshSearch(0, $this->userinfo->uid);
        $this->writeMemberActionLog($this->userinfo->uid,'注册 - 保存简历工作经历+教育经历');
        $this->ajaxReturn(200, '保存成功');
    }
    /**
     * 第四步-无工作经验：保存简历教育经历
     */
    public function stepNoWork()
    {
        $input_data = [
            'uid' => $this->userinfo->uid,
            'school' => input('post.school/s', '', 'trim,badword_filter'),
            'major' => input('post.major/s', '', 'trim,badword_filter'),
            'education' => input('post.education/d', 0, 'intval'),
            'starttime' => input('post.starttime/s', '', 'trim'),
            'endtime' => input('post.endtime/s', '', 'trim'),
            'todate' => input('post.todate/d', 0, 'intval')
        ];

        $input_data['starttime'] = strtotime($input_data['starttime']);
        if ($input_data['todate'] == 1) {
            $input_data['endtime'] = 0;
        } else {
            $input_data['endtime'] = strtotime($input_data['endtime']);
        }

        $education_info = model('ResumeEducation')
            ->where('uid', $this->userinfo->uid)
            ->find();

        if ($education_info !== null) {
            $result = model('ResumeEducation')
                ->validate(true)
                ->allowField(true)
                ->save($input_data, [
                    'id' => $education_info['id']
                ]);
        } else {
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
        }

        if (false === $result) {
            $this->ajaxReturn(500, model('ResumeEducation')->getError());
        }

        model('Resume')->updateComplete(
            [
                'education' => 1
            ],
            0,
            $this->userinfo->uid
        );
        $this->writeMemberActionLog($this->userinfo->uid,'注册 - 保存简历教育经历');

        $this->ajaxReturn(200, '保存成功');
    }
}
