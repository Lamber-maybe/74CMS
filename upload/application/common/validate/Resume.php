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
        'sex' => 'require|in:0,1,2',
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

    protected $message  =   [
        'is_display' => '请选择正确的显示状态',
        'audit' => '请选择正确的审核状态',
        'stick' => '请选择正确的置顶状态',
        'fullname' => '请填写正确的姓名',
        'sex' => '请选择正确的性别',
        'birthday' => '请填写正确的出生日期',
        'residence' => '现居住地最多30个字',
        'height' => '请填写正确的身高',
        'marriage' => '请选择正确的婚姻状况',
        'education' => '请选择正确的学历',
        'enter_job_time' => '请选择正确的参加工作时间',
        'householdaddress' => '籍贯最多30个字',
        'major1' => '请选择正确的专业一级分类',
        'major2' => '请选择正确的专业二级分类',
        'major' => '请选择正确的专业',
        'tag' => '请填写正确的简历标签',
        'idcard' => '请输入正确的身份证号码',
        'photo_img' => '请上传正确的照片',
        'current' => '请选择正确的求职状态',
        'click' => '请填写正确的点击量',
        'tpl' => '请填写正确的简历模板',
        'custom_field_1' => '自定义字段1最多255个字',
        'custom_field_2' => '自定义字段2最多255个字',
        'custom_field_3' => '自定义字段3最多255个字'
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
