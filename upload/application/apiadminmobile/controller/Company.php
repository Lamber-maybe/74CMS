<?php

namespace app\apiadminmobile\controller;

class Company extends \app\apiadmin\controller\Company
{
    public function authDetail(){
        $id = input('get.id/d', 0, 'intval');
        $info = model('Company')->find($id);
        if (!$info) {
            $this->ajaxReturn(500, '数据获取失败');
        }
        $return = [
            'id'=>$info->id,
            'companyname'=>$info->companyname
        ];
        $return['auth_status'] = $info->audit;
        $auth_info = model('CompanyAuth')->where('uid', $info->uid)->find();
        if ($auth_info!==null) {
            $return['has_auth_info'] = 1;
            $return['legal_person_idcard_front'] = model('Uploadfile')->getFileUrl($auth_info->legal_person_idcard_front);
            $return['legal_person_idcard_back'] = model('Uploadfile')->getFileUrl($auth_info->legal_person_idcard_back);
            $return['license'] = model('Uploadfile')->getFileUrl($auth_info->license);
            $return['proxy'] = model('Uploadfile')->getFileUrl($auth_info->proxy);
        } else {
            $return['has_auth_info'] = 0;
            $return['legal_person_idcard_front'] = '';
            $return['legal_person_idcard_back'] = '';
            $return['license'] = '';
            $return['proxy'] = '';
        }
        if($return['auth_status']==0 && $return['has_auth_info']==0){
            $return['auth_status'] = 3;
        }

        $this->ajaxReturn(200, '获取数据成功',$return);
    }
}
