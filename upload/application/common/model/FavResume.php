<?php
namespace app\common\model;

class FavResume extends \app\common\model\BaseModel
{
    public function favResumeAdd($data, $company_uid)
    {
        if (!isset($data['resume_id']) || !$data['resume_id']) {
            $this->error = '请选择简历';
            return false;
        }
        $resume_id = intval($data['resume_id']);
        $member_info = model('Member')
            ->where('uid', $company_uid)
            ->find();
        if ($member_info['status'] == 0) {
            $this->error =
                '您的账号处于暂停状态，请联系管理员设为正常后进行操作';
            return false;
        }
        $resume_info = model('Resume')
            ->field('uid')
            ->where('id', 'eq', $resume_id)
            ->find();
        if (null === $resume_info) {
            $this->error = '没有找到简历信息';
            return false;
        }
        if (
            null !==
            $this->where([
                'company_uid' => $company_uid,
                'resume_id' => $resume_id
            ])
                ->field('id')
                ->find()
        ) {
            $this->error = '你已经收藏过该简历了';
            return false;
        }
        $input_data['company_uid'] = $company_uid;
        $input_data['personal_uid'] = $resume_info['uid'];
        $input_data['resume_id'] = $resume_id;
        $input_data['addtime'] = time();
        $result = $this->save($input_data);
        if (false !== $result) {
            //通知
            $company_info = model('Company')
                ->field('id,companyname')
                ->where('uid', $company_uid)
                ->find();
            model('NotifyRule')->notify(
                $resume_info['uid'],
                2,
                'resume_fav',
                [
                    'companyname' => $company_info['companyname']
                ],
                $company_info['id']
            );
        }
        return $result;
    }
}
