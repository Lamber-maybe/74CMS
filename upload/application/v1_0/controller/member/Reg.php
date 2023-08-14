<?php
namespace app\v1_0\controller\member;

class Reg extends \app\v1_0\controller\common\Base
{
    public function _initialize()
    {
        parent::_initialize();
        if(config('global_config.closereg')==1){
            $this->ajaxReturn(500,'网站已关闭会员注册');
        }
    }
    /**
     * 企业注册
     */
    public function company()
    {
        $input_data = [
            'companyname' => input('post.companyname/s', '', 'trim,badword_filter'),
            'contact' => input('post.contact/s', '', 'trim,badword_filter'),
            'mobile' => input('post.mobile/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'password' => input('post.password/s', '', 'trim'),
            'scene_uuid' => input('post.scene_uuid/s', '', 'trim'),
            'scene_id' => input('post.scene_id/s', '', 'trim')
        ];
        $validate = new \think\Validate([
            'companyname' => 'require|max:60|uniqueCompanyname',
            'contact' => 'require|max:30',
            'mobile' => 'require|checkMobile',
            'code' => 'require|max:4',
            'password' => 'require|max:15|min:6'
        ]);
        $validate->extend('uniqueCompanyname', function ($value) {
            if (config('global_config.company_repeat') == 1) {
                return true;
            } else {
                $info = model('Company')
                    ->where('companyname', $value)
                    ->find();
                if ($info === null) {
                    return true;
                } else {
                    return '企业名称已被占用';
                }
            }
        });
        $validate->extend('checkMobile', function ($value) {
            if (fieldRegex($value, 'mobile')) {
                $info = model('Member')
                    ->where([
                        'mobile' => $value,
                        'utype' => 1
                    ])
                    ->find();
                if (null === $info) {
                    return true;
                } else {
                    return '手机号已被占用';
                }
            } else {
                return '请输入正确的手机号码';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $auth_result = cache('smscode_' . $input_data['mobile']);
        if (
            $auth_result === false ||
            $auth_result['code'] != $input_data['code'] ||
            $auth_result['mobile'] != $input_data['mobile'] ||
            $auth_result['utype'] != 1
        ) {
            \think\Cache::inc('smscode_error_num_' . $input_data['mobile']);
            $this->ajaxReturn(500, '验证码错误');
        }
        $error_num = \think\Cache::get(
            'smscode_error_num_' . $input_data['mobile']
        );
        if ($error_num !== false && $error_num >= 5) {
            $this->ajaxReturn(500, '验证码失效，请重新获取');
        }
        //开始注册
        $reg_userinfo = model('Member')->regCompany($input_data);
        if (false === $reg_userinfo) {
            $this->ajaxReturn(500, model('Member')->getError());
        }
        if($input_data['scene_uuid'] || $input_data['scene_id']){
            $scene_qrcode_info = model('SceneQrcode')->where('id',$input_data['scene_id'])->whereOr('uuid',$input_data['scene_uuid'])->find();
            if($scene_qrcode_info!==null){
                model('SceneQrcodeRegLog')->save(['uid'=>$reg_userinfo['uid'],'pid'=>$scene_qrcode_info['id'],'addtime'=>time()]);
            }
        }
        cache('smscode_' . $input_data['mobile'], null);
        $this->ajaxReturn(
            200,
            '注册成功',
            $this->loginExtra($reg_userinfo['uid'], 1, $input_data['mobile'])
        );
    }
    /**
     * 求职者注册
     */
    public function personal()
    {
        $input_data = [
            'mobile' => input('post.mobile/s', '', 'trim'),
            'code' => input('post.code/s', '', 'trim'),
            'password' => input('post.password/s', '', 'trim'),
            'scene_uuid' => input('post.scene_uuid/s', '', 'trim'),
            'scene_id' => input('post.scene_id/s', '', 'trim')
        ];
        $validate = new \think\Validate([
            'mobile' => 'require|checkMobile',
            'code' => 'require|max:4',
            'password' => 'require|max:15|min:6'
        ]);
        $validate->extend('checkMobile', function ($value) {
            if (fieldRegex($value, 'mobile')) {
                $info = model('Member')
                    ->where([
                        'mobile' => $value,
                        'utype' => 2
                    ])
                    ->find();
                if (null === $info) {
                    return true;
                } else {
                    return '手机号已被占用';
                }
            } else {
                return '请输入正确的手机号码';
            }
        });
        if (!$validate->check($input_data)) {
            $this->ajaxReturn(500, $validate->getError());
        }
        $auth_result = cache('smscode_' . $input_data['mobile']);
        if (
            $auth_result === false ||
            $auth_result['code'] != $input_data['code'] ||
            $auth_result['mobile'] != $input_data['mobile'] ||
            $auth_result['utype'] != 2
        ) {
            \think\Cache::inc('smscode_error_num_' . $input_data['mobile']);
            $this->ajaxReturn(500, '验证码错误');
        }
        $error_num = \think\Cache::get(
            'smscode_error_num_' . $input_data['mobile']
        );
        if ($error_num !== false && $error_num >= 5) {
            $this->ajaxReturn(500, '验证码失效，请重新获取');
        }
        //开始注册
        $reg_userinfo = model('Member')->regPersonal($input_data);
        if (false === $reg_userinfo) {
            $this->ajaxReturn(500, model('Member')->getError());
        }
        if($input_data['scene_uuid'] || $input_data['scene_id']){
            $scene_qrcode_info = model('SceneQrcode')->where('id',$input_data['scene_id'])->whereOr('uuid',$input_data['scene_uuid'])->find();
            if($scene_qrcode_info!==null){
                model('SceneQrcodeRegLog')->save(['uid'=>$reg_userinfo['uid'],'pid'=>$scene_qrcode_info['id'],'addtime'=>time()]);
            }
        }
        cache('smscode_' . $input_data['mobile'], null);
        $this->ajaxReturn(
            200,
            '注册成功',
            $this->loginExtra($reg_userinfo['uid'], 2, $input_data['mobile'])
        );
    }
}
