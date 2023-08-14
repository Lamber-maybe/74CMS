<?php
namespace app\v1_0\controller\company;

class Auth extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        $this->checkLogin(1);
        $this->interceptCompanyProfile();
    }
    /**
     * 获取企业认证信息
     */
    public function index()
    {
        $audit_text = [
            0 => '未提交审核',
            1 => '审核通过',
            2 => '审核不通过',
            3 => '已提交资料但待审核'
        ];
        $return = [
            'audit' => 0,
            'audit_text' => '',
            'audit_reason' => '',
            'companyname'=>'',
            'legal_person_idcard_front'=>'',
            'legal_person_idcard_front_img'=>'',
            'legal_person_idcard_back'=>'',
            'legal_person_idcard_back_img'=>'',
            'license'=>'',
            'license_img'=>'',
            'proxy'=>'',
            'proxy_img'=>''
        ];
        $auth = model('CompanyAuth')
                ->where('uid', $this->userinfo->uid)
                ->find();
        if($auth!==null){
            $return['legal_person_idcard_front'] = $auth['legal_person_idcard_front'].'';
            $return['legal_person_idcard_back'] = $auth['legal_person_idcard_back'].'';
            $return['license'] = $auth['license'].'';
            $return['proxy'] = $auth['proxy'].'';
        }
        $img_id_arr = $img_arr = [];
        if($return['legal_person_idcard_front']){
            $img_id_arr[] = $return['legal_person_idcard_front'];
        }
        if($return['legal_person_idcard_back']){
            $img_id_arr[] = $return['legal_person_idcard_back'];
        }
        if($return['license']){
            $img_id_arr[] = $return['license'];
        }
        if($return['proxy']){
            $img_id_arr[] = $return['proxy'];
        }
        if(!empty($img_id_arr)){
            $img_arr = model('Uploadfile')->getFileUrlBatch(
                $img_id_arr
            );
        }
        if(isset($img_arr[$return['legal_person_idcard_front']])){
            $return['legal_person_idcard_front_img'] = $img_arr[$return['legal_person_idcard_front']];
        }
        if(isset($img_arr[$return['legal_person_idcard_back']])){
            $return['legal_person_idcard_back_img'] = $img_arr[$return['legal_person_idcard_back']];
        }
        if(isset($img_arr[$return['license']])){
            $return['license_img'] = $img_arr[$return['license']];
        }
        if(isset($img_arr[$return['proxy']])){
            $return['proxy_img'] = $img_arr[$return['proxy']];
        }

        
        
        if ($this->company_profile['audit'] == 0) {
            //待审核
            if ($auth === null) {
                //未提交审核
                $return['audit'] = 0;
            } else {
                //已提交认证资料但待审核
                $return['audit'] = 3;
            }
        } elseif ($this->company_profile['audit'] == 1) {
            $return['audit'] = 1;
            if(config('global_config.audit_com_project')==1 && ($auth['legal_person_idcard_front']==0 || $auth['legal_person_idcard_back']==0 || $auth['license']==0 || $auth['proxy']==0)){
                $return['audit'] = 0;
            }else if(config('global_config.audit_com_project')==0 && $auth['license']==0){
                $return['audit'] = 0;
            }
        } else {
            $return['audit'] = 2;
            $job_audit_reason = model('CompanyAuthLog')
                ->field('reason')
                ->where(['uid' => ['eq', $this->userinfo->uid], 'audit' => 2])
                ->order('id desc')
                ->find();
            $return['audit_reason'] =
                $job_audit_reason === null ? '' : $job_audit_reason['reason'];
        }
        $return['companyname'] = $this->company_profile['companyname'];
        $return['audit_text'] = $audit_text[$return['audit']];
        $this->ajaxReturn(200, '获取数据成功', $return);
    }

    /**
     * 营业执照认证
     */
    public function license()
    {
        $input_data = [
            'legal_person_idcard_front' => input('post.legal_person_idcard_front/d', 0, 'intval'),
            'legal_person_idcard_back' => input('post.legal_person_idcard_back/d', 0, 'intval'),
            'license' => input('post.license/d', 0, 'intval'),
            'proxy' => input('post.proxy/d', 0, 'intval')
        ];
        if(config('global_config.audit_com_project')==1){
            $validate = new \think\Validate([
                'legal_person_idcard_front' => 'require|number|gt:0',
                'legal_person_idcard_back' => 'require|number|gt:0',
                'license' => 'require|number|gt:0',
                'proxy' => 'require|number|gt:0'
            ]);
        }else{
            $validate = new \think\Validate([
                'license' => 'require|number|gt:0'
            ]);
        }
        
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $auth = model('CompanyAuth')
            ->where('uid', $this->userinfo->uid)
            ->find();
        if ($auth === null) {
            $input_data['comid'] = $this->company_profile['id'];
            $input_data['uid'] = $this->company_profile['uid'];
            model('CompanyAuth')->save($input_data);
        } else {
            model('CompanyAuth')->save($input_data, ['id' => $auth['id']]);
        }
        model('Company')
            ->where('uid', $this->userinfo->uid)
            ->setField('audit', 0);

        $this->writeMemberActionLog($this->userinfo->uid,'提交营业执照认证信息');
        $this->ajaxReturn(200, '提交成功');
    }
}
