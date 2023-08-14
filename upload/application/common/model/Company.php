<?php
namespace app\common\model;

class Company extends \app\common\model\BaseModel
{
    public $map_audit = [
        0 => '待审核',
        1 => '已认证',
        2 => '未通过'
    ];
    protected $insert = ['updatetime'];
    protected $update = ['updatetime'];
    protected $readonly = ['id', 'uid', 'addtime', 'robot'];
    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'nature' => 'integer',
        'trade' => 'integer',
        'district1' => 'integer',
        'district2' => 'integer',
        'district3' => 'integer',
        'scale' => 'integer',
        'currency' => 'integer',
        'map_zoom' => 'integer',
        'audit' => 'integer',
        'addtime' => 'integer',
        'refreshtime' => 'integer',
        'updatetime' => 'integer',
        'click' => 'integer',
        'robot' => 'integer'
    ];
    protected function setUpdatetimeAttr($value = null)
    {
        return $value === null ? time() : $value;
    }
    public function setUserStatus($uid, $status)
    {
        $model = $this->where('uid', $uid)->find();
        if (null === $model) {
            return;
        }
        $model->user_status = $status;
        $model->save();
        return;
    }
    public function backendAdd($data)
    {
        $data_member = $data['member'];
        $data_contact = $data['contact'];
        $data_info = $data['info'];
        $data_setmeal = $data['setmeal'];
        unset(
            $data['member'],
            $data['contact'],
            $data['info'],
            $data['setmeal']
        );
        $data_basic = $data;

        //开启事务
        \think\Db::startTrans();

        try {
            $data_member['pwd_hash'] = randstr();
            $data_member['password'] = model('Member')->makePassword(
                $data_member['password'],
                $data_member['pwd_hash']
            );
            $data_member['platform'] = config('platform');
            if (
                false ===
                model('Member')
                    ->validate(true)
                    ->allowField(true)
                    ->save($data_member)
            ) {
                throw new \Exception(model('Member')->getError());
            }
            $data_basic['uid'] = model('Member')->uid;
            $data_basic['district'] =
            $data_basic['district3'] > 0
            ? $data_basic['district3']
            : ($data_basic['district2'] > 0
                ? $data_basic['district2']
                : $data_basic['district1']);

            $data_basic['tag'] =
                isset($data_basic['tag']) && !empty($data_basic['tag'])
                    ? implode(',', $data_basic['tag'])
                    : '';
            $data_basic['map_lat'] = isset($data_basic['map_lat'])
                ? $data_basic['map_lat']
                : 0;
            $data_basic['map_lng'] = isset($data_basic['map_lng'])
                ? $data_basic['map_lng']
                : 0;
            $data_basic['map_zoom'] = isset($data_basic['map_zoom'])
                ? $data_basic['map_zoom']
                : config('global_config.map_zoom');
            $data_basic['addtime'] = time();
            $data_basic['refreshtime'] = $data_basic['addtime'];
            $data_basic['click'] = 0;
            $data_basic['robot'] = 0;
            $data_basic['user_status'] = 1;
            $data_basic['platform'] = config('platform');
            if (
                false ===
                model('Company')
                    ->validate(true)
                    ->allowField(true)
                    ->save($data_basic)
            ) {
                throw new \Exception(model('Company')->getError());
            }
            $data_info['comid'] = model('Company')->id;
            $data_info['uid'] = model('Member')->uid;
            if (
                false ===
                model('CompanyInfo')
                    ->validate(true)
                    ->allowField(true)
                    ->save($data_info)
            ) {
                throw new \Exception(model('CompanyInfo')->getError());
            }

            $data_contact['comid'] = model('Company')->id;
            $data_contact['uid'] = model('Member')->uid;
            if (
                false ===
                model('CompanyContact')
                    ->validate(true)
                    ->allowField(true)
                    ->save($data_contact)
            ) {
                throw new \Exception(model('CompanyContact')->getError());
            }
            //赠送套餐
            $data_setmeal['uid'] = model('Member')->uid;
            $data_setmeal['note'] =
                $data_setmeal['charge'] > 0
                    ? '已收费' . $data_setmeal['charge'] . '元'
                    : '';
            model('Member')->setMemberSetmeal($data_setmeal);
            model('Task')->doTask(model('Member')->uid, 1, 'reg');
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }
        return true;
    }
    public function backendEdit($data)
    {
        $company_id = $data['id'];
        $data_contact = $data['contact'];
        $data_info = $data['info'];
        unset($data['contact'], $data['info']);
        $data_basic = $data;
        if(isset($data_basic['district3'])){
            $data_basic['district'] =
                $data_basic['district3'] > 0
                ? $data_basic['district3']
                : ($data_basic['district2'] > 0
                    ? $data_basic['district2']
                    : $data_basic['district1']);
        }
        
        if (isset($data_basic['tag'])) {
            $data_basic['tag'] = !empty($data_basic['tag'])
                ? implode(',', $data_basic['tag'])
                : '';
        }
        $company_info_before_save = $this->find($company_id);

        //开启事务
        \think\Db::startTrans();

        try {
            if (
                false ===
                model('Company')
                    ->validate(true)
                    ->allowField(true)
                    ->save($data_basic, ['id' => $company_id])
            ) {
                throw new \Exception(model('Company')->getError());
            }
            if (
                false ===
                model('CompanyInfo')
                    ->validate(true)
                    ->allowField(true)
                    ->save($data_info, ['comid' => $company_id])
            ) {
                throw new \Exception(model('CompanyInfo')->getError());
            }

            if (
                false ===
                model('CompanyContact')
                    ->validate(true)
                    ->allowField(true)
                    ->save($data_contact, ['comid' => $company_id])
            ) {
                throw new \Exception(model('CompanyContact')->getError());
            }
            //提交事务
            \think\Db::commit();
        } catch (\Exception $e) {
            \think\Db::rollBack();
            $this->error = $e->getMessage();
            return false;
        }
        //更新职位索引表对应字段，如企业名称，企业性质等
        $update_search_table = [];
        if (
            $company_info_before_save['companyname'] !=
            $data_basic['companyname']
        ) {
            $update_search_table['companyname'] = $data_basic['companyname'];
        }
        if ($company_info_before_save['nature'] != $data_basic['nature']) {
            $category_nature = model('Category')->getCache('QS_company_type');
            $update_search_table['company_nature_id'] = $data_basic['nature'];
            $update_search_table['company_nature'] =
                $category_nature[$data_basic['nature']];
        }
        if (!empty($update_search_table)) {
            model('JobSearchKey')->save($update_search_table, [
                'company_id' => $company_id
            ]);
        }

        return true;
    }
    /**
     * 认证企业
     */
    public function setAudit($idarr, $audit, $reason = '')
    {
        $timestamp = time();
        $company_data = [
            'audit'=>$audit
        ];
        model('Company')
            ->where(['id' => ['in', $idarr]])
            ->update($company_data);
        model('JobSearchKey')
            ->where(['company_id' => ['in', $idarr]])
            ->setField('license', $audit);
        model('JobSearchRtime')
            ->where(['company_id' => ['in', $idarr]])
            ->setField('license', $audit);

        $uid_arr = [];
        $audit_log = [];
        $company_list = $this->where('id', 'in', $idarr)->column(
            'id,uid',
            'id'
        );
        foreach ($company_list as $key => $value) {
            $uid_arr[] = $value;
            $arr['uid'] = $value;
            $arr['comid'] = $key;
            $arr['audit'] = $audit;
            $arr['reason'] = $reason;
            $arr['addtime'] = $timestamp;
            $audit_log[] = $arr;
        }
        model('CompanyAuthLog')->saveAll($audit_log);

        //完成企业认证任务
        if ($audit == 1) {
            foreach ($uid_arr as $key => $value) {
                model('Task')->doTask($value, 1, 'auth');
            }
        }

        //通知
        if ($audit == 1) {
            model('NotifyRule')->notify($uid_arr, 1, 'company_auth_success');
            //微信通知
            model('WechatNotifyRule')->notify(
                $uid_arr,
                1,
                'company_auth_success',
                [
                    '您提交的企业认证资料已认证通过。',
                    '通过审核',
                    date('Y年m月d日 H:i',$timestamp),
                    '点击进入会员中心'
                ],
                'member/company/index'
            );
        }
        if ($audit == 2) {
            model('NotifyRule')->notify($uid_arr, 1, 'company_auth_fail', [
                'reason' => $reason
            ]);
            model('WechatNotifyRule')->notify(
                $uid_arr,
                1,
                'company_auth_fail',
                [
                    '您提交的企业认证未通过审核。',
                    '审核未通过',
                    date('Y年m月d日 H:i',$timestamp),
                    $reason?$reason:'无',
                    '请修改后再次发布，点击去修改'
                ],
                'member/company/auth'
            );
        }
        return;
    }
    /**
     * 增加查看数
     */
    public function addViewLog($companyid)
    {
        $rand_click = config('global_config.rand_click_company');
        $rand_click = intval($rand_click);
        if($rand_click<=1){
            $rand_click = 1;
        }else{
            $rand_click = rand(1,$rand_click);
        }
        $this->where('id', 'eq', $companyid)->setInc('click',$rand_click);
    }
}
