<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class Job extends BaseValidate
{
    public function __construct()
    {
        parent::__construct();
        $this->initValidateRule('Job');
        if (!isset($this->rule['age'])) {
            unset($this->rule['minage'], $this->rule['maxage']);
        } else {
            $this->rule['minage'] = 'require|' . $this->rule['minage'];
            $this->rule['maxage'] = 'require|' . $this->rule['maxage'];
            unset($this->rule['age']);
        }
    }
    protected $rule = [
        'uid' => 'number|gt:0',
        'jobname' => 'require|max:30',
        'company_id' => 'number|gt:0',
        'emergency' => 'in:0,1',
        'stick' => 'in:0,1',
        'nature' => 'number',
        'sex' => 'in:0,1,2',
        'minage' => 'number|egt:16|elt:65',
        'maxage' => 'number|egt:16|elt:65',
        'amount' => 'number',
        'category1' => 'number|gt:0',
        'category2' => 'number',
        'category3' => 'number',
        'district1' => 'number|gt:0',
        'district2' => 'number',
        'district3' => 'number',
        'tag' => 'max:100',
        'education' => 'number',
        'experience' => 'number',
        'minwage' => 'number',
        'maxwage' => 'number',
        'negotiable' => 'in:0,1',
        'content' => 'require',
        'refreshtime' => 'number',
        'setmeal_id' => 'number',
        'audit' => 'in:0,1,2',
        'is_display' => 'in:0,1',
        'user_status' => 'in:0,1',
        'robot' => 'in:0,1',
        'custom_field_1' => 'max:255',
        'custom_field_2' => 'max:255',
        'custom_field_3' => 'max:255',
        'department' => 'max:15'
    ];
    protected $message = [
        'jobname.require'  =>  '请填写职位名称',
        'jobname.max'  =>  '职位名称长度不能超过30',
        'category1.gt' =>  '请选择职位分类',
        'district1.gt' =>  '请选择工作地区',
        'tag.require' =>  '请选择岗位福利',
        'content.require' =>  '请填写职位描述',
        'minage.require' =>  '请选择年龄要求'
    ];
}
