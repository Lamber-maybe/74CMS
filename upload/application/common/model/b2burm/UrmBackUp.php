<?php

namespace app\common\model\b2burm;

use app\common\model\BaseModel;
use Think\Db;

class UrmBackUp extends BaseModel
{
    // 主键
    protected $pk = 'id';

    // 只读字段
    protected $readonly = [
        'id'
    ];

    // 数据类型
    protected $type = [
        'id' => 'integer',
        'create_time' => 'integer',
    ];

    // 设置返回数据集的对象名
    protected $resultSetType = 'collection';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    /**
     * 需要操作的表
     * @var array
     */
    protected $operateModel = [
        [
            'model' => 'Company',
            'db'=>'company',
            'pk' => 'uid'
        ],
        [
            'model' => 'CompanyAuth',
            'db'=>'company_auth',
            'pk' => 'uid'
        ],
        [
            'model' => 'CompanyAuthLog',
            'db'=>'company_auth_log',
            'pk' => 'uid'
        ],
        [
            'model' => 'CompanyContact',
            'db'=>'company_contact',
            'pk' => 'uid'
        ],
        [
            'model' => 'CompanyDownResume',
            'db'=>'company_down_resume',
            'pk' => 'uid'
        ],
        [
            'model' => 'CompanyImg',
            'db'=>'company_img',
            'pk' => 'uid'
        ],
        [
            'model' => 'CompanyInfo',
            'db'=>'company_info',
            'pk' => 'uid'
        ],
        [
            'model' => 'CompanyInterview',
            'db'=>'company_interview',
            'pk' => 'uid'
        ],
        [
            'model' => 'CompanyInterviewVideo',
            'db'=>'company_interview_video',
            'pk' => 'uid'
        ],
        [
            'model' => 'CompanyReport',
            'db'=>'company_report',
            'pk' => 'uid'
        ],
        [
            'model' => 'CouponRecord',
            'db'=>'coupon_record',
            'pk' => 'uid'
        ],
        [
            'model' => 'CustomerServiceComplaint',
            'db'=>'customer_service_complaint',
            'pk' => 'uid'
        ],
        [
            'model' => 'Entrust',
            'db'=>'entrust',
            'pk' => 'uid'
        ],
        [
            'model' => 'FavJob',
            'db'=>'fav_job',
            'pk' => 'company_uid'
        ],
        [
            'model' => 'FavJob',
            'db'=>'fav_job',
            'pk' => 'personal_uid'
        ],
        [
            'model' => 'FavResume',
            'db'=>'fav_resume',
            'pk' => 'company_uid'
        ],
        [
            'model' => 'FavResume',
            'db'=>'fav_resume',
            'pk' => 'personal_uid'
        ],
        [
            'model' => 'Feedback',
            'db'=>'feedback',
            'pk' => 'uid'
        ],
        [
            'model' => 'JobApply',
            'db'=>'job_apply',
            'pk' => 'company_uid'
        ],
        [
            'model' => 'JobApply',
            'db'=>'job_apply',
            'pk' => 'personal_uid'
        ],
        [
            'model' => 'MarketQueue',
            'db'=>'market_queue',
            'pk' => 'uid'
        ],
        [
            'model' => 'Member',
            'db'=>'member',
            'pk' => 'uid'
        ],
        [
            'model' => 'MemberBind',
            'db'=>'member_bind',
            'pk' => 'uid'
        ],
        [
            'model' => 'MemberActionLog',
            'db'=>'member_action_log',
            'pk' => 'uid'
        ],
        [
            'model' => 'MemberSetmeal',
            'db'=>'member_setmeal',
            'pk' => 'uid'
        ],
        [
            'model' => 'MemberSetmealLog',
            'db'=>'member_setmeal_log',
            'pk' => 'uid'
        ],
        [
            'model' => 'MemberPoints',
            'db'=>'member_points',
            'pk' => 'uid'
        ],
        [
            'model' => 'MemberPointsLog',
            'db'=>'member_points_log',
            'pk' => 'uid'
        ],
        [
            'model' => 'Message',
            'db'=>'message',
            'pk' => 'uid'
        ],
        [
            'model' => 'RefreshJobLog',
            'db'=>'refresh_job_log',
            'pk' => 'uid'
        ],
        [
            'model' => 'RefreshResumeLog',
            'db'=>'refresh_resume_log',
            'pk' => 'uid'
        ],
        [
            'model' => 'RefreshjobQueue',
            'db'=>'refreshjob_queue',
            'pk' => 'uid'
        ],
        [
            'model' => 'Resume',
            'db'=>'resume',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeAuditLog',
            'db' => 'resume_audit_log',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeCertificate',
            'db' => 'resume_certificate',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeComplete',
            'db' => 'resume_complete',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeContact',
            'db' => 'resume_contact',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeEducation',
            'db' => 'resume_education',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeImg',
            'db' => 'resume_img',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeIntention',
            'db' => 'resume_intention',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeLanguage',
            'db' => 'resume_language',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeProject',
            'db' => 'resume_project',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeSearchKey',
            'db' => 'resume_search_key',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeSearchRtime',
            'db' => 'resume_search_rtime',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeTraining',
            'db' => 'resume_training',
            'pk' => 'uid'
        ],
        [
            'model' => 'ResumeWork',
            'db' => 'resume_work',
            'pk' => 'uid'
        ],
        [
            'model' => 'Shield',
            'db' => 'shield',
            'pk' => 'company_uid'
        ],
        [
            'model' => 'Shield',
            'db' => 'shield',
            'pk' => 'personal_uid'
        ],
        [
            'model' => 'StatViewJob',
            'db' => 'stat_view_job',
            'pk' => 'company_uid'
        ],
        [
            'model' => 'StatViewJob',
            'db' => 'stat_view_job',
            'pk' => 'personal_uid'
        ],
        [
            'model' => 'SubscribeJob',
            'db' => 'subscribe_job',
            'pk' => 'uid'
        ],
        [
            'model' => 'TaskRecord',
            'db' => 'task_record',
            'pk' => 'uid'
        ],
        [
            'model' => 'Tipoff',
            'db' => 'tipoff',
            'pk' => 'uid'
        ],
        [
            'model' => 'ViewJob',
            'db' => 'view_job',
            'pk' => 'company_uid'
        ],
        [
            'model' => 'ViewJob',
            'db' => 'view_job',
            'pk' => 'personal_uid'
        ],
        [
            'model' => 'ViewResume',
            'db' => 'view_resume',
            'pk' => 'company_uid'
        ],
        [
            'model' => 'ViewResume',
            'db' => 'view_resume',
            'pk' => 'personal_uid'
        ],
        [
            'model' => 'Job',
            'db' => 'job',
            'pk' => 'uid'
        ],
        [
            'model' => 'JobContact',
            'db' => 'job_contact',
            'pk' => 'uid'
        ],
        [
            'model' => 'JobSearchKey',
            'db' => 'job_search_key',
            'pk' => 'uid'
        ],
        [
            'model' => 'JobSearchRtime',
            'db' => 'job_search_rtime',
            'pk' => 'uid'
        ],
        [
            'model' => 'b2bcrm.CrmClue',
            'db' => 'crm_clue',
            'pk' => 'uid'
        ],
        [
            'model' => 'b2bcrm.CrmClueRelease',
            'db' => 'crm_clue_release',
            'pk' => 'uid'
        ],
        [
            'model' => 'b2bcrm.CrmCompanyContact',
            'db' => 'crm_company_contact',
            'pk' => 'uid'
        ],
        [
            'model' => 'b2bcrm.CrmFollowUp',
            'db' => 'crm_follow_up',
            'pk' => 'uid'
        ]
    ];
    /**
     * 通过会员ID备份会员数据
     * @param $uid
     * @param $recycleBinId
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function backUpByUid($uid, $recycleBinId)
    {
        $insert_data = [];
        foreach ($this->operateModel as $model) {
            $data = Db::name($model['db'])
                ->where($model['pk'], $uid)
                ->select();
            if (isset($data) && !empty($data)) {
                $insert_data[] = [
                    'model' => $model['model'],
                    'value' => json_encode($data),
                    'recycle_bin_id' => $recycleBinId
                ];
            }
        }

        if (empty($insert_data)) {
            throw new \Exception('备份数据异常');
        }
        $insert_result = $this->allowField(true)
            ->isUpdate(false)
            ->saveAll($insert_data);
        if (false === $insert_result) {
            throw new \Exception('备份数据失败');
        } else {
            return true;
        }
    }
    /**
     * 通过回收站ID清空备份数据
     * @param $recycleBinId
     * @return false|float|int|mixed|string
     */
    public function clear($recycleBinId)
    {
        return $this->where('recycle_bin_id', $recycleBinId)
            ->delete();
    }

    /**
     * 通过回收站ID还原备份数据
     * @param $recycleBinId
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function restore($recycleBinId)
    {
        $backup_data = $this->where('recycle_bin_id', $recycleBinId)
            ->select();

        if (empty($backup_data)) {
            throw new \Exception('备份数据异常，恢复失败');
        }else{
            $backup_data = $backup_data->toArray();
        }
        foreach ($backup_data as $data) {
            $result = model($data['model'])
                ->allowField(true)
                ->isUpdate(false)
                ->insertAll(json_decode($data['value'], true));
            if (false === $result) {
                throw new \Exception('恢复数据错误');
            }
        }

        return true;
    }
}