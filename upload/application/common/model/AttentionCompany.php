<?php
namespace app\common\model;

class AttentionCompany extends \app\common\model\BaseModel
{
    public function AttentionCompanyAdd($data, $personal_uid)
    {
        if (!isset($data['comid']) || !$data['comid']) {
            $this->error = '请选择企业';
            return false;
        }
        $comid = intval($data['comid']);
        $member_info = model('Member')
            ->where('uid', $personal_uid)
            ->find();
        if ($member_info['status'] == 0) {
            $this->error =
                '您的账号处于暂停状态，请联系管理员设为正常后进行操作';
            return false;
        }
        $com_info = model('Company')
            ->field('uid')
            ->where('id', 'eq', $comid)
            ->find();
        if (null === $com_info) {
            $this->error = '没有找到企业信息';
            return false;
        }
        if (
            null !==
            $this->where(['personal_uid' => $personal_uid, 'comid' => $comid])
                ->field('id')
                ->find()
        ) {
            $this->error = '你已经关注过该企业了';
            return false;
        }
        $input_data['company_uid'] = $com_info['uid'];
        $input_data['personal_uid'] = $personal_uid;
        $input_data['comid'] = $comid;
        $input_data['addtime'] = time();
        $result = $this->save($input_data);
        if (false !== $result) {
            //通知
            $resume_info = model('Resume')
                ->field('id,fullname')
                ->where('uid', $personal_uid)
                ->find();
            model('NotifyRule')->notify(
                $com_info['uid'],
                1,
                'company_intention',
                [
                    'fullname' => $resume_info['fullname']
                ],
                $resume_info['id']
            );
        }
        return $result;
    }
}
