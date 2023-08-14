<?php
namespace app\common\model;

class CompanyDownResume extends \app\common\model\BaseModel
{
    protected $readonly = [
        'id',
        'comid',
        'uid',
        'personal_uid',
        'resume_id',
        'addtime',
    ];
    protected $type = [
        'id' => 'integer',
        'uid' => 'integer',
        'comid' => 'integer',
        'personal_uid' => 'integer',
        'resume_id' => 'integer',
        'addtime' => 'integer',
    ];
    public function downResumeAdd($data, $company_uid)
    {
        $return_data = [
            'status' => 1,
            'msg' => '',
            'done' => 1,
            'need_upgrade' => 0
        ];
        do {
            if (!isset($data['resume_id']) || !$data['resume_id']) {
                $return_data['status'] = 0;
                $return_data['msg'] = '请选择简历';
                $return_data['done'] = 1;
                break;
            }
            $resume_id = intval($data['resume_id']);
            $member_info = model('Member')
                ->where('uid', $company_uid)
                ->find();
            if ($member_info['status'] == 0) {
                $return_data['status'] = 0;
                $return_data['msg'] = '您的账号处于暂停状态，请联系管理员设为正常后进行操作';
                $return_data['done'] = 1;
                break;
            }
            $resume_info = model('Resume')
                ->field('audit,high_quality,uid,refreshtime,fullname')
                ->where('id', 'eq', $resume_id)
                ->find();
            if (null === $resume_info) {
                $return_data['status'] = 0;
                $return_data['msg'] = '没有找到简历信息';
                $return_data['done'] = 1;
                break;
            }
            if($resume_info['audit']!=1){
                $return_data['status'] = 0;
                $return_data['msg'] = '该简历还没有审核通过，无法继续此操作';
                $return_data['done'] = 1;
                break;
            }
            if (
                null !==
                $this->where([
                    'uid' => $company_uid,
                    'resume_id' => $resume_id,
                ])
                ->field('id')
                ->find()
            ) {
                $return_data['status'] = 0;
                $return_data['msg'] = '你已经下载过该简历了';
                $return_data['done'] = 1;
                break;
            }
            $com_info = model('Company')
                ->field('id,audit,companyname')
                ->where(['uid' => $company_uid])
                ->find();
            $global_config = config('global_config');
            if ($global_config['down_resume_limit'] == 1) {
                //有在招职位
                $audit_job = model('Job')
                    ->field('id')
                    ->where([
                        'audit' => 1,
                        'is_display' => 1,
                        'uid' => $company_uid,
                    ])
                    ->find();
                if ($audit_job === null) {
                    $return_data['status'] = 0;
                    $return_data['msg'] = '当前没有有效的在招职位，无法进行下载操作';
                    $return_data['done'] = 1;
                    break;
                }
            } elseif ($global_config['down_resume_limit'] == 2) {
                //企业是否已认证
                if ($com_info['audit'] != 1) {
                    $return_data['status'] = 0;
                    $return_data['msg'] = '企业信息没有认证通过，无法进行下载操作';
                    $return_data['done'] = 1;
                    break;
                }
            }

            //修复 简历每日下载上限
            $member_setmeal = model('Member')->getMemberSetmeal($company_uid);
            if($member_setmeal['download_resume_max_perday']>0){
                $downnum = $this->where('uid',$company_uid)->where('addtime','egt',strtotime('today'))->count();
                if($downnum>=$member_setmeal['download_resume_max_perday']){
                    /**
                     * 【ID1000633】
                     * 【优化】快捷支付增加提示，引导客户购买增值包或升级套餐
                     * cy 2023-7-3
                     * 将提示改为弹窗形式
                     */
                    $return_data['status'] = 0;
                    // $return_data['msg'] = '您今天已下载 '.$downnum.' 份简历，已达到每天下载上限，请先收藏该简历，明天继续下载。';
                    $return_data['msg'] = '您今日已达下载简历上限，建议您收藏该简历，明天继续下载或升级套餐。';
                    $return_data['done'] = 0;
                    $return_data['need_upgrade'] = 1;
                    break;
                }
            }else{
                $return_data['status'] = 0;
                $return_data['msg'] = '很抱歉，您的套餐暂不支持下载简历，请升级套餐。';
                $return_data['done'] = 0;
                $return_data['need_upgrade'] = 1;
                break;
            }
            


            if ($resume_info['high_quality'] == 1) {
                $need_points = $global_config['resume_download_points_talent'];
            } else {
                $down_resume_points_config_arr =
                    $global_config['resume_download_points_conf'];
                $down_resume_points_config = [];
                foreach ($down_resume_points_config_arr as $key => $value) {
                    $down_resume_points_config[$value['alias']] = $value['value'];
                }

                if ($resume_info['refreshtime'] >= strtotime('-1 day')) {
                    //刷新时间1天之内
                    $need_points = $down_resume_points_config[1];
                } elseif ($resume_info['refreshtime'] >= strtotime('-3 day')) {
                    //刷新时间3天之内
                    $need_points = $down_resume_points_config[3];
                } elseif ($resume_info['refreshtime'] >= strtotime('-5 day')) {
                    //刷新时间5天之内
                    $need_points = $down_resume_points_config[5];
                } else {
                    //刷新时间5天以上
                    $need_points = $down_resume_points_config[0];
                }
            }

            //购买简历包修改 zch  2022/7/22

            $download_point = 0;//消耗套餐点数
            $purchase_poubt = 0;//消耗简历包点数
            $surplus_download_point = 0;//剩余套餐点数
            $surplus_purchase_poubt = 0;//剩余简历包点数

            if ($need_points > 0) {
                if ($member_setmeal['download_resume_point'] < $need_points) {
                    $return_data['status'] = 0;
                    $return_data['msg'] = '下载简历点数不足，无法进行下载操作';
                    $return_data['done'] = 0;
                    break;
                }

                if ($member_setmeal['setmeal_resume_point'] >= $need_points)
                {
                    // 套餐点数足够下载
                    $download_point = $need_points;
                    $surplus_download_point = $member_setmeal['setmeal_resume_point'] - $need_points;
                    $purchase_poubt = 0;
                    $surplus_purchase_poubt = $member_setmeal['purchase_resume_point'];
                }else
                {
                    //扣除套餐点数和简历包点数
                    $download_point = $member_setmeal['setmeal_resume_point'];
                    $surplus_download_point = 0;
                    $purchase_poubt = $need_points - $member_setmeal['setmeal_resume_point'];
                    $surplus_purchase_poubt = $member_setmeal['purchase_resume_point'] - $purchase_poubt;

                }
            }
            \think\Db::startTrans();
            try {
                $input_data['uid'] = $company_uid;
                $input_data['comid'] = $com_info['id'];
                $input_data['resume_id'] = $resume_id;
                $input_data['personal_uid'] = $resume_info['uid'];
                $input_data['addtime'] = time();
                $input_data['platform'] = config('platform');
                if (false === $this->save($input_data)) {
                    throw new \Exception($this->getError());
                }

                model('MemberSetmeal')->where('uid', $company_uid)->setDec('download_resume_point',$download_point);
                model('MemberSetmeal')->where('uid', $company_uid)->setDec('purchase_resume_point',$purchase_poubt);

                $log['uid'] = $company_uid;
                $log['content'] =
                    '下载简历-【' . $resume_info['fullname'] . '】，消耗套餐赠送下载点数 ' . $download_point . '，消耗简历包下载点数 ' . $purchase_poubt . '，剩余套餐赠送下载点数 ' . $surplus_download_point.'，剩余简历包下载点数 '.$surplus_purchase_poubt;
                $log['addtime'] = time();
                model('MemberSetmealLog')
                    ->allowField(true)
                    ->save($log);

                \think\Db::commit();
            } catch (\Exception $e) {
                \think\Db::rollBack();
                $return_data['status'] = 0;
                $return_data['done'] = 1;
                $return_data['msg'] = $e->getMessage();
                break;
            }
            //通知
            model('NotifyRule')->notify(
                $resume_info['uid'],
                2,
                'resume_down',
                [
                    'companyname' => $com_info['companyname'],
                ],
                $com_info['id']
            );
        } while (0);
        $return_data['resume_info'] = $resume_info;
        return $return_data;
    }
    public function downResumeAddSingleService($resume_id, $company_uid, $platform)
    {
        if (
            null !==
            $this->where([
                'uid' => $company_uid,
                'resume_id' => $resume_id,
            ])
            ->field('id')
            ->find()
        ) {
            return false;
        }
        $resume_info = model('Resume')
            ->field('uid')
            ->where('id', 'eq', $resume_id)
            ->find();

        $com_info = model('Company')
            ->field('id,companyname')
            ->where(['uid' => $company_uid])
            ->find();

        $input_data['uid'] = $company_uid;
        $input_data['comid'] = $com_info['id'];
        $input_data['resume_id'] = $resume_id;
        $input_data['personal_uid'] = $resume_info['uid'];
        $input_data['addtime'] = time();
        $input_data['platform'] = $platform;
        if (false === $this->save($input_data)) {
            return false;
        }
        //通知
        model('NotifyRule')->notify(
            $resume_info['uid'],
            2,
            'resume_down',
            [
                'companyname' => $com_info['companyname'],
            ],
            $com_info['id']
        );
        return true;
    }
}
