<?php

namespace app\common\validate;

use app\common\validate\BaseValidate;

class Resume extends BaseValidate
{
    public function __construct()
    {
        parent::__construct();
        $this->initValidateRule('Resume');
    }
    protected $rule = [
        'uid' => 'number|gt:0',
        'is_display' => 'in:0,1',
        'audit' => 'in:0,1,2',
        'stick' => 'in:0,1',
        'fullname' => 'require|max:15',
        'sex' => 'require|in:1,2',
        'birthday' => 'require|max:15',
        'residence' => 'max:30',
        'height' => 'max:5',
        'marriage' => 'in:0,1,2',
        'education' => 'require|number|gt:0',
        'enter_job_time' => 'require|number|egt:0',
        'householdaddress' => 'max:30',
        'major1' => 'number',
        'major2' => 'number',
        'major' => 'number',
        'tag' => 'max:50',
        'idcard' => 'checkIdcard',
        'photo_img' => 'max:255',
        'addtime' => 'number',
        'refreshtime' => 'number',
        'current' => 'number|gt:0',
        'click' => 'number',
        'tpl' => 'max:30',
        'custom_field_1' => 'max:255',
        'custom_field_2' => 'max:255',
        'custom_field_3' => 'max:255'
    ];
    protected function checkIdcard($value, $rule, $data)
    {
        if ($value == '') {
            return true;
        }
        if (is_idcard($value)) {
            return true;
        } else {
            return '请输入正确的身份证号码';
        }
    }
    protected $scene = [
        //app上注册简历（表单式）
        'reg_from_app_by_form' => [
            'fullname',
            'sex',
            'birthday',
            'education',
            'enter_job_time',
            'current'
        ],
        //app上注册简历（交互式）
        'reg_from_app_by_interactive' => [
            'fullname',
            'sex',
            'birthday',
            'education'
        ]
    ];
}
