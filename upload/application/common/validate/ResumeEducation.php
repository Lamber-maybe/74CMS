<?php
namespace app\common\validate;

use app\common\validate\BaseValidate;

class ResumeEducation extends BaseValidate
{
    public function __construct()
    {
        parent::__construct();
        $this->initValidateRule('ResumeEducation');
    }
    protected $rule = [
        'uid' => 'number|gt:0',
        'rid' => 'number|gt:0',
        'starttime' => 'require|number|checkStarttime',
        'endtime' => 'require|number|checkEndtime',
        'todate' => 'require|in:0,1',
        'school' => 'require|max:30',
        'major' => 'max:20',
        'education' => 'require|number|gt:0'
    ];
    protected $field = [
        'starttime' => '入学时间',
        'endtime' => '毕业时间',
        'todate' => '至今',
        'school' => '学校名称',
        'major' => '专业名称',
        'education' => '取得学历'
    ];
    protected function checkStarttime($value, $rule, $data)
    {
        if ($value>=time()) {
            return '入学时间不能晚于当前时间';
        } else {
            return true;
        }
    }
    protected function checkEndtime($value, $rule, $data)
    {
        if (!$value && !$data['todate']) {
            return '请选择毕业时间';
        }else if($data['todate']==0 && $value<$data['starttime']){
            return '毕业时间不能早于入学时间';
        } else {
            return true;
        }
    }
}
