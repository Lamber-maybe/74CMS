<?php
namespace app\common\model;

class FavJob extends \app\common\model\BaseModel
{
    public function favJobAdd($data, $personal_uid)
    {
        if (!isset($data['jobid']) || !$data['jobid']) {
            $this->error = '请选择职位';
            return false;
        }
        $jobid = intval($data['jobid']);
        $member_info = model('Member')
            ->where('uid', $personal_uid)
            ->find();
        if ($member_info['status'] == 0) {
            $this->error =
                '您的账号处于暂停状态，请联系管理员设为正常后进行操作';
            return false;
        }
        $job_info = model('Job')
            ->field('uid,jobname')
            ->where('id', 'eq', $jobid)
            ->find();
        if (null === $job_info) {
            $this->error = '没有找到职位信息';
            return false;
        }
        if (
            null !==
            $this->where(['personal_uid' => $personal_uid, 'jobid' => $jobid])
                ->field('id')
                ->find()
        ) {
            $this->error = '你已经收藏过该职位了';
            return false;
        }
        $resume_info = model('Resume')
            ->field('id,fullname,audit')
            ->where('uid', $personal_uid)
            ->find();
        if (null === $resume_info) {
            $this->error = '请先创建一份简历';
            return false;
        }
        if ($resume_info['audit'] != 1) {
            $this->error ='你的简历还没有审核通过，请联系管理员审核通过后进行操作';
            return false;
        }
        $input_data['company_uid'] = $job_info['uid'];
        $input_data['personal_uid'] = $personal_uid;
        $input_data['jobid'] = $jobid;
        $input_data['addtime'] = time();
        $result = $this->save($input_data);
        if (false !== $result) {
            //通知
            model('NotifyRule')->notify(
                $job_info['uid'],
                1,
                'job_fav',
                [
                    'fullname' => $resume_info['fullname'],
                    'jobname' => $job_info['jobname']
                ],
                $resume_info['id']
            );
        }
        return $result;
    }
}
