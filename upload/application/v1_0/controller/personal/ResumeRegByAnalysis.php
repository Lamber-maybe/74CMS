<?php

namespace app\v1_0\controller\personal;

use app\v1_0\controller\common\Base;

class ResumeRegByAnalysis extends Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(2);
    }

    public function importCreate()
    {
        $input_data = [
            'basic' => [
                'uid' => $this->userinfo->uid,
                // 姓名
                'fullname' => input('post.basic.fullname/s', '', 'trim,badword_filter'),
                // 性别
                'sex' => input('post.basic.sex/d', 0, 'intval'),
                // 出生日期
                'birthday' => input('post.basic.birthday/s', '', 'trim'),
                // 最高学历
                'education' => input('post.basic.education/d', 0, 'intval'),
                // 开始工作时间
                'enter_job_time' => input('post.basic.enter_job_time/s', '', 'trim,badword_filter'),
                // 现居住地
                'residence' => input('post.basic.residence/s', '', 'trim,badword_filter'),
                // 籍贯
                'householdaddress' => input('post.basic.householdaddress/s', '', 'trim,badword_filter'),
                // 婚姻状况
                'marriage' => input('post.basic.marriage/d', 0, 'intval'),
                // 身高
                'height' => input('post.basic.height/d', 0, 'intval'),
                // 求职状态
                'current' => input('post.basic.current/d', 0, 'intval'),
                // 专业分类
                'major1' => input('post.basic.major1/d', 0, 'intval'),
                'major2' => input('post.basic.major2/d', 0, 'intval'),
                'major' => input('post.basic.major/d', 0, 'intval'),
                // 特长标签
                'tag' => input('post.basic.tag/a', []),
                // 自我描述
                'specialty' => input('post.basic.specialty/s', '', 'trim,badword_filter'),
                // 简历照片
                'photo_img' => input('post.basic.photo_img/d', 0, 'intval'),
            ],
            'contact' => [
                'uid' => $this->userinfo->uid,
                'mobile' => input('post.contact.mobile/s', '', 'trim,badword_filter'),
                'weixin' => input('post.contact.weixin/s', '', 'trim,badword_filter'),
                'email' => input('post.contact.email/s', '', 'trim,badword_filter'),
                'qq' => input('post.contact.qq/s', '', 'trim,badword_filter')
            ],
            // 求职意向列表
            'intention_list' => input('post.intention_list/a', []),
            // 教育经历列表
            'education_list' => input('post.education_list/a', []),
            // 工作经历列表
            'work_list' => input('post.work_list/a', []),
            // 项目经历列表
            'project_list' => input('post.project_list/a', []),
            // 培训经历列表
            'training_list' => input('post.training_list/a', []),
            // 获得证书列表
            'certificate_list' => input('post.certificate_list/a', []),
            // 语言能力列表
            'language_list' => input('post.language_list/a', []),
            // 作品列表
            'img_list' => input('post.img_list/a', []),
            // 附件简历
            'enclosure_id' => input('post.enclosure_id/d', 0, 'intval'),
        ];

        if (!isset($input_data['enclosure_id']) || empty($input_data['enclosure_id'])){
            $this->ajaxReturn(500, '请先上传附件简历');
        }

        $input_data['basic']['enter_job_time'] =
            $input_data['basic']['enter_job_time'] == ''
                ? 0
                : strtotime($input_data['basic']['enter_job_time']);

        // 处理特长标签
        if (isset($input_data['basic']['tag']) && !empty($input_data['basic']['tag'])) {
            $input_data['basic']['tag'] = array_unique($input_data['basic']['tag']);
            $input_data['basic']['tag'] = implode(',', $input_data['basic']['tag']);
            $is_tag = 1;
        } else {
            $input_data['basic']['tag'] = '';
            $is_tag = 0;
        }

        // 处理自我描述
        if (isset($input_data['basic']['specialty']) && !empty($input_data['basic']['specialty'])) {
            $is_specialty = 1;
        } else {
            $input_data['basic']['specialty'] = '';
            $is_specialty = 0;
        }

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
            // DB-1.基本信息
            if ($basic_info !== null) {
                $basicResult = model('Resume')
                    ->validate('Resume.reg_from_app_by_form')
                    ->allowField(true)
                    ->save($input_data['basic'], [
                        'uid' => $this->userinfo->uid
                    ]);
            } else {
                $input_data['basic']['platform'] = config('platform');
                /**
                 * 为解决TP框架，数据自动完成
                 * 故先保存，紧接更新
                 */
                $basicResult_save = model('Resume')
                    ->validate('Resume.reg_from_app_by_form')
                    ->allowField(true)
                    ->save($input_data['basic']);
                $resume_id = model('Resume')->id;
                $basicResult_update = model('Resume')
                    ->validate('Resume.reg_from_app_by_form')
                    ->allowField(true)
                    ->save($input_data['basic'],
                        ['id' => $resume_id]);
            }
            if (false === $basicResult_save || false === $basicResult_update) {
                throw new \Exception(model('Resume')->getError());
            }

            if (isset($input_data['basic']['photo_img']) && !empty($input_data['basic']['photo_img'])) {
                model('Task')->doTask(
                    $this->userinfo->uid,
                    $this->userinfo->utype,
                    'upload_photo'
                );
            }

            // DB-2.联系方式
            $input_data['contact']['rid'] = $resume_id;
            if (null === $contact_info) {
                $contactResult = model('ResumeContact')
                    ->validate('ResumeContact.reg_from_app_by_form')
                    ->allowField(true)
                    ->save($input_data['contact']);
            } else {
                $contactResult = model('ResumeContact')
                    ->validate('ResumeContact.reg_from_app_by_form')
                    ->allowField(true)
                    ->save($input_data['contact'], [
                        'id' => $contact_info['id']
                    ]);
            }
            if (false === $contactResult) {
                throw new \Exception(model('ResumeContact')->getError());
            }

            // DB-3.求职意向
            if (isset($input_data['intention_list']) && !empty($input_data['intention_list'])) {
                if (count($input_data['intention_list']) >= 3) {
                    $this->ajaxReturn(500, '求职意向最多可填写三条');
                }
                $intention_data = [];
                foreach ($input_data['intention_list'] as $intention) {
                    $intention['district'] =
                        $intention['district3'] > 0
                            ? $intention['district3']
                            : ($intention['district2'] > 0
                            ? $intention['district2']
                            : $intention['district1']);
                    $intention['category'] =
                        $intention['category3'] > 0
                            ? $intention['category3']
                            : ($intention['category2'] > 0
                            ? $intention['category2']
                            : $intention['category1']);
                    $intention_data[] = [
                        'rid' => $resume_id,
                        'uid' => $this->userinfo->uid,
                        'nature' => $intention['nature'],
                        'district1' => $intention['district1'],
                        'district2' => $intention['district2'],
                        'district3' => $intention['district3'],
                        'district' => $intention['district'],
                        'category1' => $intention['category1'],
                        'category2' => $intention['category2'],
                        'category3' => $intention['category3'],
                        'category' => $intention['category'],
                        'minwage' => $intention['minwage'],
                        'maxwage' => $intention['maxwage'],
                        'trade' => $intention['trade'],
                    ];
                }
                $intentionResult = model('ResumeIntention')
                    ->validate(true)
                    ->allowField(true)
                    ->saveAll($intention_data);
                if (false === $intentionResult) {
                    throw new \Exception(model('ResumeIntention')->getError());
                }
                $is_intention = 1;
            } else {
                $is_intention = 0;
            }

            // DB-4.教育经历
            if (isset($input_data['education_list']) && !empty($input_data['education_list'])) {
                $education_data = [];
                foreach ($input_data['education_list'] as $education) {
                    $education['starttime'] = strtotime($education['starttime']);
                    if ($education['todate'] == 1) {
                        $education['endtime'] = 0;
                    } else {
                        $education['endtime'] = strtotime($education['endtime']);
                    }
                    $education_data[] = [
                        'rid' => $resume_id,
                        'uid' => $this->userinfo->uid,
                        'starttime' => $education['starttime'],
                        'endtime' => $education['endtime'],
                        'todate' => $education['todate'],
                        'school' => $education['school'],
                        'major' => $education['major'],
                        'education' => $education['education']
                    ];
                }
                $educationResult = model('ResumeEducation')
                    ->validate(true)
                    ->allowField(true)
                    ->saveAll($education_data);
                if (false === $educationResult) {
                    throw new \Exception(model('ResumeEducation')->getError());
                }
                $is_education = 1;
            } else {
                $is_education = 0;
            }

            // DB-5.工作经历
            if (isset($input_data['work_list']) && !empty($input_data['work_list'])) {
                $work_data = [];
                foreach ($input_data['work_list'] as $work) {
                    $work['starttime'] = strtotime($work['starttime']);
                    if ($work['todate'] == 1) {
                        $work['endtime'] = 0;
                    } else {
                        $work['endtime'] = strtotime($work['endtime']);
                    }
                    $work_data[] = [
                        'rid' => $resume_id,
                        'uid' => $this->userinfo->uid,
                        'starttime' => $work['starttime'],
                        'endtime' => $work['endtime'],
                        'todate' => $work['todate'],
                        'companyname' => $work['companyname'],
                        'jobname' => $work['jobname'],
                        'duty' => $work['duty']
                    ];
                }
                $workResult = model('ResumeWork')
                    ->validate(true)
                    ->allowField(true)
                    ->saveAll($work_data);
                if (false === $workResult) {
                    throw new \Exception(model('ResumeWork')->getError());
                }
                $is_work = 1;
            } else {
                $is_work = 0;
            }

            // DB-6.项目经历
            if (isset($input_data['project_list']) && !empty($input_data['project_list'])) {
                $project_data = [];
                foreach ($input_data['project_list'] as $project) {
                    $project['starttime'] = strtotime($project['starttime']);
                    if ($project['todate'] == 1) {
                        $project['endtime'] = 0;
                    } else {
                        $project['endtime'] = strtotime($project['endtime']);
                    }
                    $project_data[] = [
                        'rid' => $resume_id,
                        'uid' => $this->userinfo->uid,
                        'starttime' => $project['starttime'],
                        'endtime' => $project['endtime'],
                        'todate' => $project['todate'],
                        'projectname' => $project['projectname'],
                        'role' => $project['role'],
                        'description' => $project['description']
                    ];
                }
                $projectResult = model('ResumeProject')
                    ->validate(true)
                    ->allowField(true)
                    ->saveAll($project_data);
                if (false === $projectResult) {
                    throw new \Exception(model('ResumeProject')->getError());
                }
                $is_project = 1;
            } else {
                $is_project = 0;
            }

            // DB-7.培训经历
            if (isset($input_data['training_list']) && !empty($input_data['training_list'])) {
                $training_data = [];
                foreach ($input_data['training_list'] as $training) {
                    $training['starttime'] = strtotime($training['starttime']);
                    if ($training['todate'] == 1) {
                        $training['endtime'] = 0;
                    } else {
                        $training['endtime'] = strtotime($training['endtime']);
                    }
                    $training_data[] = [
                        'rid' => $resume_id,
                        'uid' => $this->userinfo->uid,
                        'starttime' => $training['starttime'],
                        'endtime' => $training['endtime'],
                        'todate' => $training['todate'],
                        'agency' => $training['agency'],
                        'course' => $training['course'],
                        'description' => $training['description'],
                    ];
                }
                $trainingResult = model('ResumeTraining')
                    ->validate(true)
                    ->allowField(true)
                    ->saveAll($training_data);
                if (false === $trainingResult) {
                    throw new \Exception(model('ResumeTraining')->getError());
                }
                $is_training = 1;
            } else {
                $is_training = 0;
            }

            // DB-8.获得证书
            if (isset($input_data['certificate_list']) && !empty($input_data['certificate_list'])) {
                $certificate_data = [];
                foreach ($input_data['certificate_list'] as $certificate) {
                    $certificate_data[] = [
                        'rid' => $resume_id,
                        'uid' => $this->userinfo->uid,
                        'name' => $certificate['name'],
                        'obtaintime' => strtotime($certificate['obtaintime'])
                    ];
                }
                $certificateResult = model('ResumeCertificate')
                    ->validate(true)
                    ->allowField(true)
                    ->saveAll($certificate_data);
                if (false === $certificateResult) {
                    throw new \Exception(model('ResumeCertificate')->getError());
                }
                $is_certificate = 1;
            } else {
                $is_certificate = 0;
            }

            // DB-9.语言能力
            if (isset($input_data['language_list']) && !empty($input_data['language_list'])) {
                if (count($input_data['language_list']) >= 6) {
                    $this->ajaxReturn(500, '最多可添加6种语言');
                }
                $language_data = [];
                foreach ($input_data['language_list'] as $language) {
                    $language_data[] = [
                        'rid' => $resume_id,
                        'uid' => $this->userinfo->uid,
                        'language' => $language['language'],
                        'level' => $language['level']
                    ];
                }
                $languageResult = model('ResumeLanguage')
                    ->validate(true)
                    ->allowField(true)
                    ->saveAll($language_data);
                if (false === $languageResult) {
                    throw new \Exception(model('ResumeLanguage')->getError());
                }
                $is_language = 1;
            } else {
                $is_language = 0;
            }

            // DB-10.我的作品
            if (isset($input_data['img_list']) && !empty($input_data['img_list'])) {
                if (count($input_data['img_list']) >= 6) {
                    $this->ajaxReturn(500, '最多上传6张作品');
                }
                $img_data = [];
                foreach ($input_data['img_list'] as $img) {
                    $img_data[] = [
                        'rid' => $resume_id,
                        'uid' => $this->userinfo->uid,
                        'img' => $img['file_id'],
                        'title' => '',
                        'addtime' => time(),
                        'audit' => 0,
                    ];
                }
                $imgResult = model('ResumeImg')->saveAll($img_data);
                if (false === $imgResult) {
                    throw new \Exception(model('ResumeImg')->getError());
                }
                $is_img = 1;
            } else {
                $is_img = 0;
            }

            // DB-11.附件简历
            $enclosure_data = [
                'rid' => $resume_id,
                'uid' => $this->userinfo->uid,
                'enclosure' => $input_data['enclosure_id'],
                'title' => '附件简历'.date('Ymd'),
                'addtime' => time(),
                'audit' => 1
            ];
            $enclosureResult = model('ResumeEnclosure')
                ->allowField(true)
                ->save($enclosure_data);
            if (false === $enclosureResult) {
                throw new \Exception(model('ResumeEnclosure')->getError());
            }

            //更新完整度
            model('Resume')->updateComplete(
                [
                    'basic' => 1,
                    'intention' => $is_intention,
                    'tag' => $is_tag,
                    'specialty' => $is_specialty,
                    'education' => $is_education,
                    'work' => $is_work,
                    'project' => $is_project,
                    'training' => $is_training,
                    'certificate' => $is_certificate,
                    'language' => $is_language,
                    'img' => $is_img,
                ],
                $resume_id,
                $this->userinfo->uid
            );

            //提交事务
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->ajaxReturn(500, $e->getMessage());
        }

        model('Resume')->refreshSearch($resume_id);

        $this->writeMemberActionLog($this->userinfo->uid, '注册 - 导入创建在线简历');
        $this->ajaxReturn(200, '保存成功');
    }
}
